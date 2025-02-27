<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Sorting logic
        $sort = $request->get('sort', 'latest'); // Default is 'latest'

        if ($sort == 'oldest') {
            $posts = Post::orderBy('created_at', 'asc')->get();
        } else {
            $posts = Post::orderBy('created_at', 'desc')->get();
        }

        return view('home.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('home.index'); // This is redundant since the modal is part of the index view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048', // Validate the image
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assets/storage', 'public');
        }

        // Create a new post
        $post = Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'id' => \Illuminate\Support\Facades\Auth::id(),
            'image_path' => $imagePath, // Save the image path
        ]);

        return redirect()->route('home.index')->with('flash_message', 'Post Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('post.detail', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Authorization check
        if ($post->user_id != \Illuminate\Support\Facades\Auth::user()->id) {
            return redirect()->route('user.show', ['id' => \Illuminate\Support\Facades\Auth::user()->id])->with('error', 'Unauthorized action.');
        }

        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->route('user.show', ['id' => $post->user_id])->with('flash_message', 'Post Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Authorization check
        if ($post->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return redirect()->route('user.show', ['id' => \Illuminate\Support\Facades\Auth::id()])->with('error', 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('user.show', ['id' => $post->user_id])->with('flash_message', 'Post Deleted!');
    }
}
