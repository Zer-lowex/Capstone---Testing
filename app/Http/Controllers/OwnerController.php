<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OwnerController extends Controller
{
    // PROFILE SETTINGS

    public function website()
    {
        $products = Product::with(['category', 'unit', 'supplier'])
                    ->orderBy('name')
                    ->get();

        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('owner.settings.website', compact('products', 'categories'));
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            // Delete old image if exists
            if ($product->image) {
                $oldImagePath = public_path('images/' . $product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store new image
            $image = $request->file('image');
            $imageName = 'product-' . $product->id . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            
            // Save image name to database
            $product->image = $imageName;
            $product->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image_url' => asset('images/' . $imageName)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDescription(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string|max:1000'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $product->description = $request->description;
            $product->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Description updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating description: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewProfile()
    {
        $user = Auth::user();
        return view('owner.settings.profile', compact('user'));
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
            'description' => "{$user->username} ({$usertype}) Changed Account Password",
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

        return redirect()->route('owner.profile')->with('success', 'Password Changed Successfully.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->username = $request->username;
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
            'description' => "{$user->username} ({$usertype}) Updated Profile Details",
        ]);

        return redirect()->route('owner.profile')->with('success', 'Profile Updated Successfully.');
    }

    public function viewAlerts()
    {
        $products = Product::orderBy('id', 'asc')->paginate(10);

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

        return view('owner.settings.alerts', compact('products'));
    }

    public function updateAlerts(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'reorder_level' => 'required|integer|min:0',
            'stock_alert_threshold' => 'nullable|integer|min:0',
        ]);

        $product->update([
            'reorder_level' => $request->reorder_level,
            'stock_alert_threshold' => $request->stock_alert_threshold,
        ]);

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Alert for Product ID: $product->id (Name: $product->name)",
        ]);

        return redirect('/owner/settings/alerts')->with('success', 'Product Levels Updated Successfully');
    }

    //  FOR PRODUCTS

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

        return view('owner.products.index_products', [
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
                ->with(['category', 'supplier', 'unit']) // Ensure related data is loaded
                ->get(['id', 'name', 'unit_id', 'cost_price', 'sell_price',
                    'quantity', 'category_id', 'supplier_id', 'reorder_level',
                    'stock_alert_threshold', 'expiration_date']);

            // Transform results to include related names
            $formattedResult = $result->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'unit' => $product->unit ? $product->unit->name : '',
                    'cost_price' => $product->cost_price,
                    'sell_price' => $product->sell_price,
                    'sell_price_formatted' => $product->formatted_sell_price,
                    'quantity' => $product->quantity,
                    'category' => $product->category ? $product->category->name : '',
                    'supplier_id' => $product->supplier_id,
                    'supplier' => $product->supplier ? ['name' => $product->supplier->name] : null,
                    'reorder_level' => $product->reorder_level,
                    'stock_alert_threshold' => $product->stock_alert_threshold,
                    'expiration_date' => $product->expiration_date
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }

    public function filterProducts(Request $request)
    {
        $query = Product::query();

        // Apply filters based on the request inputs
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('unit') && $request->unit) {
            $query->where('unit_id', $request->unit);
        }

        if ($request->has('supplier') && $request->supplier) {
            $query->where('supplier_id', $request->supplier);
        }

        $products = $query->with(['unit', 'category', 'supplier'])->paginate(10);

        $formattedProducts = $products->getCollection()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'unit' => $product->unit ? ['name' => $product->unit->name] : null,
                'category' => $product->category ? ['name' => $product->category->name] : null,
                'supplier' => $product->supplier ? ['name' => $product->supplier->name] : null,
                'supplier_id' => $product->supplier_id,
                'sell_price' => $product->sell_price,
                'sell_price_formatted' => $product->formatted_sell_price, // Add this
                'quantity' => $product->quantity,
                'reorder_level' => $product->reorder_level,
                'stock_alert_threshold' => $product->stock_alert_threshold,
                'expiration_date' => $product->expiration_date
            ];
        });

        return response()->json([
            'data' => $formattedProducts,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',  
            'supplier_id' => 'required|exists:suppliers,id', 
            'unit_id' => 'required|exists:units,id',  
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'stock_alert_threshold' => 'nullable|integer|min:0',
            'expiration_date' => 'nullable|date|after:today',
        ]);

        $product = Product::create($request->all());
        $user = Auth::user();
        $usertype = $user->usertype; 

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype}) Created Product ID: $product->id",
        ]);

        return redirect('/owner/products')->with('success','Product Created Successfuly');
    }

    public function editProduct($id)
    {
        $products = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = Unit::all();

        return view('owner.products.edit_products', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',  
            'supplier_id' => 'required|exists:suppliers,id',  
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after:today',
        ]);

        $product->update($request->all()); 

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Product ID: $product->id (Name: $product->name)",
        ]);

        return redirect('/owner/products')->with('success', 'Product Updated Successfully');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Product ID: $product->id (Name: $product->name)",
        ]);

        return redirect('/owner/products')->with('success', 'Product Deleted Successfully');
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::find($request->product_id);

        $product->quantity += $request->quantity;
        $product->save(); 

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'Added',
            'quantity' => $request->quantity,
        ]);
        

        // Log the activity
        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Stock',
            'description' => "{$user->username} ({$usertype}) Added {$request->quantity} Units of Stock for Product ID: {$product->id}",
        ]);

        return redirect('/owner/products')->with('success', 'Added Product Stock Successfully');
    }

    // FOR CATEGORY

    public function viewCategory()
    {
        $categories = Category::orderBy('id','asc')->paginate(10);
        return view('owner.categories.index_category', [
            'categories' => $categories
        ]);
    }

    public function searchCategory(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('term');
            $result = Category::where('name', 'LIKE', '%' . $search . '%')
                ->get(['id', 'name', 'prefix']);

            $formattedResult = $result->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'prefix' => $category->prefix,
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());

        $user = Auth::user();
        $usertype = $user->usertype; 

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype}) Created Category Titled $category->name",
        ]);

        return redirect('/owner/category')->with('success','Category Created Successfuly');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('owner.categories.edit_category', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all()); 

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Category ID: $category->id (Name: $category->name)",
        ]);

        return redirect('/owner/category')->with('success', 'Category Updated Successfully');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Category ID: $category->id (Name: $category->name)",
        ]);

        return redirect('/owner/category')->with('success', 'Category Deleted Successfully');
    }

    // FOR UNIT

    public function viewUnit()
    {
        $units = Unit::orderBy('id','asc')->paginate(10);
        return view('owner.units.index_unit', [
            'units' => $units
        ]);
    }

    public function searchUnit(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('term');
            $result = Unit::where('name', 'LIKE', '%' . $search . '%')
                ->get(['id', 'name']);

            $formattedResult = $result->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->name,
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }

    public function addUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit = Unit::create($request->all());

        $user = Auth::user();
        $usertype = $user->usertype; 

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype}) Created Unit Titled $unit->name",
        ]);

        return redirect('/owner/unit')->with('success','Unit Created Successfuly');
    }

    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('owner.units.edit_unit', compact('unit'));
    }

    public function updateUnit(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit->update($request->all()); 

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Unit ID: $unit->id (Name: $unit->name)",
        ]);

        return redirect('/owner/unit')->with('success', 'Unit Updated Successfully');
    }

    public function deleteUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Unit ID: $unit->id (Name: $unit->name)",
        ]);

        return redirect('/owner/unit')->with('success', 'Unit Deleted Successfully');
    }

    // FOR CUTOMERS

    public function viewCustomer()
    {
        $customers = Customer::orderBy('id','asc')->paginate(10);
        return view('owner.customers.index_customer', [
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
        try {
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
                'description' => "{$user->username} ({$usertype}) Created Customer ID: $customer->id",
            ]);

            return redirect('/owner/customers')->with('success','Customer Created Successfully');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('showAddCustomerModal', true);
        }
    }

    public function editCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        return view('owner.customers.edit_customers', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $customer->update($request->all()); 

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Customer ID: $customer->id (Name: $customer->name)",
        ]);

        return redirect('/owner/customers')->with('success', 'Customer Updated Successfully');
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Customer ID: $customer->id (Name: $customer->name)",
        ]);

        return redirect('/owner/customers')->with('success', 'Customer Deleted Successfully');
    }

    // FOR STAFF

    public function viewStaff()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);
        return view('owner.staff.index_staff', [
            'users' => $users
        ]);
    }

    public function searchStaff(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('term');

            $result = User::where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%"); // optional: search username too
            })
            ->get(['id', 'first_name', 'last_name', 'username', 'email', 'usertype', 'phone', 'address', 'status']);

            $formattedResult = $result->map(function ($staff) {
                return [
                    'id' => $staff->id,
                    'first_name' => $staff->first_name,
                    'last_name' => $staff->last_name,
                    'username' => $staff->username,
                    'email' => $staff->email,
                    'usertype' => $staff->usertype,
                    'phone' => $staff->phone,
                    'address' => $staff->address,
                    'status' => $staff->status,
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }

    public function addStaff(Request $request)
    {
        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'usertype' => 'required|string|max:255', 
        ]);

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->usertype = $request->usertype;

        $defaultPassword = 'Password123'; 
        $user->password = bcrypt($defaultPassword);

        $user->save();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype}) Created a new Staff",
        ]);

        return redirect('/owner/staff')->with('success','Staff Created Successfully');
    }

    public function editStaff($id)
    {
        $user = User::findOrFail($id);
        return view('owner.staff.edit_staff', compact('user'));
    }

    public function updateStaff(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $user->update($request->all()); 

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Staff ID: $user->id (Name: $user->name)",
        ]);

        return redirect('/owner/staff')->with('success', 'Staff Updated Successfully');
    }

    public function deleteStaff($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Staff ID: $user->id (Name: $user->name)",
        ]);

        return redirect('/owner/staff')->with('success', 'Staff Deleted Successfully');
    }

    // FOR SUPPLIER
    public function viewSupplier()
    {
        $suppliers = Supplier::orderBy('id', 'asc')->paginate(10);

        // Replace null email with "N/A"
        $suppliers->transform(function ($supplier) {
            $supplier->email = $supplier->email ?? 'N/A';
            return $supplier;
        });

        return view('owner.supplier.index_supplier', compact('suppliers'));
    }


    public function searchSupplier(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('term');
            $result = Supplier::where('name', 'LIKE', '%' . $search . '%')
                ->get(['id', 'name', 'email', 'phone', 'address']);

            $formattedResult = $result->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email ?? 'N/A',
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                ];
            });

            return response()->json($formattedResult);
        }
        abort(404);
    }

    public function addSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $supplier = Supplier::create($request->all());

        $user = Auth::user();
        $usertype = $user->usertype; 

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Create',
            'description' => "{$user->username} ({$usertype}) Created Supplier ID: $supplier->id",
        ]);

        return redirect('/owner/supplier')->with('success','Supplier Created Successfuly');
    }

    public function editSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('owner.supplier.edit_supplier', compact('supplier'));
    }

    public function updateSupplier(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $supplier->update($request->all()); 

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype}) Updated Supplier ID: $supplier->id (Name: $supplier->name)",
        ]);

        return redirect('/owner/supplier')->with('success', 'Supplier Updated Successfully');
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Supplier ID: $supplier->id (Name: $supplier->name)",
        ]);

        return redirect('/owner/supplier')->with('success', 'Supplier Deleted Successfully');
    }

    public function getDetails(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function getProducts(Supplier $supplier)
    {
        $products = $supplier->products()
            ->with('category', 'unit')
            ->select('id', 'name', 'category_id', 'unit_id', 'cost_price', 'quantity', 'expiration_date')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'No Category',
                    'unit' => $product->unit->name ?? 'No Unit',
                    'cost_price' => $product->cost_price,
                    'quantity' => $product->quantity,
                    'expiration_date' => $product->expiration_date 
                        ? Carbon::parse($product->expiration_date)->format('m/d/Y') 
                        : 'N/A'
                ];
            });

        return response()->json($products);
    }

    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function products(Supplier $supplier)
    {
        $products = $supplier->products()
            ->with(['category', 'unit'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'cost_price' => $product->cost_price,
                    'quantity' => $product->quantity,
                    'expiration_date' => $product->expiration_date,
                    'unit' => $product->unit ? ['name' => $product->unit->name] : null,
                    'category' => $product->category ? ['name' => $product->category->name] : null
                ];
            });

        return response()->json($products);
    }

    //TRANSACTIONS

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

        return view('owner.orders.index_orders', compact('sales'));
    }



    public function viewDeliveries(Request $request)
    {
        $query = Delivery::where('status', '!=', 'COMPLETE');

        // Apply filter if selected
        if ($request->has('filter') && !empty($request->filter)) {
            $query->where('status', $request->filter);
        }

        // Fetch sales with filtering and pagination
        $deliveries = $query->orderBy('id', 'desc')->paginate(10);
        $drivers = User::where('usertype', 'Driver')->get();

        return view('owner.orders.deliveries', [
            'deliveries' => $deliveries,
            'drivers' => $drivers,
        ]);
    }

    public function assignDriver(Request $request)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'driver_id' => 'nullable|exists:users,id',
        ]);

        $delivery = Delivery::findOrFail($request->delivery_id);
        $user = Auth::user();
        $usertype = $user->usertype;

        $customerFirstName = optional($delivery->sale->customer)->first_name ?? 'Unknown';
        $customerLastName = optional($delivery->sale->customer)->last_name ?? 'Unknown';

        $sale = Sale::where('id', $delivery->sale_id)->first();

        // If driver is selected, assign it to the delivery
        if ($request->driver_id) {
            $driver = User::find($request->driver_id);
            $driverUsername = $driver ? $driver->username : 'Unknown Driver';
            $driverId = $driver ? $driver->id : 'Unknown ID';
    
            // Assign driver
            $delivery->user_id = $driverId;
            $delivery->status = 'ONGOING';

            // Update the Sale status
            if ($sale) {
                $sale->status = 'ONGOING';
                $sale->save();
            }

            $message = "Driver Assigned Successfully";
            $description = "Assigned {$driverUsername} (ID: {$driverId}) to deliver {$customerFirstName} {$customerLastName}'s items.";
        } else {
            // If "No Driver Assigned", remove the assigned driver
            $delivery->user_id = null;
            $delivery->status = 'PENDING';
            
            // Update the Sale status
            if ($sale) {
                $sale->status = 'PENDING';
                $sale->save();
            }

            $message = "Removed Driver Successfully";
            $description = "Removed the Assigned Driver from Delivery ID: {$delivery->id}.";
        }

        $delivery->save();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Driver Assignment',
            'description' => "{$user->username} ({$usertype}) {$description}",
        ]);

        return response()->json([
            'message' => $message, 
            'redirect' => url('/owner/deliveries')
        ]);
    }

    public function viewCompleteDeliveries()
    {
        $deliveries = Delivery::where('status', 'COMPLETE')
        ->with(['user', 'sale'])
        ->orderBy('updated_at','desc')->paginate(10);
        return view('owner.orders.complete_deliveries', [
            'deliveries' => $deliveries
        ]);
    }

    public function receipt($id)
    {
        $sale = Sale::with(['customer', 'user', 'saleItems.product'])->findOrFail($id);

        return view('owner.receipts.index_receipt', [
            'sale' => $sale,
        ]);
    }

    // REPORTS

    public function salesReport(Request $request)
    {
        // Get the filter type selected by the user
        $filter = $request->input('filter', 'daily');
        $profitFilter = $request->input('profitFilter');

        // Set default date range values based on the selected filter
        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // Fetch sale items grouped by product and aggregate the data
        $sales = SaleItem::with('product.unit')
            ->whereBetween('sale_items.created_at', [$startDate, $endDate])
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'units.name as unit_name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                'products.cost_price',
                'products.sell_price',
                DB::raw('SUM(sale_items.quantity * products.sell_price) as total_revenue'),
                DB::raw('SUM(sale_items.quantity * products.sell_price) - SUM(sale_items.quantity * products.cost_price) as total_profit')
            )
            ->groupBy('products.id', 'products.name', 'units.name', 'products.cost_price', 'products.sell_price');
            
            if ($profitFilter == 'profit_asc') {
                $sales->orderBy(DB::raw('SUM(sale_items.quantity * products.sell_price) - SUM(sale_items.quantity * products.cost_price)'), 'asc');
            } elseif ($profitFilter == 'profit_desc') {
                $sales->orderBy(DB::raw('SUM(sale_items.quantity * products.sell_price) - SUM(sale_items.quantity * products.cost_price)'), 'desc');
            } elseif ($profitFilter == 'quantity_sold') {
                $sales->orderBy(DB::raw('SUM(sale_items.quantity)'), 'desc');
            }

            $sales = $sales->paginate(10);

        return view('owner.reports.sales_report', compact('sales'));
    }

    public function inventoryReport(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $stockFilter = $request->input('stockFilter');
        $unitId = $request->input('unit');
        $supplierId = $request->input('supplier');
        $categoryId = $request->input('category');

        // Date range based on filter
        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // Main query with joins
        $inventory = Product::with('category', 'supplier', 'unit')
            ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'units.name as unit_name',
                DB::raw('SUM(CASE WHEN stock_movements.type = "Added" THEN stock_movements.quantity ELSE 0 END) as stock_added'),
                DB::raw('SUM(CASE WHEN stock_movements.type = "Sold" THEN stock_movements.quantity ELSE 0 END) as stock_sold'),
                DB::raw('products.quantity as current_stock'),
                'categories.name as category_name',
                'suppliers.name as supplier_name',
                'products.reorder_level',
                'products.stock_alert_threshold',
                DB::raw('products.quantity * products.sell_price as total_value')
            )
            ->whereBetween('stock_movements.created_at', [$startDate, $endDate]);

        // Additional filtering by unit, supplier, and category
        if ($unitId) {
            $inventory->where('products.unit_id', $unitId);
        }

        if ($supplierId) {
            $inventory->where('products.supplier_id', $supplierId);
        }

        if ($categoryId) {
            $inventory->where('products.category_id', $categoryId);
        }

        // Sorting based on stock filter
        if ($stockFilter == 'stockAdded_asc') {
            $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Added" THEN stock_movements.quantity ELSE 0 END) ASC');
        } elseif ($stockFilter == 'stockAdded_desc') {
            $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Added" THEN stock_movements.quantity ELSE 0 END) DESC');
        } elseif ($stockFilter == 'stockSold_asc') {
            $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Sold" THEN stock_movements.quantity ELSE 0 END) ASC');
        } elseif ($stockFilter == 'stockSold_desc') {
            $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Sold" THEN stock_movements.quantity ELSE 0 END) DESC');
        }

        $inventory = $inventory->groupBy(
            'products.id',
            'products.name',
            'units.name',
            'products.quantity',
            'categories.name',
            'suppliers.name',
            'products.reorder_level',
            'products.stock_alert_threshold',
            'products.sell_price'
        )->paginate(10);

        // Load filters for dropdowns in view
        $units = Unit::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('owner.reports.inventory_report', compact(
            'inventory',
            'filter',
            'stockFilter',
            'unitId',
            'supplierId',
            'categoryId',
            'units',
            'suppliers',
            'categories'
        ));
    }

    public function cashierReport(Request $request)
    {
        $cashiers = User::where('usertype', 'Cashier')->get();

        $query = Sale::with(['user', 'customer'])
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'Cashier');
            });

        $filter = $request->input('filter', 'daily');

        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->has('cashier_id') && $request->cashier_id != 'all') {
            $query->where('user_id', $request->cashier_id);
        }

        // Clone query before pagination
        $totalSalesQuery = clone $query;
        $sales = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalSales = $totalSalesQuery->sum('total_amount');
        $totalTransactions = $totalSalesQuery->count();
        $averageTransactionValue = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        $itemsSoldQuery = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate]);

        if ($request->has('cashier_id') && $request->cashier_id != 'all') {
            $itemsSoldQuery->where('sales.user_id', $request->cashier_id);
        }

        $itemsSold = $itemsSoldQuery
            ->select(
                DB::raw("CONCAT(categories.prefix, '-', products.id) as product_code"), 
                'products.name',
                DB::raw('SUM(sale_items.quantity) as quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.price) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'categories.prefix')
            ->get();

            $topSellingItemsQuery = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate]);
        
        if ($request->has('cashier_id') && $request->cashier_id != 'all') {
            $topSellingItemsQuery->where('sales.user_id', $request->cashier_id);
        }
        
        $topSellingItems = $topSellingItemsQuery
            ->select(
                DB::raw("CONCAT(categories.prefix, '-', products.id) as product_code"),
                'products.name',
                DB::raw('SUM(sale_items.quantity) as quantity')
            )
            ->groupBy('products.id', 'products.name', 'categories.prefix')
            ->orderByDesc('quantity')
            ->limit(5)
            ->get();

        if ($filter === 'daily') {
            $reportDate = $startDate->format('F j, Y');
            $timeRange = '8:00 AM - 5:00 PM';
        } else {
            $reportDate = $startDate->format('F j, Y') . ' - ' . $endDate->format('F j, Y');
            $timeRange = '8:00 AM - 5:00 PM';
        }

        $selectedCashier = User::find($request->cashier_id);
        $cashierName = null;

            if ($request->has('cashier_id') && $request->cashier_id != 'all') {
                $selectedCashier = User::find($request->cashier_id);
                $cashierName = $selectedCashier ? $selectedCashier->first_name . ' ' . $selectedCashier->last_name : null;
            }

        return view('owner.reports.cashier_report', compact(
            'sales',
            'cashiers',
            'reportDate',
            'timeRange',
            'cashierName',
            'totalSales',
            'totalTransactions',
            'averageTransactionValue',
            'itemsSold',
            'topSellingItems',
            'filter'
        ));
    }

    public function viewDeliveryReport(Request $request)
    {
        $drivers = User::where('usertype', 'Driver')->get();

        $query = Delivery::with(['user', 'sale.customer'])
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'Driver');
            });

        $filter = $request->input('filter', 'daily');

        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $query->whereBetween('updated_at', [$startDate, $endDate]);

        if ($request->has('driver_id') && $request->driver_id != 'all') {
            $query->where('user_id', $request->driver_id);
        }

        // Clone query before pagination
        $totalDeliveriesQuery = clone $query;
        $deliveries = $query->orderBy('updated_at', 'desc')->paginate(10);

        $totalDeliveries = $totalDeliveriesQuery->count();
        $successfulDeliveries = $totalDeliveriesQuery->where('status', 'COMPLETED')->count();
        $failedDeliveries = $totalDeliveriesQuery->where('status', 'FAILED')->count();
        $pendingDeliveries = $totalDeliveriesQuery->where('status', 'PENDING')->count();

        if ($filter === 'daily') {
            $reportDate = $startDate->format('F j, Y');
            $timeRange = '8:00 AM - 5:00 PM';
        } else {
            $reportDate = $startDate->format('F j, Y') . ' - ' . $endDate->format('F j, Y');
            $timeRange = '8:00 AM - 5:00 PM';
        }

        $selectedDriver = null;
        $driverName = null;

        if ($request->has('driver_id') && $request->driver_id != 'all') {
            $selectedDriver = User::find($request->driver_id);
            $driverName = $selectedDriver ? $selectedDriver->first_name . ' ' . $selectedDriver->last_name : null;
        }

        return view('owner.reports.delivery_report', compact(
            'deliveries',
            'drivers',
            'reportDate',
            'timeRange',
            'driverName',
            'totalDeliveries',
            'successfulDeliveries',
            'failedDeliveries',
            'pendingDeliveries',
            'filter'
        ));
    }

    // ACTIVITY LOGS

    public function viewActivityLog(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('id', 'desc');

        // Filter by usertype
        if ($request->filled('usertype')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('usertype', $request->input('usertype'));
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $activityLogs = $query->paginate(10);
        
        return view('owner.activityLog.index_activityLog', [
            'activity_logs' => $activityLogs,
            'selectedUsertype' => $request->input('usertype'),
            'selectedDate' => $request->input('date'),
        ]);
    }

    public function deleteactivityLog($id)
    {
        $activityLogs = ActivityLog::findOrFail($id);
        $activityLogs->delete();

        $user = Auth::user();
        $usertype = $user->usertype;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Delete',
            'description' => "{$user->username} ({$usertype}) Deleted Activity Log ID: $activityLogs->id",
        ]);

        return redirect('/owner/activityLog')->with('success', 'Activity Log Deleted Successfully');
    }
}
