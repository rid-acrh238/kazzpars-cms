@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="admin-page-head">
        <h1>
            <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
            DASHBOARD
        </h1>
        <p>Selamat datang kembali, {{ Auth::user()->name }}. ✦</p>
    </div>

    <!-- PROFIL CARD -->
    <section class="admin-panel admin-profile-card">
        <div class="admin-panel-title">
            <i class="fa-solid fa-id-card" aria-hidden="true"></i>
            PROFILE
        </div>

        <div class="profile-row">
            <img
                src="{{ asset('img/photo.jpeg') }}"
                alt="Foto Kazzpars"
                class="profile-photo"
            >
            <div class="profile-info">
                <strong class="profile-name">{{ Auth::user()->name }}</strong>
                <span class="muted">{{ Auth::user()->email }}</span>
                <span class="badge badge-ok profile-role">ADMIN</span>
                <a href="{{ route('home') }}" class="profile-site" target="_blank" rel="noopener">
                    <i class="fa-solid fa-up-right-from-square" aria-hidden="true"></i>
                    Lihat situs publik
                </a>
            </div>
        </div>
    </section>

    <!-- STAT CARDS -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $totalPosts }}</span>
                <span class="stat-label">TOTAL POST</span>
            </div>
        </div>

        <div class="stat-card stat-published">
            <div class="stat-icon">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $publishedPosts }}</span>
                <span class="stat-label">TERBIT</span>
            </div>
        </div>

        <div class="stat-card stat-draft">
            <div class="stat-icon">
                <i class="fa-solid fa-pen-ruler" aria-hidden="true"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $draftPosts }}</span>
                <span class="stat-label">DRAFT</span>
            </div>
        </div>

        <div class="stat-card stat-category">
            <div class="stat-icon">
                <i class="fa-solid fa-tags" aria-hidden="true"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $totalCategories }}</span>
                <span class="stat-label">KATEGORI</span>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="admin-actions">
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            TULIS POST BARU
        </a>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn">
            <i class="fa-solid fa-tag" aria-hidden="true"></i>
            KATEGORI BARU
        </a>
    </div>

    <!-- LATEST POSTS -->
    <section class="admin-panel">
        <div class="admin-panel-title">
            <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
            POST TERBARU
        </div>

        @if ($latestPosts->isEmpty())
            <p class="admin-empty">Belum ada post sama sekali.</p>
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
                    @foreach ($latestPosts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
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
                            <td>
                                <a
                                    href="{{ route('admin.posts.edit', $post) }}"
                                    class="admin-link"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection
