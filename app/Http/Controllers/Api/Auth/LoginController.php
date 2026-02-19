<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Setting;
use Validator;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required'
            ];

            $customMessages = [
                'email.required' => 'Please enter your Email to Login ',
                'email.email' => ' Please enter correct Email to login',
                'password.required' => ' Please enter correct Password to login'
            ];

            $this->validate($request, $rules, $customMessages);

            $remember_me = !empty($request->remember_me) ? true : false;

            if (Auth::guard('web')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                $user = User::where('email', $request->email)->first();
                return redirect()->route('dashboard');
            } else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect'],
                ]);
            }
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()
            ->route('login')
            ->with('status', 'Session has been logged out!');
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
