@extends('layouts.app-master')

@section('content')
    <style>
        body {
            background-color: #fff5e4 !important;
            /* Use a specific class to avoid affecting the navbar */
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        @if (auth()->check())
            @php
                $liked = $post->likes->contains('user_id', auth()->id());
            @endphp

            <button id="like-button" class="btn {{ $liked ? 'btn-danger' : 'btn-primary' }}"
                data-liked="{{ $liked ? 'true' : 'false' }}" data-post-id="{{ $post->id }}">
                <i class="bi {{ $liked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i>
                <span id="like-text">{{ $liked ? 'Unlike' : 'Like' }}</span>
            </button>
        @endif

        <p id="like-count">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>

        <hr>

        {{-- Chapters Section --}}
        <h2>Chapters</h2>
        @if ($chapters->isEmpty())
            <p>No chapters available for this post.</p>
        @else
            <ul>
                @foreach ($chapters as $chapter)
                    <li>
                        <a href="{{ route('chapters.show', $chapter->id) }}">{{ $chapter->title }}</a>

                        {{-- Check if the authenticated user is the owner of the chapter --}}
                        @if (auth()->id() === $chapter->user_id)
                            {{-- Delete Chapter Button --}}
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteChapterModal{{ $chapter->id }}">Delete</button>
                        @endif
                    </li>


                    {{-- Delete Chapter Modal --}}
                    @if (auth()->id() === $chapter->user_id)
                        <div class="modal fade" id="deleteChapterModal{{ $chapter->id }}" tabindex="-1"
                            aria-labelledby="deleteChapterModalLabel{{ $chapter->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('chapters.destroy', $chapter->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteChapterModalLabel{{ $chapter->id }}">Delete
                                                Chapter</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the chapter "{{ $chapter->title }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </ul>
        @endif

        <hr>

        {{-- Comments Section --}}
        @include('layouts.partials.comments')
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const likeButton = document.getElementById('like-button');
        const likeCount = document.getElementById('like-count');
        const likeText = document.getElementById('like-text');

        if (likeButton) {
            likeButton.addEventListener('click', function() {
                const postId = likeButton.getAttribute('data-post-id');
                const liked = likeButton.getAttribute('data-liked') === 'true';

                fetch(liked ? '/likes' : '/likes', {
                        method: liked ? 'DELETE' : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: JSON.stringify({
                            post_id: postId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button state
                            likeButton.setAttribute('data-liked', liked ? 'false' : 'true');
                            likeButton.classList.toggle('btn-danger', !liked);
                            likeButton.classList.toggle('btn-primary', liked);
                            likeText.textContent = liked ? 'Like' : 'Unlike';

                            // Update like count
                            likeCount.textContent =
                                `${data.likes_count} ${data.likes_count === 1 ? 'like' : 'likes'}`;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
