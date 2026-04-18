@extends('layouts.app')

@section('title', $post->title.' - Projek Cloud 1')

@section('content')
    <article class="panel article">
        <p class="meta">{{ optional($post->published_at)->format('d M Y') }} - {{ $post->reading_time }}</p>
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->excerpt }}</p>
        <div class="article-body">{{ $post->body }}</div>
    </article>
@endsection
