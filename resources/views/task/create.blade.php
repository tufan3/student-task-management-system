@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-4">Assign New Task</h2>
        <a href="{{ route('tasks.index') }}" class="btn btn-primary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST" class="shadow p-3 mb-5 bg-white rounded">
        @csrf

        <div class="mb-3">
            <label for="student_id" class="form-label">Select Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">Choose student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Task Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Task Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Assign Task</button>
    </form>
</div>
@endsection
