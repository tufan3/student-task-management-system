@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student Delete Requests List</h2>
    </div>
    <table id="students-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $key => $request)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $request->user->code }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->user->email }}</td>
                    <td>{{ $request->user->phone }}</td>
                    <td>
                        <span class="badge bg-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') }}">{{ $request->status }}</span>
                    </td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                            <i class="fa fa-check"></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal for this request -->
                <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $request->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('headmaster.approve', $request->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $request->id }}">Approve Delete Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Student ID:</strong> {{ $request->user->code }}</p>
                                    <p><strong>Name:</strong> {{ $request->user->name }}</p>
                                    <p><strong>Email:</strong> {{ $request->user->email }}</p>
                                    <p><strong>Phone:</strong> {{ $request->user->phone }}</p>
                                    <p>status: <span class="badge bg-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') }}">{{ $request->status }}</span></p>
                                    @if(auth()->user()->isTeacher() && $request->status == 'pending')
                                        <div class="ml-3">
                                            <p class="text-danger">You are not authorized to approve this request.</p>
                                        </div>
                                    @endif
                                </div>
                                @if(auth()->user()->isHeadmaster() && $request->status == 'pending')
                                    <div class="modal-footer">
                                        <button formaction="{{ route('headmaster.approve', $request->id) }}" class="btn btn-success">Approve</button>
                                        <button formaction="{{ route('headmaster.reject', $request->id) }}" class="btn btn-danger">Reject</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
    <!-- DataTable CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#students-table').DataTable();
        });
    </script>
@endpush
