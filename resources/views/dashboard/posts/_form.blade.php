@csrf

@if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<label>
    Judul
    <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
</label>

<label>
    Ringkasan
    <textarea name="excerpt" required>{{ old('excerpt', $post->excerpt) }}</textarea>
</label>

<label>
    Isi tulisan
    <textarea name="body" required>{{ old('body', $post->body) }}</textarea>
</label>

<label style="display: flex; grid-template-columns: auto 1fr; align-items: center;">
    <input type="checkbox" name="is_published" value="1" style="width: auto;" @checked(old('is_published', $post->is_published))>
    Publish post
</label>

<button class="button" type="submit">{{ $submit }}</button>
