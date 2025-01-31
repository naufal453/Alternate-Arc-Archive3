@extends("layouts.app-master")
<style>
    #search {
        padding-top: 10px
    }
</style>
@section("content")
    <div class="p-5 rounded">
        <h1>{{ $user->username }}</h1>
        <h2>Posts</h2>
        @foreach ($user->posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ $post->description }}</p>
                    @if ($post->image_path)
                        <img src="{{ asset("storage/" . $post->image_path) }}" alt="Post Image" class="img-fluid">
                    @endif
                    <small class="text-body-secondary">
                        Posted {{ \Carbon\Carbon::parse($post->updated_at)->diffForHumans() }}
                    </small>
                    @if (auth()->id() == $post->user_id)
                        <div class="mt-3">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editPostModal{{ $post->id }}">Edit</button>
                            <form action="{{ route("posts.destroy", $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Edit Post Modal -->
            <div class="modal fade" id="editPostModal{{ $post->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="editPostModalLabel{{ $post->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editPostModalLabel{{ $post->id }}">Edit Post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route("posts.update", $post->id) }}" method="POST">
                                @csrf
                                @method("PUT")
                                <!-- Title input -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $post->title }}" required />
                                </div>

                                <!-- Content textarea -->
                                <div class="mb-3">
                                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $post->description }}</textarea>
                                </div>

                                <!-- Submit button -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->description }}</p>
        @if ($post->image_path)
            <img src="{{ asset("assets/storage/" . $post->image_path) }}" alt="Post Image" class="img-fluid">
        @endif
        <small>Posted by {{ $post->user->username }} on {{ $post->created_at->format("M d, Y") }}</small>
    </div>
@endsection
