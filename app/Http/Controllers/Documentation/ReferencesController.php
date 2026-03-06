<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferencesController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'references index'], 200);
    }

    public function show($id)
    {
        return response()->json(['message' => 'reference detail', 'id' => $id], 200);
    }
}
