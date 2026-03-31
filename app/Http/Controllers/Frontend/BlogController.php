<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = Blog::where('status', 1)
            ->with(['category:id,name,slug'])
            ->select(['id', 'title', 'slug', 'image', 'description', 'category_id', 'created_at'])
            ->orderBy('id', 'desc');

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $blogs = $query->paginate(12)->withQueryString();

        // Get all active blog categories
        $categories = BlogCategory::where('status', 1)
            ->get(['id', 'name', 'slug']);

        return Inertia::render('BlogPage', [
            'blogs' => $blogs,
            'categories' => $categories,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Display the specified blog post
     */
    public function show(string $slug)
    {
        $blog = Blog::where('status', 1)
            ->where('slug', $slug)
            ->with(['category:id,name,slug', 'user:id,name'])
            ->select(['id', 'title', 'slug', 'image', 'description', 'category_id', 'user_id', 'seo_title', 'seo_description', 'created_at'])
            ->firstOrFail();

        // Get related blogs from the same category
        $relatedBlogs = Blog::where('status', 1)
            ->where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->select(['id', 'title', 'slug', 'image', 'created_at'])
            ->take(3)
            ->orderBy('id', 'desc')
            ->get();

        // Get Previous Blog
        $prevBlog = Blog::where('status', 1)
            ->where('id', '<', $blog->id)
            ->orderBy('id', 'desc')
            ->select(['id', 'title', 'slug'])
            ->first();

        // Get Next Blog
        $nextBlog = Blog::where('status', 1)
            ->where('id', '>', $blog->id)
            ->orderBy('id', 'asc')
            ->select(['id', 'title', 'slug'])
            ->first();

        return Inertia::render('BlogDetailPage', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'prevBlog' => $prevBlog,
            'nextBlog' => $nextBlog,
        ]);
    }
}
