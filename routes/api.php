<?php

use App\Http\Controllers\Admin\PushNotification\PushNotificationController;
use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Common\CommonController;
// Auth
use App\Http\Controllers\Api\Customer\CustomerController;
// Common
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\HRM\AttendanceController;
use App\Http\Controllers\Api\HRM\EmployeeController;
// IndentRequest
use App\Http\Controllers\Api\IncomeExpense\ExpenseController;
use App\Http\Controllers\Api\IncomeExpense\UserAdvanceController;
// Purchase
use App\Http\Controllers\Api\IndentRequest\StoreIndentReqestController;
use App\Http\Controllers\Api\IndentRequest\WarehouseIndentRequestController;

// Sales
use App\Http\Controllers\Api\Product\FishCuttingController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\ReDistributionController;
use App\Http\Controllers\Api\Product\SpoilageController;
use App\Http\Controllers\Api\Purchase\PurchaseController;
use App\Http\Controllers\Api\Report\ReportController;
use App\Http\Controllers\Api\Sales\SalesOrderController;

// Cash Paid
use App\Http\Controllers\Api\Store\CashRegisterController;
use App\Http\Controllers\Api\Store\CashtoOfficeController;

// HRM
use App\Http\Controllers\Api\Store\ReturnController;

// Report
use App\Http\Controllers\Api\supplier\HomeController as SupplierHomeController;

// Supplier API
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});*/

// Route::post('/s3-logo-upload', [SettingsController::class, 's3LogoUpload']);

// Auth Routes
Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'loginStore']);

    Route::post('/verify-otp', [AuthenticatedSessionController::class, 'verifyOtp']);
    Route::post('/verify_token', [AuthenticatedSessionController::class, 'apiVerifyToken']);

    // FCM Token Stoe APT
    Route::post('/save-token', [AuthenticatedSessionController::class, 'saveToken']);

    // Route::get('/users', [SampleDataController::class, 'getUsers']);

    // Invoice number
    Route::post('invoice-number', [CommonController::class, 'getinvoicecode']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('notification/list', [CommonController::class, 'notification_list']);
        // App Menu
        Route::post('app-menu/list', [CommonController::class, 'appmenu']);

        // DashBoard
        Route::post('dashboard', [HomeController::class, 'dashboard']);

        // Warehouse List
        Route::post('warehouse/list', [CommonController::class, 'warehouselist']);

        // Store List
        Route::post('store/list', [CommonController::class, 'storeslist']);

        // Customer List
        Route::post('customer/list', [CommonController::class, 'customerlist']);
        Route::post('customer-details', [CommonController::class, 'customerDetails']);
        Route::post('customer-order/list', [CommonController::class, 'customerSaleslist']);
        Route::post('customer-order/multi-payment-update', [CommonController::class, 'multipleSalesOrderpaymentUpdate']);
        Route::post('customer-advance/store', [CommonController::class, 'customerAdvanceStore']);

        //staff advance

        Route::post('staff-advance/view', [CommonController::class, 'staffAdvanceListView']);
        Route::post('staff-advance/list', [CommonController::class, 'staffAdvanceList']);
        Route::post('staff-advance/store', [CommonController::class, 'staffAdvanceStore']);
        Route::post('staff-advance/staffAdvanceEdit', [CommonController::class, 'staffAdvanceStore']);
        Route::post('staff-advance/staffAdvanceUpdate', [CommonController::class, 'staffAdvanceStore']);

        // Supplier List
        Route::post('supplier/list', [CommonController::class, 'supplierlist']);
        Route::post('supplier/details', [CommonController::class, 'supplierDetails']);

        // Admin List
        Route::post('admin/list', [CommonController::class, 'adminlist']);

        // Store Manager/Partner List
        Route::post('store/manager/list', [CommonController::class, 'storemanagerpartnerlist']);

        // Product List
        Route::post('product/list', [CommonController::class, 'productlist']);
        Route::post('product/fish-cutting/list', [CommonController::class, 'fishCuttingProductList']);

        // Category List
        Route::post('category/list', [CommonController::class, 'categorylist']);

        // Unit List
        Route::post('dropdown/list', [CommonController::class, 'dropdownlist']);

        // Fish Cutting
        Route::post('fish-cutting/store', [CommonController::class, 'fishcuttingstore']);

        // Indent Request List
        Route::post('filter-indent-request', [PurchaseController::class, 'filterindentrequest']);

        // State city API
        Route::post('/getstatebycountry', [CommonController::class, 'getstate'])->name('getStatesByCountry');
        Route::post('/getcitybystate', [CommonController::class, 'getcity'])->name('getCityByState');

        // Warehouse Indent Request
        Route::post('warehouse-indent-request/list', [WarehouseIndentRequestController::class, 'warehouseindentrequestlist']);
        Route::post('warehouse-indent-request/details', [WarehouseIndentRequestController::class, 'warehouseindentrequestdetails']);
        Route::post('warehouse-indent-request/store', [WarehouseIndentRequestController::class, 'warehouseindentrequeststore']);
        Route::post('warehouse-indent-request/edit', [WarehouseIndentRequestController::class, 'warehouseindentrequestedit']);
        Route::post('warehouse-indent-request/update', [WarehouseIndentRequestController::class, 'warehouseindentrequestupdate']);

        // Store Indent Request
        Route::post('store-indent-request/list', [StoreIndentReqestController::class, 'storeindentrequestlist']);
        Route::post('store-indent-request/details', [StoreIndentReqestController::class, 'storeindentrequestdetails']);
        Route::post('store-indent-request/store', [StoreIndentReqestController::class, 'storeindentrequeststore']);
        Route::post('store-indent-request/edit', [StoreIndentReqestController::class, 'storeindentrequestedit']);
        Route::post('store-indent-request/update', [StoreIndentReqestController::class, 'storeindentrequestupdate']);
        Route::get('test-notification', [PushNotificationController::class, 'test_notification'])->name('test_notification');
        // Purchase List
        Route::post('purchase-order/list', [PurchaseController::class, 'purchaselist']);
        Route::post('purchase-order/details', [PurchaseController::class, 'purchasedetails']);
        Route::post('purchase-order/store', [PurchaseController::class, 'purchasestore']);
        Route::post('purchase-order/expense', [PurchaseController::class, 'purchaseorderexpense']);
        Route::post('purchase-order/edit', [PurchaseController::class, 'purchaseorderedit']);
        Route::post('purchase-order/update', [PurchaseController::class, 'purchaseorderupdate']);
        Route::post('purchase-order/expense/update', [PurchaseController::class, 'purchaseorderexpenseupdate']);
        Route::post('purchase-order/transactions', [PurchaseController::class, 'purchaseordertransactions']);
        Route::post('purchase-order/paymentstatus/update', [PurchaseController::class, 'purchasepaymentstatusupdate']);

        // Payment Transaction Details
        Route::post('purchase-order/payment-transaction/edit', [PurchaseController::class, 'purchasepaymenttransactionedit']);
        Route::post('purchase-order/payment-transaction/update', [PurchaseController::class, 'purchasepaymenttransactionupdate']);
        Route::post('purchase-order/payment-transaction/delete', [PurchaseController::class, 'purchasepaymenttransactiondelete']);

        // Multiple purchase payment  update
        Route::post('purchase-order/multi-payment-update', [PurchaseController::class, 'multiplepurchaseorderpaymentupdate']);

        // Sales List
        Route::post('sales-order/list', [SalesOrderController::class, 'salesorderlist']);
        Route::post('sales-order/details', [SalesOrderController::class, 'salesorderdetails']);
        Route::post('sales-order/store', [SalesOrderController::class, 'salesorderstore']);
        Route::post('sales-order/update', [SalesOrderController::class, 'salesorderupdate']);
        Route::post('sales-order/expense/update', [SalesOrderController::class, 'salesorderexpenseupdate']);
        Route::post('sales-order/transport-tracking/update', [SalesOrderController::class, 'salesordertransporttrackingupdate']);
        Route::post('sales-order/transactions', [SalesOrderController::class, 'salesordertransactions']);
        Route::post('sales-order/paymentstatus/update', [SalesOrderController::class, 'salespaymentstatusupdate']);
        Route::post('sales-order/status-update', [SalesOrderController::class, 'salesorderstatusupdate']);

        // Sales Payment Transaction Details
        Route::post('sales-order/payment-transaction/edit', [SalesOrderController::class, 'salespaymenttransactionedit']);
        Route::post('sales-order/payment-transaction/update', [SalesOrderController::class, 'salespaymenttransactionupdate']);
        Route::post('sales-order/payment-transaction/delete', [SalesOrderController::class, 'salespaymenttransactiondelete']);
        Route::post('payment-transaction-document/delete', [SalesOrderController::class, 'paymenttransactiondelete']);

        // Cash Register
        Route::post('cash-register/checks', [CashRegisterController::class, 'cashregisterchecks']);
        Route::post('cash-register/list', [CashRegisterController::class, 'cashregisterlist']);
        Route::post('cash-register/transactions', [CashRegisterController::class, 'cashregistertransactionlist']);
        Route::post('store/cash-register', [CashRegisterController::class, 'cashregisterstore']);
        Route::post('store/cash-register/transaction', [CashRegisterController::class, 'cashregistertransactionstore']);
        Route::post('store/daily-checklist', [CashRegisterController::class, 'dailycheckliststore']);
        Route::post('store/sales-chart', [CashRegisterController::class, 'storesaleschart']);
        Route::post('store/closing', [CashRegisterController::class, 'storeClosingList']);

        // Expense
        Route::post('expense/list', [ExpenseController::class, 'expenselist']);
        Route::post('expense/details', [ExpenseController::class, 'expensedetails']);
        Route::post('expense/store', [ExpenseController::class, 'expensestore']);
        Route::post('expense/update', [ExpenseController::class, 'expenseupdate']);
        Route::post('expense/payment-transactions', [ExpenseController::class, 'expensepaymenttransactions']);
        Route::post('expense/payment-status/update', [ExpenseController::class, 'expensepaymentstatusupdate']);

        // Expense Transaction Details
        Route::post('expense/payment-transaction/edit', [ExpenseController::class, 'expensepaymenttransactionedit']);
        Route::post('expense/payment-transaction/update', [ExpenseController::class, 'expensepaymenttransactionupdate']);
        Route::post('expense/payment-transaction/delete', [ExpenseController::class, 'expensepaymenttransactiondelete']);

        // Product
        Route::post('product-price/list', [ProductController::class, 'productpricelist']);
        Route::post('product-price/update', [ProductController::class, 'productpriceupdate']);
        Route::post('store-stock/list', [ProductController::class, 'storeproductstock']);
        Route::post('store-stock/update', [ProductController::class, 'storeproductstockupdate']);
        Route::post('store-stock/history', [ProductController::class, 'storeproductstockhistory']);
        Route::post('store-stock-adjustment', [ProductController::class, 'storestockadjustment']);

        // Store Return
        Route::post('store-return/list', [ReturnController::class, 'returnlist']);
        Route::post('store-return/details', [ReturnController::class, 'returnorderdetails']);
        Route::post('store-return/store', [ReturnController::class, 'returnorderstore']);
        Route::post('store-return/update', [ReturnController::class, 'returnorderupdate']);
        Route::post('store-return/expense/update', [ReturnController::class, 'returnorderexpenseupdate']);
        Route::post('store-return/transport-tracking/update', [ReturnController::class, 'returnordertransporttrackingupdate']);
        Route::post('store-return/transactions', [ReturnController::class, 'returnordertransactions']);
        Route::post('store-return/paymentstatus/update', [ReturnController::class, 'returnpaymentstatusupdate']);

        // Sales Payment Transaction Details
        Route::post('store-return/payment-transaction/edit', [ReturnController::class, 'returnpaymenttransactionedit']);
        Route::post('store-return/payment-transaction/update', [ReturnController::class, 'returnpaymenttransactionupdate']);
        Route::post('store-return/payment-transaction/delete', [ReturnController::class, 'returnpaymenttransactiondelete']);

        // Cash Paid to office
        Route::post('cash-paid/list', [CashtoOfficeController::class, 'cashpaidtoofficelist']);
        Route::post('cash-paid/details', [CashtoOfficeController::class, 'cashpaidtoofficedetail']);
        Route::post('cash-paid/store', [CashtoOfficeController::class, 'cashpaidtoofficestore']);
        Route::post('cash-paid/update', [CashtoOfficeController::class, 'cashpaidtoofficeupdate']);
        Route::post('cash-paid/denominationlist', [CashtoOfficeController::class, 'cashPaidtoOfficeDenomination']);
        Route::post('cash-paid/lastUpdatedDatelist', [CashtoOfficeController::class, 'lastUpdatedDatelist']);

        // Spoilage
        Route::post('spoilage/list', [SpoilageController::class, 'spoilagelist']);
        Route::post('spoilage/details', [SpoilageController::class, 'spoilagedetail']);
        Route::post('spoilage/store', [SpoilageController::class, 'spoilagestore']);
        Route::post('spoilage/update', [SpoilageController::class, 'spoilageupdate']);
        Route::post('spoilage/expense/update', [SpoilageController::class, 'spoilageexpenseupdate']);
        Route::post('spoilage/transport-tracking/update', [SpoilageController::class, 'spoilagetransporttrackingupdate']);

        // Re-Distribution
        Route::post('redistribution/list', [ReDistributionController::class, 'redistributionlist']);
        Route::post('redistribution/indent-request/list', [ReDistributionController::class, 'redistributionindentrequestlist']);
        Route::post('redistribution/details', [ReDistributionController::class, 'redistributiondetails']);
        Route::post('redistribution/store', [ReDistributionController::class, 'redistributionstore']);
        Route::post('redistribution/update', [ReDistributionController::class, 'redistributionupdate']);
        Route::post('redistribution/expense/update', [ReDistributionController::class, 'producttransferexpenseupdate']);
        Route::post('redistribution/transport-tracking/update', [ReDistributionController::class, 'producttransfertransporttrackingupdate']);

        Route::post('employee/list', [EmployeeController::class, 'employeelist']);
        Route::post('employee/details', [EmployeeController::class, 'employeedetails']);
        Route::post('employee/create', [EmployeeController::class, 'employeecreate']);
        Route::post('employee/store', [EmployeeController::class, 'employeestore']);
        Route::post('employee/update', [EmployeeController::class, 'employeeupdate']);
        Route::post('employee-attendance/list', [EmployeeController::class, 'employeeattendancelist']);

        Route::post('store-attendance/list', [AttendanceController::class, 'storeattendancelist']);
        Route::post('store-attendance/store', [AttendanceController::class, 'attendancestore']);
        Route::post('store-attendance/update', [AttendanceController::class, 'attendanceupdate']);

        // Fish Cutting
        Route::post('fish-cutting/list', [FishCuttingController::class, 'fishcuttinglist']);
        Route::post('fish-cutting/store', [FishCuttingController::class, 'fishcuttingstore']);
        Route::post('fish-cutting/update', [FishCuttingController::class, 'fishcuttingupdate']);

        // User Advance
        Route::post('user-advance/list', [UserAdvanceController::class, 'useradvancelist']);
        Route::post('user-advance/store', [UserAdvanceController::class, 'useradvancestore']);

        // Report
        Route::post('report/store-stock-report', [ReportController::class, 'store_stock_report']);
        Route::post('report/stock-distribute-report', [ReportController::class, 'stockdistributereport']);

        //report
        Route::get('report/plu-sales-chart', [ReportController::class, 'productsalechart']);

        Route::get('report/plu-sales-report', [ReportController::class, 'productwisesalereport']);

        Route::get('report/plu-report', [ReportController::class, 'productwisereport']);

        Route::POST('report-data', [ReportController::class, 'commonreportdata']);

        Route::POST('customer/store', [CustomerController::class, 'customerstore']);

        Route::POST('customer/update', [CustomerController::class, 'customerupdate']);

        Route::POST('store/today-received-stock', [ProductController::class, 'todayreceivedstock']);

        Route::POST('report/indent-request', [ReportController::class, 'productwiseindentrequestdata']);
    });
});

// Route::prefix('supplier')->middleware('auth:supplier')->group(function () {
//     Route::post('notification/list', [CommonController::class, 'notification_list']);
//     // Route::group(['middleware' => ['auth:supplier']], function () {
Route::group(['prefix' => 'supplier/v1', 'middleware' => 'auth:supplier'], function () {
    Route::post('notification/list', [CommonController::class, 'notification_list']);
    // App Menu
    Route::post('app-menu/list', [CommonController::class, 'appmenu']);

    // DashBoard
    Route::post('dashboard', [SupplierHomeController::class, 'dashboard']);

    // Warehouse List
    Route::post('warehouse/list', [CommonController::class, 'warehouselist']);

    // Supplier List
    Route::post('supplier/details', [CommonController::class, 'supplierDetails']);

    // Product List
    Route::post('product/list', [CommonController::class, 'productlist']);
    Route::post('product/fish-cutting/list', [CommonController::class, 'fishCuttingProductList']);

    // Category List
    Route::post('category/list', [CommonController::class, 'categorylist']);

    // Unit List
    Route::post('dropdown/list', [CommonController::class, 'dropdownlist']);

    // Invoice number
    Route::post('invoice-number', [CommonController::class, 'getinvoicecode']);

    // Warehouse Indent Request
    Route::post('warehouse-indent-request/list', [WarehouseIndentRequestController::class, 'warehouseindentrequestlist']);
    Route::post('warehouse-indent-request/details', [WarehouseIndentRequestController::class, 'warehouseindentrequestdetails']);
    Route::post('warehouse-indent-request/update', [WarehouseIndentRequestController::class, 'warehouseindentrequestupdate']);

    // Purchase List
    Route::post('purchase-order/list', [PurchaseController::class, 'purchaselist']);
    Route::post('purchase-order/details', [PurchaseController::class, 'purchasedetails']);
    Route::post('purchase-order/store', [PurchaseController::class, 'purchasestore']);
    Route::post('purchase-order/expense', [PurchaseController::class, 'purchaseorderexpense']);
    Route::post('purchase-order/edit', [PurchaseController::class, 'purchaseorderedit']);
    Route::post('purchase-order/update', [PurchaseController::class, 'purchaseorderupdate']);
    Route::post('purchase-order/expense/update', [PurchaseController::class, 'purchaseorderexpenseupdate']);
    Route::post('purchase-order/transactions', [PurchaseController::class, 'purchaseordertransactions']);
    Route::post('purchase-order/paymentstatus/update', [PurchaseController::class, 'purchasepaymentstatusupdate']);

    // Payment Transaction Details
    Route::post('purchase-order/payment-transaction/edit', [PurchaseController::class, 'purchasepaymenttransactionedit']);
    Route::post('purchase-order/payment-transaction/update', [PurchaseController::class, 'purchasepaymenttransactionupdate']);
    Route::post('purchase-order/payment-transaction/delete', [PurchaseController::class, 'purchasepaymenttransactiondelete']);

    // Multiple purchase payment  update
    Route::post('purchase-order/multi-payment-update', [PurchaseController::class, 'multiplepurchaseorderpaymentupdate']);

    // User Advance
    Route::post('user-advance/list', [UserAdvanceController::class, 'useradvancelist']);
    Route::post('user-advance/store', [UserAdvanceController::class, 'useradvancestore']);
});
