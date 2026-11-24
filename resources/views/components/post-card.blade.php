@props(['post'])

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

    <h2 class="post-title">
        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
    </h2>

    @if ($post->cover_image)
        <a
            href="{{ route('posts.show', $post->slug) }}"
            class="post-cover-link"
            tabindex="-1"
            aria-hidden="true"
        >
            <img
                src="{{ asset($post->cover_image) }}"
                alt=""
                class="post-cover"
                loading="lazy"
            >
        </a>
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
