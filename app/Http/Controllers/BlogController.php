<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(6);

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->is_published, 404);

        return view('blog.show', compact('post'));
    }
}
