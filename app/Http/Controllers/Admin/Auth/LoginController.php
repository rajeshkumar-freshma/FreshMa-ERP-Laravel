<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {

            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('status', 'You are Logged in as Admin!');
        } else {

            return view('auth.login', [
                'title' => 'Admin Login',
                'loginRoute' => 'admin.login',
                'forgotPasswordRoute' => 'admin.password.request',
            ]);
        }
    }

    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        Log::info("Log In Form Entered");
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = ['email' => 'required|max:255', 'password' => 'required'];

            $customMessages = ['email.required' => 'Please enter your Email to Login ', 'email.email' => ' Please enter correct Email to login', 'password.required' => ' Please enter correct Password to login'];

            $this->validate($request, $rules, $customMessages);

            $remember_me = !empty($request->remember_me) ? true : false;

            if (is_numeric($request->get('email'))) {
                $credentials = ['phone_number' => $request->get('email'), 'password' => $request->get('password')];
            } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            } else {
                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            }

            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $user = Admin::where('email', $request->email)->first();
                // $permissionNames = $data['selectedUser']->getPermissionNames(); // collection of name strings
                // $permissions = $data['selectedUser']->permissions; // collection of permission objects

                // // get all permissions for the user, either directly, or from roles, or from both
                // $permissions = $data['selectedUser']->getDirectPermissions();
                $permissions = $user->getPermissionsViaRoles();
                Log::info("permissions");
                Log::info($permissions);
                // $permissions = $data['selectedUser']->getAllPermissions();

                return response($user);
            } else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect'],
                ]);
            }
        }

        return view('auth.login');
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()
            ->route('admin.login')
            ->with('status', 'Admin has been logged out!');
    }

    /**
     * Validate the form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    private function validator(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:admins|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules, $messages);
    }

    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Login failed, please try again!');
    }

    public function username()
    {
        return 'phone_number';
    }
}
