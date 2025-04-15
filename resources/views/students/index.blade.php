<!-- resources/views/students/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Students List</h2>
        @if(auth()->user()->isTeacher())
            <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>
        @endif
    </div>
    <table id="students-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                @if(auth()->user()->isHeadmaster() || auth()->user()->isTeacher())
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $key => $student)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $student->code }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>
                        @if(auth()->user()->isHeadmaster())
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                        @endif
                        @if(auth()->user()->isTeacher())
                            <a href="{{ route('students.destroy', $student->id) }}" onclick="return confirm('Are you sure you want to delete this student?')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                        @endif
                    </td>
                </tr>
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
            $('#teachers-table').DataTable();
        });
    </script>
@endpush
