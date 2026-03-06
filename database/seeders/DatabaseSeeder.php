<?php

namespace Database\Seeders;

use Database\Seeders\SettingTableSeeder as SeedersSettingTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('production')) {

            // 1) Users
            $this->call([
                UsersSeeder::class,
            ]);

            // 2) Location & Core Masters
            $this->call([
                CurrencyTableSeeder::class,
                CountryTableSeeder::class,
                StatesTableSeeder::class,
                CitySeeder::class,
                UnitsTableSeeder::class,
                TaxRatesTableSeeder::class,
                CategoriesTableSeeder::class,
                PartnershipTypesTableSeeder::class,
                IncomeExpenseTypesTableSeeder::class,
                ItemTypesTableSeeder::class,
                TransportTypesTableSeeder::class,
                DenominationTypeSeeder::class,
            ]);

            // 3) Warehouse Structure
            $this->call([
                WarehousesTableSeeder::class,
                StoresTableSeeder::class,
            ]);

            // 4) Payment Types (after stores)
            $this->call([
                PaymentTypeSeeder::class,
            ]);

            // 5) Access Control
            $this->call([
                PermissionGroupTableSeeder::class,
                PermissionsTableSeeder::class,
                RolesTableSeeder::class,
                ModelHasRolesTableSeeder::class,
            ]);

            // 6) Business Masters
            $this->call([
                SupplierTableSeeder::class,
                VendorTableSeeder::class,
                PartnerTableSeeder::class,
                ProductTableSeeder::class,
                FishCuttingTableSeeder::class,
                ProductFishCuttingMappingSeeder::class,
            ]);

            // 7) HR Masters
            $this->call([
                EmployeeSeeder::class,
                HolidaySeeder::class,
                LeaveTypeSeeder::class,
            ]);

            // 8) Transactional Data
            $this->call([
                PurchaseOrderSeeder::class,
                StoreIndentRequestSeeder::class,
                VendorIndentRequestSeeder::class,
                WarehouseIndentRequestSeeder::class,
                SalesOrderSeeder::class,
            ]);

            // 9) System Configurations
            $this->call([
                EmailTemplateSeeder::class,
                SeedersSettingTableSeeder::class,
                AdminMenuSeeder::class,
            ]);
        } else {
            // 1) Users
            $this->call([
                UsersSeeder::class,
            ]);

            // 2) Location & Core Masters
            $this->call([
                CurrencyTableSeeder::class,
                CountryTableSeeder::class,
                StatesTableSeeder::class,
                CitySeeder::class,
                UnitsTableSeeder::class,
                TaxRatesTableSeeder::class,
                CategoriesTableSeeder::class,
                PartnershipTypesTableSeeder::class,
                IncomeExpenseTypesTableSeeder::class,
                ItemTypesTableSeeder::class,
                TransportTypesTableSeeder::class,
                DenominationTypeSeeder::class,
            ]);

            // 3) Warehouse Structure
            $this->call([
                WarehousesTableSeeder::class,
                StoresTableSeeder::class,
            ]);

            // 4) Payment Types (after stores)
            $this->call([
                PaymentTypeSeeder::class,
            ]);

            // 5) Access Control
            $this->call([
                PermissionGroupTableSeeder::class,
                PermissionsTableSeeder::class,
                RolesTableSeeder::class,
                ModelHasRolesTableSeeder::class,
            ]);

            // 6) Business Masters
            $this->call([
                SupplierTableSeeder::class,
                VendorTableSeeder::class,
                PartnerTableSeeder::class,
                ProductTableSeeder::class,
                FishCuttingTableSeeder::class,
                ProductFishCuttingMappingSeeder::class,
            ]);

            // 7) HR Masters
            $this->call([
                EmployeeSeeder::class,
                HolidaySeeder::class,
                LeaveTypeSeeder::class,
            ]);

            // 8) Transactional Data
            $this->call([
                PurchaseOrderSeeder::class,
                StoreIndentRequestSeeder::class,
                VendorIndentRequestSeeder::class,
                WarehouseIndentRequestSeeder::class,
                SalesOrderSeeder::class,
            ]);

            // 9) System Configurations
            $this->call([
                EmailTemplateSeeder::class,
                SeedersSettingTableSeeder::class,
                AdminMenuSeeder::class,
            ]);
        }
    }
}
