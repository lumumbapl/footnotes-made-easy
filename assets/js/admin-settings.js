/**
 * Footnotes Made Easy — Admin JS
 *
 * Handles tab switching (settings page), video modal (dashboard),
 * and notice auto-dismiss. All data is passed via wp_localize_script
 * as fmeSettings — no inline scripts needed.
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
(function () {
    'use strict';

    /* ── Auto-dismiss notices (runs on all plugin pages) ─── */
    document.querySelectorAll( '.fme-notice-autodismiss' ).forEach( function( notice ) {
        function dismissNotice() { notice.classList.add( 'fme-notice-hiding' ); }
        setTimeout( dismissNotice, 5000 );
        var closeBtn = notice.querySelector( '.fme-notice-close' );
        if ( closeBtn ) { closeBtn.addEventListener( 'click', dismissNotice ); }
    } );

    if ( typeof fmeSettings === 'undefined' ) { return; }

    /* ── Tab switching (Settings page) ─────────────────────── */
    var tabNav = document.getElementById('fme-tabs-nav');
    if ( tabNav ) {
        var tabs   = tabNav.querySelectorAll('.fme-inner-tab');
        var panels = document.querySelectorAll('.fme-tab-panel');

        var submitBtn = document.querySelector('.fme-settings-main .button-primary, .fme-settings-main .fme-save-btn');

        function switchTab( id ) {
            var validTabs = ['display', 'behaviour', 'suppress', 'advanced', 'citations'];
            if ( validTabs.indexOf( id ) === -1 ) { id = 'display'; }

            panels.forEach( function (p) { p.style.display = 'none'; } );
            tabs.forEach(   function (t) { t.classList.remove('fme-active'); } );

            var panel = document.getElementById( 'fme-panel-' + id );
            var tab   = tabNav.querySelector( '.fme-inner-tab[data-tab="' + id + '"]' );
            if ( panel ) { panel.style.display = ''; }
            if ( tab )   { tab.classList.add('fme-active'); }

            var inp = document.getElementById('fme-active-tab-input');
            if ( inp ) { inp.value = id; }

            // Disable Save button when on a locked Citations tab
            if ( submitBtn ) {
                var isLockedCitations = id === 'citations' && !! document.querySelector('#fme-panel-citations .fme-fullpage-lock');
                submitBtn.disabled = isLockedCitations;
                submitBtn.style.opacity       = isLockedCitations ? '0.4' : '';
                submitBtn.style.cursor        = isLockedCitations ? 'not-allowed' : '';
                submitBtn.style.pointerEvents = isLockedCitations ? 'none' : '';
                // Toggle sidebar visibility
                document.body.classList.toggle( 'fme-citations-locked', isLockedCitations );
            }

            if ( history.replaceState ) {
                history.replaceState( null, '', '#' + id );
            }
        }

        tabs.forEach( function (tab) {
            tab.addEventListener( 'click', function (e) {
                // Don't intercept external Pro landing page links
                if ( tab.classList.contains('fme-inner-tab--link') ) {
                    return;
                }
                e.preventDefault();
                switchTab( tab.getAttribute('data-tab') );
            } );
        } );

        // Restore active tab: posted value > URL hash > default
        var validTabs = ['display', 'behaviour', 'suppress', 'advanced', 'citations'];
        var posted  = ( fmeSettings.postedTab && validTabs.indexOf( fmeSettings.postedTab ) !== -1 ) ? fmeSettings.postedTab : '';
        var hash    = window.location.hash.replace( '#', '' );
        var initial = posted  ? posted
                    : ( hash && validTabs.indexOf( hash ) !== -1 ) ? hash
                    : 'display';
        switchTab( initial );
    }

    /* ── Video modal (Dashboard page) ──────────────────────── */
    var watchBtn = document.getElementById('fme-watch-video-btn');
    if ( watchBtn ) {
        var modal  = document.getElementById('fme-video-modal');
        var iframe = document.getElementById('fme-video-iframe');
        var closeBtn = document.getElementById('fme-video-close');
        var videoId = 'Bl9p2-lSZMU';

        function openModal() {
            if ( ! modal || ! iframe ) { return; }
            iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            if ( ! modal || ! iframe ) { return; }
            modal.style.display = 'none';
            iframe.src = '';
            document.body.style.overflow = '';
        }

        watchBtn.addEventListener( 'click', openModal );
        if ( closeBtn ) { closeBtn.addEventListener( 'click', closeModal ); }
        if ( modal ) {
            modal.addEventListener( 'click', function (e) {
                if ( e.target === modal ) { closeModal(); }
            } );
        }
        document.addEventListener( 'keydown', function (e) {
            if ( e.key === 'Escape' && modal && modal.style.display === 'flex' ) { closeModal(); }
        } );
    }

    /* ── Reset settings modal (Tools page) ─────────────── */
    var resetTrigger = document.getElementById('fme-reset-trigger');
    var resetModal   = document.getElementById('fme-reset-modal');
    var modalCancel  = document.getElementById('fme-modal-cancel');
    var modalConfirm = document.getElementById('fme-modal-confirm');
    var resetForm    = document.getElementById('fme-reset-form');

    if ( resetTrigger && resetModal ) {
        function openResetModal() {
            resetModal.classList.add('fme-modal-open');
            if ( modalCancel ) { modalCancel.focus(); }
        }
        function closeResetModal() {
            resetModal.classList.remove('fme-modal-open');
        }

        resetTrigger.addEventListener( 'click', openResetModal );
        if ( modalCancel )  { modalCancel.addEventListener(  'click', closeResetModal ); }
        if ( modalConfirm ) { modalConfirm.addEventListener( 'click', function() { resetForm.submit(); } ); }

        // Close on overlay click
        resetModal.addEventListener( 'click', function(e) {
            if ( e.target === resetModal ) { closeResetModal(); }
        } );

        // Close on Escape
        document.addEventListener( 'keydown', function(e) {
            if ( e.key === 'Escape' && resetModal.classList.contains('fme-modal-open') ) {
                closeResetModal();
            }
        } );
    }

    /* ── Import settings modal (Tools page) ─────────────── */
    var importTrigger      = document.getElementById('fme-import-trigger');
    var importModal        = document.getElementById('fme-import-modal');
    var importModalCancel  = document.getElementById('fme-import-modal-cancel');
    var importModalConfirm = document.getElementById('fme-import-modal-confirm');
    var importForm         = document.getElementById('fme-import-form');
    var importFileInput    = document.getElementById('fme_import_file');

    if ( importTrigger && importModal ) {
        function openImportModal() {
            // Require a file to be selected first
            if ( ! importFileInput || ! importFileInput.value ) {
                importFileInput && importFileInput.focus();
                return;
            }
            importModal.classList.add('fme-modal-open');
            if ( importModalCancel ) { importModalCancel.focus(); }
        }
        function closeImportModal() {
            importModal.classList.remove('fme-modal-open');
        }

        importTrigger.addEventListener( 'click', openImportModal );
        if ( importModalCancel )  { importModalCancel.addEventListener(  'click', closeImportModal ); }
        if ( importModalConfirm ) { importModalConfirm.addEventListener( 'click', function() { importForm.submit(); } ); }

        importModal.addEventListener( 'click', function(e) {
            if ( e.target === importModal ) { closeImportModal(); }
        } );

        document.addEventListener( 'keydown', function(e) {
            if ( e.key === 'Escape' && importModal.classList.contains('fme-modal-open') ) {
                closeImportModal();
            }
        } );
    }


    /* ── Feedback modal (Help page) ─────────────────────── */
    var feedbackTrigger = document.getElementById('fme-feedback-trigger');

    if ( feedbackTrigger && typeof fmeFeedback !== 'undefined' ) {

        // Build and append overlay to <body> — same pattern as deactivation survey
        function buildFeedbackModal() {
            var html  = '<div class="fme-feedback-overlay" id="fme-feedback-overlay" role="dialog" aria-modal="true" aria-labelledby="fme-feedback-title">';
                html += '<div class="fme-feedback-modal">';
                html += '<button type="button" class="fme-feedback-modal__close" id="fme-feedback-close" aria-label="Close">';
                html += '<svg viewBox="0 0 12 12" fill="none" width="12" height="12"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
                html += '</button>';
                html += '<div class="fme-feedback-modal__head">';
                html += '<h3 class="fme-feedback-modal__title" id="fme-feedback-title">Send feedback</h3>';
                html += '<p class="fme-feedback-modal__desc">Got a bug to report or an idea to share? We\'d love to hear from you.</p>';
                html += '</div>';
                html += '<div class="fme-feedback-modal__body">';
                html += '<div class="fme-feedback-modal__field">';
                html += '<label class="fme-feedback-modal__label" for="fme-fb-type">Type</label>';
                html += '<select id="fme-fb-type" class="fme-feedback-modal__select">';
                html += '<option value="bug_report">Bug report</option>';
                html += '<option value="feature_request">Feature request</option>';
                html += '<option value="general">General feedback</option>';
                html += '</select>';
                html += '</div>';
                html += '<div class="fme-feedback-modal__field">';
                html += '<label class="fme-feedback-modal__label" for="fme-fb-name">Your name</label>';
                html += '<input type="text" id="fme-fb-name" class="fme-feedback-modal__input" placeholder="Jane Smith" autocomplete="name">';
                html += '</div>';
                html += '<div class="fme-feedback-modal__field">';
                html += '<label class="fme-feedback-modal__label" for="fme-fb-email">Email address</label>';
                html += '<input type="email" id="fme-fb-email" class="fme-feedback-modal__input" placeholder="jane@example.com" autocomplete="email">';
                html += '</div>';
                html += '<div class="fme-feedback-modal__field">';
                html += '<label class="fme-feedback-modal__label" for="fme-fb-message">Message</label>';
                html += '<textarea id="fme-fb-message" class="fme-feedback-modal__textarea" rows="5" placeholder="Describe the issue or idea in as much detail as you can\u2026"></textarea>';
                html += '</div>';
                html += '<p id="fme-fb-status" class="fme-feedback-modal__status" aria-live="polite"></p>';
                html += '</div>';
                html += '<div class="fme-feedback-modal__actions">';
                html += '<button type="button" id="fme-fb-cancel" class="button">Cancel</button>';
                html += '<button type="button" id="fme-fb-submit" class="button button-primary">Send message</button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';

            var div = document.createElement('div');
            div.innerHTML = html;
            document.body.appendChild( div.firstChild );
        }

        function initFeedbackModal() {
            var overlay   = document.getElementById('fme-feedback-overlay');
            var closeBtn  = document.getElementById('fme-feedback-close');
            var cancelBtn = document.getElementById('fme-fb-cancel');
            var submitBtn = document.getElementById('fme-fb-submit');
            var typeEl    = document.getElementById('fme-fb-type');
            var nameEl    = document.getElementById('fme-fb-name');
            var emailEl   = document.getElementById('fme-fb-email');
            var messageEl = document.getElementById('fme-fb-message');
            var statusEl  = document.getElementById('fme-fb-status');

            function openModal( type ) {
                // Reset state
                if ( typeEl )    { typeEl.value    = type || 'bug_report'; }
                if ( nameEl )    { nameEl.value    = ''; }
                if ( emailEl )   { emailEl.value   = ''; }
                if ( messageEl ) { messageEl.value = ''; }
                if ( statusEl )  { statusEl.textContent = ''; statusEl.className = 'fme-feedback-modal__status'; }
                if ( submitBtn ) { submitBtn.disabled = false; submitBtn.textContent = 'Send message'; }

                overlay.classList.add('fme-modal-open');
                document.body.style.overflow = 'hidden';
                if ( nameEl ) { nameEl.focus(); }
            }

            function closeModal() {
                overlay.classList.remove('fme-modal-open');
                document.body.style.overflow = '';
            }

            // Trigger
            feedbackTrigger.addEventListener( 'click', function () {
                openModal( feedbackTrigger.getAttribute('data-type') || 'bug_report' );
            } );

            // Close buttons
            if ( closeBtn )  { closeBtn.addEventListener(  'click', closeModal ); }
            if ( cancelBtn ) { cancelBtn.addEventListener( 'click', closeModal ); }

            // Overlay click
            overlay.addEventListener( 'click', function (e) {
                if ( e.target === overlay ) { closeModal(); }
            } );

            // Escape key
            document.addEventListener( 'keydown', function (e) {
                if ( e.key === 'Escape' && overlay.classList.contains('fme-modal-open') ) { closeModal(); }
            } );

            // Submit
            if ( submitBtn ) {
                submitBtn.addEventListener( 'click', function () {
                    var message = messageEl ? messageEl.value.trim() : '';

                    if ( ! message || message.length < 10 ) {
                        statusEl.textContent = 'Please enter a message (at least 10 characters).';
                        statusEl.className   = 'fme-feedback-modal__status fme-feedback-modal__status--error';
                        if ( messageEl ) { messageEl.focus(); }
                        return;
                    }

                    submitBtn.disabled    = true;
                    submitBtn.textContent = fmeFeedback.i18n.sending;
                    statusEl.textContent  = '';
                    statusEl.className    = 'fme-feedback-modal__status';

                    var type = typeEl  ? typeEl.value           : 'bug_report';
                    var name = nameEl  ? nameEl.value.trim()    : '';
                    var email = emailEl ? emailEl.value.trim()  : '';
                    var subjectMap = { bug_report: 'Bug Report', feature_request: 'Feature Request', general: 'General Feedback' };

                    // Route each type to its dedicated endpoint
                    var endpointMap = {
                        bug_report:      fmeFeedback.endpointBugReports,
                        feature_request: fmeFeedback.endpointFeatureRequests,
                        general:         fmeFeedback.endpointFeedback,
                    };
                    var endpoint = endpointMap[ type ] || fmeFeedback.endpointFeedback;

                    // Build payload — feature_request uses title+description, others use subject+message
                    var payload;
                    if ( type === 'feature_request' ) {
                        payload = {
                            plugin_slug:    fmeFeedback.pluginSlug,
                            plugin_version: fmeFeedback.pluginVersion,
                            wp_version:     fmeFeedback.wpVersion,
                            site_url:       fmeFeedback.siteUrl,
                            title:          subjectMap[ type ],
                            description:    message,
                        };
                    } else {
                        payload = {
                            plugin_slug:    fmeFeedback.pluginSlug,
                            plugin_version: fmeFeedback.pluginVersion,
                            wp_version:     fmeFeedback.wpVersion,
                            site_url:       fmeFeedback.siteUrl,
                            subject:        subjectMap[ type ],
                            message:        message,
                            sender_name:    name,
                            sender_email:   email,
                            anonymous:      ( ! name && ! email ) ? 1 : 0,
                        };
                    }

                    fetch( endpoint, {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify( payload ),
                    } )
                    .then( function (res) { return res.json(); } )
                    .then( function (data) {
                        if ( data && data.success ) {
                            statusEl.textContent = fmeFeedback.i18n.sent;
                            statusEl.className   = 'fme-feedback-modal__status fme-feedback-modal__status--success';
                            submitBtn.textContent = fmeFeedback.i18n.submit;
                            setTimeout( closeModal, 2500 );
                        } else {
                            throw new Error('failed');
                        }
                    } )
                    .catch( function () {
                        statusEl.textContent  = fmeFeedback.i18n.error;
                        statusEl.className    = 'fme-feedback-modal__status fme-feedback-modal__status--error';
                        submitBtn.disabled    = false;
                        submitBtn.textContent = fmeFeedback.i18n.submit;
                    } );
                } );
            }
        }

        document.addEventListener( 'DOMContentLoaded', function () {
            buildFeedbackModal();
            initFeedbackModal();
        } );
    }

}());
