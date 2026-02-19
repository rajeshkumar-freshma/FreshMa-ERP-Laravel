<?php

namespace Database\Seeders;

use App\Models\AppMenu;
use App\Models\UserAppMenuMapping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            ['name' => 'Home', 'component' => 'Home', 'headerTitle' => null, 'headerShown' => true],
            ['name' => 'Store', 'component' => 'Store', 'headerTitle' => null, 'headerShown' => true],
            ['name' => 'Suppliers', 'component' => 'Suppliers', 'headerTitle' => null, 'headerShown' => true],
            ['name' => 'Purchase', 'component' => 'Purchase', 'headerTitle' => null, 'headerShown' => true],
            ['name' => 'Sales', 'component' => 'Sales', 'headerTitle' => null, 'headerShown' => true],
            ['name' => 'Menu', 'component' => 'Menu', 'headerTitle' => null, 'headerShown' => false],
        ];

        $json_data = json_encode($data);

        AppMenu::create([
            'app_menu_json' => $json_data,
            'menu_type' => 1,
            'status' => 1,
        ]);

        $data = [
            [
                "title" => "Stores",
                "route" => "Store",
                "icon" => "store",
                "type" => "material-community"
            ],
            [
                "title" => "Suppliers",
                "route" => "Suppliers",
                "icon" => "human-dolly",
                "type" => "material-community"
            ],
            [
                "title" => "Customers",
                "route" => "Customers",
                "icon" => "user",
                "type" => "feather"
            ],
            [
                "title" => "Purchase",
                "route" => "Purchase",
                "icon" => "shopping-outline",
                "type" => "material-community"
            ],
            [
                "title" => "Sales",
                "route" => "Sales",
                "icon" => "cash",
                "type" => "material-community"
            ],
            [
                "title" => "Today Sales Price",
                "route" => "TodaySalesPrice",
                "icon" => "user",
                "type" => "feather"
            ],
            [
                "title" => "Daily Fish Price Admin",
                "route" => "DailyFishPriceAdmin",
                "icon" => "user",
                "type" => "feather"
            ],
            [
                "title" => "Indent Request",
                "icon" => "cash",
                "type" => "material-community",
                "route" => [
                    [
                        "title" => "Store Indent Request",
                        "route" => "StoreIndentRequest",
                        "icon" => "user",
                        "type" => "feather"
                    ],
                    [
                        "title" => "Warehouse Indent Request",
                        "route" => "WarehouseIndentRequest",
                        "icon" => "user",
                        "type" => "feather"
                    ]
                ]
            ],
            [
                "title" => "Returns",
                "icon" => "cash",
                "type" => "material-community",
                "route" => [
                    [
                        "title" => "Store Returns",
                        "route" => "Return",
                        "icon" => "user",
                        "type" => "feather",
                        "return_from" => "1",
                    ],
                    [
                        "title" => "Customer Returns",
                        "route" => "Return",
                        "icon" => "user",
                        "type" => "feather",
                        "return_from" => "2"
                    ],
                    [
                        "title" => "Spoilage",
                        "route" => "Spoilage",
                        "icon" => "user",
                        "type" => "feather",
                    ],
                    // [
                    //     "title" => "Distribution",
                    //     "route" => "Distribution",
                    //     "icon" => "user",
                    //     "type" => "feather"
                    // ]
                ]
            ],
            [
                "title" => "Reports",
                "icon" => "cash",
                "type" => "material-community",
                "route" => [
                    [
                        "id" => 10,
                        "title" => "Indent Request Report",
                        "route" => "Reports",
                        "icon" => "user",
                        "type" => "feather"
                    ], [
                        "id" => 15,
                        "title" => "Stock Report",
                        "route" => "Reports",
                        "icon" => "user",
                        "type" => "feather"
                    ],
                    [
                        "id" => 16,
                        "title" => "Product wise Sales Report",
                        "route" => "Reports",
                        "icon" => "user",
                        "type" => "feather"
                    ]
                ]
            ]
        ];

        $json_data = json_encode($data);

        AppMenu::create([
            'app_menu_json' => $json_data,
            'menu_type' => 2,
            'status' => 1,
        ]);

        if(ENV('APP_ENV') == 'production') {
        } else {
        $user_app_menu_mappings = array(
            array(
                // "id" => 1,
                "admin_id" => 1,
                "admin_type" => 1,
                "menu_type" => 1,
                "app_menu_json" => "[{\"name\":\"Home\",\"component\":\"Home\",\"headerTitle\":null,\"headerShown\":true},{\"name\":\"Store\",\"component\":\"Store\",\"headerTitle\":null,\"headerShown\":true},{\"name\":\"Suppliers\",\"component\":\"Suppliers\",\"headerTitle\":null,\"headerShown\":true},{\"name\":\"Purchase\",\"component\":\"Purchase\",\"headerTitle\":null,\"headerShown\":true},{\"name\":\"Sales\",\"component\":\"Sales\",\"headerTitle\":null,\"headerShown\":true},{\"name\":\"Menu\",\"component\":\"Menu\",\"headerTitle\":null,\"headerShown\":false}]",
                "remarks" => null,
                "status" => 1,
                "created_at" => "2024-07-04T06:13:09.657Z",
                "updated_at" => "2024-07-04T06:13:09.657Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 2,
                "admin_id" => 1,
                "admin_type" => 1,
                "menu_type" => 2,
                "app_menu_json" => "[{\"title\":\"Stores\",\"route\":\"Store\",\"icon\":\"store\",\"type\":\"material-community\"},{\"title\":\"Suppliers\",\"route\":\"Suppliers\",\"icon\":\"human-dolly\",\"type\":\"material-community\"},{\"title\":\"Customers\",\"route\":\"Customers\",\"icon\":\"user\",\"type\":\"feather\"},{\"title\":\"Purchase\",\"route\":\"Purchase\",\"icon\":\"shopping-outline\",\"type\":\"material-community\"},{\"title\":\"Sales\",\"route\":\"Sales\",\"icon\":\"cash\",\"type\":\"material-community\"},{\"title\":\"Today Sales Price\",\"route\":\"TodaySalesPrice\",\"icon\":\"user\",\"type\":\"feather\"},{\"title\":\"Daily Fish Price Admin\",\"route\":\"DailyFishPriceAdmin\",\"icon\":\"user\",\"type\":\"feather\"},{\"title\":\"Indent Request\",\"icon\":\"cash\",\"type\":\"material-community\",\"route\":[{\"title\":\"Store Indent Request\",\"route\":\"StoreIndentRequest\",\"icon\":\"user\",\"type\":\"feather\"},{\"title\":\"Warehouse Indent Request\",\"route\":\"WarehouseIndentRequest\",\"icon\":\"user\",\"type\":\"feather\"}]},{\"title\":\"Returns\",\"icon\":\"cash\",\"type\":\"material-community\",\"route\":[{\"title\":\"Store Returns\",\"route\":\"Return\",\"icon\":\"user\",\"type\":\"feather\",\"return_from\":\"1\"},{\"title\":\"Customer Returns\",\"route\":\"Return\",\"icon\":\"user\",\"type\":\"feather\",\"return_from\":\"2\"},{\"title\":\"Spoilage\",\"route\":\"Spoilage\",\"icon\":\"user\",\"type\":\"feather\"}]},{\"title\":\"Reports\",\"icon\":\"cash\",\"type\":\"material-community\",\"route\":[{\"id\":10,\"title\":\"Indent Request Report\",\"route\":\"Reports\",\"icon\":\"user\",\"type\":\"feather\"},{\"id\":15,\"title\":\"Stock Report\",\"route\":\"Reports\",\"icon\":\"user\",\"type\":\"feather\"},{\"id\":16,\"title\":\"Product wise Sales Report\",\"route\":\"Reports\",\"icon\":\"user\",\"type\":\"feather\"}]}]",
                "remarks" => null,
                "status" => 1,
                "created_at" => "2024-07-04T06:13:09.737Z",
                "updated_at" => "2024-07-04T06:13:09.737Z",
                "deleted_at" => null
            )
        );
        foreach ($user_app_menu_mappings as $user_app_menu_mapping) {
            UserAppMenuMapping::create($user_app_menu_mapping);
        }
    }

    }
}
