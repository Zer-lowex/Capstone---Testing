<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = $request->user();
            $usertype = $user->usertype;

            $user->status = 'Online';
            $user->save();

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Login',
                'description' => ($usertype === 'Owner' || $usertype === 'Admin') 
                    ? "{$user->username} ({$usertype}) has logged in." 
                    : "{$user->username} ({$usertype} ID: {$user->id}) has logged in.",
            ]);

            // Generate a personal access token for the user
            $token = $user->createToken('WebLoginToken')->plainTextToken;

            // Store the token in the session (optional, if you want to return it)
            $request->session()->put('api_token', $token);

            if ($user->usertype === 'Admin') {
                return redirect('admin/dashboard');
            } elseif ($user->usertype === 'Staff') {
                return redirect('staff/dashboard');
            } elseif ($user->usertype === 'Cashier') {
                return redirect('cashier/dashboard');
            } elseif ($user->usertype === 'Owner') {
                return redirect('owner/dashboard');
            }

            return redirect()->intended(route('dashboard'));
        } catch (ValidationException $e) {

            ActivityLog::create([
                'user_id' => null,
                'action' => 'Failed Login',
                'description' => "Login failed for {$request->input('username')}",
            ]);

            return redirect()->back()->with('login_error', 'Invalid username or password. Please try again.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $user = $request->user();
        $usertype = $user->usertype;

        $user->status = 'Offline';
        $user->save();          

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Logout',
            'description' => ($usertype === 'Owner' || $usertype === 'Admin') 
                ? "{$user->username} ({$usertype}) has logged out." 
                : "{$user->username} ({$usertype} ID: {$user->id}) has logged out.",
        ]);

        // Revoke the personal access token if it exists
        if ($user->tokens->count()) {
            $user->tokens->each(function ($token) {
                $token->delete(); // Revoke all tokens
            });
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
