/**
 * Footnotes Made Easy — Admin Settings UI
 *
 * Handles tab switching, saved-notice dismissal, rating banner,
 * and the video tutorial modal on the plugin settings page.
 *
 * Strings are passed from PHP via wp_localize_script() as fmeSettings.
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */

(function () {

    /* ── Tab switching ──────────────────────────────── */
    var tabs = {
        display:   { title: fmeSettings.tabs.display.title,   sub: fmeSettings.tabs.display.sub   },
        behaviour: { title: fmeSettings.tabs.behaviour.title, sub: fmeSettings.tabs.behaviour.sub },
        suppress:  { title: fmeSettings.tabs.suppress.title,  sub: fmeSettings.tabs.suppress.sub  },
        advanced:  { title: fmeSettings.tabs.advanced.title,  sub: fmeSettings.tabs.advanced.sub  },
        about:     { title: fmeSettings.tabs.about.title,     sub: fmeSettings.tabs.about.sub     }
    };

    var btnEls   = document.querySelectorAll('.fme-tab-btn');
    var titleEl  = document.getElementById('fme-tab-title');
    var subEl    = document.getElementById('fme-tab-sub');
    var tabInput = document.getElementById('fme-active-tab-input');

    function activateTab( id ) {
        if ( ! tabs[id] ) { id = 'display'; }
        btnEls.forEach(function (b) { b.classList.remove('fme-active'); });
        var activeBtn = document.querySelector('.fme-tab-btn[data-tab="' + id + '"]');
        if ( activeBtn ) { activeBtn.classList.add('fme-active'); }
        document.querySelectorAll('.fme-tab-panel').forEach(function (p) { p.classList.remove('fme-active'); });
        var activePanel = document.getElementById('fme-panel-' + id);
        if ( activePanel ) { activePanel.classList.add('fme-active'); }
        titleEl.textContent = tabs[id].title;
        subEl.textContent   = tabs[id].sub;
        if ( tabInput ) { tabInput.value = id; }
        // Persist the active tab in the URL hash so plain reloads restore it
        if ( history.replaceState ) {
            history.replaceState( null, '', '#' + id );
        }
        // Hide the save footer on the About tab — it has nothing to save
        var footer = document.querySelector('.fme-form-footer');
        if ( footer ) { footer.style.display = ( id === 'about' ) ? 'none' : ''; }
    }

    btnEls.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activateTab( btn.getAttribute('data-tab') );
        });
    });

    /* ── Restore the active tab (save OR plain reload) ─ */
    /*
     * Priority order:
     * 1. URL hash  — set on every tab click; survives plain reloads.
     * 2. POST value — set on form save; takes over when the page is
     *    submitted (hash is preserved across the redirect too, but
     *    the POST value is the canonical source after a save).
     * 3. Default  — 'display'.
     */
    (function () {
        var hash    = window.location.hash.replace('#', '');
        var posted  = tabInput ? tabInput.value : '';
        var initial = ( posted && posted !== 'display' ) ? posted
                    : ( hash   && tabs[hash]            ) ? hash
                    : ( posted                          ) ? posted
                    : 'display';
        activateTab( initial );
    }());

    /* ── Auto-dismiss saved notice after 4 s ───────── */
    var notice = document.getElementById('fme-notice-saved');
    if (notice) {
        setTimeout(function () {
            notice.classList.add('fme-notice-hiding');
            notice.addEventListener('transitionend', function () {
                notice.style.display = 'none';
            }, { once: true });
        }, 4000);
    }

    /* ── Dismiss rating banner ──────────────────────── */
    var dismissBtn = document.getElementById('fme-dismiss-banner');
    if (dismissBtn) {
        dismissBtn.addEventListener('click', function () {
            var banner = document.getElementById('fme-rating-banner');
            if (banner) { banner.classList.add('fme-dismissed'); }
        });
    }

    /* ── Video modal ────────────────────────────────── */
    var videoModal   = document.getElementById('fme-video-modal');
    var videoIframe  = document.getElementById('fme-video-iframe');
    var videoTitleEl = document.getElementById('fme-video-title');
    var videoClose   = document.getElementById('fme-video-close');

    function openVideo( vid, title ) {
        videoIframe.src          = 'https://www.youtube.com/embed/' + vid + '?autoplay=1';
        videoTitleEl.textContent = title;
        videoModal.style.display = 'flex';
    }

    function closeVideo() {
        videoIframe.src          = '';
        videoModal.style.display = 'none';
    }

    document.querySelectorAll('.fme-about-tutorial').forEach(function (card) {
        card.addEventListener('click', function () {
            openVideo( card.getAttribute('data-video'), card.getAttribute('data-title') );
        });
        // Keyboard accessibility — Enter / Space open the modal
        card.addEventListener('keydown', function (e) {
            if ( e.key === 'Enter' || e.key === ' ' ) {
                e.preventDefault();
                openVideo( card.getAttribute('data-video'), card.getAttribute('data-title') );
            }
        });
    });

    if ( videoClose ) {
        videoClose.addEventListener('click', closeVideo);
    }

    if ( videoModal ) {
        videoModal.addEventListener('click', function (e) {
            if ( e.target === videoModal ) { closeVideo(); }
        });
        document.addEventListener('keydown', function (e) {
            if ( e.key === 'Escape' && videoModal.style.display === 'flex' ) { closeVideo(); }
        });
    }

}());
