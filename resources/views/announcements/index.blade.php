@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Announcements</h2>
        @if(auth()->user()->isHeadmaster())
            <a href="{{ route('announcements.create') }}" class="btn btn-primary mb-3">Create Announcement</a>
        @endif
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SL.</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Scheduled At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($announcements as $key => $announcement)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $announcement->title }}</td>
                    <td>{{ $announcement->description }}</td>
                    <td><img src="{{ asset('storage/' . $announcement->image) }}" width="80" height="50"></td>
                    <td>{{ $announcement->scheduled_at }}</td>
                    <td>
                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No announcements found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
