<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::table('permission_groups')->delete();

        $permissionGroups = [
            [
                'name' => 'Dashboard',
            ],
            [
                'name' => 'Warehouse',
            ],
            [
                'name' => 'Store',
            ],
            [
                'name' => 'Item Type',
            ],
            [
                'name' => 'Denomination',
            ],
            [
                'name' => 'Unit',
            ],
            [
                'name' => 'Income/Expense Category',
            ],
            [
                'name' => 'Tax Rate',
            ],
            [
                'name' => 'Partnership Type',
            ],
            [
                'name' => 'Customer',
            ],
            [
                'name' => 'Category',
            ],
            [
                'name' => 'Partner/Manager',
            ],
            [
                'name' => 'Supplier',
            ],
            [
                'name' => 'Transport Type',
            ],
            [
                'name' => 'Machine Details',
            ],
            [
                'name' => 'Payment Type',
            ],
            [
                'name' => 'Products',
            ],
            [
                'name' => 'Stock Management',
            ],
            [
                'name' => 'Adjustment',
            ],
            [
                'name' => 'Fish Cutting',
            ],
            [
                'name' => 'Product Fish Cutting Mapping',
            ],
            [
                'name' => 'Daily Product Price Update',
            ],
            [
                'name' => 'Store Indent Request',
            ],
            [
                'name' => 'Customer Indent Request',
            ],
            [
                'name' => 'Warehouse Indent Request',
            ],
            [
                'name' => 'Purchase Order',
            ],
            [
                'name' => 'Product Pin Mapping',
            ],
            [
                'name' => 'Purchase Credit Notes',
            ],
            [
                'name' => 'Sales Order',
            ],
            [
                'name' => 'Sales Credit',
            ],
            [
                'name' => 'Income Expense Add',
            ],
            [
                'name' => 'Supplier Payment',
            ],
            [
                'name' => 'Cash Paind To Offiice',
            ],
            [
                'name' => 'Cash Register',
            ],
            [
                'name' => 'Daily Stock Update',
            ],
            [
                'name' => 'Purchase Return',
            ],
            [
                'name' => 'Sales Return',
            ],
            [
                'name' => 'Product Transfer',
            ],
            [
                'name' => 'Bulk Product Transfer',
            ],
            [
                'name' => 'Accounts',
            ],
            [
                'name' => 'Transfer',
            ],
            [
                'name' => 'Transaction',
            ],
            [
                'name' => 'Transaction Report',
            ],
            [
                'name' => 'Bulk Transaction Upload',
            ],
            [
                'name' => 'Department',
            ],
            [
                'name' => 'Desigination',
            ],
            [
                'name' => 'Employee',
            ],
            [
                'name' => 'Staff Attendance',
            ],
            [
                'name' => 'Staff Advanced',
            ],
            [
                'name' => 'Leave Type',
            ],
            [
                'name' => 'Holiday',
            ],
            [
                'name' => 'Leave',
            ],
            [
                'name' => 'Payroll Type',
            ],
            [
                'name' => 'Payroll Template',
            ],
            [
                'name' => 'Payroll Setup',
            ],
            [
                'name' => 'Apply Loan',
            ],
            [
                'name' => 'RePayment',
            ],
            [
                'name' => 'Loan Products',
            ],
            [
                'name' => 'Loan Charges',
            ],
            [
                'name' => 'Transactions',
            ],
            [
                'name' => 'Suppliers',
            ],
            [
                'name' => 'Users',
            ],
            [
                'name' => 'Payment Collection',
            ],
            [
                'name' => 'Branch Sales Report',
            ],
            [
                'name' => 'Product Sales Report',
            ],
            [
                'name' => 'Product Purchase Report',
            ],
            [
                'name' => 'Supplier Wise Purchase Report',
            ],
            [
                'name' => 'Daily Sales Report',
            ],
            [
                'name' => 'Daily Store Report',
            ],
            [
                'name' => 'Profit And Loss Report',
            ],
            [
                'name' => 'Indent Request Report',
            ],
            [
                'name' => 'Sales Orders Report',
            ],
            [
                'name' => 'Fish Cutting Details Report',
            ],
            [
                'name' => 'Payments Report',
            ],
            [
                'name' => 'Employee Report',
            ],
            [
                'name' => 'Store Stock Report',
            ],
            [
                'name' => 'Product Warehouse Report',
            ],
            [
                'name' => 'App Menu Mapping',
            ],
            [
                'name' => 'System Mapping',
            ],
            [
                'name' => 'System Setting',
            ],
            [
                'name' => 'Email Template',
            ],
            [
                'name' => 'SMTP',
            ],
            [
                'name' => 'Users List',
            ],
            [
                'name' => 'Role',
            ],
            [
                'name' => 'Assign Role To User',
            ],
            [
                'name' => 'Activity Log',
            ],

        ];

        foreach ($permissionGroups as $value) {
            $permissionGroup = new PermissionGroup(); // Create a new PermfissionGroup object
            $permissionGroup->name = $value['name']; // Set the 'name' attribute
            $permissionGroup->save(); // Save the PermissionGroup object to the database
        }
    }

    // public function data()
    // {
    //     $data = [];
    //     // list of model permission
    //     $model = ['content', 'user', 'role', 'permission'];

    //     foreach ($model as $value) {
    //         foreach ($this->crudActions($value) as $action) {
    //             $data[] = ['name' => $action];
    //         }
    //     }

    //     return $data;
    // }

    // public function crudActions($name)
    // {
    //     $actions = [];
    //     // list of permission actions
    //     $crud = ['create', 'read', 'update', 'delete'];

    //     foreach ($crud as $value) {
    //         $actions[] = $value.' '.$name;
    //     }

    //     return $actions;
    // }
}
