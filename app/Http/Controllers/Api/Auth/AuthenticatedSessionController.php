<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAppMenuMapping;
use App\Traits\SMSTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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

    public function loginStore(Request $request)
    {
        if ($request->email != null && $request->password != null) {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|min:4|max:255',
            ], [
                'email.required' => 'Please Enter Your Mail Address',
                'email.unique' => 'Email Already Exists',
                'email.email' => 'Email Must be an valid address',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
            if (is_numeric($request->get('email'))) {
                $credentials = ['phone_number' => $request->get('email'), 'password' => $request->get('password')];
            } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            } else {
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            }

            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) { // this is is admin check
                //Authentication Success...
                Log::info("guard admin entered with credentials");
                $admin = Admin::where('email', $request->email)->OrWhere('phone_number', $request->email)->first();
                $access_token = $admin->createToken('token')->accessToken;

                $bottom_menu = UserAppMenuMapping::where('admin_id', $admin->id)->where('menu_type', 1)->where('admin_type', 1)->get();
                $sidebar_menu = UserAppMenuMapping::where('admin_id', $admin->id)->where('menu_type', 2)->where('admin_type', 1)->get();
                Log::info($bottom_menu);
                Log::info($sidebar_menu);
                if (count($bottom_menu) == 0 && count($sidebar_menu) == 0) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'This Supplier Does Not having an Menu mapping Pls contact the admin After try!',
                ]);
                }

                if (count($admin->user_warehouse_data($admin)) > 0 && $admin->user_type == 1) {
                    $admin_access_level = 'super_admin';
                } else if (count($admin->user_warehouse_data($admin)) > 0) {
                    $admin_access_level = 'warehouse';
                } else if (count($admin->user_store_data($admin)) > 0) {
                    $admin_access_level = 'store';
                } else {
                    $admin_access_level = 'super_admin';
                }
                $admin->access_level = $admin_access_level;

                return response()->json([
                    'status' => 200,
                    'data' => $admin,
                    'is_supplier' =>0,
                    'access_token' => $access_token,
                    'message' => 'Your Logged in successfully.',
                ]);
            } elseif (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                //Authentication Success...
                Log::info("guard supplier");
                $supplier = User::where('user_type', 2)->where('email', $request->email)->first();

                Log::info($supplier);
                $bottom_menu = UserAppMenuMapping::where('admin_id', $supplier->id)->where('menu_type', 1)->where('admin_type', 2)->get();
                $sidebar_menu = UserAppMenuMapping::where('admin_id', $supplier->id)->where('menu_type', 2)->where('admin_type', 2)->get();

                Log::info("App Menu");
                Log::info($bottom_menu);
                Log::info($sidebar_menu);

                if (count($bottom_menu) == 0 && count($sidebar_menu) == 0) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'This Supplier Does Not having an Menu mapping Pls contact the admin After try!',
                    ]);
                }

                Log::info($supplier->createToken('token')->accessToken);
                Log::info("guard supplier login completed");

                return response()->json([
                    'status' => 200,
                    'data' => $supplier,
                    'is_supplier' =>1,
                    'access_token' => $supplier->createToken('token')->accessToken,
                    'message' => 'Your Logged in successfully. supplier',
                ]);
            }
        } elseif ($request->filled('phone_number')) {
            Log::info('Phone number');
            Log::info($request->phone_number);

            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|min:10|max:12',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
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

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required',
                'otp' => 'required|numeric',
            ], [
                'otp.required' => 'OTP is required',
                'otp.numeric' => 'OTP must be a number',
            ]);

            $admin = Admin::where('phone_number', $request->phone_number)->first();

            if ($admin && $admin->status == 0) {
                return response()->json(['status' => 400, 'message' => 'Account is Inactive']);
            }
            Log::info($admin->createToken('token')->accessToken);
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
            return response()->json(['status' => 400, 'message' => $e->getMessage()]);
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
    public function apiVerifyToken(Request $request)
    {
        $request->validate([
            'api_token' => 'required',
        ]);

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
    public function saveToken(Request $request)
    {
        try {
            $userId = Auth::guard('api')->user()->id; // Assuming 'api' guard is used for Admin model
            Log::info("userIduserId");
            Log::info($userId);
            Log::info("supplierUserId");
            $supplierUserId = Auth::guard('supplier')->user()->id;
            Log::info($supplierUserId);
            $datas = [
                'os' => $request->os,
                'fcm_token' => $request->fcmToken,
                'voipToken' => $request->voipToken,
            ];

            // Validate $datas here if needed

            if ($userId) {
                Log::info("auth user id comes");
                Admin::where('id', $userId)->update($datas);
            }

            if ($supplierUserId) {
                Log::info("auth supplier id comes");
                User::where('id', $supplierUserId)->update($datas);
            }

            Log::info("Tokens updated successfully for Admin ID: $userId and Supplier ID: $supplierUserId");

            // Prepare response
            $data['message'] = 'Token Updated Successfully';
            return response()->json(['is_success' => true, 'data' => $data]);

        } catch (\Throwable $e) {
            // Log error
            Log::error("Error updating tokens: " . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 400,
                'details' => 'Failed to update tokens. Please try again later.',
            ]);
        }

        // try {
        //     $datas = [
        //         'os' => $request->os,
        //         'fcm_token' => $request->fcmToken,
        //         'voipToken' => $request->voipToken,
        //     ];
        //     Log::info("supplier log in save token");
        //     Admin::where('id', Auth::guard('api')->user()->id)->update($datas);
        //     User::where('id', Auth::guard('supplier')->supplier()->id)->update($datas);
        //     $data['message'] = 'Token Updated Successfully';
        //     return response()->json(['is_success' => true, 'data' => $data]);
        // } catch (\Throwable $e) {
        //     Log::info("Authcontroller Token Check");
        //     Log::info($e);
        //     return response()->json([
        //         'status' => 400,
        //         'details' => $e->getMessage(),
        //     ]);
        // }
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
}
