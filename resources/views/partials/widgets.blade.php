<!-- SIDEBAR KANAN: WIDGETS -->
<aside class="retro-widgets">

    <!-- Profile -->
    <section class="widget">
        <div class="widget-title">
            <i class="fa-solid fa-user" aria-hidden="true"></i>
            PROFILE.SYS
        </div>
        <div class="widget-body">
            <img
                src="{{ asset('img/photo.jpeg') }}"
                alt="Foto Kazzpars"
                class="widget-photo"
                loading="lazy"
            >
            <p class="widget-bio">
                Halo! Aku <strong>Kazzpars</strong>, mahasiswa
                Informatika yang suka teknologi, Linux, dan web.
            </p>
            <a href="{{ route('home') }}#about" class="widget-more">
                Baca selengkapnya &raquo;
            </a>
        </div>
    </section>

    <!-- Categories -->
    <section class="widget">
        <div class="widget-title">
            <i class="fa-solid fa-tags" aria-hidden="true"></i>
            CATEGORIES
        </div>
        <div class="widget-body">
            <ul class="widget-list">
                @forelse ($widgetCategories as $category)
                    <li>
                        <a href="{{ route('home') }}#blog">
                            {{ $category->name }}
                        </a>
                        <span>({{ $category->posts_count }})</span>
                    </li>
                @empty
                    <li><span>Belum ada kategori</span></li>
                @endforelse
            </ul>
        </div>
    </section>

    <!-- Archive -->
    <section class="widget">
        <div class="widget-title">
            <i class="fa-solid fa-box-archive" aria-hidden="true"></i>
            ARCHIVE
        </div>
        <div class="widget-body">
            <ul class="widget-list">
                @forelse ($widgetArchive as $month => $monthPosts)
                    <li>
                        <a href="{{ route('home') }}#blog">
                            {{ $month }}
                        </a>
                        <span>({{ $monthPosts->count() }})</span>
                    </li>
                @empty
                    <li><span>Belum ada post terbit</span></li>
                @endforelse
            </ul>
        </div>
    </section>

    <!-- Contact -->
    <section class="widget" id="contact">
        <div class="widget-title">
            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            CONTACT
        </div>
        <div class="widget-body">
            <p>
                Punya ide proyek, ingin berdiskusi, atau sekadar
                menyapa? Kirim pesan kepadaku!
            </p>

            <div class="contact-buttons widget-contact">
                <a
                    href="mailto:kazzpars0@gmail.com"
                    class="contact-button"
                >
                    <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                    EMAIL ME
                </a>

                <a
                    href="https://github.com/rid-acrh238"
                    class="contact-button"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-brands fa-github" aria-hidden="true"></i>
                    MY GITHUB
                </a>
            </div>

            <p class="contact-footer">
                BEST REGARDS FROM KAZZPARS
            </p>
        </div>
    </section>

    <!-- Blogroll -->
    <section class="widget">
        <div class="widget-title">
            <i class="fa-solid fa-link" aria-hidden="true"></i>
            BLOGROLL
        </div>
        <div class="widget-body">
            <ul class="widget-list">
                <li>
                    <a href="{{ asset('nocturne.html') }}">
                        Nocturne — quiet archive
                    </a>
                </li>
                <li>
                    <a
                        href="https://github.com/rid-acrh238"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        My GitHub
                    </a>
                </li>
                <li>
                    <a
                        href="https://kazzpars.atabook.org/"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Guestbook wall
                    </a>
                </li>
            </ul>
        </div>
    </section>

</aside>
