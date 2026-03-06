<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'pages index'], 200);
    }

    public function show($slug = null)
    {
        return response()->json(['message' => 'page', 'slug' => $slug], 200);
    }
}
