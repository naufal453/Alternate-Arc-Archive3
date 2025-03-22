<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    /**
     * Display a listing of the chapters.
     */
    public function index()
    {
        $chapters = Chapter::all();
        return view('chapters.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new chapter.
     */
    public function create()
    {
        return view('chapters.create');
    }

    /**
     * Store a newly created chapter in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        $validated['user_id'] = Auth::user()->id;

        Chapter::create($validated);

        return redirect()->back()->with('success', 'Chapter added successfully.');
    }

    /**
     * Display the specified chapter.
     */
    public function show($id)
    {
        $chapter = Chapter::findOrFail($id);
        return view('chapters.show', compact('chapter'));
    }

    /**
     * Show the form for editing the specified chapter.
     */
    public function edit($id)
    {
        $chapter = Chapter::findOrFail($id);
        return view('chapters.edit', compact('chapter'));
    }

    /**
     * Update the specified chapter in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $chapter = Chapter::findOrFail($id);
        $chapter->update($validated);

        return redirect()->back()->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified chapter from storage.
     */
    public function destroy($id)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->delete();

        return redirect()->back()->with('success', 'Chapter deleted successfully.');
    }
}
