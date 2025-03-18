@extends("layouts.app-master")

@section("content")
<div class="container">
    {{-- Post Details --}}
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->description }}</p>
    @if ($post->image_path)
        <img src="{{ asset("storage/" . $post->image_path) }}" alt="Post Image" class="img-fluid">
    @endif
    <small>Posted by {{ $post->user->username }} on {{ $post->created_at->format("M d, Y") }}</small>

    <hr>

    {{-- Comments Section --}}
    <h3>Comments</h3>

    {{-- Display Existing Comments --}}
    <div class="comments-list">
        @forelse($post->comments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $comment->user->username }}</h5>
                    <p>{{ $comment->content }}</p>
                    <small>{{ $comment->created_at->format('d M Y, H:i') }}</small>
                    @if(Auth::id() === $comment->user_id)
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p>No comments yet. Be the first to comment!</p>
        @endforelse
    </div>

    {{-- Add Comment Form --}}
    <form action="{{ route('comments.store') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="content" class="form-label">Add a Comment</label>
            <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
        </div>
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
