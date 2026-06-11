/**
 * Footnotes Made Easy — Pro "Coming Soon" page
 * Countdown timer and Mailchimp waitlist signup.
 *
 * Expects a localized `fmeComingSoon` object with:
 *   launchDate  — ISO date string for the launch
 *   mailchimp   — Mailchimp subscribe POST URL
 *   ajaxUrl     — admin-ajax.php URL
 *   nonce       — waitlist nonce
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
( function () {
    var config = window.fmeComingSoon || {};

    var launch = new Date( config.launchDate || '2026-07-30T00:00:00' ).getTime();

    function pad( n ) { return String( n ).padStart( 2, '0' ); }

    function tick() {
        var now  = Date.now();
        var diff = Math.max( 0, launch - now );
        var d    = Math.floor( diff / 86400000 );
        var h    = Math.floor( ( diff % 86400000 ) / 3600000 );
        var m    = Math.floor( ( diff % 3600000 )  / 60000 );
        var s    = Math.floor( ( diff % 60000 )    / 1000 );

        var elDays  = document.getElementById( 'fme-cd-days' );
        var elHours = document.getElementById( 'fme-cd-hours' );
        var elMins  = document.getElementById( 'fme-cd-mins' );
        var elSecs  = document.getElementById( 'fme-cd-secs' );

        if ( elDays )  { elDays.textContent  = pad( d ); }
        if ( elHours ) { elHours.textContent = pad( h ); }
        if ( elMins )  { elMins.textContent  = pad( m ); }
        if ( elSecs )  { elSecs.textContent  = pad( s ); }
    }

    // Only run the countdown if the elements exist on the page
    if ( document.getElementById( 'fme-cd-days' ) ) {
        tick();
        setInterval( tick, 1000 );
    }

    // Email form — only runs if form is present (not shown when already subscribed)
    var csForm = document.getElementById( 'fme-cs-form' );
    if ( csForm ) {
        csForm.addEventListener( 'submit', function ( e ) {
            e.preventDefault();
            var email = document.getElementById( 'fme-cs-email' ).value.trim();
            var btn   = this.querySelector( 'button[type="submit"]' );
            if ( ! email ) { return; }

            // Loading state
            btn.disabled      = true;
            btn.innerHTML     = '<span class="fme-cs-btn-spinner"></span> Sending...';
            btn.style.opacity = '0.85';
            btn.style.cursor  = 'not-allowed';

            if ( ! config.mailchimp ) { return; }

            fetch( config.mailchimp, {
                method:  'POST',
                mode:    'no-cors',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'EMAIL=' + encodeURIComponent( email ) + '&tags=fme-pro-coming-soon',
            } ).then( function () {
                document.getElementById( 'fme-cs-form-wrap' ).style.display = 'none';
                document.getElementById( 'fme-cs-success' ).style.display   = 'flex';

                // Record subscription in user meta so we show "already subscribed" next visit
                if ( config.ajaxUrl && config.nonce ) {
                    fetch( config.ajaxUrl, {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body:    'action=fme_record_waitlist&nonce=' + encodeURIComponent( config.nonce ),
                    } );
                }
            } );
        } );
    }
} )();
