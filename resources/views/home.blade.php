@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (auth()->user()->isStudent())
                        @if ($announcement)
                            <div class="card">
                                <div class="card-body">
                                <h5 class="card-title">{{ $announcement->title }}</h5>
                                <p class="card-text">{{ $announcement->description }}</p>
                                <img src="{{ asset('storage/' . $announcement->image) }}" class="img-fluid" alt="Announcement Image" style="width: 100%; height: 200px;">
                            </div>
                        </div>
                        @else
                            <p>No announcements found</p>
                        @endif
                    @else
                        {{ __('You are logged in!') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
