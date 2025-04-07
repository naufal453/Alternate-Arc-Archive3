@extends('layouts.app-master')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@section('content')
    <div class="p-5 rounded">


        <h2>
            @if (auth()->id() === $user->id)
                Dashboard
            @else
                Author: {{ $user->username }}
            @endif
        </h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($user->posts as $post)
                <div class="col">
                    <div class="card h-100">
                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="Post Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('home.post.detail', ['id' => $post->id]) }}" class="text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="card-text">
                                {!! Str::limit($post->description, 150) !!}
                            </p>
                            {{-- <button class="btn btn-link p-0" data-bs-toggle="modal"
                                data-bs-target="#viewPostModal{{ $post->id }}">Read More</button> --}}
                            <br>
                            <small class="text-body-secondary">
                                Posted {{ \Carbon\Carbon::parse($post->updated_at)->diffForHumans() }}
                            </small>
                        </div>
                        @if (auth()->id() == $post->user_id)
                            <div class="card-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addChapterModal{{ $post->id }}">
                                    Add Chapter
                                </button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editPostModal{{ $post->id }}">Edit</button>
                                <!-- Delete Story Button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteStoryModal{{ $post->id }}">Delete</button>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- @include('home.post.desc') --}}
                @include('home.post.edit')
                <!-- Add Chapter Modal -->
                <div class="modal fade" id="addChapterModal{{ $post->id }}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="addChapterModalLabel{{ $post->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('chapters.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addChapterModalLabel{{ $post->id }}">Add Chapter to
                                        "{{ $post->title }}"</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="overflow-y: auto; max-height: calc(100% - 120px);">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Chapter Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            minlength="9" maxlength="60" style="height: 50px; resize: none;">
                                        <!-- Static height -->
                                    </div>
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Chapter Content</label>
                                        <!-- Quill Editor -->
                                        <div id="quill-editor-{{ $post->id }}" style="height: 300px;"></div>
                                        <input type="hidden" id="content-{{ $post->id }}" name="content"
                                            maxlength="250" required>
                                        <!-- Hidden input -->
                                        <div id="content-warning-{{ $post->id }}" class="text-danger mt-2"></div>
                                        <!-- Warning message -->
                                        <div id="content-counter-{{ $post->id }}" class="text-muted mt-2"></div>
                                        <!-- Character counter -->
                                    </div>
                                    <!-- Hidden input to pass the post ID -->
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit Chapter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Delete Story Confirmation Modal -->
                <div class="modal fade" id="deleteStoryModal{{ $post->id }}" tabindex="-1"
                    aria-labelledby="deleteStoryModalLabel{{ $post->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteStoryModalLabel{{ $post->id }}">Delete Story</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete the story "{{ $post->title }}"? This action cannot be
                                    undone.
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
            @endforeach
        </div>
    </div>
@endsection

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const maxContentLength = 9000; // Set the character limit for chapter content

        @foreach ($user->posts as $post)
            // Initialize Quill for each post's Add Chapter Modal
            var quill{{ $post->id }} = new Quill('#quill-editor-{{ $post->id }}', {
                theme: 'snow',
                placeholder: 'Write your chapter content here...',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['link', 'image'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['clean'] // Remove formatting
                    ]
                }
            });

            // Monitor Quill content length
            quill{{ $post->id }}.on('text-change', function(delta, oldDelta, source) {
                var contentLength = quill{{ $post->id }}.getText().trim().length;
                var warningElement = document.querySelector('#content-warning-{{ $post->id }}');
                var counterElement = document.querySelector('#content-counter-{{ $post->id }}');

                // Update the character counter
                counterElement.textContent = `${contentLength}/${maxContentLength}`;

                if (contentLength > maxContentLength) {
                    // Prevent further input if the limit is reached
                    quill{{ $post->id }}.deleteText(maxContentLength, contentLength);
                    warningElement.textContent =
                        `Content is limited to ${maxContentLength} characters.`;
                } else {
                    warningElement.textContent = ''; // Clear warning if within limit
                }
            });

            // Sync Quill content with the hidden input before form submission
            var form{{ $post->id }} = document.querySelector('#addChapterModal{{ $post->id }} form');
            form{{ $post->id }}.addEventListener('submit', function() {
                var contentInput = document.querySelector('#content-{{ $post->id }}');
                contentInput.value = quill{{ $post->id }}.root
                    .innerHTML; // Set Quill content to hidden input
            });
        @endforeach
    });
</script>
