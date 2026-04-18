@extends('layouts.app')

@section('title', 'Blog - Projek Cloud 1')

@section('content')
    <section class="hero">
        <div class="eyebrow">Web pribadi</div>
        <h1>Catatan Cloud, Laravel, dan Deployment</h1>
        <p>
            Web pribadi ini berisi catatan belajar dan praktik membangun aplikasi Laravel dengan Docker,
            MySQL, serta deployment ke Railway.
        </p>
    </section>

    <section class="grid">
        @forelse ($posts as $post)
            <article class="panel post-card">
                <p class="meta">{{ optional($post->published_at)->format('d M Y') }} - {{ $post->reading_time }}</p>
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->excerpt }}</p>
                <a class="button button-secondary" href="{{ route('blog.show', $post) }}">Baca tulisan</a>
            </article>
        @empty
            <article class="panel post-card">
                <h2>Belum ada tulisan</h2>
                <p>Silakan login ke dashboard untuk membuat tulisan pertama.</p>
            </article>
        @endforelse
    </section>

    <div style="margin-top: 22px;">
        {{ $posts->links() }}
    </div>
@endsection
