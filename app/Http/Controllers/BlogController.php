<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index()
    {
        $posts = BlogPost::published()
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(BlogPost $blogPost)
    {
        // Only show published posts
        if ($blogPost->status !== 'published' || !$blogPost->published_at || $blogPost->published_at->isFuture()) {
            abort(404);
        }

        // Get recent posts for sidebar
        $recentPosts = BlogPost::published()
            ->where('id', '!=', $blogPost->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('blog.show', compact('blogPost', 'recentPosts'));
    }
}
