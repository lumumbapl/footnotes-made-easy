/**
 * Deactivation Survey — Footnotes Made Easy
 * Intercepts the Deactivate link and shows a feedback modal.
 */
( function () {
    'use strict';

    var config   = window.fmeDeactivation || {};
    var endpoint = config.endpoint || '';
    var pluginSlug = config.pluginSlug || '';
    var deactivateUrl = '';

    // ── Build modal HTML ──────────────────────────────────────

    var reasons = [
        { value: 'temporary',       label: 'I only needed it temporarily',          detail: false },
        { value: 'better_plugin',   label: 'I found a better plugin',               detail: 'Which plugin?', placeholder: 'Plugin name...' },
        { value: 'broke_site',      label: 'The plugin broke something on my site', detail: 'What broke?',   placeholder: 'Describe the issue...' },
        { value: 'missing_feature', label: "It's missing a feature I need",         detail: 'What feature?', placeholder: 'Describe the feature...' },
        { value: 'too_difficult',   label: 'Too difficult to use',                  detail: 'What was confusing?', placeholder: 'Let us know...' },
        { value: 'no_longer_needed', label: 'No longer needed',                     detail: false },
        { value: 'other',           label: 'Other',                                 detail: 'Please tell us more', placeholder: 'Your reason...' },
    ];

    function buildModal() {
        var html = '<div class="fme-deact-overlay" id="fme-deact-overlay" role="dialog" aria-modal="true" aria-labelledby="fme-deact-title">';
        html += '<div class="fme-deact-modal">';
        html += '<button type="button" class="fme-deact-modal__close" id="fme-deact-close" aria-label="Cancel">';
        html += '<svg viewBox="0 0 12 12" fill="none" width="12" height="12"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
        html += '</button>';
        html += '<div class="fme-deact-modal__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm0 5v5m0 4h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>';
        html += '<h3 class="fme-deact-modal__title" id="fme-deact-title">Quick question before you go</h3>';
        html += '<p class="fme-deact-modal__desc">Help us improve <strong>Footnotes Made Easy</strong>. Why are you deactivating?</p>';
        html += '<div class="fme-deact-modal__reasons">';

        reasons.forEach( function ( r ) {
            html += '<label class="fme-deact-modal__reason">';
            html += '<input type="radio" name="fme_deact_reason" value="' + r.value + '">';
            html += '<div style="flex:1;">';
            html += '<span>' + r.label + '</span>';
            if ( r.detail ) {
                html += '<textarea class="fme-deact-modal__detail" data-reason="' + r.value + '" placeholder="' + ( r.placeholder || '' ) + '" rows="2"></textarea>';
            }
            html += '</div>';
            html += '</label>';
        } );

        html += '</div>';
        html += '<div class="fme-deact-modal__actions">';
        html += '<button type="button" class="fme-deact-modal__skip" id="fme-deact-skip">Skip &amp; Deactivate</button>';
        html += '<button type="button" class="fme-deact-modal__submit" id="fme-deact-submit" disabled>Submit &amp; Deactivate</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        var div = document.createElement( 'div' );
        div.innerHTML = html;
        document.body.appendChild( div.firstChild );
    }

    // ── Wire up modal events ──────────────────────────────────

    function initModal() {
        var overlay = document.getElementById( 'fme-deact-overlay' );
        var skipBtn = document.getElementById( 'fme-deact-skip' );
        var submitBtn = document.getElementById( 'fme-deact-submit' );
        var reasons = overlay.querySelectorAll( 'input[type="radio"]' );

        // Enable submit when a reason is selected
        reasons.forEach( function ( radio ) {
            radio.addEventListener( 'change', function () {
                submitBtn.disabled = false;

                // Highlight selected, deselect others
                overlay.querySelectorAll( '.fme-deact-modal__reason' ).forEach( function ( lbl ) {
                    lbl.classList.remove( 'fme-deact-modal__reason--selected' );
                } );
                this.closest( '.fme-deact-modal__reason' ).classList.add( 'fme-deact-modal__reason--selected' );

                // Show/hide detail textarea
                overlay.querySelectorAll( '.fme-deact-modal__detail' ).forEach( function ( ta ) {
                    ta.style.display = 'none';
                } );
                var detail = overlay.querySelector( '.fme-deact-modal__detail[data-reason="' + this.value + '"]' );
                if ( detail ) {
                    detail.style.display = 'block';
                    detail.focus();
                }
            } );
        } );

        // Skip — just deactivate
        skipBtn.addEventListener( 'click', function () {
            window.location.href = deactivateUrl;
        } );

        // Submit — send data then deactivate
        submitBtn.addEventListener( 'click', function () {
            var selected = overlay.querySelector( 'input[name="fme_deact_reason"]:checked' );
            if ( ! selected ) { window.location.href = deactivateUrl; return; }

            var reason = selected.value;
            var detailEl = overlay.querySelector( '.fme-deact-modal__detail[data-reason="' + reason + '"]' );
            var reasonText = detailEl ? detailEl.value.trim() : '';

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            var payload = {
                plugin_slug:    pluginSlug,
                event_type:     'deactivation',
                reason:         reason,
                reason_text:    reasonText,
                site_url:       window.location.origin,
                wp_version:     config.wpVersion    || '',
                plugin_version: config.pluginVersion || '',
                php_version:    config.phpVersion   || '',
            };

            // Fire and forget — always proceed with deactivation regardless of response
            if ( endpoint ) {
                fetch( endpoint, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify( payload ),
                } ).finally( function () {
                    window.location.href = deactivateUrl;
                } );
            } else {
                window.location.href = deactivateUrl;
            }
        } );

        // Close button — cancel (no deactivation)
        var closeBtn = document.getElementById( 'fme-deact-close' );
        if ( closeBtn ) {
            closeBtn.addEventListener( 'click', function () {
                overlay.classList.remove( 'fme-modal-open' );
            } );
        }

        // Close on overlay click — cancel (no deactivation)
        overlay.addEventListener( 'click', function ( e ) {
            if ( e.target === overlay ) { overlay.classList.remove( 'fme-modal-open' ); }
        } );

        // Close on Escape
        document.addEventListener( 'keydown', function ( e ) {
            if ( e.key === 'Escape' && overlay.classList.contains( 'fme-modal-open' ) ) {
                overlay.classList.remove( 'fme-modal-open' );
            }
        } );
    }

    // ── Intercept deactivate link ─────────────────────────────

    function interceptDeactivate() {
        // Find deactivate link for our plugin specifically
        var deactivateLink = document.querySelector(
            'tr[data-slug="' + pluginSlug + '"] span.deactivate a, ' +
            'tr[data-plugin="' + config.pluginFile + '"] span.deactivate a'
        );

        if ( ! deactivateLink ) { return; }

        deactivateLink.addEventListener( 'click', function ( e ) {
            e.preventDefault();
            deactivateUrl = this.href;

            var overlay = document.getElementById( 'fme-deact-overlay' );
            if ( overlay ) {
                overlay.classList.add( 'fme-modal-open' );
                // Focus first radio
                var first = overlay.querySelector( 'input[type="radio"]' );
                if ( first ) { first.focus(); }
            }
        } );
    }

    // ── Init ─────────────────────────────────────────────────

    document.addEventListener( 'DOMContentLoaded', function () {
        buildModal();
        initModal();
        interceptDeactivate();
    } );

} )();
