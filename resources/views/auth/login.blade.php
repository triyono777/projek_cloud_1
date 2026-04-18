@extends('layouts.app')

@section('title', 'Login Dashboard')

@section('content')
    <section class="panel article" style="max-width: 560px; margin: 24px auto;">
        <div class="eyebrow">Dashboard</div>
        <h1>Login</h1>
        <p>Masuk untuk mengelola konten blog.</p>

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form class="form" action="{{ route('login.store') }}" method="POST">
            @csrf
            <label>
                Email
                <input type="email" name="email" value="{{ old('email', 'admin@example.com') }}" required autofocus>
            </label>
            <label>
                Password
                <input type="password" name="password" placeholder="password" required>
            </label>
            <label style="display: flex; grid-template-columns: auto 1fr; align-items: center;">
                <input type="checkbox" name="remember" value="1" style="width: auto;">
                Ingat saya
            </label>
            <button class="button" type="submit">Masuk Dashboard</button>
        </form>

        <p class="meta">Akun demo: <strong>admin@example.com</strong> / <strong>password</strong></p>
    </section>
@endsection
