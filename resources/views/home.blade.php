@extends('layouts.app')

@section('title', 'Kazzpars Portal — Personal Blog')

@section('content')
    <!-- Home -->
    <section id="home" class="hero">
        <div class="fire-decoration">
            <img src="{{ asset('img/fire2.gif') }}" alt="Animated fire">
        </div>

        <div class="retro-banner">
            <span class="banner-star">★</span>
            <span>WELCOME TO KAZZPARS PORTAL</span>
            <span class="banner-star">★</span>
        </div>

        <div class="marquee-box">
            <div class="marquee-text">
                ✦ KAZZPARS PORTAL ✦
                PERSONAL BLOG OF AN INFORMATICS STUDENT ✦
                KAZZPARS PORTAL ✦
            </div>
        </div>

        <p class="home-link">
            <a href="#blog">Baca post terbaru &raquo;</a>
        </p>
    </section>

    <!-- BLOG STREAM -->
    <div class="blog-stream" id="blog">
        @forelse ($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <article class="blog-post">
                <div class="post-header">
                    <span class="post-date">
                        <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                        —
                    </span>
                    <span class="post-category">INFO</span>
                </div>

                <h2 class="post-title">BELUM ADA POST</h2>

                <div class="post-body">
                    <p>
                        Blog ini masih kosong. Nantikan tulisan
                        pertamanya lewat panel admin. ✦
                    </p>
                </div>
            </article>
        @endforelse
    </div>
@endsection
