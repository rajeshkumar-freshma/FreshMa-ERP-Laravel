<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemLogsController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'system logs'], 200);
    }
}
