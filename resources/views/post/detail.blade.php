@extends("layouts.app-master")
<style>
    #search {
        padding-top: 10px
    }
</style>
@section("content")
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->description }}</p>
        <small>Posted by {{ $post->user->username }} on {{ $post->created_at->format("M d, Y") }}</small>
    </div>
@endsection
