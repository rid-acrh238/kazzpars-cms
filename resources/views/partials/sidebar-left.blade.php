<!-- SIDEBAR KIRI: JAM, MUSIK, DEKORASI -->
<aside class="retro-sidebar">

    <!-- Clock and Calendar -->
    <section id="retro-datetime">
        <div class="datetime-header">
            <i class="fa-regular fa-clock"></i>
            SYSTEM DATE & TIME.EXE
        </div>

        <div class="clock-display">
            <div class="clock-label">CURRENT TIME</div>
            <div id="digital-clock">00:00:00</div>
            <div id="clock-period">WIB (UTC+7)</div>
        </div>

        <div class="calendar-display">
            <div class="calendar-heading">
                <i class="fa-regular fa-calendar-days"></i>
                <span id="calendar-month">MONTH YEAR</span>
            </div>

            <div class="calendar-weekdays">
                <span>MIN</span>
                <span>SEN</span>
                <span>SEL</span>
                <span>RAB</span>
                <span>KAM</span>
                <span>JUM</span>
                <span>SAB</span>
            </div>

            <div id="calendar-days" class="calendar-days"></div>

            <div id="calendar-today" class="calendar-today">
                TODAY: -
            </div>
        </div>
    </section>

    <!-- Music Player -->
    <section id="music-player">
        <div class="player-title">
            <i class="fa-solid fa-compact-disc"></i>
            KAZZPARS PLAYER.EXE
            <span class="player-version">v1.0</span>
        </div>

        <div class="player-body">
            <div class="album-icon" id="album-icon">
                <i class="fa-solid fa-compact-disc"></i>
            </div>

            <div class="player-info">
                <div class="now-playing">NOW PLAYING:</div>
                <div id="track-name">No track selected</div>

                <div class="equalizer" id="equalizer">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <audio id="audio-player" preload="metadata"></audio>

        <div class="player-controls">
            <button id="prev-track" aria-label="Previous track">
                <i class="fa-solid fa-backward-step"></i>
            </button>

            <button id="play-pause" aria-label="Play music">
                <i class="fa-solid fa-play"></i>
            </button>

            <button id="next-track" aria-label="Next track">
                <i class="fa-solid fa-forward-step"></i>
            </button>

            <span id="player-time">00:00 / 00:00</span>
        </div>

        <div class="progress-container">
            <div id="progress-bar"></div>
        </div>

        <label class="volume-control">
            <i class="fa-solid fa-volume-high"></i>
            Volume

            <input
                id="volume-slider"
                type="range"
                min="0"
                max="1"
                step="0.05"
                value="0.7"
            >
        </label>

        <div id="player-status" role="status" aria-live="polite">
            READY TO PLAY
        </div>
    </section>

    <!-- Sidebar Decoration -->
    <div class="sidebar-decoration">
        <p>✦ YOU ARE VISITOR ✦</p>
        <img
            src="{{ asset('img/underconstruction.gif') }}"
            alt="Under construction"
            loading="lazy"
        >
        <p>BEST VIEWED WITH CURIOSITY!</p>
    </div>

</aside>
