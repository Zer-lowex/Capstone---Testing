<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ReservedProducts;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with([
                'category:id,name',
                'unit:id,name',
            ])
                ->orderBy('id', 'asc')
                ->paginate(10);

            // Add image_url to each product
            $products->getCollection()->transform(function ($product) {
                $product->append('image_url');
                return $product;
            });

            $categories = Category::orderBy('name')->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'products' => $products,
                'categories' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function home()
    {
        try {
            $products = Product::with([
                'category:id,name',
                'unit:id,name',
            ])
                ->orderBy('id', 'asc')
                ->paginate(10);

            // Add image_url to each product
            $products->getCollection()->transform(function ($product) {
                $product->append('image_url');
                return $product;
            });

            $categories = Category::orderBy('name')->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'products' => $products,
                'categories' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getReservedProducts(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $reservedProducts = ReservedProducts::with(['product.unit', 'customer'])
            ->where('status', 'pending')
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'reserved_products' => $reservedProducts,
        ]);
    }

    public function reserveProduct(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) {
                    if ($value > config('app.max_reservation_quantity', 50)) {
                        $fail('Maximum reservation quantity is ' . config('app.max_reservation_quantity', 50));
                    }
                },
            ],
        ]);

        try {
            DB::beginTransaction();

            // Get product with pessimistic locking
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $customer = Auth::user();

            if (!$customer) {
                throw new \Exception('Customer authentication failed');
            }

            $customerId = $customer->id;

            // Check stock availability
            if ($product->quantity < $validated['quantity']) {
                throw new \Exception(
                    'Not enough stock available. Only ' . $product->quantity . ' items available.'
                );
            }

            // Create reservation
            $expiredAt = now()->addDays(config('app.reservation_expiry_days', 2));

            $reservedProduct = ReservedProducts::create([
                'product_id' => $product->id,
                'customer_id' => $customerId,
                'quantity' => $validated['quantity'],
                'status' => 'pending',  
                'expired_at' => $expiredAt
            ]);

            // Update product quantity (no reserved_quantity column)
            $product->decrement('quantity', $validated['quantity']);

            // Verify no negative stock
            if ($product->fresh()->quantity < 0) {
                throw new \Exception('Stock cannot be negative');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product reserved successfully',
                'data' => [
                    'reservation_id' => $reservedProduct->id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'remaining_stock' => $product->quantity,
                    ],
                    'quantity_reserved' => $validated['quantity'],
                    'expires_at' => $expiredAt->toIso8601String(),
                    'reservation_date' => now()->toIso8601String()
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'RESERVATION_FAILED',
                'available_stock' => $product->quantity ?? null
            ], 400);
        }
    }

    public function getHistory(): JsonResponse
    {
        $user = Auth::user();

        // If you're using API tokens
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $sales = Sale::with('saleItems.product')
            ->where('customer_id', $user->id)
            ->latest()
            ->get();

        $history = $sales->map(function ($sale) {
            $totalItems = $sale->saleItems->sum('quantity');
            return [
                'sale_id'      => $sale->id,
                'total_items'  => $totalItems,
                'total_amount' => $sale->total_amount,
                'purchase_date' => $sale->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'purchase_history' => $history
        ]);
    }

    public function cancelReservation($id)
    {
        $customer = Auth::user();

        $reservation = ReservedProducts::where('status', 'pending')
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // Return stock to inventory
            $reservation->product->increment('quantity', $reservation->quantity);

            // Delete the reservation
            $reservation->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDelivery(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        // Eager load sale_items and their associated product
        $sales = Sale::with(['saleItems.product.unit'])
            ->where('customer_id', $user->id)
            ->where('delivery', 'YES')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'deliveries' => $sales,
        ]);
    }

    public function getProfile(): JsonResponse
    {
        $customer = Auth::user();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login to access your profile.'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Customer profile fetched successfully',
            'data' => [
                'id' => $customer->id,
                'username' => $customer->username,
                'firstName' => $customer->first_name,
                'lastName' => $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No authenticated user'
                ], 401);
            }

            // Revoke all tokens (more secure than just current token)
            $request->user()->tokens()->delete();

            // Optional: Clear any session data
            auth()->guard('web')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out from all devices'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function updateCustomerProfile(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . Auth::id(),
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            // Get the authenticated customer
            $customer = Auth::user();

            // Update the customer's profile
            $customer->username = $request->input('username');
            $customer->first_name = $request->input('firstName');
            $customer->last_name = $request->input('lastName');
            $customer->email = $request->input('email');
            $customer->phone = $request->input('phone');
            $customer->address = $request->input('address');

            // Save the updated customer data
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.',
                'data' => [
                    'id' => $customer->id,
                    'firstName' => $customer->first_name,
                    'lastName' => $customer->last_name,
                    'username' => $customer->username,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Profile update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile. Please try again.',
            ], 500);
        }
    }

    public function updateCustomerPassword(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Return validation errors as a response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password does not match our records.',
            ]);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Return a success response
        return response()->json(['message' => 'Password Changed Successfully.'], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
