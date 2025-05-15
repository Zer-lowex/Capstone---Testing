<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\ReservedProducts;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function indexAdmin()
    {
        $customerCount = Customer::count();
        $productCount = Product::count();
        $expiredCount = Product::where('expiration_date', '<', Carbon::now())->count();
        $almostExpiredCount = Product::where('expiration_date', '>', Carbon::now())
            ->where('expiration_date', '<', Carbon::now()->addMonth()) // add 1 month
            ->count();
        $saleCount = Sale::where('status', 'COMPLETE')->count();
        $supplierCount = Supplier::count();
        // $newCustomersCount = $this->getNewCustomersCount();
        $activity_logs = ActivityLog::orderBy('updated_at', 'desc')->take(10)->get();
        $deliveryCount = Delivery::where('status', 'PENDING')->count();
        $stockAlertThresholdCount = Product::where('quantity', '<=', DB::raw('stock_alert_threshold'))->count();
        $lowStockCount = Product::where('quantity', '<=', DB::raw('reorder_level'))
            ->where('quantity', '>', DB::raw('stock_alert_threshold'))
            ->count();

        $monthlyProfit = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->selectRaw('
                            SUM(
                                (sale_items.total_amount / 1.12) -
                                (sale_items.quantity * products.cost_price)
                            ) as profit
                        ')
            ->first()->profit ?? 0;
        $dailyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                            DAY(sale_items.created_at) as day,
                            SUM(
                                (sale_items.total_amount / 1.12) - 
                                (sale_items.quantity * products.cost_price)
                            ) as profit
                        ')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('DAY(sale_items.created_at)'))
            ->orderBy('day')
            ->get();
        $dailyProfit = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('DATE(sale_items.created_at) as date, SUM(sale_items.total_amount - (sale_items.quantity * products.cost_price)) as profit')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('DATE(sale_items.created_at)'))
            ->get();

        $todayProfit = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                            SUM(
                                (sale_items.total_amount / 1.12) - 
                                (sale_items.quantity * products.cost_price)
                            ) as profit
                        ')
            ->whereDate('sale_items.created_at', Carbon::today())
            ->first()->profit ?? 0;
        $weeklyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                            WEEK(sale_items.created_at) as week,
                            SUM(
                                (sale_items.total_amount / 1.12) - 
                                (sale_items.quantity * products.cost_price)
                            ) as profit
                        ')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('WEEK(sale_items.created_at)'))
            ->orderBy('week')
            ->get();
        $yearlyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                            MONTH(sale_items.created_at) as month,
                            SUM(
                                (sale_items.total_amount / 1.12) - 
                                (sale_items.quantity * products.cost_price)
                            ) as profit
                        ')
            ->whereYear('sale_items.created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(sale_items.created_at)'))
            ->orderBy('month')
            ->get();

        $topCategories = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(sale_items.quantity) as value'))
            ->groupBy('categories.name')
            ->orderByDesc('value')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'products.name',
                'units.name as unit',
                DB::raw('SUM(sale_items.quantity) as value')
            )
            ->groupBy('products.name', 'units.name')
            ->orderByDesc('value')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        return view('admin.index', compact([
            'customerCount',
            'monthlyProfit',
            'saleCount',
            'supplierCount',
            'activity_logs',
            'topCategories',
            'topProducts',
            'todayProfit',
            'productCount',
            'lowStockCount',
            'stockAlertThresholdCount',
            'deliveryCount',
            'dailyProfitData',
            'weeklyProfitData',
            'yearlyProfitData',
            'expiredCount',
            'almostExpiredCount'
        ]));
    }

    public function indexOwner()
    {
        $customerCount = Customer::count();
        $productCount = Product::count();
        $expiredCount = Product::where('expiration_date', '<', Carbon::now())->count();
        $almostExpiredCount = Product::where('expiration_date', '>', Carbon::now())
            ->where('expiration_date', '<', Carbon::now()->addMonth()) // add 1 month
            ->count();
        $saleCount = Sale::where('status', 'COMPLETE')->count();
        $supplierCount = Supplier::count();
        $newCustomersCount = $this->getNewCustomersCount();
        $activity_logs = ActivityLog::orderBy('updated_at', 'desc')->take(10)->get();
        $deliveryCount = Delivery::where('status', 'PENDING')->count();
        $stockAlertThresholdCount = Product::where('quantity', '<=', DB::raw('stock_alert_threshold'))->count();
        $lowStockCount = Product::where('quantity', '<=', DB::raw('reorder_level'))
            ->where('quantity', '>', DB::raw('stock_alert_threshold'))
            ->count();

        $monthlyProfit = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->selectRaw('
                SUM(
                    (sale_items.total_amount / 1.12) -
                    (sale_items.quantity * products.cost_price)
                ) as profit
            ')
            ->first()->profit ?? 0;
        $dailyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                DAY(sale_items.created_at) as day,
                SUM(
                    (sale_items.total_amount / 1.12) - 
                    (sale_items.quantity * products.cost_price)
                ) as profit
            ')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('DAY(sale_items.created_at)'))
            ->orderBy('day')
            ->get();
        $dailyProfit = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('DATE(sale_items.created_at) as date, SUM(sale_items.total_amount - (sale_items.quantity * products.cost_price)) as profit')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('DATE(sale_items.created_at)'))
            ->get();

        $todayProfit = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                SUM(
                    (sale_items.total_amount / 1.12) - 
                    (sale_items.quantity * products.cost_price)
                ) as profit
            ')
            ->whereDate('sale_items.created_at', Carbon::today())
            ->first()->profit ?? 0;
        $weeklyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                WEEK(sale_items.created_at) as week,
                SUM(
                    (sale_items.total_amount / 1.12) - 
                    (sale_items.quantity * products.cost_price)
                ) as profit
            ')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('WEEK(sale_items.created_at)'))
            ->orderBy('week')
            ->get();
        $yearlyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                MONTH(sale_items.created_at) as month,
                SUM(
                    (sale_items.total_amount / 1.12) - 
                    (sale_items.quantity * products.cost_price)
                ) as profit
            ')
            ->whereYear('sale_items.created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(sale_items.created_at)'))
            ->orderBy('month')
            ->get();

        $topCategories = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(sale_items.quantity) as value'))
            ->groupBy('categories.name')
            ->orderByDesc('value')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'products.name',
                'units.name as unit',
                DB::raw('SUM(sale_items.quantity) as value')
            )
            ->groupBy('products.name', 'units.name')
            ->orderByDesc('value')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        return view('owner.index', compact([
            'customerCount',
            'monthlyProfit',
            'saleCount',
            'supplierCount',
            'newCustomersCount',
            'activity_logs',
            'topCategories',
            'topProducts',
            'todayProfit',
            'productCount',
            'lowStockCount',
            'stockAlertThresholdCount',
            'deliveryCount',
            'dailyProfitData',
            'weeklyProfitData',
            'yearlyProfitData',
            'expiredCount',
            'almostExpiredCount'
        ]));
    }

    public function getNewCustomersCount()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $newCustomersCount = Customer::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        return $newCustomersCount;
    }

    public function indexStaff()
    {
        $activity_logs = ActivityLog::orderBy('updated_at', 'desc')->take(10)->get();
        $productCount = Product::count();
        $expiredCount = Product::where('expiration_date', '<', Carbon::now())->count();
        $almostExpiredCount = Product::where('expiration_date', '>', Carbon::now())
            ->where('expiration_date', '<', Carbon::now()->addMonth()) // add 1 month
            ->count();
        $supplierCount = Supplier::count();
        $deliveryCount = Delivery::where('status', 'PENDING')->count();
        $lowStockCount = Product::where('quantity', '<=', DB::raw('reorder_level'))
            ->where('quantity', '>', DB::raw('stock_alert_threshold'))
            ->count();
        $stockAlertThresholdCount = Product::where('quantity', '<=', DB::raw('stock_alert_threshold'))->count();
        $dailyProfitData = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                DAY(sale_items.created_at) as day,
                SUM(
                    (sale_items.total_amount / 1.12) - 
                    (sale_items.quantity * products.cost_price)
                ) as profit
            ')
            ->whereMonth('sale_items.created_at', Carbon::now()->month)
            ->groupBy(DB::raw('DAY(sale_items.created_at)'))
            ->orderBy('day')
            ->get();

        $topCategories = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(sale_items.quantity) as value'))
            ->groupBy('categories.name')
            ->orderByDesc('value')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'products.name',
                'units.name as unit',
                DB::raw('SUM(sale_items.quantity) as value')
            )
            ->groupBy('products.name', 'units.name')
            ->orderByDesc('value')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        return view('staff.index', compact(
            'activity_logs',
            'productCount',
            'supplierCount',
            'deliveryCount',
            'topCategories',
            'topProducts',
            'dailyProfitData',
            'lowStockCount',
            'stockAlertThresholdCount',
            'expiredCount',
            'almostExpiredCount'
        ));
    }

    public function indexCashier()
    {
        $customers = Customer::all();
        $products = Product::with('unit')->get();

        $sale = Sale::with(['customer', 'user', 'saleItems.product'])->latest()->first(); // Fetch the latest sale

        $reservedProducts = ReservedProducts::with('product.unit')
            ->where('status', 'accepted')
            ->get();

        // Prepare reserved products data
        $reservedProductsData = [];
        foreach ($reservedProducts as $reservedProduct) {
            $reservedProductsData[] = [
                'id' => $reservedProduct->product->id,
                'name' => $reservedProduct->product->name,
                'unit' => $reservedProduct->product->unit->name ?? 'N/A',
                'price' => $reservedProduct->product->sell_price,
                'quantity' => $reservedProduct->quantity
            ];
        }

        // Return the view with the reserved products
        return view('cashier.index2', compact('customers', 'products', 'sale', 'reservedProductsData'));
    }

    public function indexCashier2()
    {
        $customers = Customer::all();
        $products = Product::with('unit')->get();

        $sale = Sale::with(['customer', 'user', 'saleItems.product'])->latest()->first(); // Fetch the latest sale

        $reservedProducts = ReservedProducts::with('product.unit')
            ->where('status', 'accepted')
            ->get();

        // Prepare reserved products data
        $reservedProductsData = [];
        foreach ($reservedProducts as $reservedProduct) {
            $reservedProductsData[] = [
                'id' => $reservedProduct->product->id,
                'name' => $reservedProduct->product->name,
                'unit' => $reservedProduct->product->unit->name ?? 'N/A',
                'price' => $reservedProduct->product->sell_price,
                'quantity' => $reservedProduct->quantity
            ];
        }

        // Return the view with the reserved products
        return view('cashier.indexR', compact('customers', 'products', 'sale', 'reservedProductsData'));
    }
}
