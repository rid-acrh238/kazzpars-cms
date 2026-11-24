@extends('layouts.admin')

@section('title', 'Posts')

@section('content')
    <div class="admin-page-head admin-page-head-row">
        <h1>
            <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
            SEMUA POST
        </h1>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            TULIS POST BARU
        </a>
    </div>

    <!-- FILTER + SEARCH -->
    <form method="GET" class="admin-filters">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua status</option>
            <option value="published" {{ $currentStatus === 'published' ? 'selected' : '' }}>
                Terbit
            </option>
            <option value="draft" {{ $currentStatus === 'draft' ? 'selected' : '' }}>
                Draft
            </option>
        </select>

        <input
            type="text"
            name="q"
            placeholder="Cari judul atau slug..."
            value="{{ $currentSearch }}"
        >

        <button type="submit" class="admin-btn">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            CARI
        </button>
    </form>

    <section class="admin-panel">
        @if ($posts->isEmpty())
            <p class="admin-empty">
                Tidak ada post yang cocok. Yuk
                <a href="{{ route('admin.posts.create') }}">tulis yang pertama</a>!
            </p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>JUDUL</th>
                        <th>KATEGORI</th>
                        <th>STATUS</th>
                        <th>TANGGAL</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <strong>{{ $post->title }}</strong>
                                <br>
                                <small class="muted">/post/{{ $post->slug }}</small>
                            </td>
                            <td>{{ $post->category?->name ?? '—' }}</td>
                            <td>
                                <span
                                    class="badge {{ $post->status === 'published' ? 'badge-ok' : 'badge-draft' }}"
                                >
                                    {{ strtoupper($post->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $post->published_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="admin-actions-cell">
                                <a
                                    href="{{ route('admin.posts.edit', $post) }}"
                                    class="admin-link"
                                >
                                    Edit
                                </a>

                                @if ($post->status === 'published')
                                    <a
                                        href="{{ route('posts.show', $post->slug) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="admin-link"
                                    >
                                        Lihat
                                    </a>
                                @endif

                                <form
                                    method="POST"
                                    action="{{ route('admin.posts.toggle-status', $post) }}"
                                    class="inline-form"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="admin-link admin-link-btn">
                                        {{ $post->status === 'published' ? 'Unpublish' : 'Terbitkan' }}
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('admin.posts.destroy', $post) }}"
                                    class="inline-form"
                                    onsubmit="return confirm('Yakin hapus post ini? Tidak bisa dibatalkan.')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-link admin-link-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="admin-pagination">
                {{ $posts->links() }}
            </div>
        @endif
    </section>
@endsection
