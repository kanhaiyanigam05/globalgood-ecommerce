<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('vendor.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('vendor')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('vendor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('vendor.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'legal_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'store_name' => 'required|string|max:255',
        ]);

        $vendor = Vendor::create([
            'legal_name' => $request->legal_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'kyc_status' => 'pending',
        ]);

        VendorProfile::create([
            'vendor_id' => $vendor->id,
            'store_name' => $request->store_name,
            'slug' => Str::slug($request->store_name),
        ]);

        // Trigger email to Super Admin
        Mail::to('leselaf116@aixind.com')->send(new \App\Mail\NewVendorRegistered($vendor));

        Auth::guard('vendor')->login($vendor);

        return redirect()->route('vendor.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
}
