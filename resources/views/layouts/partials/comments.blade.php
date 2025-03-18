@extends('layouts.app')

@section('content')

<div class="container">
    <h3>Comments</h3>

    {{-- Display Comments --}}
    <div class="comments-list">
        @foreach($comments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <p>{{ $comment->content }}</p>
                    <small>Posted by: {{ $comment->user->name }} on {{ $comment->created_at->format('d M Y, H:i') }}</small>
                    @if(Auth::id() === $comment->user_id)
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Add Comment Form --}}
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <textarea name="content" required></textarea>
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <button type="submit">Submit</button>
    </form>
</div>
@endsection
