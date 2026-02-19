<?php

namespace Database\Seeders;

use Database\Seeders\SettingTableSeeder as SeedersSettingTableSeeder;
use Illuminate\Database\Seeder;
use SettingTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(ENV('APP_ENV') == 'production') {
        $this->call([
            CurrencyTableSeeder::class,
            CountryTableSeeder::class,
            StatesTableSeeder::class,
            CitySeeder::class,
            UsersSeeder::class,
            WarehousesTableSeeder::class,
            StoresTableSeeder::class,
            IncomeExpenseTypesTableSeeder::class,
            ItemTypesTableSeeder::class,
            PaymentTypeSeeder::class,
            UnitsTableSeeder::class,
            TaxRatesTableSeeder::class,
            PartnershipTypesTableSeeder::class,
            CategoriesTableSeeder::class,
            EmailTemplateSeeder::class,
            PermissionGroupTableSeeder::class,
            // DefaultImageSeeder::class,
            SupplierTableSeeder::class,
            VendorTableSeeder::class,
            ProductTableSeeder::class,
            PurchaseOrderSeeder::class,
            PartnerTableSeeder::class,
            ProductFishCuttingMappingSeeder::class,
            FishCuttingTableSeeder::class,
            StoreIndentRequestSeeder::class,
            VendorIndentRequestSeeder::class,
            WarehouseIndentRequestSeeder::class,
            SalesOrderSeeder::class,
            EmployeeSeeder::class,
            HolidaySeeder::class,
            LeaveTypeSeeder::class,
            // StoresTableSeeder::class,
            // ItemTypesTableSeeder::class,
            // CategoriesTableSeeder::class,
            
            // TaxRatesTableSeeder::class,
          
            // UsersTableSeeder::class,
            // TransportTypesTableSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            // RoleHasPermissionsTableSeeder::class,
            ModelHasRolesTableSeeder::class,

            // PaymentTypeSeeder::class,
            // DesignationSeeder::class,
            // DepartmentSeeder::class,
            SeedersSettingTableSeeder::class,
            AdminMenuSeeder::class,
            DenominationTypeSeeder::class,
        ]);
        } else {
            $this->call([
                CurrencyTableSeeder::class,
                CountryTableSeeder::class,
                StatesTableSeeder::class,
                CitySeeder::class,
                UsersSeeder::class,
                WarehousesTableSeeder::class,
                StoresTableSeeder::class,
                IncomeExpenseTypesTableSeeder::class,
                ItemTypesTableSeeder::class,
                PaymentTypeSeeder::class,
                UnitsTableSeeder::class,
                TaxRatesTableSeeder::class,
                CategoriesTableSeeder::class,
                PartnershipTypesTableSeeder::class,
                EmailTemplateSeeder::class,
                PermissionGroupTableSeeder::class,
                // DefaultImageSeeder::class,
                SupplierTableSeeder::class,
                VendorTableSeeder::class,
                ProductTableSeeder::class,
                PurchaseOrderSeeder::class,
                PartnerTableSeeder::class,
                ProductFishCuttingMappingSeeder::class,
                FishCuttingTableSeeder::class,
                StoreIndentRequestSeeder::class,
                VendorIndentRequestSeeder::class,
                WarehouseIndentRequestSeeder::class,
                SalesOrderSeeder::class,
                EmployeeSeeder::class,
                HolidaySeeder::class,
                LeaveTypeSeeder::class,
                // StoresTableSeeder::class,
                // ItemTypesTableSeeder::class,
                
                // UnitsTableSeeder::class,
                // TaxRatesTableSeeder::class,
                // PartnershipTypesTableSeeder::class,
                // UsersTableSeeder::class,
                // TransportTypesTableSeeder::class,
                PermissionsTableSeeder::class,
                RolesTableSeeder::class,
                // RoleHasPermissionsTableSeeder::class,
                ModelHasRolesTableSeeder::class,

                // PaymentTypeSeeder::class,
                // DesignationSeeder::class,
                // DepartmentSeeder::class,
                SeedersSettingTableSeeder::class,
                AdminMenuSeeder::class,

                TransportTypesTableSeeder::class,
                DenominationTypeSeeder::class,
                // ProductSeeder::class,
                // FishCuttingProductMapSeeder::class,

            ]);
        }
    }
}
