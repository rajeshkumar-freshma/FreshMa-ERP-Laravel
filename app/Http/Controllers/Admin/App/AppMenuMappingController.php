<?php

namespace App\Http\Controllers\Admin\App;

use App\DataTables\Setting\UserMenuMapDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AppMenu;
use App\Models\User;
use App\Models\UserAppMenuMapping;
use Illuminate\Http\Request;

class AppMenuMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserMenuMapDataTable $dataTable)
    {
        return $dataTable->render('pages.setting.app.menu_map.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['admins'] = Admin::where('status', 1)->get();
        $data['suppliers'] = User::where('status', 1)->where('user_type', 2)->get();
        $data['bottom_menus'] = AppMenu::where('status', 1)->where('menu_type', 1)->first();
        $data['sidebar_menus'] = AppMenu::where('status', 1)->where('menu_type', 2)->first();
        return view('pages.setting.app.menu_map.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bottom_menus = AppMenu::where('status', 1)->where('menu_type', 1)->first();
        $sidebar_menus = AppMenu::where('status', 1)->where('menu_type', 2)->first();
        $approve_bottom_menu = $request->bottom_menu;
        $supplier_id = $request->supplier_id;
        $admin_id = $request->admin_id;
        // admin only
        if ($admin_id !== null) {
            if (!empty($bottom_menus) && $bottom_menus->app_menu_json != 'null' && !empty($approve_bottom_menu)) {
                $bottom_menu_json = json_decode($bottom_menus->app_menu_json, true);

                foreach ($bottom_menu_json as $key => $bottom_menu) {
                    if (!in_array($bottom_menu['name'], $approve_bottom_menu)) {
                        unset($bottom_menu_json[$key]);
                    }
                }

                UserAppMenuMapping::create([
                    'admin_id' => $admin_id,
                    'admin_type' => 1, // 1 means admin
                    'menu_type' => 1, // bottom menu
                    'app_menu_json' => json_encode(array_values($bottom_menu_json)),
                    'status' => $request->status,
                ]);
            }
            $approve_sidebar_menu = $request->sidebar_menu;
            if (!empty($sidebar_menus) && $sidebar_menus->app_menu_json != 'null' && is_array($approve_sidebar_menu) && count($approve_sidebar_menu) > 0) {
                $sidebar_menu_json = collect(json_decode($sidebar_menus->app_menu_json, true));

                $sidebar_sub_menu = $request->sub_menu;
                foreach ($sidebar_menu_json as $key => $sidebar_menu) {
                    if (in_array($sidebar_menu['title'], $approve_sidebar_menu)) {
                        if (is_array($sidebar_menu['route']) && count($sidebar_menu['route']) > 0) {
                            foreach ($sidebar_menu['route'] as $key2 => $submenu) {
                                if (!in_array($submenu['title'], $sidebar_sub_menu)) {
                                    if (isset($sidebar_menu['route'][$key2])) {
                                        array_shift($sidebar_menu['route'][$key2]);
                                    }
                                }
                            }
                        }
                    } else {
                        unset($sidebar_menu_json[$key]);
                    }
                }
                UserAppMenuMapping::create([
                    'admin_id' => $admin_id,
                    'admin_type' => 1, // 1 means admin
                    'menu_type' => 2, //2 side bar menu
                    'app_menu_json' => json_encode(array_values($sidebar_menu_json->toArray())),
                    'status' => $request->status,
                ]);
            }
        }

        //supplier only
        if ($supplier_id !== null) {

            if (!empty($bottom_menus) && $bottom_menus->app_menu_json != 'null' && !empty($approve_bottom_menu)) {
                $bottom_menu_json = json_decode($bottom_menus->app_menu_json, true);

                foreach ($bottom_menu_json as $key => $bottom_menu) {
                    if (!in_array($bottom_menu['name'], $approve_bottom_menu)) {
                        unset($bottom_menu_json[$key]);
                    }
                }

                UserAppMenuMapping::create([
                    'admin_id' => $supplier_id,
                    'admin_type' => 2, // 2 means supplier
                    'menu_type' => 1, // bottom menu
                    'app_menu_json' => json_encode(array_values($bottom_menu_json)),
                    'status' => $request->status,
                ]);
                $approve_sidebar_menu = $request->sidebar_menu;
                if (!empty($sidebar_menus) && $sidebar_menus->app_menu_json != 'null' && is_array($approve_sidebar_menu) && count($approve_sidebar_menu) > 0) {
                    $sidebar_menu_json = collect(json_decode($sidebar_menus->app_menu_json, true));

                    $sidebar_sub_menu = $request->sub_menu;
                    foreach ($sidebar_menu_json as $key => $sidebar_menu) {
                        if (in_array($sidebar_menu['title'], $approve_sidebar_menu)) {
                            if (is_array($sidebar_menu['route']) && count($sidebar_menu['route']) > 0) {
                                foreach ($sidebar_menu['route'] as $key2 => $submenu) {
                                    if (!in_array($submenu['title'], $sidebar_sub_menu)) {
                                        if (isset($sidebar_menu['route'][$key2])) {
                                            array_shift($sidebar_menu['route'][$key2]);
                                        }
                                    }
                                }
                            }
                        } else {
                            unset($sidebar_menu_json[$key]);
                        }
                    }
                    UserAppMenuMapping::create([
                        'admin_id' => $supplier_id,
                        'admin_type' => 2, // 1 means supplier
                        'menu_type' => 2, //2 side bar menu
                        'app_menu_json' => json_encode(array_values($sidebar_menu_json->toArray())),
                        'status' => $request->status,
                    ]);
                }
            }
        }

        if ($request->submission_type == 1) {
            return redirect()->route('admin.app-menu-mapping.index')->with('success', 'App User Menu Mapping Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'App User Menu Mapping Stored Successfully');
        }

        // return $sidebar_menu_data = json_encode(array_values($sidebar_menu_json->toArray()));
        // return $bottom_menu_data = array_values($bottom_menu_json);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['admins'] = Admin::where('status', 1)->get();
        $data['suppliers'] = User::where('status', 1)->where('user_type', 2)->get();
        $data['user_menu_map'] = UserAppMenuMapping::findOrFail($id);
        $menu_data = $data['user_menu_map'];
        $data['approved_bottom_menu_keys'] = [];
        $data['approved_sidebar_menu_keys'] = [];
        $data['approved_sidebar_sub_menu_keys'] = [];
        if ($menu_data->menu_type == 1) {
            $approved_bottom_menu = json_decode($menu_data->app_menu_json, true);
            foreach ($approved_bottom_menu as $key => $approved_bottom_menu_data) {
                $data['approved_bottom_menu_keys'][] = $approved_bottom_menu_data['name'];
            }
        }

        if ($menu_data->menu_type == 2) {
            $approved_sidebar_menu = json_decode($menu_data->app_menu_json, true);
            foreach ($approved_sidebar_menu as $key => $approved_sidebar) {
                $data['approved_sidebar_menu_keys'][] = $approved_sidebar['title'];
                if (is_array($approved_sidebar['route']) && count($approved_sidebar['route']) > 0) {
                    foreach ($approved_sidebar['route'] as $key => $approved_submenu) {
                        $data['approved_sidebar_sub_menu_keys'][] = $approved_submenu['title'];
                    }
                }
            }
        }

        $data['bottom_menus'] = AppMenu::where('status', 1)->where('menu_type', 1)->first();
        $data['sidebar_menus'] = AppMenu::where('status', 1)->where('menu_type', 2)->first();
        return view('pages.setting.app.menu_map.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request;
        $bottom_menus = AppMenu::where('status', 1)->where('menu_type', 1)->first();
        $sidebar_menus = AppMenu::where('status', 1)->where('menu_type', 2)->first();

        $approve_bottom_menu = $request->bottom_menu;
        $approve_sidebar_menu = $request->sidebar_menu;
        $supplier_id = $request->supplier_id;
        $admin_id = $request->admin_id;

        //admin only update
        if ($admin_id != null) {
            if (!empty($approve_bottom_menu) && !empty($bottom_menus) && $bottom_menus->app_menu_json != 'null') {
                $bottom_menu_json = json_decode($bottom_menus->app_menu_json, true);

                foreach ($bottom_menu_json as $key => $bottom_menu) {
                    if (!in_array($bottom_menu['name'], $approve_bottom_menu)) {
                        unset($bottom_menu_json[$key]);
                    }
                }

                UserAppMenuMapping::where('id', $id)->update([
                    'admin_id' => $admin_id,
                    'admin_type' => 1,
                    'menu_type' => 1,
                    'app_menu_json' => json_encode(array_values($bottom_menu_json)),
                    'status' => $request->status,
                ]);
            }

            if (!empty($approve_sidebar_menu) && !empty($sidebar_menus) && is_array($approve_sidebar_menu) && $sidebar_menus->app_menu_json != 'null') {
                $sidebar_menu_json = collect(json_decode($sidebar_menus->app_menu_json, true));

                $sidebar_sub_menu = $request->sub_menu;
                foreach ($sidebar_menu_json as $key => $sidebar_menu) {
                    if (in_array($sidebar_menu['title'], $approve_sidebar_menu)) {
                        if (is_array($sidebar_menu['route']) && count($sidebar_menu['route']) > 0) {
                            foreach ($sidebar_menu['route'] as $key2 => $submenu) {
                                if (!in_array($submenu['title'], $sidebar_sub_menu)) {
                                    if (isset($sidebar_menu['route'][$key2])) {
                                        array_shift($sidebar_menu['route'][$key2]);
                                    }
                                }
                            }
                        }
                    } else {
                        unset($sidebar_menu_json[$key]);
                    }
                }
                UserAppMenuMapping::where('id', $id)->update([
                    'admin_id' => $admin_id,
                    'admin_type' => 1,
                    'menu_type' => 2,
                    'app_menu_json' => json_encode(array_values($sidebar_menu_json->toArray())),
                    'status' => $request->status,
                ]);
            }
        }

        //supplie only update menu
        if ($supplier_id != null) {
            if (!empty($approve_bottom_menu) && !empty($bottom_menus) && $bottom_menus->app_menu_json != 'null') {
                $bottom_menu_json = json_decode($bottom_menus->app_menu_json, true);

                foreach ($bottom_menu_json as $key => $bottom_menu) {
                    if (!in_array($bottom_menu['name'], $approve_bottom_menu)) {
                        unset($bottom_menu_json[$key]);
                    }
                }

                UserAppMenuMapping::where('id', $id)->update([
                    'admin_id' => $supplier_id,
                    'admin_type' => 2,
                    'menu_type' => 1,
                    'app_menu_json' => json_encode(array_values($bottom_menu_json)),
                    'status' => $request->status,
                ]);
            }

            if (!empty($approve_sidebar_menu) && !empty($sidebar_menus) && is_array($approve_sidebar_menu) && $sidebar_menus->app_menu_json != 'null') {
                $sidebar_menu_json = collect(json_decode($sidebar_menus->app_menu_json, true));

                $sidebar_sub_menu = $request->sub_menu;
                foreach ($sidebar_menu_json as $key => $sidebar_menu) {
                    if (in_array($sidebar_menu['title'], $approve_sidebar_menu)) {
                        if (is_array($sidebar_menu['route']) && count($sidebar_menu['route']) > 0) {
                            foreach ($sidebar_menu['route'] as $key2 => $submenu) {
                                if (!in_array($submenu['title'], $sidebar_sub_menu)) {
                                    if (isset($sidebar_menu['route'][$key2])) {
                                        array_shift($sidebar_menu['route'][$key2]);
                                    }
                                }
                            }
                        }
                    } else {
                        unset($sidebar_menu_json[$key]);
                    }
                }
                UserAppMenuMapping::where('id', $id)->update([
                    'admin_id' => $supplier_id,
                    'admin_type' => 2,
                    'menu_type' => 2,
                    'app_menu_json' => json_encode(array_values($sidebar_menu_json->toArray())),
                    'status' => $request->status,
                ]);
            }
        }
        return redirect()->route('admin.app-menu-mapping.index')->with('success', 'App User Menu Mapping Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /* $data = [
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
    return json_encode($data); */

    /* $data = [
['name' => 'Home', 'component' => 'Home', 'headerTitle' => null, 'headerShown' => true],
['name' => 'Store', 'component' => 'Store', 'headerTitle' => null, 'headerShown' => true],
['name' => 'Suppliers', 'component' => 'Suppliers', 'headerTitle' => null, 'headerShown' => true],
['name' => 'Purchase', 'component' => 'Purchase', 'headerTitle' => null, 'headerShown' => true],
['name' => 'Sales', 'component' => 'Sales', 'headerTitle' => null, 'headerShown' => true],
['name' => 'Menu', 'component' => 'Menu', 'headerTitle' => null, 'headerShown' => false],
];

return json_encode($data); */
}
