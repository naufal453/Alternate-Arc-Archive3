@extends("layouts.app-master")
@section("content")
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->description }}</p>
        @if ($post->image_path)
            <img src="{{ asset("storage/" . $post->image_path) }}" alt="Post Image" class="img-fluid">
        @endif
        <small>Posted by {{ $post->user->username }} on {{ $post->created_at->format("M d, Y") }}</small>
    </div>
@endsection
