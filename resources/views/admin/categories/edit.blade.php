@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <div class="admin-page-head">
        <h1>
            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
            EDIT KATEGORI
        </h1>
        <p>Kategori: <strong>{{ $category->name }}</strong></p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.categories.update', $category) }}"
        class="admin-form admin-form-narrow"
    >
        @csrf
        @method('PUT')

        <label class="admin-field">
            NAMA KATEGORI <span class="req">*</span>
            <input
                type="text"
                name="name"
                value="{{ old('name', $category->name) }}"
                required
                maxlength="100"
            >
            @error('name')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </label>

        <div class="admin-form-actions">
            <a href="{{ route('admin.categories.index') }}" class="admin-btn">
                &laquo; KEMBALI
            </a>
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fa-solid fa-save" aria-hidden="true"></i>
                SIMPAN
            </button>
        </div>
    </form>
@endsection
