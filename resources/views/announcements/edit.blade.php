@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Edit Announcement</h2>
        @if(auth()->user()->isHeadmaster())
            <a href="{{ route('announcements.index') }}" class="btn btn-primary">Back</a>
        @endif
    </div>
    <form action="{{ route('announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required>{{ $announcement->description }}</textarea>
        </div>
        <div class="form-group mt-3">
            <label>Replace Image (optional)</label><br>
            <img src="{{ asset('storage/' . $announcement->image) }}" width="120" height="100" style="margin-bottom: 10px;">
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            <br>
            <img id="imagePreview" src="#" style="display:none; width: 120px;" height="100px"/>
        </div>
        <div class="form-group mt-3">
            <label>Scheduled At</label>
            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($announcement->scheduled_at)) }}">
        </div>
        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.style.display = 'block';
    }
</script>
