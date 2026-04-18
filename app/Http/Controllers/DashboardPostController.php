<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardPostController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->latest()
            ->paginate(10);

        return view('dashboard.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('dashboard.posts.create', [
            'post' => new BlogPost(['is_published' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        BlogPost::create($data);

        return redirect()
            ->route('dashboard.posts.index')
            ->with('status', 'Post blog berhasil dibuat.');
    }

    public function edit(BlogPost $post): View
    {
        return view('dashboard.posts.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $this->validated($request, $post);
        $data['slug'] = $this->uniqueSlug($data['title'], $post);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published']
            ? ($post->published_at ?? now())
            : null;

        $post->update($data);

        return redirect()
            ->route('dashboard.posts.index')
            ->with('status', 'Post blog berhasil diperbarui.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('dashboard.posts.index')
            ->with('status', 'Post blog berhasil dihapus.');
    }

    private function validated(Request $request, ?BlogPost $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'min:20'],
            'is_published' => ['nullable', Rule::in(['1', 'on', 'true'])],
        ]);
    }

    private function uniqueSlug(string $title, ?BlogPost $post = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (BlogPost::query()
            ->where('slug', $slug)
            ->when($post, fn ($query) => $query->whereKeyNot($post->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
