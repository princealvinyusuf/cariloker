<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $posts = BlogPost::latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $slug = Str::slug($validated['title']);
        $uniqueSlug = $slug;
        $counter = 1;
        while (BlogPost::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $counter;
            $counter++;
        }

        $post = new BlogPost();
        $post->user_id = Auth::id();
        $post->title = $validated['title'];
        $post->slug = $uniqueSlug;
        $post->excerpt = $validated['excerpt'] ?? null;
        $post->content = $validated['content'];
        $post->status = $validated['status'];
        $post->published_at = $validated['status'] === 'published' 
            ? ($validated['published_at'] ?? now())
            : null;

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $request->file('featured_image')->store('blog', 'public');
        }

        $post->save();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blog)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blog)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        // Update slug if title changed
        if ($blog->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $uniqueSlug = $slug;
            $counter = 1;
            while (BlogPost::where('slug', $uniqueSlug)->where('id', '!=', $blog->id)->exists()) {
                $uniqueSlug = $slug . '-' . $counter;
                $counter++;
            }
            $blog->slug = $uniqueSlug;
        }

        $blog->title = $validated['title'];
        $blog->excerpt = $validated['excerpt'] ?? null;
        $blog->content = $validated['content'];
        $blog->status = $validated['status'];
        $blog->published_at = $validated['status'] === 'published' 
            ? ($validated['published_at'] ?? $blog->published_at ?? now())
            : null;

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $blog->featured_image = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->save();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blog)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Delete featured image if exists
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
