<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guest',
        ]);

        Guest::create([
            'user_id'   => $user->id,
            'full_name' => $request->name,
            'phone'     => $request->phone,
        ]);

        // Trigger email verification
        event(new Registered($user));

        // Keep user logged in so they can see the verification notice page
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $secretKey = config('services.recaptcha.secret_key');
        $captchaResponse = $request->input('g-recaptcha-response');

        if (!empty($secretKey) && !app()->environment('testing') && empty($captchaResponse)) {
            return back()
                ->withErrors([
                    'g-recaptcha-response' => 'Silakan selesaikan verifikasi CAPTCHA.',
                ])
                ->withInput();
        }

        if (!empty($secretKey) && !app()->environment('testing')) {
            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => $secretKey,
                    'response' => $captchaResponse,
                    'remoteip' => $request->ip(),
                ]
            );

            $result = $response->json();

            if (!($result['success'] ?? false)) {
                return back()
                    ->withErrors([
                        'g-recaptcha-response' => 'Silakan selesaikan verifikasi CAPTCHA.',
                    ])
                    ->withInput();
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $loginAs = $request->input('login_as', 'guest');

            // Validasi: login_as=guest hanya untuk role guest
            if ($loginAs === 'guest' && $user->role !== 'guest') {
                Auth::logout();

                return back()->withErrors([
                    'login_as' => 'Invalid customer account',
                ])->withInput($request->only('email', 'remember', 'login_as'));
            }

            // Validasi: login_as=admin (Staff) hanya untuk role admin atau manager
            if ($loginAs === 'admin' && !in_array($user->role, ['admin', 'manager'])) {
                Auth::logout();

                return back()->withErrors([
                    'login_as' => 'You are not authorized as staff',
                ])->withInput($request->only('email', 'remember', 'login_as'));
            }

            if (!$user->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', [], false))
                    ->with('warning', 'Silakan verifikasi email Anda terlebih dahulu.');
            }

            // Redirect berdasarkan role dari database
            $redirectTo = match($user->role) {
                'admin'   => '/admin/dashboard',
                'manager' => '/manager/dashboard',
                default   => '/user/dashboard',
            };

            return redirect()->intended($redirectTo);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }


    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email verifikasi telah dikirim ulang.');
    }
}
