<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\ReservedProducts;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CashierController extends Controller
{
    public function viewProfile()
    {
        $user = Auth::user();
        return view('cashier.settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype} ID: $user->id) Updated Profile Details",
        ]);

        return redirect()->route('cashier.profile')->with('success', 'Profile Updated Successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype} ID: $user->id) Changed Account Password",
        ]);

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password does not match our records.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('cashier.profile')->with('success', 'Password Changed Successfully.');
    }

    // PRODUCTS

    public function viewProduct()
    {
        $products = Product::with(['category', 'supplier', 'unit'])->orderBy('id', 'asc')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        }

        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = Unit::all();

        return view('cashier.products.index_products', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }



    public function searchProduct(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('term');

            $result = Product::where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('prefix', 'LIKE', '%' . $search . '%'); // Search by category prefix
                })
                ->with(['category', 'supplier', 'unit']) // Ensure related data is loaded
                ->get([
                    'id',
                    'name',
                    'unit_id',
                    'cost_price',
                    'sell_price',
                    'quantity',
                    'category_id',
                    'supplier_id',
                    'reorder_level',
                    'stock_alert_threshold',
                    'expiration_date'
                ]);

            // Transform results to include related names
            $formattedResult = $result->map(function ($product) {
                $prefix = $product->category ? $product->category->prefix : '';

                return [
                    'id' => $product->id,
                    'code_id' => $prefix . '-' . $product->id,
                    'name' => $product->name,
                    'unit' => $product->unit ? $product->unit->name : '',
                    'cost_price' => $product->cost_price,
                    'sell_price' => $product->sell_price,
                    'sell_price_formatted' => $product->formatted_sell_price,
                    'quantity' => $product->quantity,
                    'category' => $product->category ? $product->category->name : '',
                    'supplier' => $product->supplier ? $product->supplier->name : '',
                    'reorder_level' => $product->reorder_level,
                    'stock_alert_threshold' => $product->stock_alert_threshold,
                    'expiration_date' => $product->expiration_date
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }

    public function reservedProduct()
    {
        // Get customers with their pending reservations
        $reservations = ReservedProducts::with(['product.category', 'customer'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('customer_id');

        // For pagination, we'll need a different approach
        $customerIds = $reservations->keys()->filter()->toArray();
        $customers = Customer::with(['reservedProducts' => function ($query) {
            $query->where('status', 'pending')->with('product.category');
        }])
            ->whereIn('id', $customerIds)
            ->paginate(10);

        return view('cashier.products.reserved_products', [
            'customers' => $customers,
            'allReservations' => $reservations
        ]);
    }

    public function acceptReservation(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($request->customer_id);

            $reservedProducts = ReservedProducts::with('product.unit')
                ->where('status', 'pending')
                ->where('customer_id', $customer->id)
                ->get();

            if ($reservedProducts->isEmpty()) {
                throw new \Exception('No pending reservations found for this customer');
            }

            $productsData = $reservedProducts->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'code_id' => $item->product->code_id ?? $item->product_id,
                    'name' => $item->product->name,
                    'unit' => $item->product->unit->name ?? 'N/A',
                    'price' => $item->product->sell_price,
                    'quantity' => $item->quantity
                ];
            })->toArray();

            // Update status and stock
            ReservedProducts::whereIn('id', $reservedProducts->pluck('id'))
                ->update(['status' => 'accepted']);

            foreach ($reservedProducts as $reservedProduct) {
                $reservedProduct->product->increment('quantity', $reservedProduct->quantity);
            }

            DB::commit();

            return redirect()->route('cashier.dashboard2')
                ->with([
                    'accepted_products' => $productsData,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->username,
                    'success' => 'Reservation accepted and added to POS'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancelReservation(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reserved_products,id,status,pending'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $reservation = ReservedProducts::where('status', 'pending')
                    ->lockForUpdate()
                    ->findOrFail($request->reservation_id);

                // Return stock
                Product::where('id', $reservation->product_id)
                    ->increment('quantity', $reservation->quantity);

                $reservation->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully. Stock has been updated.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelReservationPOS(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);

        DB::beginTransaction();
        try {
            // Get all reserved products for this customer
            $reservedProducts = ReservedProducts::where('customer_id', $request->customer_id)
                ->where('status', 'accepted')
                ->get();

            // Return quantities to stock
            foreach ($reservedProducts as $reserved) {
                $reserved->product->decrement('quantity', $reserved->quantity);
            }

            ReservedProducts::where('customer_id', $request->customer_id)
                ->where('status', 'accepted')
                ->update(['status' => 'pending']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'LIKE', '%' . $query . '%')
            ->with('unit') // Add this line to fetch related unit
            ->limit(5)
            ->get(['id', 'name', 'unit_id', 'sell_price']);

        // Map the result to include unit name
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'unit_name' => $product->unit ? $product->unit->name : 'N/A',
                'sell_price' => $product->sell_price
            ];
        });

        return response()->json($products);
    }


    // CUSTOMERS

    public function viewCustomer()
    {
        $customers = Customer::orderBy('id', 'desc')->paginate(10);
        return view('cashier.customers.index_customer', [
            'customers' => $customers
        ]);
    }

    public function searchCustomer(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('term');
            $result = Customer::where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%"); // optional: search username too
            })
                ->get(['id', 'first_name', 'last_name', 'username', 'phone', 'address']);

            $formattedResult = $result->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'username' => $customer->username,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }
    public function addCustomer(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:customers,username'],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $customer = new Customer($validated);
        $customer->password = bcrypt('Password123');
        $customer->save();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype} ID: $user->id) Created Customer ID: $customer->id",
        ]);

        return redirect('/cashier/dashboard')->with('success', 'Customer Created Successfuly');
    }

    // SALE 

    public function addSale(Request $request)
    {
        // return $request->all();

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'products' => 'required|array|min:1', // Ensure at least one product is added
            'products.*.quantity' => 'required|integer|min:1', // Quantity must be at least 1
            'products.*.price' => 'required|numeric|min:0', // Price must be non-negative
            'delivery' => 'nullable|string|in:YES,NO', // Validates delivery as either 'YES' or 'NO'
            'delivery_fee' => 'nullable|numeric|min:1|required_if:delivery,YES',
            'customer_address' => 'nullable|required_if:delivery,YES|string|max:255', // Address required if delivery is 'YES'
            'paid_amount' => 'required|numeric|min:0', // Paid amount is required and must be a non-negative number
            'balance' => 'gte:0', // Balance must be a non-negative number
            'discount_type' => 'nullable|in:senior/pwd,custom',
            'discount' => 'nullable|numeric|min:0',
            'custom_discount_value' => 'nullable|required_if:discount_type,custom|numeric|min:0',
            'custom_discount_type' => 'nullable|required_if:discount_type,custom|in:percent,peso',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->discount_type === 'senior/pwd') {
            $request->validate([
                'discount_percentage' => 'required|numeric|in:20',
            ], [
                'discount_percentage.in' => 'Senior/PWD discount must be 20%'
            ]);
        }

        $user = Auth::user();

        // Check if there is sufficient stock for each product
        $stockErrors = [];
        foreach ($request->products as $product) {
            $productRecord = Product::find($product['id']);
            if (!$productRecord || $productRecord->quantity < $product['quantity']) {
                $stockErrors[] = "Insufficient stock for product: {$productRecord->name}";
            }
        }

        if (!empty($stockErrors)) {
            return redirect()
                ->back()
                ->with('stockErrors', $stockErrors)
                ->withInput();
        }

        // Prevent delivery for WALK-IN
        if (empty($request->customer_id) && $request->delivery === 'YES') {
            return response()->json([
                'errors' => ['delivery' => ['Delivery not available for WALK-IN customers']]
            ], 422);
        }

        // Calculate subtotal
        $grossSubtotal = collect($request->products)->sum(function ($product) {
            return $product['quantity'] * $product['price'];
        });

        // Add delivery fee if applicable
        if ($request->delivery === 'YES') {
            $grossSubtotal += $request->delivery_fee;
        }

        // Calculate net amount (VAT-exclusive) and VAT amount
        $netSubtotal = $grossSubtotal / 1.12;
        $vatAmount = $grossSubtotal - $netSubtotal;

        // Calculate discount amount
        $discountAmount = 0;
        if ($request->discount_type === 'senior/pwd') {
            $discountAmount = $grossSubtotal * 0.20;
        } elseif ($request->discount_type === 'custom') {
            if ($request->custom_discount_type === 'percent') {
                $discountAmount = $grossSubtotal * ($request->custom_discount_value / 100);
            } else {
                $discountAmount = $request->custom_discount_value;
            }
        }

        // Calculate final total
        $totalAmount = $grossSubtotal - $discountAmount;
        $netAmountAfterDiscount = $totalAmount / 1.12;
        $vatAmountAfterDiscount = $totalAmount - $netAmountAfterDiscount;

        // Create Sale
        $sale = Sale::create([
            'user_id' => $user->id,
            'customer_id' => $request->customer_id,
            'total_amount' => $totalAmount,
            'net_amount' => $netAmountAfterDiscount,
            'vat_amount' => $vatAmountAfterDiscount,
            'delivery' => $request->delivery === 'YES' ? 'YES' : 'NO',
            'delivery_fee' => $request->delivery_fee,
            'customer_address' => $request->delivery === 'YES' ? $request->customer_address : null,
            'status' => $request->delivery === 'YES' ? 'PENDING' : 'COMPLETE',
            'paid_amount' => $request->paid_amount,
            'sukli' => $request->balance,
            'discount_type' => $request->discount_type,
            'discount' => $discountAmount,
            'discount_percentage' => $request->discount_type === 'senior/pwd' ? 20 : ($request->custom_discount_type === 'percent' ? $request->custom_discount_value : null)
        ]);

        if ($request->delivery === 'YES') {
            $delivery = Delivery::create([
                'user_id' => $user->id,
                'sale_id' => $sale->id,
                'status' => 'PENDING'
            ]);

            $sale->update(['delivery_id' => $delivery->id]);
        }

        // Create Sale Items and Update Inventory
        foreach ($request->products as $product) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product['id'],
                'unit' => $product['unit'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'total_amount' => $product['quantity'] * $product['price'],
            ]);

            // Update inventory
            $productRecord = Product::find($product['id']);
            $productRecord->decrement('quantity', $product['quantity']);

            // Log Stock Movement for the sold quantity
            StockMovement::create([
                'product_id' => $product['id'],
                'type' => 'Sold',
                'quantity' => $product['quantity'],
            ]);
        }

        // Log Activity for Deliveries
        if ($request->delivery === 'YES') {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Delivery',
                'description' => "A New Delivery Created for Sale ID: $sale->id",
            ]);
        }

        $usertype = $user->usertype;

        // Log Activity for Sale
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype} ID: $user->id) Created Sale ID: $sale->id",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully!',
            'redirect' => url('/cashier/dashboard'),
            'printReceipt' => $sale->id
        ]);
    }

    public function receipt($id)
    {
        $sale = Sale::with(['customer', 'user', 'saleItems.product'])->findOrFail($id);

        return view('cashier.receipts.index_receipt', [
            'sale' => $sale,
        ]);
    }

    public function receipt2($id)
    {
        $sale = Sale::with(['customer', 'user', 'saleItems.product'])->findOrFail($id);

        return view('cashier.receipts.index_receipt2', [
            'sale' => $sale,
        ]);
    }

    // RECENT TRANSACTIONS

    public function viewOrders(Request $request)
    {
        $query = Sale::query();

        // Apply filter if selected
        if ($request->has('filter') && !empty($request->filter)) {
            $query->where('status', $request->filter);
        }

        // Fetch sales with filtering and pagination
        $sales = $query->orderBy('id', 'desc')->paginate(10);

        // Replace null delivery_fee and customer_address with "N/A"
        $sales->transform(function ($sale) {
            $sale->delivery_fee = $sale->delivery_fee ?? 'N/A';
            $sale->customer_address = $sale->customer_address ?? 'N/A';
            return $sale;
        });

        return view('cashier.orders.index_orders', compact('sales'));
    }
}
