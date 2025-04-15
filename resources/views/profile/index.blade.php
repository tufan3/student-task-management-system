@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('error'))
        <div class="alert alert-danger">
            {{session('error')}}
        </div>
    @endif
    <form action="{{ route('profile.update') }}" method="post" class="shadow p-3 mb-5 bg-white rounded">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name' , $profile->name ?? '')}}" required>
                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email' , $profile->email ?? '')}}" required>
                    @error('email')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{old('phone' , $profile->phone ?? '')}}" required>
                    @error('phone')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            @if(auth()->user()->isTeacher())
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" value="{{old('designation' , $profile->designation ?? '')}}" required>
                        @error('designation')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{old('subject' , $profile->subject ?? '')}}" required>
                        @error('subject')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $profile->address ?? '') }}</textarea>
                    @error('address')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            @if(auth()->user()->isStudent())
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <input type="text" name="class" class="form-control @error('class') is-invalid @enderror" value="{{old('class' , $profile->class ?? '')}}" required>
                        @error('class')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" value="{{old('section' , $profile->section ?? '')}}" required>
                        @error('section')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
