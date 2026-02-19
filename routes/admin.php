<?php

use App\Http\Controllers\Admin\ActivityLog;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\HRM\DepartmentController;
use App\Http\Controllers\Admin\HRM\DesignationController;
use App\Http\Controllers\Admin\HRM\EmployeeController;
use App\Http\Controllers\Admin\HRM\LeaveTypeController;
use App\Http\Controllers\Admin\HRM\HolidayController;
use App\Http\Controllers\Admin\HRM\LeaveController;
use App\Http\Controllers\Admin\HRM\StaffAdvanceController;
use App\Http\Controllers\Admin\HRM\StaffAttendanceController;
use App\Http\Controllers\Admin\HRM\PayrollTypeController;
use App\Http\Controllers\Admin\HRM\PayrollTemplateController;
use App\Http\Controllers\Admin\HRM\PayrollController;
use App\Http\Controllers\Admin\Accounting\AccountController;
use App\Http\Controllers\Admin\Accounting\BulkTransactionUploadController;
use App\Http\Controllers\Admin\Accounting\TransactionController;
use App\Http\Controllers\Admin\Accounting\TransactionReportController;
use App\Http\Controllers\Admin\Accounting\TransferController;
use App\Http\Controllers\Admin\Report\ProfitAndLossReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Master\ItemTypeController;
use App\Http\Controllers\Admin\Master\CategoryController;
use App\Http\Controllers\Admin\Master\UnitController;
use App\Http\Controllers\Admin\Master\IncomeExpenseTypeController;
use App\Http\Controllers\Admin\Master\TaxRateController;
use App\Http\Controllers\Admin\Master\WarehouseController;
use App\Http\Controllers\Admin\Master\StoreController;
use App\Http\Controllers\Admin\Master\PartnershipTypeController;
use App\Http\Controllers\Admin\Master\VendorController;
use App\Http\Controllers\Admin\Master\PartnerController;
use App\Http\Controllers\Admin\Master\SupplierController;
use App\Http\Controllers\Admin\Master\TransportTypeController;
use App\Http\Controllers\Admin\Master\MachineDetailController;
use App\Http\Controllers\Admin\Master\PaymentTypeController;
use App\Http\Controllers\Admin\Master\DenominationTypeController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\StockManagementController;
use App\Http\Controllers\Admin\Product\AdjustmentController;
use App\Http\Controllers\Admin\Product\ProductTransferController;
use App\Http\Controllers\Admin\Product\BulkProductTransferController;
use App\Http\Controllers\Admin\IndentRequest\StoreIndentRequestController;
use App\Http\Controllers\Admin\IndentRequest\VendorIndentRequestController;
use App\Http\Controllers\Admin\IndentRequest\WarehouseIndentRequestController;
use App\Http\Controllers\Admin\Sales\VendorSalesController;
use App\Http\Controllers\Admin\Sales\SalesOrderController;
use App\Http\Controllers\Admin\Sales\StoreSalesController;
use App\Http\Controllers\Admin\Sales\SalesReportController;
use App\Http\Controllers\Admin\Purchase\ProductPurchaseController;
use App\Http\Controllers\Admin\Purchase\ProductPurchaseCreditController;
use App\Http\Controllers\Admin\Report\ProductwiseReportController;
use App\Http\Controllers\Admin\Returns\SalesReturnController;
use App\Http\Controllers\Admin\Returns\PurchaseReturnController;
use App\Http\Controllers\Admin\App\AppMenuMappingController;
use App\Http\Controllers\Admin\Setting\SystemSettingController;
use App\Http\Controllers\Admin\CashPaidToOffice\StoreCashPaidToOfficeController;
use App\Http\Controllers\Admin\CashRegister\StoreCashRegisterController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\GoogleOAuthController;
use App\Http\Controllers\Admin\IncomeAndExpense\IncomeAndExpenseController;
use App\Http\Controllers\Admin\LoanManagement\LoanCategoryController;
use App\Http\Controllers\Admin\LoanManagement\LoanChargeController;
use App\Http\Controllers\Admin\LoanManagement\LoanController;
use App\Http\Controllers\Admin\LoanManagement\LoanRepaymentController;
use App\Http\Controllers\Admin\Payment\PaymentTransactionController;
use App\Http\Controllers\Admin\Product\DailyFishProductPriceUpdateController;
use App\Http\Controllers\Admin\Product\FishCuttingController;
use App\Http\Controllers\Admin\Report\SupplierReportController;
use App\Http\Controllers\Admin\Product\FishCuttingProductMapController;
use App\Http\Controllers\Admin\Purchase\ProductPinMappingController;
use App\Http\Controllers\Admin\PushNotification\PushNotificationController;
use App\Http\Controllers\Admin\Report\DailySalesReportController;
use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Admin\Report\DailystoreReportController;
use App\Http\Controllers\Admin\Report\EmployeeReportController;
use App\Http\Controllers\Admin\Report\FishCuttingDetailsReportController;
use App\Http\Controllers\Admin\Report\PaymentsReportController;
use App\Http\Controllers\Admin\Report\ProductWarehouseReportController;
use App\Http\Controllers\Admin\Report\ProductWiseIndentRequestReportController;
use App\Http\Controllers\Admin\Report\ProductWisePurchaseReportController;
use App\Http\Controllers\Admin\Report\SalesOrderReportController;
use App\Http\Controllers\Admin\Report\StoreStockReportController;
use App\Http\Controllers\Admin\Report\SupplierWisePurchaseReportController;
use App\Http\Controllers\Admin\Role\AssignRoleToUserController;
use App\Http\Controllers\Admin\Sales\SalesCreditController;
use App\Http\Controllers\Admin\Setting\ApiKeySettingController;
use App\Http\Controllers\Admin\Setting\EmailTemplateController;
use App\Http\Controllers\Admin\Setting\MailSettingController;
use App\Http\Controllers\Admin\Store\DailyStoreStockUpdateController;
use App\Http\Controllers\Admin\SupplierPayment\SupplierBulkPaymentController;
use App\Models\Department;
use App\Models\Designation;
use App\Models\FishCuttingDetail;
use App\Models\Helper;
use App\Models\PaymentTransaction;
use App\Models\SalesOrder;
use App\Models\Transaction;

Route::prefix(env('ADMIN_PREFIX'))
    ->name('admin.')
    ->group(function () {
        //Login Routes
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

        Route::post('/login', [LoginController::class, 'login']);

        // country state city
        Route::get('/getstatebycountry', [HomeController::class, 'getstate'])->name('getStatesByCountry');

        Route::get('/getcitybystate', [HomeController::class, 'getcity'])->name('getCityByState');

        Route::post('/autocomplete/search', [HomeController::class, 'autocomplete'])->name('autocomplete');

        Route::post('/product/detail', [HomeController::class, 'getproductdetails'])->name('get_product_details');

        Route::post('/indent-request/product/detail', [HomeController::class, 'get_indent_request_product_details'])->name('get_indent_request_product_details');

        // firebase access token generated
        Route::get('auth/code', [GoogleOAuthController::class, 'OAuthResponse'])->name('OAuthResponse');
        Route::get('get-oauth-credentials', [GoogleOAuthController::class, 'getAuthToken'])->name('getAuthToken');
    });

Route::get('purchase-order/invoice/{id}/view', [PaymentTransactionController::class, 'invoice'])->name('purchase-invoice');
Route::get('sales-order/invoice/{id}/view', [PaymentTransactionController::class, 'invoice'])->name('sales-invoice');
Route::prefix(env('ADMIN_PREFIX'))
    ->name('admin.')
    ->middleware(['auth:admin'])
    ->group(function () {
        Route::name('user-management.')->group(function () {
            Route::resource('/user-management/users', UserManagementController::class);
            Route::resource('/user-management/roles', RoleManagementController::class);
            Route::resource('/user-management/permissions', PermissionManagementController::class);
        });
        Route::name('role-management.')->group(function () {
            // Route::resource('/user-role-management/users', UserManagem:entController::class);
            Route::resource('/role-management/roles', RoleController::class);
            Route::resource('/role-management/assign-role-to-users', AssignRoleToUserController::class);
            // Route::resource('/user-role-management/permissions', PermissionManagementController::class);
        });

        //  Push Notifications
        Route::resource('push-notification', PushNotificationController::class);
        Route::post('/store-token', [PushNotificationController::class, 'storeToken'])->name('store.token');
        Route::post('/send-web-notification', [PushNotificationController::class, 'sendWebNotification'])->name('send.web-notification');


        Route::get('/', [HomeController::class, 'home'])->name('dashboard');
        Route::get('/pdf-download/{id}', [Helper::class, 'downloadInvoice'])->name('pdf_download');

        /* Master */
        /* Item-Type */
        Route::get('item-detail', [ProductPurchaseController::class, 'itemdetailrender'])->name('purchaseitem.render');
        Route::resource('master/item-type', ItemTypeController::class);

        /* Denomination Type */
        Route::resource('master/denomination-type', DenominationTypeController::class);

        /* Category */
        Route::POST('master/category/deleteimage', [CategoryController::class, 'deleteS3Image'])->name('category.imagedelete');
        Route::resource('master/category', CategoryController::class);

        /* unit */
        Route::resource('master/unit', UnitController::class);

        /* Download Pdf */
        // Route::post('download-pdf', [PaymentTransactionController::class, 'createPDF'])->name('download_pdf');

        /* income */
        Route::resource('master/income-expense-type', IncomeExpenseTypeController::class);

        /* income-and-expense-add */
        Route::post('income-and-expense/category-get', [IncomeAndExpenseController::class, 'incomeAndExpenseType'])->name('income_expense_cetegory_get');
        Route::resource('income-and-expense', IncomeAndExpenseController::class);

        /* tax */
        Route::resource('master/tax-rate', TaxRateController::class);

        /* warehouse */
        Route::post('master/warehouse/status-change', [WarehouseController::class, 'warehouseDefaultStatus'])->name('warehouse.statuschange');
        Route::post('master/warehouse/default-change', [WarehouseController::class, 'defaultWarehouseUpdate'])->name('warehouse.defaultwarehouse.update');
        Route::resource('master/warehouse', WarehouseController::class);

        /* store */
        Route::resource('master/store', StoreController::class);

        /* vendor */
        Route::resource('master/customer', VendorController::class);
        Route::get('master/{id}/customer-sales-order', [VendorController::class, 'salesorder_table'])->name('salesorder_table');
        Route::get('master/{id}/customer-customer-intent', [VendorController::class, 'Customer_intent_table'])->name('Customer_intent_table');
        Route::get('master/{id}/customer-payment-transaction', [VendorController::class, 'Customer_payment_transaction'])->name('Customer_payment_transaction');
        /* partner */
        Route::resource('master/partner', PartnerController::class);

        /* partner */
        Route::resource('master/partnership-type', PartnershipTypeController::class);

        /* supplier */

        Route::resource('master/supplier', SupplierController::class);
        Route::get('master/{id}/supplier-purchase', [SupplierController::class, 'supplier_purchase'])->name('supplier_purchase');
        Route::get('master/{id}/supplier-warhouse', [SupplierController::class, 'supplier_warehouse'])->name('supplier_warehouse');
        Route::get('master/{id}/supplier-payment', [SupplierController::class, 'supplier_payment_details'])->name('supplier_payment_details');

        /* Supplier Bulk Payment */
        Route::get('supplier-bulk-payment/supplier-purchase-orders', [SupplierBulkPaymentController::class, 'supplierPurchaseOrders'])->name('supplier_purchase_orders_data_get');
        Route::get('supplier-bulk-payment/supplier-purchase-orders/edit', [SupplierBulkPaymentController::class, 'supplierPurchaseOrdersEdit'])->name('supplier_purchase_orders_edit_data_get');
        Route::resource('supplier-bulk-payment', SupplierBulkPaymentController::class);

        /* transport-type */
        Route::resource('master/transport-type', TransportTypeController::class);

        /* machine-details */
        Route::resource('master/machine-details', MachineDetailController::class);

        /* payment-type */
        Route::resource('master/payment-type', PaymentTypeController::class);

        /* Indent Request */
        // Store
        Route::resource('store-indent-request', StoreIndentRequestController::class);
        // Vendor
        Route::resource('customer-indent-request', VendorIndentRequestController::class);
        // warehouse
        Route::resource('warehouse-indent-request', WarehouseIndentRequestController::class);

        Route::post('warehouse-indent-request/staus', [WarehouseIndentRequestController::class, 'indent_request_status_change'])->name('indent_request.status');

        /* partner */
        Route::resource('product', ProductController::class);
        Route::get('master/{id}/product-purchase', [ProductController::class, 'product_purchase'])->name('product_purchase');
        Route::get('master/{id}/product-sales', [ProductController::class, 'product_sales'])->name('product_sales');
        Route::get('master/{id}/product-store_request', [ProductController::class, 'product_store_intent_request'])->name('product_store_intent_request');
        Route::get('master/{id}/product-warehouse_request', [ProductController::class, 'product_warehouse_intent_request'])->name('product_warehouse_intent_request');
        Route::get('master/{id}/product-store_stock', [ProductController::class, 'store_stock'])->name('store_stock');
        Route::get('master/{id}/product-warehouse_stock', [ProductController::class, 'warehouse_stock'])->name('warehouse_stock');
        /* stock-management */
        Route::resource('stock-management', StockManagementController::class);
        /* adjustment */
        Route::resource('adjustment', AdjustmentController::class);

        /* Purchase */
        Route::resource('purchase', ProductPurchaseController::class);
        // Purchase Order
        Route::resource('purchase-order', ProductPurchaseController::class);
        Route::get('purchase-order/{id}/purchase/', [ProductPurchaseController::class, 'product_data'])->name('product_data');
        Route::get('purchase-order/{id}/transport', [ProductPurchaseController::class, 'transport_data'])->name('transport_data');
        Route::get('purchase-order/{id}/expences', [ProductPurchaseController::class, 'expences_data'])->name('expences_data');
        Route::get('purchase-order/{id}/payment', [ProductPurchaseController::class, 'payment_data'])->name('payment_data');
        Route::get('purchase-order/{id}/product-pin_mapping', [ProductPurchaseController::class, 'productPinMapping'])->name('product_pin_mapping');
        Route::post('purchase-order/{id}/product-mapping-store', [ProductPurchaseController::class, 'pinMapping'])->name('product_pin_mapping_store');

        // Product Pin Mapping
        Route::get('product-pin-mapping', [ProductPinMappingController::class, 'productpinMappingView'])->name('productmapping');

        // Purchase Credit Notes
        Route::resource('purchase-credit', ProductPurchaseCreditController::class);

        /* Sales */
        Route::resource('sales-credit', SalesCreditController::class);

        // Vendor sales
        Route::resource('report/branch-machine-sale', SalesReportController::class);
        Route::get('report/branch-machine-sale/export', [SalesReportController::class, 'machineSalesExport'])->name('branch-machine-sale.export');


        // Vendor sales
        Route::resource('customer-sales', VendorSalesController::class);
        Route::resource('sales-order', SalesOrderController::class);
        Route::get('sales-order/{id}/sales/', [SalesOrderController::class, 'productsales_data'])->name('productsales_data');
        Route::get('sales-order/{id}/transport', [SalesOrderController::class, 'transportsales_data'])->name('transportsales_data');
        Route::get('sales-order/{id}/expences', [SalesOrderController::class, 'salesexpences_data'])->name('salesexpences_data');
        Route::get('sales-order/{id}/payment', [SalesOrderController::class, 'salespayment_data'])->name('salespayment_data');
        Route::get('/test-notification', [PushNotificationController::class, 'test_notification'])->name('test_notification');
        // Store sales
        Route::get('get-store-sales-from-ease', [StoreSalesController::class, 'getstoresalesfromease']);
        Route::resource('store-sales', StoreSalesController::class);

        // Sales Return
        Route::get('get-sales-order', [SalesReturnController::class, 'get_sales_order'])->name('get_sales_order');
        Route::post('get-sales-order-details', [SalesReturnController::class, 'get_sales_order_details'])->name('get_sales_order_details');

        Route::get('sales-return/{id}/product', [SalesReturnController::class, 'product_sales_return_data'])->name('productsalesreturn_data');
        Route::get('sales-return/{id}/transport', [SalesReturnController::class, 'sales_transport_return_data'])->name('transportreturn_data');
        Route::get('sales-return/{id}/expences', [SalesReturnController::class, 'sales_return_expences_data'])->name('returnexpences_data');
        Route::get('sales-return/{id}/payment', [SalesReturnController::class, 'sales_return_payment_data'])->name('returnpayment_data');
        Route::resource('sales-return', SalesReturnController::class);

        // Purchase Return
        Route::get('get-purchase-order', [PurchaseReturnController::class, 'get_purchase_order'])->name('get_purchase_order');
        Route::post('get-purchase-order-details', [PurchaseReturnController::class, 'get_purchase_order_details'])->name('get_purchase_order_details');

        Route::get('purchase-return/{id}/product', [PurchaseReturnController::class, 'PurchaseProductReturnData'])->name('productpurchasereturn_data');
        Route::get('purchase-return/{id}/transport', [PurchaseReturnController::class, 'PurchaseTransportReturnData'])->name('purchasereturntransport_data');
        Route::get('purchase-return/{id}/expences', [PurchaseReturnController::class, 'purchasereturnExpensesData'])->name('purchasereturnnexpences_data');
        Route::get('purchase-return/{id}/payment', [PurchaseReturnController::class, 'PurchasereturnPaymentData'])->name('purchasereturnpayment_data');
        Route::resource('purchase-return', PurchaseReturnController::class);

        // Employee
        Route::resource('hrm/employee', EmployeeController::class);
        Route::resource('hrm/staff-advance', StaffAdvanceController::class);

        // HRM//
        Route::resource('hrm/leave_type', LeaveTypeController::class);
        Route::resource('hrm/holiday', HolidayController::class);
        Route::post('hrm/leave/stored-leave-type', [LeaveController::class, 'storedLeaveType'])->name('leave_type_stored');
        Route::resource('hrm/leave', LeaveController::class);
        Route::resource('hrm/designation', DesignationController::class);
        Route::resource('hrm/department', DepartmentController::class);
        Route::post('hrm/staff_attendance/get-employees', [StaffAttendanceController::class, 'getEmployees'])->name('get_store_employees');
        Route::resource('hrm/staff_attendance', StaffAttendanceController::class);
        Route::resource('hrm/pay-roll-type', PayrollTypeController::class);
        Route::resource('hrm/pay-roll-template', PayrollTemplateController::class);
        Route::post('hrm/payroll/get-employee-pay-silp', [PayrollController::class, 'getPayrollSlip'])->name('get_employee_pay_silp');
        Route::post('hrm/payroll/get-employee-pay-silp-edit', [PayrollController::class, 'getPayrollSlipwithpreviousdata'])->name('get_employee_pay_silp_previous_edit');
        Route::post('hrm/payroll/check-user-advanced-exists', [PayrollController::class, 'userAdvanced'])->name('check_employee_exists_user_advanced');
        Route::post('hrm/payroll/add-payroll-earnings', [PayrollController::class, 'AddPayrollMethod'])->name('add_payroll_earning');
        Route::resource('hrm/payroll', PayrollController::class);

        // Accounting
        Route::resource('accounting/accounts', AccountController::class);
        Route::post('accounting/transfer/bank-balance', [TransferController::class, 'getBankBalance'])->name('get_transfer_bank_balance');
        Route::resource('accounting/transfer', TransferController::class);
        Route::post('accounting/transaction/bank-balance', [TransactionController::class, 'getBankBalance'])->name('get_bank_balance');
        Route::resource('accounting/transaction', TransactionController::class);
        Route::resource('bank-transactions-report', TransactionReportController::class);
        Route::post('bulk-transaction-import', [BulkTransactionUploadController::class, 'upload'])->name('bulk-transactions-upload');
        Route::resource('accounting/upload-transactions', BulkTransactionUploadController::class);

        // Payments
        Route::resource('payments/payment-transaction-report', PaymentTransactionController::class);
        Route::resource('payments/suppliers-report', SupplierReportController::class);

        // INVOICES-DOWNLOAD
        Route::get('purchase-order/invoice-1/{id}/view', [PaymentTransactionController::class, 'invoice1'])->name('purchase-invoice1');
        Route::get('payment/transactions/{id}/receipt', [PaymentTransactionController::class, 'receipt'])->name('purchase-receipt');

        //SALES INVOICE
        Route::get('sales-order/invoice-1/{id}/view', [PaymentTransactionController::class, 'invoice1'])->name('sales-invoice1');

        // LAON MANAGEMENT PART
        Route::resource('loan-management/loans', LoanController::class);
        Route::post('loan-management/loans/store-bank-account', [LoanController::class, 'storeBankAccount'])->name('storeBankAccount');
        Route::post('loan-management/loans/get-loan-category-details', [LoanController::class, 'getLoanCategoryDetails'])->name('getLoanCategoryDetails');
        Route::POST('loan-management/loans/status-changing', [LoanController::class, 'loanStatusChanged'])->name('loanStatusChanged');
        Route::GET('loan-management/loan-repayment/loan-details', [LoanRepaymentController::class, 'getLoanDetails'])->name('getLoanDetails');
        Route::resource('loan-management/loan-repayment', LoanRepaymentController::class);
        Route::resource('loan-management/loan-charges', LoanChargeController::class);
        Route::resource('loan-management/loan-categories', LoanCategoryController::class);

        // Product Transfer
        Route::resource('product-transfer', ProductTransferController::class);
        Route::get('product-transfer/{id}/product', [ProductTransferController::class, 'producttrans_data'])->name('producttrans_data');
        Route::get('product-transfer/{id}/trannsport', [ProductTransferController::class, 'transport_product_data'])->name('transport_product_data');
        Route::get('product-transfer/{id}/expences', [ProductTransferController::class, 'expences_product_data'])->name('expences_product_data');
        Route::get('product-transfer/{id}/payment', [ProductTransferController::class, 'payment_product_data'])->name('payment_product_data');

        // Store Cash Paid Cash Regsiter
        Route::get('store-cash/payer-details', [StoreCashPaidToOfficeController::class, 'getPayerDetails'])->name('getpayerdetails');
        Route::get('/not-updated-dates/{id}', [StoreCashPaidToOfficeController::class, 'getNotUpdatedDates'])->name('notUpdatedDates');
        Route::get('/get-last-updated-date', [StoreCashPaidToOfficeController::class, 'getUpdatedDates'])->name('lastUpdatedDate');
        Route::resource('store-cash/cash-paid-office', StoreCashPaidToOfficeController::class);
        Route::get('store-cash/cash-register/create/{id}', [StoreCashRegisterController::class, 'create'])->name('cash-register-create');
        Route::resource('store-cash/cash-register', StoreCashRegisterController::class);

        //Daily Store Stock Update
        Route::resource('store-stock/daily-stock-update', DailyStoreStockUpdateController::class);

        //Daily Fish Price  Update
        Route::resource('fish-price-update', DailyFishProductPriceUpdateController::class);

        //Bulk product transfer
        Route::get('get-bulk-product-list', [BulkProductTransferController::class, 'getbulktransferproducts'])->name('bulk_product_list.render');
        Route::resource('bulk-product-transfer', BulkProductTransferController::class);

        // Report
        Route::get('report/product-wise-purchase', [ProductWisePurchaseReportController::class, 'productWisePurchaseReport'])->name('productwisepurchasereport');
        Route::get('report/purchase-product-list', [ProductWisePurchaseReportController::class, 'getProductList'])->name('getPurchaseProductList');
        Route::get('report/product-wise-sales', [ProductwiseReportController::class, 'productwisesalereport'])->name('productwisesalereport');
        Route::get('report/product-list', [ProductwiseReportController::class, 'getProductList'])->name('getProductList');
        Route::get('report/product-wise-indent-request', [ProductWiseIndentRequestReportController::class, 'productwiseindentrequestreport'])->name('productwiseindentrequestreport');
        Route::post('report/product-wise-indent-request-list', [ProductWiseIndentRequestReportController::class, 'productwiseindentrequestdata'])->name('productwiseindentrequestdata');
        Route::get('report/payments-report', [PaymentsReportController::class, 'paymentsReport'])->name('payments_report');
        Route::get('report/daily-sales-report/{id}/view', [DailySalesReportController::class, 'show'])->name('dailysalesreportview');
        Route::get('report/daily-sales-orders/show', [SalesOrderController::class, 'index'])->name('dailysalesordersgetdate');
        Route::get('report/daily-sales-report', [DailySalesReportController::class, 'dailySalesReport'])->name('dailysalesreport');
        Route::get('report/profit-and-loss', [ProfitAndLossReportController::class, 'profitAndLoss'])->name('profit_and_loss');
        Route::get('report/supplier-wise-purchase-orders', [SupplierWisePurchaseReportController::class, 'SupplierWisePurchaseReport'])->name('supplier_wise_purchase_orders');
        Route::get('report/supplier-wise-purchase-orders/view', [ProductPurchaseController::class, 'index'])->name('supplier_wise_purchase_view');
        Route::get('report/supplier-wise-payment-transactions/view', [PaymentTransactionController::class, 'PaymentTransactionReport'])->name('supplier_payment_transactions');
        Route::get('report/employee-view/{values}', [EmployeeReportController::class, 'view'])->name('employee_view');
        Route::get('report/employee-report', [EmployeeReportController::class, 'employeeReport'])->name('employee_report');
        Route::get('report/store-stock-report', [StoreStockReportController::class, 'storeStockReportData'])->name('store_stock_report');
        Route::get('report/warehouse-stock-report', [ProductWarehouseReportController::class, 'productWarehouseReportData'])->name('warehouse_stock_report');

        // Sales Order report
        Route::get('report/sales-orders-report', [SalesOrderReportController::class, 'salesOrderReportData'])->name('salesorderreportdata');

        // Fish Cutting Details report
        Route::get('report/fish-cutting-details-report', [FishCuttingDetailsReportController::class, 'fishCuttingDetailsData'])->name('fishcuttingdetailsreport');

        //    Daily Store Report
        Route::get('report/daily-store-report', [DailystoreReportController::class, 'dailystorereportdata'])->name('dailystorereportdata');

        Route::get('get-code', [CommonController::class, 'getusercode'])->name('getusercode');

        // get Acivity log
        Route::get('activity-logs', [ActivityLog::class, 'ActivityLogList'])->name('activitylog');

        // App User Menu Mapping
        Route::resource('setting/app-menu-mapping', AppMenuMappingController::class);
        Route::post('system-setting/email-preference', [SystemSettingController::class, 'setEmailMethod'])->name('set_email_method');
        Route::post('system-setting/site-configuration', [SystemSettingController::class, 'siteConfigStore'])->name('set_config_stored');
        Route::get('system-setting/auto-cron-run', [SystemSettingController::class, 'autoCronRun'])->name('auto_cron_run');
        Route::resource('setting/system-setting', SystemSettingController::class);
        // Route::resource('setting/pdf-setting', SystemSettingController::class);
        Route::resource('setting/notification', PushNotificationController::class);
        Route::resource('setting/api-keys', ApiKeySettingController::class);
        Route::resource('setting/mail-setting', MailSettingController::class);
        //Email Template Settings
        Route::resource('setting/email-template', EmailTemplateController::class);
        Route::post('setting/email-template/update', [EmailTemplateController::class, 'update'])->name('setting.emailTemplateUpdate');
        Route::get('/setting/get-email-template/{id}', [EmailTemplateController::class, 'getEmailTemplate'])->name('get_email_template');

        //Fish Cutting Product Mapping
        Route::resource('fish-cutting-product-map', FishCuttingProductMapController::class);

        //Fish Cutting
        Route::resource('fish-cutting', FishCuttingController::class);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('admin')
            ->name('logout');

        // Fallback route
        Route::fallback(function(){
            return response()->view('errors.404');
        });

    });
