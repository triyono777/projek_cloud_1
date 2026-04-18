@extends('layouts.app')

@section('title', 'Dashboard Blog')

@section('content')
    <section class="hero">
        <div class="eyebrow">Dashboard</div>
        <h1>Manajemen Blog</h1>
        <p>Kelola tulisan web pribadi: buat draft, publish, edit, dan hapus post.</p>
        <a class="button" href="{{ route('dashboard.posts.create') }}">Tulis post baru</a>
    </section>

    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    <section class="panel post-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            <div class="meta">{{ $post->slug }}</div>
                        </td>
                        <td>{{ $post->is_published ? 'Published' : 'Draft' }}</td>
                        <td>{{ optional($post->published_at ?? $post->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="table-actions">
                                @if ($post->is_published)
                                    <a class="button button-secondary" href="{{ route('blog.show', $post) }}">Lihat</a>
                                @endif
                                <a class="button" href="{{ route('dashboard.posts.edit', $post) }}">Edit</a>
                                <form action="{{ route('dashboard.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Hapus post ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button button-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada post.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div style="margin-top: 22px;">
        {{ $posts->links() }}
    </div>
@endsection
