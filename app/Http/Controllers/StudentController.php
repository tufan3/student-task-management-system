<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\StudentWelcomeNotification;
use App\Notifications\StudentCreatedForHeadmaster;
use App\Models\DeleteRequest;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student');
        if(Auth::user()->role == 'teacher'){
            $students = $students->where('student_created_by', Auth::user()->id);
        }
        $students = $students->get();
        return view('students.index', compact('students'));
    }
    public function create()
    {
        return view('students.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|max:11|min:11',
            'class' => 'required',
            'section' => 'required',
            'password' => 'required|min:6',
            // 'student_id' => 'required|unique:users',
        ]);

        $plainPassword = $request->password;

        $student = new User();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->class = $request->class;
        $student->section = $request->section;
        $student->address = $request->address;
        $student->password = Hash::make($plainPassword);
        $student->code = $request->student_id;
        $student->student_created_by = auth()->user()->id;
        $student->role = 'student';
        $student->save();

        // Send welcome email to student
        $student->notify(new StudentWelcomeNotification($plainPassword));

    // Get the headmaster (assuming there's only one)
    $headmaster = User::where('role', 'headmaster')->first();
    $createdBy = User::where('id', $student->student_created_by)->first();
    $create_by_name = $createdBy->name;
    if ($headmaster) {
        $headmaster->notify(new StudentCreatedForHeadmaster($student, $create_by_name));
    }


        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }
    public function edit(User $student)
    {
        return view('students.edit', compact('student'));
    }
    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'required|max:11|min:11',
            'class' => 'required',
            'section' => 'required',
            ]);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->class = $request->class;
        $student->section = $request->section;
        $student->address = $request->address;
        $student->save();
        return redirect()->route('students.index')->with('success', 'Student updated successfully');
    }
    public function destroy(User $student)
    {
        // Make a request instead of deleting directly
        DeleteRequest::create([
            'user_id' => $student->id,
            'requested_by' => auth()->user()->id,
            'status' => 'pending',
        ]);

        return redirect()->route('students.index')->with('info', 'Delete request sent to Headmaster for approval.');
    }


}
