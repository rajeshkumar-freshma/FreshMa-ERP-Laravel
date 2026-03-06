<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginStoreRequest;
use App\Http\Requests\Auth\ApiSaveTokenRequest;
use App\Http\Requests\Auth\ApiVerifyOtpRequest;
use App\Http\Requests\Auth\ApiVerifyTokenRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAppMenuMapping;
use App\Traits\SMSTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    use SMSTrait;

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle an incoming api authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function credentials($request)
    {
        if (is_numeric($request->get('email'))) {
            return ['phone_number' => $request->get('email'), 'password' => $request->get('password')];
        } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password' => $request->get('password')];
        }
        return ['email' => $request->get('email'), 'password' => $request->get('password')];
    }

    public function loginStore(ApiLoginStoreRequest $request)
    {
        if ($request->email != null && $request->password != null) {
            if (is_numeric($request->get('email'))) {
                $credentials = ['phone_number' => $request->get('email'), 'password' => $request->get('password')];
            } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            } else {
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            }

            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) { // this is is admin check
                //Authentication Success...
                /** @var \App\Models\Admin $admin */
                $admin = Auth::guard('admin')->user();
                $access_token = $admin->createToken('token')->accessToken;

                $hasBottomMenu = UserAppMenuMapping::where('admin_id', $admin->id)->where('menu_type', 1)->where('admin_type', 1)->exists();
                $hasSidebarMenu = UserAppMenuMapping::where('admin_id', $admin->id)->where('menu_type', 2)->where('admin_type', 1)->exists();
                if (!$hasBottomMenu && !$hasSidebarMenu) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'This Supplier Does Not having an Menu mapping Pls contact the admin After try!',
                ]);
                }

                $admin->access_level = $this->resolveAdminAccessLevel($admin);

                return response()->json([
                    'status' => 200,
                    'data' => $admin,
                    'is_supplier' =>0,
                    'access_token' => $access_token,
                    'message' => 'Your Logged in successfully.',
                ]);
            } elseif (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                //Authentication Success...
                $supplier = User::where('user_type', 2)->where('email', $request->email)->first();
                $bottom_menu = UserAppMenuMapping::where('admin_id', $supplier->id)->where('menu_type', 1)->where('admin_type', 2)->get();
                $sidebar_menu = UserAppMenuMapping::where('admin_id', $supplier->id)->where('menu_type', 2)->where('admin_type', 2)->get();

                if (count($bottom_menu) == 0 && count($sidebar_menu) == 0) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'This Supplier Does Not having an Menu mapping Pls contact the admin After try!',
                    ]);
                }

                return response()->json([
                    'status' => 200,
                    'data' => $supplier,
                    'is_supplier' =>1,
                    'access_token' => $supplier->createToken('token')->accessToken,
                    'message' => 'Your Logged in successfully. supplier',
                ]);
            }
        } elseif ($request->filled('phone_number')) {
            $admin = Admin::where('phone_number', $request->phone_number)->orderBy('id', 'DESC')->first();

            if ($admin && $admin->status == 0) {
                return response()->json(['status' => 400, 'message' => 'Account Blocked by Admin']);
            }

            if ($admin && $admin->status == 1) {
                $otp = random_int(100000, 999999);

                $admin->otp = $otp;
                $admin->save();

                $message = "<#> FreshMa - One Time Password(OTP) is $otp. Don't share it with anyone. We don't call/email you to verify OTP. " . '/' . env('ANDROID_AUTO_DETECT_OTP_CODE');

                $this->sendSMS($request->phone_number, $message);

                return response()->json([
                    'status' => 200,
                    'data' => $admin,
                    'otp' => $otp,
                    'phone_number' => $request->phone_number ?? '',
                    'message' => 'Your Logged in successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'phone_number' => $request->phone_number ?? '',
                    'message' => 'Your Logged in Failed.',
                ]);
            }
        }


        //Authentication failed...
        return $this->loginFailed();
    }

    public function verifyOtp(ApiVerifyOtpRequest $request)
    {
        try {
            $admin = Admin::where('phone_number', $request->phone_number)->first();

            if ($admin && $admin->status == 0) {
                return response()->json(['status' => 400, 'message' => 'Account is Inactive']);
            }
            if ($admin && $admin->otp == $request->otp) {
                return response()->json([
                    'status' => 200,
                    'user' => $admin,
                    'access_token' => $admin->createToken('token')->accessToken,
                ]);
            } else {
                return response()->json(['status' => 403, 'message' => 'OTP Invalid']);
            }
        } catch (\Throwable $e) {
            Log::error('OTP verification failed', ['message' => $e->getMessage()]);
            return response()->json(['status' => 400, 'message' => 'OTP verification failed']);
        }
    }

    private function loginFailed()
    {
        return response()->json([
            'status' => 400,
            'message' => 'Login failed, please try again!',
        ]);
    }

    /**
     * Verifies user token.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function apiVerifyToken(ApiVerifyTokenRequest $request)
    {
        $api_token = Admin::where('api_token', $request->api_token)->exists();
        if ($api_token) {
            $user = Admin::where('api_token', $request->api_token)->exists();
        } else {
            $user = User::where('api_token', $request->api_token)->exists();
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'token' => ['Invalid token'],
            ]);
        }
        return response($user);
    }

    /**
     * Save  user fcmToken.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     *  */
    public function saveToken(ApiSaveTokenRequest $request)
    {
        try {
            $payload = [
                'os' => $request->os,
                'fcm_token' => $request->fcmToken,
                'voipToken' => $request->voipToken,
            ];

            $adminUser = Auth::guard('api')->user();
            $supplierUser = Auth::guard('supplier')->user();
            $updated = false;

            DB::transaction(function () use ($adminUser, $supplierUser, $payload, &$updated): void {
                if ($adminUser) {
                    Admin::where('id', $adminUser->id)->update($payload);
                    $updated = true;
                }

                if ($supplierUser) {
                    User::where('id', $supplierUser->id)->update($payload);
                    $updated = true;
                }
            });

            if (!$updated) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            return response()->json([
                'is_success' => true,
                'data' => [
                    'message' => 'Token Updated Successfully',
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Error updating push token', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 400,
                'details' => 'Failed to update tokens. Please try again later.',
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('api')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function resolveAdminAccessLevel(Admin $admin): string
    {
        $warehouseIds = $admin->user_warehouse_data($admin);
        if (!empty($warehouseIds) && (int) $admin->user_type === 1) {
            return 'super_admin';
        }

        if (!empty($warehouseIds)) {
            return 'warehouse';
        }

        $storeIds = $admin->user_store_data($admin);
        if (!empty($storeIds)) {
            return 'store';
        }

        return 'super_admin';
    }
}
