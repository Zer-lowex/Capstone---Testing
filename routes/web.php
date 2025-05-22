<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Routes for Admin
Route::middleware(['auth', 'Admin', 'PreventBackHistory'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'indexAdmin'])->name('admin.dashboard'); //Dashboard for Admin
    Route::get('/admin/settings/profile', [AdminController::class, 'viewProfile'])->name('admin.profile'); //Profile for Admin
    Route::put('/admin/settings/reset-password', [AdminController::class, 'updatePassword'])->name('admin.updatePass'); //Update Password for Admin
    Route::put('/admin/settings/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update'); //Update Profile for Admin
    Route::get('/admin/settings/alerts', [AdminController::class, 'viewAlerts'])->name('admin.profile.alerts'); //Product Levels for Admin
    Route::put('/admin/settings/alerts/update/{id}', [AdminController::class, 'updateAlerts'])->name('admin.update.alerts'); //Update Product Levels for Admin
    Route::get('/admin/settings/site', [AdminController::class, 'website'])->name('admin.view.website');
    Route::post('/admin/settings/site-update-image', [AdminController::class, 'updateImage'])->name('admin.site.update-image');
    Route::put('/admin/settings/site-update-description', [AdminController::class, 'updateDescription'])->name('admin.site.update-description');

    Route::get('/admin/products', [AdminController::class, 'viewProduct'])->name('product.view'); //View Products
    Route::get('/admin/products/search', [AdminController::class, 'searchProduct'])->name('admin.product.search'); //Search Product Function
    Route::get('/admin/products/filter', [AdminController::class, 'filterProducts'])->name('admin.products.filter');
    Route::post('/admin/products/add', [AdminController::class, 'addProduct'])->name('product.add'); //Add Product
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'editProduct'])->name('product.edit'); //Edit Product
    Route::put('/admin/products/update/{id}', [AdminController::class, 'updateProduct'])->name('product.update'); //Update Products
    Route::delete('/admin/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('product.delete'); //Delete Products
    Route::post('/admin/products/add-stock', [AdminController::class, 'addStock'])->name('admin.stock.add'); //Add Product Stock

    Route::get('/admin/customers', [AdminController::class, 'viewCustomer'])->name('customer.view'); //View Customers
    Route::get('/admin/customers/search', [AdminController::class, 'searchCustomer'])->name('admin.customer.search'); //Search Customers Function
    Route::post('/admin/customers/add', [AdminController::class, 'addCustomer']); //Add Customers
    Route::get('/admin/customers/edit/{id}', [AdminController::class, 'editCustomer']); //Edit Customers
    Route::put('/admin/customers/update/{id}', [AdminController::class, 'updateCustomer'])->name('customers.update'); //Update Customers
    Route::delete('/admin/customers/delete/{id}', [AdminController::class, 'deleteCustomer'])->name('customers.delete'); //Delete Customer

    Route::get('/admin/staff', [AdminController::class, 'viewStaff'])->name('staff.view'); //View Staff
    Route::get('/admin/staff/search', [AdminController::class, 'searchStaff'])->name('staff.search'); //Search Staff Function
    Route::post('/admin/staff/add', [AdminController::class, 'addStaff'])->name('staff.add'); //Add Staff
    Route::get('/admin/staff/edit/{id}', [AdminController::class, 'editStaff']); //Edit Staff
    Route::put('/admin/staff/update/{id}', [AdminController::class, 'updateStaff'])->name('staff.update'); //Update Staff
    Route::delete('/admin/staff/delete/{id}', [AdminController::class, 'deleteStaff'])->name('staff.delete'); //Delete Staff
    Route::put('/admin/staff/reset-password/{id}', [AdminController::class, 'resetPassword'])->name('staff.resetPassword'); //Reset Password Function

    Route::get('/admin/category', [AdminController::class, 'viewCategory'])->name('category.view'); //View Categories
    Route::get('/admin/category/search', [AdminController::class, 'searchCategory'])->name('category.search'); //Search Categories Function
    Route::post('/admin/category/add', [AdminController::class, 'addCategory'])->name('category.add'); //Add Category
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'editCategory'])->name('category.edit'); //Edit Category
    Route::put('/admin/category/update/{id}', [AdminController::class, 'updateCategory'])->name('category.update'); //Update Category
    Route::delete('/admin/category/delete/{id}', [AdminController::class, 'deleteCategory'])->name('category.delete'); //Delete Category

    Route::get('/admin/unit', [AdminController::class, 'viewUnit'])->name('unit.view'); //View Units
    Route::get('/admin/unit/search', [AdminController::class, 'searchUnit'])->name('unit.search'); //Search Units Function
    Route::post('/admin/unit/add', [AdminController::class, 'addUnit'])->name('unit.add'); //Add Unit
    Route::get('/admin/unit/edit/{id}', [AdminController::class, 'editUnit'])->name('unit.edit'); //Edit Unit
    Route::put('/admin/unit/update/{id}', [AdminController::class, 'updateUnit'])->name('unit.update'); //Update Unit
    Route::delete('/admin/unit/delete/{id}', [AdminController::class, 'deleteUnit'])->name('unit.delete'); //Delete Unit

    Route::get('/admin/supplier', [AdminController::class, 'viewSupplier'])->name('supplier.view'); //View Supplier
    Route::get('/admin/supplier/search', [AdminController::class, 'searchSupplier'])->name('supplier.search'); //Search Supplier Function
    Route::post('/admin/supplier/add', [AdminController::class, 'addSupplier'])->name('supplier.add'); //Add Supplier
    Route::get('/admin/supplier/edit/{id}', [AdminController::class, 'editSupplier'])->name('supplier.edit'); //Edit Supplier
    Route::put('/admin/supplier/update/{id}', [AdminController::class, 'updateSupplier'])->name('supplier.update'); //Update Supplier
    Route::delete('/admin/supplier/delete/{id}', [AdminController::class, 'deleteSupplier'])->name('supplier.delete'); //Delete Supplier
    Route::get('/admin/supplier/details/{supplier}', [AdminController::class, 'getDetails'])->name('supplier.details');
    Route::get('/admin/supplier/products/{supplier}', [AdminController::class, 'getProducts'])->name('supplier.products');
    Route::get('/admin/suppliers/{supplier}', [AdminController::class, 'show']);
    Route::get('/admin/suppliers/{supplier}/products', [AdminController::class, 'products']);

    Route::get('/admin/orders', [AdminController::class, 'viewOrders'])->name('admin.order.view'); //View Orders
    Route::get('/admin/pending-deliveries', [AdminController::class, 'viewDeliveries'])->name('admin.delivery.view'); //View Orders
    Route::post('/admin/deliveries/assign-driver', [AdminController::class, 'assignDriver'])->name('admin.assign.driver'); //Assign Driver
    Route::get('/admin/complete-deliveries', [AdminController::class, 'viewCompleteDeliveries']); //View Completed Orders
    Route::get('/admin/receipts/{id}', [AdminController::class, 'receipt'])->name('admin.receipt.view'); //View Receipt

    Route::get('/admin/reports', [AdminController::class, 'salesReport'])->name('admin.sales.view'); //View Sales
    Route::get('/admin/inventory-reports', [AdminController::class, 'inventoryReport'])->name('admin.inventory.view'); //View Inventory Report
    Route::get('/admin/cashier-reports', [AdminController::class, 'cashierReport'])->name('admin.cashierReport.view'); //View Cashier Report
    Route::get('/admin/delivery-reports', [AdminController::class, 'viewDeliveryReport'])->name('admin.deliveryReport.view'); //View Delivery Report

    Route::get('/admin/activityLog', [AdminController::class, 'viewActivityLog'])->name('admin.activityLog.view'); //View Activity Logs
    Route::delete('/admin/activityLog/delete/{id}', [AdminController::class, 'deleteActivityLog'])->name('activityLog.delete'); //Delete Activity Log

});

//Routes for Owner
Route::middleware(['auth', 'Owner', 'PreventBackHistory'])->group(function () {
    Route::get('/owner/dashboard', [HomeController::class, 'indexOwner'])->name('owner.dashboard'); //Dashboard for Owner
    Route::get('/owner/settings/profile', [OwnerController::class, 'viewProfile'])->name('owner.profile'); //Profile for Owner
    Route::put('/owner/settings/reset-password', [OwnerController::class, 'updatePassword'])->name('owner.updatePass'); //Update Password for Owner
    Route::put('/owner/settings/profile', [OwnerController::class, 'updateProfile'])->name('owner.profile.update'); //Update Profile for Owner
    Route::get('/owner/settings/alerts', [OwnerController::class, 'viewAlerts'])->name('owner.profile.alerts'); //Product Levels for Admin
    Route::put('/owner/settings/alerts/update/{id}', [OwnerController::class, 'updateAlerts'])->name('owner.update.alerts'); //Update Product Levels for Admin
    Route::get('/owner/settings/site', [OwnerController::class, 'website'])->name('owner.view.website');
    Route::post('/owner/settings/site-update-image', [OwnerController::class, 'updateImage'])->name('owner.site.update-image');
    Route::put('/owner/settings/site-update-description', [OwnerController::class, 'updateDescription'])->name('owner.site.update-description');

    Route::get('/owner/products', [OwnerController::class, 'viewProduct'])->name('owner.product.view'); //View Products
    Route::get('/owner/products/search', [OwnerController::class, 'searchProduct'])->name('owner.product.search'); //Search Product Function
    Route::get('/owner/products/filter', [OwnerController::class, 'filterProducts'])->name('owner.products.filter');
    Route::post('/owner/products/add', [OwnerController::class, 'addProduct'])->name('owner.product.add'); //Add Product
    Route::get('/owner/products/edit/{id}', [OwnerController::class, 'editProduct'])->name('owner.product.edit'); //Edit Product
    Route::put('/owner/products/update/{id}', [OwnerController::class, 'updateProduct'])->name('owner.product.update'); //Update Products
    Route::delete('/owner/products/delete/{id}', [OwnerController::class, 'deleteProduct'])->name('owner.product.delete'); //Delete Products
    Route::post('/owner/products/add-stock', [OwnerController::class, 'addStock'])->name('owner.stock.add'); //Add Product Stock

    Route::get('/owner/category', [OwnerController::class, 'viewCategory'])->name('owner.category.view'); //View Categories
    Route::get('/owner/category/search', [OwnerController::class, 'searchCategory'])->name('owner.category.search'); //Search Categories Function
    Route::post('/owner/category/add', [OwnerController::class, 'addCategory'])->name('owner.category.add'); //Add Category
    Route::get('/owner/category/edit/{id}', [OwnerController::class, 'editCategory'])->name('owner.category.edit'); //Edit Category
    Route::put('/owner/category/update/{id}', [OwnerController::class, 'updateCategory'])->name('owner.category.update'); //Update Category
    Route::delete('/owner/category/delete/{id}', [OwnerController::class, 'deleteCategory'])->name('owner.category.delete'); //Delete Category

    Route::get('/owner/unit', [OwnerController::class, 'viewUnit'])->name('owner.unit.view'); //View Units
    Route::get('/owner/unit/search', [OwnerController::class, 'searchUnit'])->name('owner.unit.search'); //Search Units Function
    Route::post('/owner/unit/add', [OwnerController::class, 'addUnit'])->name('owner.unit.add'); //Add Unit
    Route::get('/owner/unit/edit/{id}', [OwnerController::class, 'editUnit'])->name('owner.unit.edit'); //Edit Unit
    Route::put('/owner/unit/update/{id}', [OwnerController::class, 'updateUnit'])->name('owner.unit.update'); //Update Unit
    Route::delete('/owner/unit/delete/{id}', [OwnerController::class, 'deleteUnit'])->name('owner.unit.delete'); //Delete Unit

    Route::get('/owner/customers', [OwnerController::class, 'viewCustomer'])->name('owner.customer.view'); //View Customers
    Route::get('/owner/customers/search', [OwnerController::class, 'searchCustomer'])->name('owner.customer.search'); //Search Customers Function
    Route::post('/owner/customers/add', [OwnerController::class, 'addCustomer'])->name('owner.customer.add'); //Add Customers
    Route::get('/owner/customers/edit/{id}', [OwnerController::class, 'editCustomer'])->name('owner.customer.edit'); //Edit Customers
    Route::put('/owner/customers/update/{id}', [OwnerController::class, 'updateCustomer'])->name('owner.customer.update'); //Update Customers
    Route::delete('/owner/customers/delete/{id}', [OwnerController::class, 'deleteCustomer'])->name('owner.customer.delete'); //Delete Customer

    Route::get('/owner/staff', [OwnerController::class, 'viewStaff'])->name('owner.staff.view'); //View Staff
    Route::get('/owner/staff/search', [OwnerController::class, 'searchStaff'])->name('owner.staff.search'); //Search Staff Function
    Route::post('/owner/staff/add', [OwnerController::class, 'addStaff'])->name('owner.staff.add'); //Add Staff
    Route::get('/owner/staff/edit/{id}', [OwnerController::class, 'editStaff'])->name('owner.staff.edit'); //Edit Staff
    Route::put('/owner/staff/update/{id}', [OwnerController::class, 'updateStaff'])->name('owner.staff.update'); //Update Staff
    Route::delete('/owner/staff/delete/{id}', [OwnerController::class, 'deleteStaff'])->name('owner.staff.delete'); //Delete Staff

    Route::get('/owner/supplier', [OwnerController::class, 'viewSupplier'])->name('owner.supplier.view'); //View Supplier
    Route::get('/owner/supplier/search', [OwnerController::class, 'searchSupplier'])->name('owner.supplier.search'); //Search Supplier Function
    Route::post('/owner/supplier/add', [OwnerController::class, 'addSupplier'])->name('owner.supplier.add'); //Add Supplier
    Route::get('/owner/supplier/edit/{id}', [OwnerController::class, 'editSupplier'])->name('owner.supplier.edit'); //Edit Supplier
    Route::put('/owner/supplier/update/{id}', [OwnerController::class, 'updateSupplier'])->name('owner.supplier.update'); //Update Supplier
    Route::delete('/owner/supplier/delete/{id}', [OwnerController::class, 'deleteSupplier'])->name('owner.supplier.delete'); //Delete Supplier
    Route::get('/owner/supplier/details/{supplier}', [AdminController::class, 'getDetails'])->name('owner.supplier.details');
    Route::get('/owner/supplier/products/{supplier}', [AdminController::class, 'getProducts'])->name('owner.supplier.products');
    Route::get('/owner/suppliers/{supplier}', [OwnerController::class, 'show']);
    Route::get('/owner/suppliers/{supplier}/products', [OwnerController::class, 'products']);

    Route::get('/owner/orders', [OwnerController::class, 'viewOrders'])->name('owner.order.view'); //View Orders
    Route::get('/owner/pending-deliveries', [OwnerController::class, 'viewDeliveries'])->name('owner.delivery.view'); //View Orders
    Route::post('/owner/deliveries/assign-driver', [OwnerController::class, 'assignDriver'])->name('owner.assign.driver'); //Assign Driver
    Route::get('/owner/complete-deliveries', [OwnerController::class, 'viewCompleteDeliveries']); //View Orders
    Route::get('/owner/receipts/{id}', [OwnerController::class, 'receipt'])->name('owner.receipt.view'); //View Receipt

    Route::get('/owner/reports', [OwnerController::class, 'salesReport'])->name('owner.sales.view'); //View Sales
    Route::get('/owner/inventory-reports', [OwnerController::class, 'inventoryReport'])->name('owner.inventory.view'); //View Inventory Report
    Route::get('/owner/cashier-reports', [OwnerController::class, 'cashierReport'])->name('owner.cashierReport.view'); //View Cashier Report
    Route::get('/owner/delivery-reports', [OwnerController::class, 'viewDeliveryReport'])->name('owner.deliveryReport.view'); //View Delivery Report

    Route::get('/owner/activityLog', [OwnerController::class, 'viewActivityLog'])->name('owner.activityLog.view'); //View Activity Logs
    Route::delete('/owner/activityLog/delete/{id}', [OwnerController::class, 'deleteActivityLog'])->name('owner.activityLog.delete'); //Delete Activity Log
});

//Routes for Staff
Route::middleware(['auth', 'Staff', 'PreventBackHistory'])->group(function () {
    Route::get('/staff/dashboard', [HomeController::class, 'indexStaff'])->name('staff.dashboard'); //Dashboard for Staff
    Route::get('/staff/settings/profile', [StaffController::class, 'viewProfile'])->name('staff.profile'); //Profile for Staff
    Route::put('/staff/settings/reset-password', [StaffController::class, 'updatePassword'])->name('staff.updatePass'); //Update Password for Staff
    Route::put('/staff/settings/profile', [StaffController::class, 'updateProfile'])->name('staff.profile.update'); //Update Profile for Staff

    Route::get('/staff/products', [StaffController::class, 'viewProduct'])->name('staff.product.view'); //View Products
    Route::get('/staff/products/search', [StaffController::class, 'searchProduct'])->name('staff.product.search'); //Search Product Function
    Route::get('/products/filter', [StaffController::class, 'filterProducts'])->name('staff.products.filter');
    Route::post('/staff/products/add', [StaffController::class, 'addStock'])->name('stock.add'); //Add Product Stock

    Route::get('/staff/orders', [StaffController::class, 'viewOrders'])->name('staff.order.view'); //View Orders
    Route::get('/staff/deliveries', [StaffController::class, 'viewDeliveries'])->name('staff.delivery.view'); //View Orders
    Route::get('/staff/complete-deliveries', [StaffController::class, 'viewCompleteDeliveries']); //View Orders
    Route::get('/staff/receipts/{id}', [StaffController::class, 'receipt'])->name('staff.receipt.view'); //View Receipt

    Route::get('/staff/reports', [StaffController::class, 'salesReport'])->name('staff.sales.view'); //View Sales
    Route::get('/staff/inventory-reports', [StaffController::class, 'inventoryReport'])->name('staff.inventory.view'); //View Inventory Report

    Route::get('/staff/supplier', [StaffController::class, 'viewSupplier'])->name('staff.supplier.view'); //View Supplier
    Route::get('/staff/supplier/search', [StaffController::class, 'searchSupplier'])->name('staff.supplier.search'); //Search Supplier Function
    Route::get('/staff/activityLog', [StaffController::class, 'viewActivityLog'])->name('staff.activityLog.view'); //View Activity Logs
    Route::get('/staff/supplier/details/{supplier}', [StaffController::class, 'getDetails'])->name('staff.supplier.details');
    Route::get('/staff/supplier/products/{supplier}', [StaffController::class, 'getProducts'])->name('staff.supplier.products');
    Route::get('/staff/suppliers/{supplier}', [StaffController::class, 'show']);
    Route::get('/staff/suppliers/{supplier}/products', [StaffController::class, 'products']);
});

//Routes for Cashier
Route::middleware(['auth', 'Cashier', 'PreventBackHistory'])->group(function () {
    Route::get('/cashier/dashboard/{id?}', [HomeController::class, 'indexCashier'])->name('cashier.dashboard'); //Dashboard for Cashier
    Route::get('/cashier/dashboard2', [HomeController::class, 'indexCashier2'])->name('cashier.dashboard2'); //Dashboard for Cashier
    Route::get('/cashier/settings/profile', [CashierController::class, 'viewProfile'])->name('cashier.profile'); //Profile for Cashier
    Route::put('/cashier/settings/reset-password', [CashierController::class, 'updatePassword'])->name('cashier.updatePass'); //Update Password for Cashier
    Route::put('/cashier/settings/profile', [CashierController::class, 'updateProfile'])->name('cashier.profile.update'); //Update Profile for Cashier

    Route::get('/cashier/products', [CashierController::class, 'viewProduct'])->name('cashier.product.view'); //View Products
    Route::get('/cashier/products/search', [CashierController::class, 'searchProduct'])->name('cashier.product.search'); //Search Product Function
    Route::get('/cashier/products/reserved', [CashierController::class, 'reservedProduct'])->name('cashier.product.reserved'); 
    Route::post('/cashier/reservations/cancel', [CashierController::class, 'cancelReservation'])->name('cashier.reservations.cancel');
    Route::post('/cashier/reservations/accept', [CashierController::class, 'acceptReservation'])->name('cashier.reservations.accept');
    Route::post('/cashier/cancel-reservation', [CashierController::class, 'cancelReservationPOS'])->name('cashier.cancelReservation'); // Cancel Reservation on POS

    Route::get('/cashier/customers', [CashierController::class, 'viewCustomer'])->name('customer.view'); //View Customers
    Route::get('/cashier/autocomplete', [CashierController::class, 'autocomplete'])->name('products.autocomplete');
    Route::get('/cashier/customers/search', [CashierController::class, 'searchCustomer'])->name('cashier.customer.search'); //Search Customers Function
    Route::post('/cashier/customers/add', [CashierController::class, 'addCustomer']); //Add Customers

    Route::post('/cashier/dashboard/add', [CashierController::class, 'addSale'])->name('cashier.addSale'); //Add Sale

    Route::get('/cashier/orders', [CashierController::class, 'viewOrders'])->name('cashier.order.view'); //View Orders
    Route::get('/cashier/receipts/{id}', [CashierController::class, 'receipt'])->name('cashier.receipt.view'); //View Receipt
    Route::get('/cashier/receipts2/{id}', [CashierController::class, 'receipt2'])->name('cashier.receipt2.view'); //View Receipt

});


require __DIR__.'/customer-auth.php';


