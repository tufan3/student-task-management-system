@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Create Announcement</h2>
        @if(auth()->user()->isHeadmaster())
            <a href="{{ route('announcements.index') }}" class="btn btn-primary">Back</a>
        @endif
    </div>
    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        {{-- <div class="form-group">
            <label>Image</label>

            <input type="file" name="image" class="form-control" required accept="image/*">
        </div> --}}

        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required accept="image/*" onchange="previewImage(event)">
            <br>
            <img id="imagePreview" src="#" style="display:none; width: 120px;" height="100px"/>
        </div>
        <div class="form-group mt-3">
            <label>Scheduled At (optional)</label>
            <input type="datetime-local" name="scheduled_at" class="form-control">
        </div>
        <button class="btn btn-success mt-3">Submit</button>
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
