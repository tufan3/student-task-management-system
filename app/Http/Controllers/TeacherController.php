<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('teachers.index', compact('teachers'));
    }
    public function create()
    {
        return view('teachers.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|max:11|min:11',
            'subject' => 'required',
            'address' => 'required',
            'password' => 'required|min:6',
            'designation' => 'required',
            // 'teacher_id' => 'required|unique:users',
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->subject = $request->subject;
        $user->designation = $request->designation;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->code = $request->teacher_id;
        $user->role = 'teacher';
        // dd($user);
        $user->save();
        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully');
    }

    public function edit(User $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'required|max:11|min:11',
            'subject' => 'required',
            'designation' => 'required',
        ]);
        $teacher->code = $request->teacher_id;
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone;
        $teacher->subject = $request->subject;
        $teacher->address = $request->address;
        $teacher->designation = $request->designation;
        $teacher->save();
        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function destroy(User $teacher)
    {
        $teacher->delete();
        return redirect()->back()->with('success', 'Teacher deleted successfully');
    }

}
