@extends('layouts.app')

@section('title', 'Tulis Post Baru')

@section('content')
    <section class="panel article">
        <div class="eyebrow">Dashboard</div>
        <h1>Tulis Post Baru</h1>
        <form class="form" action="{{ route('dashboard.posts.store') }}" method="POST">
            @include('dashboard.posts._form', ['submit' => 'Simpan post'])
        </form>
    </section>
@endsection
