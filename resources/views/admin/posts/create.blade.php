@extends('layouts.admin')

@section('title', 'Tulis Post')

@section('content')
    <div class="admin-page-head">
        <h1>
            <i class="fa-solid fa-pen-fancy" aria-hidden="true"></i>
            TULIS POST BARU
        </h1>
        <p>Isi form di bawah, lalu simpan sebagai draft atau terbitkan langsung.</p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.posts.store') }}"
        enctype="multipart/form-data"
        class="admin-form"
    >
        @csrf

        @include('admin.posts.partials.fields', ['post' => null])

        <div class="admin-form-actions">
            <a href="{{ route('admin.posts.index') }}" class="admin-btn">
                &laquo; BATAL
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
                TERBITKAN
            </button>
        </div>
    </form>
@endsection
