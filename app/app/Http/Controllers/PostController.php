<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Request $request, Post $post)
    {
        try {
            // যদি ইমেজ File থাকে এবং তুমি চাইলে ডিলেট করতে পারো:
            if ($post->image && \Storage::exists($post->image)) {
                \Storage::delete($post->image);
            }

            $post->delete();

            $message = 'Post deleted successfully.';

            // If AJAX request (fetch expects JSON)
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => $message], 200);
            }

            return redirect()->route('posts.index')->with('success', $message);
        } catch (\Exception $e) {
            //Log::error('Post delete error: '.$e->getMessage());

            $message = 'Failed to delete the post. Try again.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }

            return redirect()->route('posts.index')->with('error', $message);
        }
    }
}
