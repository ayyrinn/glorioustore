<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AttendenceController;
use App\Http\Controllers\Dashboard\DatabaseBackupController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PosController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('Manager') || Auth::user()->hasRole('Kasir') || Auth::user()->hasRole('Kurir')) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('customerdashboard.index');
        }
    }
    return view('welcome');
});


// // CUSTOMER DASHBOARD
// Route::middleware(['auth', 'role:customer'])->group(function () {
//     Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

//     // DEFAULT DASHBOARD
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

// // DEFAULT PROFILE ROUTES
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
//     Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
// });


// // CUSTOMER DASHBOARD
// Route::middleware(['permission:customer.dashboard'])->group(function () {
//     Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customerdashboard.dashboard');
// });
//Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customerdashboard.dashboard');
Route::get('/customer/dashboard/products/{id}', [ProductController::class, 'showDetail'])->name('productdetailcustomer.index');
Route::get('/customer/dashboard', [CustomerDashboardController::class, 'getProductsByCategory'])->name('customerdashboard.index');

Route::middleware(['auth', 'checkrole:Customer'])->group(function () {
    Route::get('/customer/keranjang', [CartController::class, 'index'])->name('keranjang.index');
    Route::post('/customer/keranjang/add', [CartController::class, 'addCart'])->name('keranjang.addCart');
    Route::put('/customer/keranjang/update/{rowId}', [CartController::class, 'updateCart'])->name('keranjang.updateCart');
    Route::post('/customer/keranjang/update/{rowId}', [CartController::class, 'updateCart'])->name('keranjang.updateCart');
    Route::delete('/customer/keranjang/delete/{rowId}', [CartController::class, 'deleteCart'])->name('keranjang.deleteCart');
    Route::get('/customer/transaksi', [TransactionController::class, 'createOnlineOrder'])->name('transaksi.createOnlineOrder');
    Route::post('/customer/transaksi/store', [TransactionController::class, 'storeOnlineOrder'])->name('transaksi.storeOnlineOrder');
    Route::get('/customer/transaksi/pembayaran/{transactionid}', [TransactionController::class, 'payment'])->name('transaksi.payment');
    Route::post('/customer/transaksi/pembayaran/store', [TransactionController::class, 'storePayment'])->name('transaksi.storePayment');
    Route::get('/customer/historytransaksi', [TransactionController::class, 'showOrderHistory'])->name('transaksi.showOrderHistory');
    Route::get('/customer/transaksi/details/{transactionid}', [TransactionController::class, 'transactionOnlineDetails'])->name('transaksi.transactionDetails');


    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');


});


// ADMIN DASHBOARD
Route::middleware(['auth', 'checkrole:SuperAdmin,Admin,Manager,Kasir,Kurir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
});

// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// ====== USERS ======
Route::middleware(['permission:user.menu'])->group(function () {
    Route::resource('/users', UserController::class)->except(['show']);
});

// ====== CUSTOMERS ======
Route::middleware(['permission:customer.menu'])->group(function () {
    Route::resource('/customers', CustomerController::class);
});

// ====== SUPPLIERS ======
Route::middleware(['permission:supplier.menu'])->group(function () {
    Route::resource('/suppliers', SupplierController::class);
});

// ====== EMPLOYEES ======
Route::middleware(['permission:employee.menu'])->group(function () {
    Route::resource('/employees', EmployeeController::class);
});

// ====== EMPLOYEE ATTENDENCE ======
Route::middleware(['permission:attendence.menu'])->group(function () {
    Route::resource('/employee/attendence', AttendenceController::class)->except(['update', 'destroy']);
});

// ====== PRODUCTS ======
Route::middleware(['permission:product.menu'])->group(function () {
    Route::get('/products/import', [ProductController::class, 'importView'])->name('products.importView');
    Route::post('/products/import', [ProductController::class, 'importStore'])->name('products.importStore');
    Route::get('/products/export', [ProductController::class, 'exportData'])->name('products.exportData');
    Route::resource('/products', ProductController::class);
    Route::post('/products/addstock', [ProductController::class, 'addStock'])->name('products.addstock');

});

// ====== CATEGORY PRODUCTS ======
Route::middleware(['permission:category.menu'])->group(function () {
    Route::resource('/categories', CategoryController::class);
});

// ====== POS ======
Route::middleware(['permission:pos.menu'])->group(function () {
    Route::get('/pos', [PosController::class,'index'])->name('pos.index');
    Route::post('/pos/add', [PosController::class, 'addCart'])->name('pos.addCart');
    Route::post('/pos/update/{rowId}', [PosController::class, 'updateCart'])->name('pos.updateCart');
    Route::get('/pos/delete/{rowId}', [PosController::class, 'deleteCart'])->name('pos.deleteCart');
    Route::post('/pos/invoice/create', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    Route::post('/pos/invoice/print', [PosController::class, 'printInvoice'])->name('pos.printInvoice');

    // Create Transaction
    Route::post('/pos/order', [TransactionController::class, 'storeTransaction'])->name('pos.storeTransaction');
});

// ====== TRANSACTIONS ======
Route::middleware(['permission:transactions.menu'])->group(function () {
    Route::get('/transactions/offline', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transactions/offline/details/{transactionid}', [TransactionController::class, 'transactionDetails'])->name('transaction.transactionDetails');
    Route::get('/transactions/offline/invoice/download/{transactionid}', [TransactionController::class, 'invoiceDownload'])->name('transaction.invoiceDownload');
    
    // Online Transaction
    Route::get('/transactions/online', [TransactionController::class, 'online'])->name('transaction.online');
    Route::get('/transactions/online/update/{transactionid}', [TransactionController::class, 'editOnline'])->name('transaction.editOnline');
    Route::post('/transactions/online/update/cashier', [TransactionController::class, 'updateOnlineCashier'])->name('transaction.updateOnlineCashier');
    Route::post('/transactions/online/update/courier', [TransactionController::class, 'updateOnlineCourier'])->name('transaction.updateOnlineCourier');
    Route::put('/transactions/online/update/status', [TransactionController::class, 'updateStatusOnline'])->name('transaction.updateStatusOnline');
    Route::put('/transactions/online/update/cancel', [TransactionController::class, 'cancelStatusOnline'])->name('transaction.cancelStatusOnline');
    Route::post('/transactions/online/update/send', [TransactionController::class, 'completeOnline'])->name('transaction.completeOnline');
    // Route::resource('/transactions', TransactionController::class);
});

// ====== ORDERS ======
Route::middleware(['permission:orders.menu'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/add', [OrderController::class, 'addCart'])->name('order.addCart');
    Route::post('/orders/update/{rowId}', [OrderController::class, 'updateCart'])->name('order.updateCart');
    Route::get('/orders/delete/{rowId}', [OrderController::class, 'deleteCart'])->name('order.deleteCart');
    Route::post('/orders/invoice/create', [OrderController::class, 'createInvoice'])->name('order.createInvoice');
    Route::put('/orders/update/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::get('/orders/details/{orderid}', [OrderController::class, 'orderDetails'])->name('order.details');

    // Create Order
    Route::post('/orders/order', [OrderController::class, 'storeOrder'])->name('order.storeOrder');
});

// ====== DATABASE BACKUP ======
Route::middleware(['permission:database.menu'])->group(function () {
    Route::get('/database/backup', [DatabaseBackupController::class, 'index'])->name('backup.index');
    Route::get('/database/backup/now', [DatabaseBackupController::class, 'create'])->name('backup.create');
    Route::get('/database/backup/download/{getFileName}', [DatabaseBackupController::class, 'download'])->name('backup.download');
    Route::get('/database/backup/delete/{getFileName}', [DatabaseBackupController::class, 'delete'])->name('backup.delete');
});

// ====== ROLE CONTROLLER ======
Route::middleware(['permission:roles.menu'])->group(function () {
    // Permissions
    Route::get('/permission', [RoleController::class, 'permissionIndex'])->name('permission.index');
    Route::get('/permission/create', [RoleController::class, 'permissionCreate'])->name('permission.create');
    Route::post('/permission', [RoleController::class, 'permissionStore'])->name('permission.store');
    Route::get('/permission/edit/{id}', [RoleController::class, 'permissionEdit'])->name('permission.edit');
    Route::put('/permission/{id}', [RoleController::class, 'permissionUpdate'])->name('permission.update');
    Route::delete('/permission/{id}', [RoleController::class, 'permissionDestroy'])->name('permission.destroy');

    // Roles
    Route::get('/role', [RoleController::class, 'roleIndex'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'roleCreate'])->name('role.create');
    Route::post('/role', [RoleController::class, 'roleStore'])->name('role.store');
    Route::get('/role/edit/{id}', [RoleController::class, 'roleEdit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'roleUpdate'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'roleDestroy'])->name('role.destroy');

    // Role Permissions
    Route::get('/role/permission', [RoleController::class, 'rolePermissionIndex'])->name('rolePermission.index');
    Route::get('/role/permission/create', [RoleController::class, 'rolePermissionCreate'])->name('rolePermission.create');
    Route::post('/role/permission', [RoleController::class, 'rolePermissionStore'])->name('rolePermission.store');
    Route::get('/role/permission/{id}', [RoleController::class, 'rolePermissionEdit'])->name('rolePermission.edit');
    Route::put('/role/permission/{id}', [RoleController::class, 'rolePermissionUpdate'])->name('rolePermission.update');
    Route::delete('/role/permission/{id}', [RoleController::class, 'rolePermissionDestroy'])->name('rolePermission.destroy');
});

require __DIR__.'/auth.php';


