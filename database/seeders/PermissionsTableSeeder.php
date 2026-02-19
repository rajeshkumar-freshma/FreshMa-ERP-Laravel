<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        $permissions = [
            [
                'name' => 'Dashboard View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Dashboard')->first()
            ],
            // Masters Menu

            [
                'name' => 'Warehouse View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Warehouse')->first()
            ],
            [
                'name' => 'Warehouse Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Warehouse')->first()
            ],
            [
                'name' => 'Warehouse Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Warehouse')->first()
            ],
            [
                'name' => 'Store View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store')->first()
            ],
            [
                'name' => 'Store Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store')->first()
            ],
            [
                'name' => 'Store Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store')->first()
            ],
            [
                'name' => 'Item Type View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Item Type')->first()
            ],
            [
                'name' => 'Item Type Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Item Type')->first()
            ],
            [
                'name' => 'Item Type Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Item Type')->first()
            ],
            [
                'name' => 'Denomination View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Denomination ')->first()
            ],
            [
                'name' => 'Denomination Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Denomination ')->first()
            ],
            [
                'name' => 'Denomination Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Denomination ')->first()
            ],
            [
                'name' => 'Unit View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Unit')->first()
            ],
            [
                'name' => 'Unit Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Unit')->first()
            ],
            [
                'name' => 'Unit Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Unit')->first()
            ],
            [
                'name' => 'Income/Expense Category View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Income/Expense Category')->first(),
            ],
            [
                'name' => 'Income/Expense Category Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Income/Expense Category')->first(),
            ],
            [
                'name' => 'Income/Expense Category Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Income/Expense Category')->first(),
            ],
            [
                'name' => 'Tax Rate View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Tax Rate')->first()
            ],
            [
                'name' => 'Tax Rate Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Tax Rate')->first()
            ],
            [
                'name' => 'Tax Rate Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Tax Rate')->first()
            ],
            [
                'name' => 'Partnership Type View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Partnership Type')->first(),
            ],
            [
                'name' => 'Partnership Type Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Partnership Type')->first(),
            ],
            [
                'name' => 'Partnership Type Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Partnership Type')->first(),
            ],
            [
                'name' => 'Customer View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Customer')->first(),
            ],
            [
                'name' => 'Customer Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Customer')->first(),
            ],
            [
                'name' => 'Customer Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Customer')->first(),
            ],
            [
                'name' => 'Category View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Category')->first(),
            ],
            [
                'name' => 'Category Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Category')->first(),
            ],
            [
                'name' => 'Category Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Category')->first(),
            ],
            [
                'name' => 'Partner/Manager View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Partner/Manager')->first(),
            ],
            [
                'name' => 'Partner/Manager Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Partner/Manager')->first(),
            ],
            [
                'name' => 'Partner/Manager Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Partner/Manager')->first(),
            ],
            [
                'name' => 'Supplier View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier')->first()
            ],
            [
                'name' => 'Supplier Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier')->first()
            ],
            [
                'name' => 'Supplier Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier')->first()
            ],
            [
                'name' => 'Transport Type View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transport Type')->first(),
            ],
            [
                'name' => 'Transport Type Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transport Type')->first(),
            ],
            [
                'name' => 'Transport Type Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transport Type')->first(),
            ],
            [
                'name' => 'Machine Details View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Machine Details')->first(),
            ],
            [
                'name' => 'Machine Details Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Machine Details')->first(),
            ],
            [
                'name' => 'Machine Details Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Machine Details')->first(),
            ],
            [
                'name' => 'Payment Type View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payment Type')->first(),
            ],
            [
                'name' => 'Payment Type Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payment Type')->first(),
            ],
            [
                'name' => 'Payment Type Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payment Type')->first(),
            ],
            [
                'name' => 'Products View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Products')->first()
            ],
            [
                'name' => 'Products Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Products')->first()
            ],
            [
                'name' => 'Products Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Products')->first()
            ],
            [
                'name' => 'Stock Management View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Stock Management')->first(),
            ],
            [
                'name' => 'Stock Management Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Stock Management')->first(),
            ],
            [
                'name' => 'Stock Management Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Stock Management')->first(),
            ],
            [
                'name' => 'Adjustment View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Adjustment')->first(),
            ],
            [
                'name' => 'Adjustment Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Adjustment')->first(),
            ],
            [
                'name' => 'Adjustment Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Adjustment')->first(),
            ],
            [
                'name' => 'Fish Cutting View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Fish Cutting')->first(),
            ],
            [
                'name' => 'Fish Cutting Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Fish Cutting')->first(),
            ],
            [
                'name' => 'Fish Cutting Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Fish Cutting')->first(),
            ],
            [
                'name' => 'Product Fish Cutting Mapping View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Fish Cutting Mapping')->first(),
            ],
            [
                'name' => 'Product Fish Cutting Mapping Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Fish Cutting Mapping')->first(),
            ],
            [
                'name' => 'Product Fish Cutting Mapping Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Fish Cutting Mapping')->first(),
            ],
            [
                'name' => 'Daily Product Price Update View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Product Price Update')->first(),
            ],
            [
                'name' => 'Daily Product Price Update Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Product Price Update')->first(),
            ],
            [
                'name' => 'Daily Product Price Update Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Product Price Update')->first(),
            ],
            [
                'name' => 'Store Indent Request View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store Indent Request')->first(),
            ],
            [
                'name' => 'Store Indent Request Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store Indent Request')->first(),
            ],
            [
                'name' => 'Store Indent Request Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store Indent Request')->first(),
            ],
            [
                'name' => 'Customer Indent Request View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Customer Indent Request')->first(),
            ],
            [
                'name' => 'Customer Indent Request Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Customer Indent Request')->first(),
            ],
            [
                'name' => 'Customer Indent Request Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Customer Indent Request')->first(),
            ],
            [
                'name' => 'Warehouse Indent Request View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Warehouse Indent Request')->first(),
            ],
            [
                'name' => 'Warehouse Indent Request Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Warehouse Indent Request')->first(),
            ],
            [
                'name' => 'Warehouse Indent Request Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Warehouse Indent Request')->first(),
            ],
            [
                'name' => 'Purchase Order View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Order')->first(),
            ],
            [
                'name' => 'Purchase Order Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Order')->first(),
            ],
            [
                'name' => 'Purchase Order Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Order')->first(),
            ],
            [
                'name' => 'Product Pin Mapping View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Pin Mapping')->first(),
            ],
            [
                'name' => 'Product Pin Mapping Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Pin Mapping')->first(),
            ],
            [
                'name' => 'Product Pin Mapping Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Pin Mapping')->first(),
            ],
            [
                'name' => 'Purchase Credit Notes View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Credit Notes')->first(),
            ],
            [
                'name' => 'Purchase Credit Notes Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Credit Notes')->first(),
            ],
            [
                'name' => 'Purchase Credit Notes Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Credit Notes')->first(),
            ],
            [
                'name' => 'Sales Order View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Order')->first(),
            ],
            [
                'name' => 'Sales Order Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Order')->first(),
            ],
            [
                'name' => 'Sales Order Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Order')->first(),
            ],
            [
                'name' => 'Sales Credit View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Credit')->first(),
            ],
            [
                'name' => 'Sales Credit Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Credit')->first(),
            ],
            [
                'name' => 'Sales Credit Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Credit')->first(),
            ],
            [
                'name' => 'Income Expense Add View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Income Expense Add')->first(),
            ],
            [
                'name' => 'Income Expense Add Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Income Expense Add')->first(),
            ],
            [
                'name' => 'Income Expense Add Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Income Expense Add')->first(),
            ],
            [
                'name' => 'Supplier Payment View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier Payment')->first(),
            ],
            [
                'name' => 'Supplier Payment Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier Payment')->first(),
            ],
            [
                'name' => 'Supplier Payment Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier Payment')->first(),
            ],
            [
                'name' => 'Cash Paind To Offiice View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Cash Paind To Offiice')->first(),
            ],
            [
                'name' => 'Cash Paind To Offiice Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Cash Paind To Offiice')->first(),
            ],
            [
                'name' => 'Cash Paind To Offiice Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Cash Paind To Offiice')->first(),
            ],
            [
                'name' => 'Cash Register View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Cash Register')->first(),
            ],
            [
                'name' => 'Cash Register Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Cash Register')->first(),
            ],
            [
                'name' => 'Cash Register Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Cash Register')->first(),
            ],
            [
                'name' => 'Daily Stock Update View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Stock Update')->first(),
            ],
            [
                'name' => 'Daily Stock Update Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Stock Update')->first(),
            ],
            [
                'name' => 'Daily Stock Update Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Stock Update')->first(),
            ],
            [
                'name' => 'Purchase Return View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Return')->first(),
            ],
            [
                'name' => 'Purchase Return Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Return')->first(),
            ],
            [
                'name' => 'Purchase Return Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Purchase Return')->first(),
            ],
            [
                'name' => 'Sales Return View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Return')->first(),
            ],
            [
                'name' => 'Sales Return Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Return')->first(),
            ],
            [
                'name' => 'Sales Return Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Return')->first(),
            ],
            [
                'name' => 'Product Transfer View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Transfer')->first(),
            ],
            [
                'name' => 'Product Transfer Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Transfer')->first(),
            ],
            [
                'name' => 'Product Transfer Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Transfer')->first(),
            ],
            [
                'name' => 'Bulk Product Transfer View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Bulk Product Transfer')->first(),
            ],
            [
                'name' => 'Bulk Product Transfer Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Bulk Product Transfer')->first(),
            ],
            [
                'name' => 'Bulk Product Transfer Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Bulk Product Transfer')->first(),
            ],
            [
                'name' => 'Accounts View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Accounts')->first()
            ],
            [
                'name' => 'Accounts Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Accounts')->first()
            ],
            [
                'name' => 'Accounts Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Accounts')->first()
            ],
            [
                'name' => 'Transfer View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transfer')->first()
            ],
            [
                'name' => 'Transfer Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transfer')->first()
            ],
            [
                'name' => 'Transfer Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transfer')->first()
            ],
            [
                'name' => 'Transaction View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transaction')->first(),
            ],
            [
                'name' => 'Transaction Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transaction')->first(),
            ],
            [
                'name' => 'Transaction Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transaction')->first(),
            ],
            [
                'name' => 'Transaction Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transaction Report')->first(),
            ],
            [
                'name' => 'Transaction Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transaction Report')->first(),
            ],
            [
                'name' => 'Bulk Transaction Upload View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Bulk Transaction Upload')->first(),
            ],
            [
                'name' => 'Bulk Transaction Upload Data',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Bulk Transaction Upload')->first(),
            ],
            [
                'name' => 'Department View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Department')->first(),
            ],
            [
                'name' => 'Department Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Department')->first(),
            ],
            [
                'name' => 'Department Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Department')->first(),
            ],
            [
                'name' => 'Desigination View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Desigination')->first(),
            ],
            [
                'name' => 'Desigination Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Desigination')->first(),
            ],
            [
                'name' => 'Desigination Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Desigination')->first(),
            ],
            [
                'name' => 'Employee View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Employee')->first()
            ],
            [
                'name' => 'Employee Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Employee')->first()
            ],
            [
                'name' => 'Employee Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Employee')->first()
            ],
            [
                'name' => 'Staff Attendance View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Staff Attendance')->first(),
            ],
            [
                'name' => 'Staff Attendance Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Staff Attendance')->first(),
            ],
            [
                'name' => 'Staff Attendance Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Staff Attendance')->first(),
            ],
            [
                'name' => 'Staff Advanced View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Staff Advanced')->first(),
            ],
            [
                'name' => 'Staff Advanced Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Staff Advanced')->first(),
            ],
            [
                'name' => 'Staff Advanced Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Staff Advanced')->first(),
            ],
            [
                'name' => 'Leave Type View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Leave Type')->first(),
            ],
            [
                'name' => 'Leave Type Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Leave Type')->first(),
            ],
            [
                'name' => 'Leave Type Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Leave Type')->first(),
            ],
            [
                'name' => 'Holiday View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Holiday')->first()
            ],
            [
                'name' => 'Holiday Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Holiday')->first()
            ],
            [
                'name' => 'Holiday Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Holiday')->first()
            ],
            [
                'name' => 'Leave View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Leave')->first()
            ],
            [
                'name' => 'Leave Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Leave')->first()
            ],
            [
                'name' => 'Leave Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Leave')->first()
            ],
            [
                'name' => 'Payroll Type View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Type')->first(),
            ],
            [
                'name' => 'Payroll Type Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Type')->first(),
            ],
            [
                'name' => 'Payroll Type Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Type')->first(),
            ],
            [
                'name' => 'Payroll Template View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Template')->first(),
            ],
            [
                'name' => 'Payroll Template Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Template')->first(),
            ],
            [
                'name' => 'Payroll Template Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Template')->first(),
            ],
            [
                'name' => 'Payroll Setup View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Setup')->first(),
            ],
            [
                'name' => 'Payroll Setup Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Setup')->first(),
            ],
            [
                'name' => 'Payroll Setup Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payroll Setup')->first(),
            ],
            [
                'name' => 'Apply Loan View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Apply Loan')->first(),
            ],
            [
                'name' => 'Apply Loan Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Apply Loan')->first(),
            ],
            [
                'name' => 'Apply Loan Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Apply Loan')->first(),
            ],
            [
                'name' => 'RePayment View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'RePayment')->first()
            ],
            [
                'name' => 'RePayment Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'RePayment')->first()
            ],
            [
                'name' => 'RePayment Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'RePayment')->first()
            ],
            [
                'name' => 'Loan Products View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Loan Products')->first(),
            ],
            [
                'name' => 'Loan Products Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Loan Products')->first(),
            ],
            [
                'name' => 'Loan Products Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Loan Products')->first(),
            ],
            [
                'name' => 'Loan Charges View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Loan Charges')->first()
            ],
            [
                'name' => 'Loan Charges Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Loan Charges')->first()
            ],
            [
                'name' => 'Loan Charges Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Loan Charges')->first()
            ],
            [
                'name' => 'Transactions View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transactions')->first(),
            ],
            [
                'name' => 'Transactions Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Transactions')->first(),
            ],
            [
                'name' => 'Suppliers View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Suppliers')->first()
            ],
            [
                'name' => 'Suppliers Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Suppliers')->first()
            ],
            [
                'name' => 'Suppliers Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Suppliers')->first()
            ],
            [
                'name' => 'Users View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Users')->first()
            ],
            [
                'name' => 'Users Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Users')->first()
            ],
            [
                'name' => 'Users Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Users')->first()
            ],
            [
                'name' => 'Payment Collection View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payment Collection')->first(),
            ],
            [
                'name' => 'Payment Collection Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payment Collection')->first(),
            ],
            [
                'name' => 'Payment Collection Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payment Collection')->first(),
            ],
            [
                'name' => 'Branch Sales Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Branch Sales Report')->first(),
            ],
            [
                'name' => 'Branch Sales Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Branch Sales Report')->first(),
            ],
            [
                'name' => 'Product Sales Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Sales Report')->first(),
            ],
            [
                'name' => 'Product Sales Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Sales Report')->first(),
            ],
            [
                'name' => 'Product Purchase Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Purchase Report')->first(),
            ],
            [
                'name' => 'Product Purchase Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Purchase Report')->first(),
            ],
            [
                'name' => 'Supplier Wise Purchase Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier Wise Purchase Report')->first(),
            ],
            [
                'name' => 'Supplier Wise Purchase Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Supplier Wise Purchase Report')->first(),
            ],
            [
                'name' => 'Daily Sales Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Sales Report')->first(),
            ],
            [
                'name' => 'Daily Sales Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Sales Report')->first(),
            ],
            [
                'name' => 'Daily Store Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Store Report')->first(),
            ],
            [
                'name' => 'Daily Store Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Daily Store Report')->first(),
            ],
            [
                'name' => 'Profit And Loss Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Profit And Loss Report')->first(),
            ],
            [
                'name' => 'Profit And Loss Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Profit And Loss Report')->first(),
            ],
            [
                'name' => 'Indent Request Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Indent Request Report')->first(),
            ],
            [
                'name' => 'Indent Request Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Indent Request Report')->first(),
            ],
            [
                'name' => 'Sales Orders Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Orders Report')->first(),
            ],
            [
                'name' => 'Sales Orders Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Sales Orders Report')->first(),
            ],
            [
                'name' => 'Fish Cutting Details Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Fish Cutting Details Report')->first(),
            ],
            [
                'name' => 'Fish Cutting Details Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Fish Cutting Details Report')->first(),
            ],
            [
                'name' => 'Payments Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payments Report')->first(),
            ],
            [
                'name' => 'Payments Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Payments Report')->first(),
            ],
            [
                'name' => 'Employee Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Employee Report')->first(),
            ],
            [
                'name' => 'Employee Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Employee Report')->first(),
            ],
            [
                'name' => 'Store Stock Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store Stock Report')->first(),
            ],
            [
                'name' => 'Store Stock Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Store Stock Report')->first(),
            ],
            [
                'name' => 'Product Warehouse Report View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Warehouse Report')->first(),
            ],
            [
                'name' => 'Product Warehouse Report Download',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Product Warehouse Report')->first(),
            ],
            [
                'name' => 'App Menu Mapping View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'App Menu Mapping')->first(),
            ],
            [
                'name' => 'App Menu Mapping Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'App Menu Mapping')->first(),
            ],
            [
                'name' => 'App Menu Mapping Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'App Menu Mapping')->first(),
            ],
            [
                'name' => 'System Mapping View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'System Mapping')->first(),
            ],
            [
                'name' => 'System Mapping Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'System Mapping')->first(),
            ],
            [
                'name' => 'System Mapping Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'System Mapping')->first(),
            ],
            [
                'name' => 'System Setting Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'System Setting')->first(),
            ],
            [
                'name' => 'System Setting Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'System Setting')->first(),
            ],
            [
                'name' => 'System Setting View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'System Setting')->first(),
            ],
            [
                'name' => 'Email Template Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Email Template')->first(),
            ],
            [
                'name' => 'Email Template Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Email Template')->first(),
            ],
            [
                'name' => 'Email Template View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Email Template')->first(),
            ],
            [
                'name' => 'SMTP Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'SMTP')->first(),
            ],
            [
                'name' => 'SMTP Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'SMTP')->first(),
            ],
            [
                'name' => 'SMTP View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'SMTP')->first(),
            ],
            [
                'name' => 'Users List View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Users List')->first(),
            ],
            [
                'name' => 'Role View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Role')->first()
            ],
            [
                'name' => 'Role Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Role')->first()
            ],
            [
                'name' => 'Role Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Role')->first()
            ],
            [
                'name' => 'Assign Role To User View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Assign Role To User')->first()
            ],
            [
                'name' => 'Assign Role To User Create',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Assign Role To User')->first()
            ],
            [
                'name' => 'Assign Role To User Edit',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Assign Role To User')->first()
            ],
            [
                'name' => 'Activity Log View',
                'guard_name' => 'admin',
                'permission_group_id' => PermissionGroup::where('name', 'Activity Log')->first()
            ],
        ];


        foreach ($permissions as $value) {
            $permission = new Permission();
            $permission->name = $value['name'];
            $permission->guard_name = $value['guard_name'];
            $permission->permission_group_id = $value['permission_group_id']->id;
            $permission->save();
        }
    }
}
