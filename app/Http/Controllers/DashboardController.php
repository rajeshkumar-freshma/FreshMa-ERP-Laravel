<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);

        return view('pages.dashboards.index');
    }

    public function privacyPolicies()
    {
        return view('privacy.privacy_policy');
    }
}
