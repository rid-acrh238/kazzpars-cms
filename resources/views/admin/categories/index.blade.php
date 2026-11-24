@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <div class="admin-page-head admin-page-head-row">
        <h1>
            <i class="fa-solid fa-tags" aria-hidden="true"></i>
            SEMUA KATEGORI
        </h1>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            KATEGORI BARU
        </a>
    </div>

    <section class="admin-panel">
        @if ($categories->isEmpty())
            <p class="admin-empty">Belum ada kategori.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>NAMA</th>
                        <th>SLUG</th>
                        <th>JUMLAH POST</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><small class="muted">{{ $category->slug }}</small></td>
                            <td>{{ $category->posts_count }}</td>
                            <td class="admin-actions-cell">
                                <a
                                    href="{{ route('admin.categories.edit', $category) }}"
                                    class="admin-link"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    class="inline-form"
                                    onsubmit="return confirm('Hapus kategori {{ $category->name }}?')"
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
        @endif
    </section>
@endsection
