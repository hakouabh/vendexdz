<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserManagementController as AdminUserController;
use App\Http\Controllers\Admin\LinkManagementController as ManagerLinkController;
use App\Http\Controllers\Admin\StatuManagementController  as ManagerStatuController;
use App\Http\Controllers\Admin\PriceManagementController  as ManagerFeesController;
use App\Http\Controllers\Admin\OrderManagementController as AdminOrderController;

use App\Http\Controllers\Agent\OrderManagementController as AgentOrderController;
use App\Http\Controllers\Agent\ChatController;
use App\Http\Controllers\Agent\ProductController;
use App\Http\Controllers\Agent\BillsController;
use App\Http\Controllers\Agent\ReportController;

use App\Http\Controllers\Store\CompaniesManagementController  as ManagerCompaniesController;
use App\Http\Controllers\Store\ProductsManagementController  as ManagerProductsController;
use App\Http\Controllers\Store\TerritoryManagementController  as ManagerTerritoryController;
use App\Http\Controllers\Store\OrderManagementController as StoreOrderController;
use App\Http\Controllers\Store\BillsController as StoreBillController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
// Route::get('/sync', [SyncController::class, 'handle'])
//     ->withoutMiddleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware([
    'auth:web',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/waitactivation', function () {
        return view('waitactivation');
    })->name('waitactivation');



    /*
|--------------------------------------------------------------------------
| MANAGER ROUTES
|--------------------------------------------------------------------------
| URL: domain.com/manager/users/...
*/
    Route::middleware(['auth', 'role:2'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.admin-dashboard');
        })->name('admin-dashboard');
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
        Route::get('/impersonate/{user}', [AdminUserController::class, 'impersonate'])->name('impersonate');
        Route::get('/admin/link', [ManagerLinkController::class, 'index'])->name('admin.link');
        Route::get('/admin/status', [ManagerStatuController::class, 'index'])->name('admin.status');
        Route::get('/admin/fees', [ManagerFeesController::class, 'index'])->name('admin.fees');
        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
        Route::get('/admin/create-order', [AdminOrderController::class, 'create'])->name('admin.create-order');
        Route::get('/admin/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('log-viewer');
    });
    Route::middleware(['auth', 'role:4'])->group(function () {
        Route::get('/agent/dashboard', function () {
            return view('agent.agent-dashboard');
        })->name('agent-dashboard');
        Route::get('/agent/orders', [AgentOrderController::class, 'index'])->name('agent.orders');
        Route::get('/agent/create-orders', [AgentOrderController::class, 'create'])->name('agent.create-order');
        Route::get('/agent/products', [ProductController::class, 'index'])->name('agent.products');
        Route::get('/agent/chat', [ChatController::class, 'index'])->name('agent.chat');
        Route::get('/agent/bills', [BillsController::class, 'index'])->name('agent.bills');
        Route::get('/agent/report', [ReportController::class, 'index'])->name('agent.report');
    });

    Route::middleware(['auth', 'role:5'])->group(function () {
        Route::get('/store/dashboard', function () {
            return view('store.store-dashboard');
        })->name('store-dashboard');
        Route::get('/store/companies', [ManagerCompaniesController::class, 'index'])->name('store.companies');
        Route::get('/store/orders', [StoreOrderController::class, 'index'])->name('store.orders');
        Route::get('/store/create-order', [StoreOrderController::class, 'create'])->name('store.create-order');
        Route::get('/store/bills', [StoreBillController::class, 'index'])->name('store.bills');
        Route::get('/store/territories', [ManagerTerritoryController::class, 'index'])->name('store.territories');
        Route::get('/store/products', [ManagerProductsController::class, 'index'])->name('store.products');
    });
    Route::get('/impersonate-leave', [AdminUserController::class, 'leave'])->name('impersonate.leave');
});
