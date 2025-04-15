<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('profile.index', ['profile' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|max:11|min:11',
            'address' => 'required',
        ]);

        if(auth()->user()->isTeacher()){
            $request->validate([
                'designation' => 'required',
                'subject' => 'required',
            ]);
        }

        if(auth()->user()->isStudent()){
            $request->validate([
                'class' => 'required',
                'section' => 'required',
            ]);
        }

        $profile = auth()->user();
        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->designation = $request->designation;
        $profile->subject = $request->subject;
        $profile->class = $request->class;
        $profile->section = $request->section;
        $profile->address = $request->address;
        $profile->save();
        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function passwordChange()
    {
        return view('profile.change_password', ['profile' => auth()->user()]);
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if(!Hash::check($request->current_password, auth()->user()->password)){
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        $profile = auth()->user();
        $profile->password = Hash::make($request->password);
        $profile->save();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Password updated successfully');
    }
}
