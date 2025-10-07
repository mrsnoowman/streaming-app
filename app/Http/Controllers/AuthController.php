<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('cctv');
        }
        
        return view('auth.login_simple');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Attempt to authenticate user
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Get authenticated user
            $user = Auth::user();
            
            // Log successful login
            \Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            return redirect()->intended(route('cctv'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Log failed login attempt
        \Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password'])
            ->withInput($request->except('password'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout
        \Log::info('User logged out', [
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Create a test user (REMOVED FOR PRODUCTION)
     * This function has been removed for production security
     */
    // public function createTestUser() - REMOVED FOR PRODUCTION

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('auth.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'current_password.required_with' => 'Current password is required to change password',
            'new_password.min' => 'New password must be at least 6 characters',
            'new_password.confirmed' => 'New password confirmation does not match',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
        }

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect'])
                    ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }
            
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        \Log::info('User profile updated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Profile updated successfully!');
    }
}
