<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/customer/auth/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function viewProducts()
    {
        $products = Product::with(['category', 'unit'])
            ->orderBy('id', 'asc')
            ->paginate(10);
            
        $categories = Category::orderBy('name')->pluck('name');

        return view('customer.products', compact('products', 'categories'));
    }

    public function authProducts()
    {
        $products = Product::with(['category', 'unit'])
            ->orderBy('id', 'asc')
            ->paginate(10);
            
        $categories = Category::orderBy('name')->pluck('name');

        return view('customer.auth.products', compact('products', 'categories'));
    }

    public function index()
    {
        return view('customer.home');
    }

    public function dashboard()
    {
        return view('customer.auth.dashboard');
    }

    public function reserveProduct(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                if ($product->quantity < $validated['quantity']) {
                    throw new \Exception('Not enough stock available. Only ' . $product->quantity . ' items left.');
                }

                $expiredAt = now()->addDays(3);

                StockMovement::create([
                    'product_id' => $product->id,
                    'customer_id' => Auth::guard('customer')->id(),
                    'type' => 'Reserved',
                    'quantity' => $validated['quantity'],
                    'created_at' => now(),
                    'expired_at' => $expiredAt,
                ]);

                $product->decrement('quantity', $validated['quantity']);

                if ($product->quantity < 0) {
                    throw new \Exception('Stock cannot be negative');
                }
            });

            return redirect()->route('customer.auth.products')->with('success', 'Product reserved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function aboutUs()
    {
        return view('customer.about');
    }

    public function authAboutUs()
    {
        return view('customer.auth.about');
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:customers,username,'.$customer->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($data);

        return back()
            ->with('status', 'profile-updated')
            ->with('success', 'Profile updated successfully');
    }

    public function viewProfile()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get paginated reservations (5 per page)
        $reservedProducts = $customer->reservations()
            ->with('product.unit')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'reservations_page');
        
        // Get paginated purchases with items count (5 per page)
        $purchases = $customer->purchases()
            ->withCount('saleItems') // This will count the number of sale items
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'purchases_page');
        
        return view('customer.auth.profile', [
            'customer' => $customer,
            'reservedProducts' => $reservedProducts,
            'purchases' => $purchases
        ]);
    }

    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $request->validate([
            'current_password' => 'required|current_password:customer',
            'password' => 'required|min:8|confirmed',
        ]);

        $customer = Auth::guard('customer')->user();
        $customer->update([
            'password' => Hash::make($request->password)
        ]);

        return back()
            ->with('status', 'password-updated')
            ->with('success', 'Password updated successfully');
    }

    public function cancelReservation($id)
    {
        $customer = Auth::guard('customer')->user();
        
        $reservation = StockMovement::where('type', 'Reserved')
            ->where('customer_id', $customer->id)
            ->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Return stock to inventory
            $reservation->product->increment('quantity', $reservation->quantity);
            
            
            // Delete the reservation
            $reservation->delete();
            
            DB::commit();
            
            return back()->with('success', 'Reservation cancelled successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel reservation: ' . $e->getMessage());
        }
    }

}
