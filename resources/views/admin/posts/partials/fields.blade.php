@php($editing = isset($post) && $post)

@if ($errors->any())
    <div class="login-error" role="alert">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        Ada field yang belum valid — periksa lagi.
    </div>
@endif

<label class="admin-field">
    JUDUL <span class="req">*</span>
    <input
        type="text"
        name="title"
        value="{{ old('title', $post->title ?? '') }}"
        placeholder="Judul post..."
        required
        maxlength="255"
    >
    @error('title')
        <small class="field-error">{{ $message }}</small>
    @enderror
</label>

<label class="admin-field">
    KATEGORI
    <select name="category_id">
        <option value="">— Tanpa kategori —</option>
        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ (old('category_id', $post->category_id ?? null) == $category->id) ? 'selected' : '' }}
            >
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <small class="field-error">{{ $message }}</small>
    @enderror
</label>

<label class="admin-field">
    EXCERPT <small class="muted">(ringkasan, maks 200 karakter)</small>
    <textarea
        name="excerpt"
        rows="2"
        maxlength="200"
        placeholder="Satu dua kalimat ringkasan..."
    >{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    @error('excerpt')
        <small class="field-error">{{ $message }}</small>
    @enderror
</label>

<!-- COVER IMAGE -->
<div class="admin-field">
    <span class="admin-field-label">
        COVER IMAGE <small class="muted">(opsional · jpeg/png/gif/webp · maks 2 MB)</small>
    </span>

    @if ($editing && $post->cover_image)
        <div class="cover-current">
            <img
                src="{{ asset($post->cover_image) }}"
                alt="Cover {{ $post->title }}"
                class="cover-preview"
            >
            <label class="cover-remove">
                <input
                    type="checkbox"
                    name="remove_cover"
                    value="1"
                    {{ old('remove_cover') ? 'checked' : '' }}
                >
                Hapus cover saat disimpan
            </label>
        </div>
    @endif

    <input
        type="file"
        name="cover"
        accept="image/jpeg,image/png,image/gif,image/webp"
        class="cover-input"
    >

    @error('cover')
        <small class="field-error">{{ $message }}</small>
    @enderror
</div>

<div class="admin-field">
    <label for="post-body">ISI POST (HTML) <span class="req">*</span></label>

    <!-- TOOLBAR (muncul kalau JS aktif) -->
    <div class="editor-toolbar" role="toolbar" aria-label="Formatting post">
        <button type="button" data-wrap-before="&lt;b&gt;" data-wrap-after="&lt;/b&gt;" title="Tebal (Ctrl+B)"><b>B</b></button>
        <button type="button" data-wrap-before="&lt;i&gt;" data-wrap-after="&lt;/i&gt;" title="Miring (Ctrl+I)"><i>I</i></button>
        <button type="button" data-wrap-before="&lt;u&gt;" data-wrap-after="&lt;/u&gt;" title="Garis bawah (Ctrl+U)"><u>U</u></button>
        <button type="button" data-wrap-before="&lt;code&gt;" data-wrap-after="&lt;/code&gt;" title="Kode">{ }</button>

        <span class="toolbar-sep" aria-hidden="true"></span>

        <button type="button" data-insert="&lt;h2&gt;[]&lt;/h2&gt;" title="Judul H2">H2</button>
        <button type="button" data-insert="&lt;p&gt;[]&lt;/p&gt;" title="Paragraf">¶</button>
        <button type="button" data-insert="&lt;ul&gt;&#10;    &lt;li&gt;[]&lt;/li&gt;&#10;&lt;/ul&gt;" title="Daftar">• List</button>
        <button type="button" data-insert="&lt;blockquote&gt;&#10;    []&#10;&lt;/blockquote&gt;" title="Kutipan">❝ Quote</button>

        <span class="toolbar-sep" aria-hidden="true"></span>

        <button type="button" data-wrap-before="&lt;a href=&quot;#&quot;&gt;" data-wrap-after="&lt;/a&gt;" title="Link">🔗 Link</button>
        <button type="button" data-insert="&lt;img src=&quot;/img/[]&quot; alt=&quot;Deskripsi&quot;&gt;" title="Gambar">🖼 Img</button>
    </div>

    <textarea
        id="post-body"
        name="body"
        rows="16"
        placeholder="<p>Tulis isi post di sini...</p>"
        required
    >{{ old('body', $post->body ?? '') }}</textarea>

    @error('body')
        <small class="field-error">{{ $message }}</small>
    @enderror
</div>
