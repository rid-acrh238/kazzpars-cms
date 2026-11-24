@extends('layouts.app')

@section('title', $post->title . ' — Kazzpars Portal')

@section('content')
    <div class="retro-banner">
        <span class="banner-star">★</span>
        <span>{{ strtoupper($post->title) }}</span>
        <span class="banner-star">★</span>
    </div>

    <div class="blog-stream">
        <article class="blog-post" id="{{ $post->slug }}">
            <div class="post-header">
                <span class="post-date">
                    <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                    {{ strtoupper($post->published_at?->format('d M Y') ?? 'DRAFT') }}
                </span>
                <span class="post-category">
                    {{ $post->category?->name ?? 'GENERAL' }}
                </span>
            </div>

            <h1 class="post-title">{{ $post->title }}</h1>

            @if ($post->cover_image)
                <figure class="retro-figure">
                    <figcaption class="retro-figure-bar">
                        <i class="fa-solid fa-image" aria-hidden="true"></i>
                        {{ strtoupper($post->title) }}.JPG
                    </figcaption>
                    <img
                        src="{{ asset($post->cover_image) }}"
                        alt="Cover: {{ $post->title }}"
                        class="post-cover post-cover-single"
                        loading="lazy"
                    >
                </figure>
            @endif

            <div class="post-body">
                {!! $post->body !!}
            </div>

            <div class="post-footer">
                <span class="post-tags">
                    #{{ $post->slug }}@if ($post->category) #{{ \Illuminate\Support\Str::slug($post->category->name) }}@endif
                </span>
            </div>
        </article>
    </div>

    <p class="home-link">
        <a href="{{ route('home') }}">&laquo; Kembali ke beranda</a>
    </p>
@endsection
