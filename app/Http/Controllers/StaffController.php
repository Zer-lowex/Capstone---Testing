<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StaffController extends Controller
{
    // PROFILE
    
    public function viewProfile()
    {
        $user = Auth::user();
        return view('staff.settings.profile', compact('user'));
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

        return redirect()->route('staff.profile')->with('success', 'Profile Updated Successfully.');
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

        return redirect()->route('staff.profile')->with('success', 'Password Changed Successfully.');
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

        return view('staff.products.index_products', [
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
            'description' => "{$user->username} ({$usertype} ID: $user->id) Added {$request->quantity} Units of Stock for Product ID: {$product->id}",
        ]);

        return redirect('/staff/products')->with('success', 'Added Product Stock Successfully');
    }


    // SUPPLIER

    public function viewSupplier()
    {
        $supplier = Supplier::orderBy('id','asc')->paginate(10);
        return view('staff.supplier.index_supplier', [
            'suppliers' => $supplier
        ]);
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

        return view('staff.orders.index_orders', compact('sales'));
    }

    public function viewDeliveries(Request $request)
    {
        $query = Delivery::query();

        // Apply filter if selected
        if ($request->has('filter') && !empty($request->filter)) {
            $query->where('status', $request->filter);
        }

        // Fetch sales with filtering and pagination
        $deliveries = $query->orderBy('id', 'desc')->paginate(10);

        return view('staff.orders.deliveries', [
            'deliveries' => $deliveries,
        ]);
    }

    public function viewCompleteDeliveries()
    {
        $deliveries = Delivery::where('status', 'COMPLETE')
        ->with(['user', 'sale'])
        ->orderBy('id','desc')->paginate(10);
        return view('staff.orders.complete_deliveries', [
            'deliveries' => $deliveries
        ]);
    }

    public function receipt($id)
    {
        $sale = Sale::with(['customer', 'user', 'saleItems.product'])->findOrFail($id);

        return view('staff.receipts.index_receipt', [
            'sale' => $sale,
        ]);
    }

    // REPORTS

    public function salesReport(Request $request)
    {
        // Get the filter type selected by the user
        $filter = $request->input('filter', 'monthly');
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

        return view('staff.reports.sales_report', compact('sales'));
    }

    public function inventoryReport(Request $request)
    {
        // Get the filter type selected by the user
        $filter = $request->input('filter', 'monthly');
        $stockFilter = $request->input('stockFilter');

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

        // Fetch inventory data, including stock movements and current stock
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
            ->whereBetween('stock_movements.created_at', [$startDate, $endDate]) 
            ->groupBy('products.id', 'products.name', 'units.name', 'products.quantity', 'categories.name', 'suppliers.name', 'products.reorder_level', 'products.stock_alert_threshold', 'products.sell_price');
            
            if ($request->has('stockFilter')) {
                $inventory->whereBetween('stock_movements.created_at', [$startDate, $endDate]);
            }

            if ($stockFilter == 'stockAdded_asc') {
                $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Added" THEN stock_movements.quantity ELSE 0 END) ASC');
            } elseif ($stockFilter == 'stockAdded_desc') {
                $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Added" THEN stock_movements.quantity ELSE 0 END) DESC');
            } elseif ($stockFilter == 'stockSold_asc') {
                $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Sold" THEN stock_movements.quantity ELSE 0 END) ASC');
            } elseif ($stockFilter == 'stockSold_desc') {
                $inventory->orderByRaw('SUM(CASE WHEN stock_movements.type = "Sold" THEN stock_movements.quantity ELSE 0 END) DESC');
            }
            
            $inventory = $inventory->paginate(10);


        return view('staff.reports.inventory_report', compact('inventory', 'filter', 'stockFilter'));
    }
    
    // ACTIVITY LOG

    public function viewActivityLog(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('id', 'desc');

        // Exclude Owner and Admin
        $query->whereHas('user', function ($q) {
            $q->whereNotIn('usertype', ['Owner', 'Admin']);
        });

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
        
        return view('staff.activityLog.index_activityLog', [
            'activity_logs' => $activityLogs,
            'selectedUsertype' => $request->input('usertype'),
            'selectedDate' => $request->input('date'),
        ]);
    }
}
