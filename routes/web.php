<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\OnDeliveryController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserStatisticsController;


// use App\Http\Controllers\SyncController;
use App\Http\Middleware\CheckPermission;

//new 
use App\Http\Controllers\Admin\UserManagementController as AdminUserController;
use App\Http\Controllers\Manager\UserManagementController as ManagerUserController;
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
});
// Route::get('/sync', [SyncController::class, 'handle'])
//     ->withoutMiddleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware([
    'auth:sanctum',
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
      Route::get('/admin/dashboard', function () {return view('admin.admin-dashboard');})->name('admin-dashboard');
      Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
      Route::get('/admin/link', [ManagerLinkController::class, 'index'])->name('admin.link');
      Route::get('/admin/status', [ManagerStatuController::class, 'index'])->name('admin.status');
      Route::get('/admin/fees', [ManagerFeesController::class, 'index'])->name('admin.fees');
      Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
      });

     
      //Agent Routs
      Route::middleware(['auth', 'role:4'])->group(function () {
      Route::get('/agent/dashboard', function () {return view('agent.agent-dashboard');})->name('agent-dashboard');
      Route::get('/agent/orders', [AgentOrderController::class, 'index'])->name('agent.orders');
      Route::get('/agent/products', [ProductController::class, 'index'])->name('agent.products');
      Route::get('/agent/chat', [ChatController::class, 'index'])->name('agent.chat');
      Route::get('/agent/bills', [BillsController::class, 'index'])->name('agent.bills');
      Route::get('/agent/report', [ReportController::class, 'index'])->name('agent.report');
      });
      //store 
     // Route::middleware(['auth', 'role:5'])->group(function () {
      Route::get('/store/dashboard', function () {return view('store.store-dashboard');})->name('store-dashboard');
      Route::get('/store/companies', [ManagerCompaniesController::class, 'index'])->name('store.companies');
      Route::get('/store/orders', [StoreOrderController::class, 'index'])->name('store.orders');
      Route::get('/store/bills', [StoreBillController::class, 'index'])->name('store.bills');
      Route::get('/store/territories', [ManagerTerritoryController::class, 'index'])->name('store.territories');
      Route::get('/store/products', [ManagerProductsController::class, 'index'])->name('store.products');
     // });
  //  Route::middleware([CheckPermission::class . ':show_users'])->get('/admin/users/{$user}', [UserController::class, 'index'])->name('admin.users');
  //  Route::middleware([CheckPermission::class . ':show_users'])->get('/admin/shops/{$shop}', [UserController::class, 'index'])->name('admin.users');
  //  Route::middleware([CheckPermission::class . ':show_users'])->get('/admin/shops/{$shop}', [UserController::class, 'index'])->name('admin.users');

  //  Route::middleware([CheckPermission::class . ':show_sheets'])->get('/admin/sheets', [SheetController::class, 'index'])->name('admin.sheets');
  //  Route::middleware([CheckPermission::class . ':show_orders'])->get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
  //  Route::middleware([CheckPermission::class . ':show_shops'])->get('/admin/shops', [ShopController::class, 'index'])->name('admin.shops');
  //  Route::middleware([CheckPermission::class . ':show_roles'])->get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
  //  Route::middleware([CheckPermission::class . ':show_settings'])->get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
  //  Route::middleware([CheckPermission::class . ':show_companies'])->get('/admin/companies', [CompaniesController::class, 'index'])->name('admin.companies');
  //  Route::middleware([CheckPermission::class . ':show_ondelivery'])->get('/admin/ondelivery', [OnDeliveryController::class, 'index'])->name('admin.ondelivery');
  //  Route::middleware([CheckPermission::class . ':show_productinfo'])->get('/admin/info', [ProductsController::class, 'index'])->name('admin.info');     
  //  Route::middleware([CheckPermission::class . ':show_statics_user'])->get('/admin/statics/users', [UserStatisticsController::class, 'index'])->name('admin.statics.users');
  //  Route::middleware([CheckPermission::class . ':show_statics_user'])->get('/admin/statics/users/{id}', [UserStatisticsController::class, 'show'])->name('admin.statics.users.show');
  //  Route::middleware([CheckPermission::class . ':show_statics_shop'])->get('/admin/statics/shops', [ProductsController::class, 'index'])->name('admin.statics.shops');
  //  Route::middleware([CheckPermission::class . ':show_bills'])->get('/admin/bills', [BillsController::class, 'index'])->name('admin.bills');
  //  Route::post('/user-ping', [UserStatusController::class, 'ping']);
    


});


use App\Services\AndersonServices\AndersonCreateOrderService;
use App\Services\AndersonServices\AndersonTrackingService;

Route::get('/test-traking', function () {
    $service = new AndersonTrackingService();
    return $service->TrackThem();
});

Route::get('/test-zr', function () {
    $service = new \App\Services\TerritoryServices\ZRTerritoryService();
    return $service->getEverythingCached();
});
Route::get('/test-and', function () {
    $service = new \App\Services\TerritoryServices\AndrsonTerritoryService();
    return $service->getEverythingCached();
});
Route::get('/test-anderson-order', function () {
    $service = new AndersonCreateOrderService();

    // تجهيز بيانات طلب تجريبي (Mock Data)
    $testOrder = [
        "0" => [
                "tracking"=>"DEMO85345",
                "reference"=>"DEMO853",
                "nom_client"=> "client 2",
                "telephone"=> "0776671566",
                "telephone_2"=> "",
                "adresse"=> "17 rue med",
                "code_postal"=>"",
                "commune"=> "Draria",
                "code_wilaya"=> "16",
                "montant"=> "5000",
                "remarque"=> "test",
                "produit"=> "tesrty",
                "quantite"=> "1",
                "type"=> "1",
                "stop_desk"=>0,
                "weight"=> "2"
            
        ]
    ];

    try {
        $result = $service->sendOrders($testOrder);
        
        // عرض النتيجة بشكل منسق للفحص
        return response()->json([
            'status' => 'Request Sent',
            'payload_sent' => $testOrder,
            'api_response' => $result
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Error',
            'message' => $e->getMessage()
        ], 500);
    }
});
Route::get('/test-tracking/{tracking_number}', function ($tracking_number) {
    $service = new AndersonTrackingService();
    $result = $service->getTrackingHistory($tracking_number);

    if (!$result) {
        return response()->json(['error' => 'Could not fetch tracking data'], 404);
    }

    return response()->json([
        'message' => 'Tracking data retrieved and mapped successfully',
        'unified_status' => $result['last_status'], 
        'unified_history' => $result['history'],  
        'original_api_response' => $result['raw_data'] 
    ]);
});