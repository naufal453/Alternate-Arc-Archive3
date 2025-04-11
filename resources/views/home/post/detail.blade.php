@extends('layouts.app-master')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="main-content">
        <div class="row align-items-start g-4">
            <div class="col-md-4 text-center">
                @if ($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image"
                        class="img-fluid book-cover mb-3">
                @endif
            </div>

            <div class="col-md-8">
                <h1>{{ $post->title }}</h1>
                <h6 class="text-uppercase">by <strong>{{ $post->user->username }}</strong></h6>
                <p>{{ $post->created_at->format('M d, Y') }}</p>
                <div class="mb-2">
                    @foreach ($post->genres as $genre)
                        <span class="badge bg-secondary me-1">{{ $genre->name }}</span>
                    @endforeach
                </div>

                <div class="post-description">{!! $post->description !!}</div>




                <div class="d-flex align-items-center mt-3">
                    @if (auth()->check())
                        @php
                            $liked = $post->likes->contains('user_id', auth()->id());
                        @endphp
                        <button id="like-button" class="btn {{ $liked ? 'btn-danger' : 'btn-primary' }} me-2"
                            data-liked="{{ $liked ? 'true' : 'false' }}" data-post-id="{{ $post->id }}">
                            <i id="like-icon"
                                class="bi {{ $liked ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-up-fill' }}"></i>
                            <span id="like-text">{{ $liked ? 'Dislike' : 'Like' }}</span>
                        </button>
                    @endif
                    <p id="like-count" class="mb-0">{{ $post->likes->count() }}
                        {{ Str::plural('like', $post->likes->count()) }}</p>
                </div>

                <hr>
                <h4>Chapters</h4>
                @if ($chapters->isEmpty())
                    <p>No chapters available for this post.</p>
                @else
                    <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true"
                        class="scrollspy-example overflow-auto" tabindex="0" style="height: 150px;">
                        <ol class="list-group list-group-numbered list-group-flush mb-5">
                            @foreach ($chapters as $chapter)
                                <li class="list-group-item bg-transparent border-0">
                                    <a href="{{ route('chapters.show', $chapter->id) }}">{{ $chapter->title }}</a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                <hr>
                @include('layouts.partials.comments')
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('js/like-button.js') }}"></script>
<script>
    function showConfirmation(chapterId, chapterTitle) {
        const confirmation = confirm(`Are you sure you want to delete the chapter "${chapterTitle}"?`);
        if (confirmation) {
            document.getElementById(`delete-form-${chapterId}`).submit();
        }
    }
</script>
