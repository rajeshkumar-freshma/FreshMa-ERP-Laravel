<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialiteLoginController extends Controller
{
    public function redirect($provider = null)
    {
        return response()->json(['message' => 'social redirect', 'provider' => $provider], 200);
    }

    public function callback($provider = null)
    {
        return response()->json(['message' => 'social callback', 'provider' => $provider], 200);
    }
}
