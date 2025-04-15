@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h2 class="mb-4">Task List</h2> --}}

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Approved Tasks List</h2>
        {{-- <a href="{{ route('tasks.index') }}" class="btn btn-primary mb-3">Back</a> --}}
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}



    <table class="table table-bordered table-striped">
        <thead >
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Title</th>
                <th>Status</th>
                <th>Assigned By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $index => $task)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $task->student->code }}</td>
                    <td>{{ $task->student->name }}</td>
                    <td>{{ $task->title }}</td>
                    <td>
                        <span class="badge bg-{{ $task->status == 'pending' ? 'warning' : ($task->status == 'approved' ? 'success' : 'danger') }}">{{ $task->status }}</span>

                    </td>
                    <td>{{ $task->teacher->name }}</td>
                    <td>
                        {{-- @if(Auth::user()->role === 'headmaster' && !$task->approved_at) --}}
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal{{ $task->id }}">
                                <i class="fa fa-check"></i>
                            </button>
                        {{-- @endif --}}
                    </td>
                </tr>

                <!-- Modal for this request -->
                <div class="modal fade" id="approveModal{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $task->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('tasks.approve', $task->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $task->id }}">Approve Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Student ID:</strong> {{ $task->student->code }}</p>
                                    <p><strong>Name:</strong> {{ $task->student->name }}</p>
                                    <p><strong>Email:</strong> {{ $task->student->email }}</p>
                                    <p><strong>Phone:</strong> {{ $task->student->phone }}</p>
                                    <p><strong>Assigned By:</strong> {{ $task->teacher->name }}</p>
                                    <hr>
                                    <p><strong>Task Title:</strong> {{ $task->title }}</p>
                                    <p><strong>Task Description:</strong> {{ $task->description }}</p>
                                    <p>status: <span class="badge bg-{{ $task->status == 'pending' ? 'warning' : ($task->status == 'approved' ? 'success' : 'danger') }}">{{ $task->status }}</span></p>
                                </div>
                                @if(auth()->user()->isHeadmaster() && $task->status == 'pending')
                                    <div class="modal-footer">
                                        <button formaction="{{ route('tasks.approve', $task->id) }}" class="btn btn-success">Approve</button>
                                        <button formaction="{{ route('tasks.reject', $task->id) }}" class="btn btn-danger">Reject</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="7" class="text-center">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
