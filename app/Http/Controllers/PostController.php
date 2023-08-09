<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('comments')->get();

        return view('classrooms.show', compact('posts'));
    }

    public function store(Request $request , Classroom $classroom)
    {
        $request->validate([
            'content' => 'required|string|max:1024'
        ]);

        $post = new Post([
            'classroom_id' => $classroom->id,
            'user_id' => auth()->user()->id,
            'content' => $request->input('content')
        ]);
    
        $post->save();
    
        return redirect()->back()->with('success', 'Post created successfully.');

    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
        
    }
}
