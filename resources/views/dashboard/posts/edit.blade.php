@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <section class="panel article">
        <div class="eyebrow">Dashboard</div>
        <h1>Edit Post</h1>
        <form class="form" action="{{ route('dashboard.posts.update', $post) }}" method="POST">
            @method('PUT')
            @include('dashboard.posts._form', ['submit' => 'Update post'])
        </form>
    </section>
@endsection
