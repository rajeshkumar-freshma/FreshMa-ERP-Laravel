<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'settings index'], 200);
    }

    public function update(Request $request)
    {
        return response()->json(['message' => 'settings updated'], 200);
    }

    public function changeEmail(Request $request)
    {
        return response()->json(['message' => 'email changed'], 200);
    }

    public function changePassword(Request $request)
    {
        return response()->json(['message' => 'password changed'], 200);
    }

    public function s3LogoUpload(Request $request)
    {
        return response()->json(['message' => 'logo uploaded'], 200);
    }
}
