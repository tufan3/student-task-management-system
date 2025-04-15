<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeleteRequest;
use Illuminate\Support\Facades\Auth;
class HeadmasterController extends Controller
{
    public function deleteRequests()
    {
        $requests = DeleteRequest::with('user');
        if(Auth::user()->role == 'teacher'){
            $requests = $requests->where('requested_by', Auth::user()->id);
        }
        $requests = $requests->get();
        return view('headmaster.delete_requests', compact('requests'));
    }


    public function approve($id)
    {
        $request = DeleteRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        $request->user->delete(); // Soft delete

        return back()->with('success', 'Student deleted successfully');
    }

    public function reject($id)
    {
        $request = DeleteRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        return back()->with('info', 'Delete request rejected.');
    }
}
