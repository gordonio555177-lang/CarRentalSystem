<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $customer = auth()->user();
        return view('user.profile.edit', compact('customer'));
    }
    
    public function update(Request $request)
    {
        $customer = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $customer->update($validated);
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
                'current_password' => 'required|current_password'
            ]);
            $customer->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->route('user.profile.edit')
            ->with('success', 'Profile updated successfully');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);
        
        $user = auth()->user();
        auth()->logout();
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Account deleted successfully');
    }
}