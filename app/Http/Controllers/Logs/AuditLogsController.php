<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'audit logs'], 200);
    }
}
