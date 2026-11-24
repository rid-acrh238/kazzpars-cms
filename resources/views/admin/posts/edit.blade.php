@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
    <div class="admin-page-head">
        <h1>
            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
            EDIT POST
        </h1>
        <p>
            <strong>{{ $post->title }}</strong> —
            status:
            <span
                class="badge {{ $post->status === 'published' ? 'badge-ok' : 'badge-draft' }}"
            >
                {{ strtoupper($post->status) }}
            </span>
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.posts.update', $post) }}"
        enctype="multipart/form-data"
        class="admin-form"
    >
        @csrf
        @method('PUT')

        @include('admin.posts.partials.fields', ['post' => $post])

        <div class="admin-form-actions">
            <a href="{{ route('admin.posts.index') }}" class="admin-btn">
                &laquo; KEMBALI
            </a>
            <button
                type="submit"
                name="status"
                value="draft"
                class="admin-btn"
            >
                <i class="fa-solid fa-save" aria-hidden="true"></i>
                SIMPAN DRAFT
            </button>
            <button
                type="submit"
                name="status"
                value="published"
                class="admin-btn admin-btn-primary"
            >
                <i class="fa-solid fa-rocket" aria-hidden="true"></i>
                SIMPAN & TERBITKAN
            </button>
        </div>
    </form>

    <!-- HAPUS POST -->
    <section class="admin-panel admin-panel-danger">
        <div class="admin-panel-title">
            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
            ZONA BERBAHAYA
        </div>
        <form
            method="POST"
            action="{{ route('admin.posts.destroy', $post) }}"
            onsubmit="return confirm('Yakin HAPUS post ini permanen? Tidak bisa dibatalkan.')"
            class="inline-form"
        >
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn admin-btn-danger">
                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                HAPUS POST INI
            </button>
        </form>
    </section>
@endsection
