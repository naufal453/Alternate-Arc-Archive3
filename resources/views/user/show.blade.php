@extends('layouts.app-master')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@section('content')
    <div class="p-5 rounded">
        <h1>{{ $user->username }}</h1>
        <h2>Timeline</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($user->posts as $post)
                <div class="col">
                    <div class="card h-100">
                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="Post Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">
                                {{ Str::limit($post->description, 150) }}
                            </p>
                            <button class="btn btn-link p-0" data-bs-toggle="modal"
                                data-bs-target="#viewPostModal{{ $post->id }}">Read More</button>
                            <br>
                            <small class="text-body-secondary">
                                Posted {{ \Carbon\Carbon::parse($post->updated_at)->diffForHumans() }}
                            </small>
                        </div>
                        @if (auth()->id() == $post->user_id)
                            <div class="card-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-success btn-sm">Add Chapter</button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editPostModal{{ $post->id }}">Edit</button>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>

                            </div>
                        @endif
                    </div>
                </div>
                @include('home.post.desc')
                @include('home.post.edit')
            @endforeach
        </div>
    </div>
@endsection
