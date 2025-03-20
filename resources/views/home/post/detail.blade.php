@extends('layouts.app-master')

@section('content')
    <style>
        body {
            background-color: #fff5e4 !important;
            /* Use a specific class to avoid affecting the navbar */
        }
    </style>
    <div class="container">
        {{-- Post Details --}}
        <h1>{{ $post->title }}</h1>

        @if ($post->image_path)
            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="img-fluid"
                style="border-radius:10px;max-height: 700px;">
        @endif
        <br>
        <br>
        <p>{{ $post->description }}</p>
        <br>
        <small>Posted by {{ $post->user->username }} on {{ $post->created_at->format('M d, Y') }}</small>

        <hr>

        {{-- Comments Section --}}

        {{-- Display Existing Comments --}}
        @include('layouts.partials.comments')
    </div>
@endsection
