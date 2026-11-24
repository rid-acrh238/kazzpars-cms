/**
 * KAZZPARS CMS — toolbar editor sederhana.
 * Progressive enhancement: kalau JS mati, textarea tetap jalan normal.
 *
 * Konvensi tombol:
 *   data-wrap-before / data-wrap-after → bungkus teks terpilih
 *   data-insert                        → sisipkan snippet di kursor
 *                                        (penanda "[] = posisi kursor")
 */
(function () {
    'use strict';

    var textarea = document.getElementById('post-body');
    var toolbar = document.querySelector('.editor-toolbar');

    if (!textarea || !toolbar) return;

    /** Bungkus teks terpilih dengan tag (bold, italic, link...). */
    function wrapSelection(before, after) {
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var value = textarea.value;
        var selected = value.slice(start, end);

        textarea.value =
            value.slice(0, start) + before + selected + after + value.slice(end);

        textarea.focus();
        var cursor = start + before.length;
        textarea.setSelectionRange(cursor, cursor + selected.length);
    }

    /** Sisipkan snippet HTML di posisi kursor ("[] ditandai lalu dihapus). */
    function insertAtCursor(snippet) {
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var value = textarea.value;

        var markerIndex = snippet.indexOf('[]');
        var clean = snippet.replace('[]', '');

        textarea.value = value.slice(0, start) + clean + value.slice(end);

        textarea.focus();
        var cursor = markerIndex === -1 ? start + clean.length : start + markerIndex;
        textarea.setSelectionRange(cursor, cursor);
    }

    // ---- Bind semua tombol lewat data-attribute ----
    toolbar.addEventListener('click', function (event) {
        var button = event.target.closest('button');
        if (!button) return;

        if (button.dataset.wrapBefore !== undefined) {
            wrapSelection(button.dataset.wrapBefore, button.dataset.wrapAfter || '');
        } else if (button.dataset.insert !== undefined) {
            insertAtCursor(button.dataset.insert);
        }
    });

    // ---- Shortcut keyboard: Ctrl+B / Ctrl+I / Ctrl+U ----
    textarea.addEventListener('keydown', function (event) {
        if (!event.ctrlKey && !event.metaKey) return;

        var map = {
            b: ['<b>', '</b>'],
            i: ['<i>', '</i>'],
            u: ['<u>', '</u>'],
        };

        var tag = map[event.key.toLowerCase()];
        if (!tag) return;

        event.preventDefault();
        wrapSelection(tag[0], tag[1]);
    });
})();
