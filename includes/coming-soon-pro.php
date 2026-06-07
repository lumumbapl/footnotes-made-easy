<?php
/**
 * Pro Coming Soon page — Footnotes Made Easy
 * Shown in the plugin admin to promote the upcoming Pro version.
 *
 * @package footnotes-made-easy
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file included within class method scope; all variables use fme_ prefix.

defined( 'ABSPATH' ) || exit;

$fme_launch_date    = '2026-07-30';
$fme_launch_ts      = strtotime( $fme_launch_date );
$fme_days_left      = max( 0, (int) ceil( ( $fme_launch_ts - time() ) / DAY_IN_SECONDS ) );
$fme_version        = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php', false, false )['Version'] ?? '';
$fme_current_user   = wp_get_current_user();
$fme_admin_email    = $fme_current_user->user_email ?? '';
$fme_already_signed = (bool) get_user_meta( $fme_current_user->ID, 'fme_pro_waitlist_subscribed', true );
?>
<div class="wrap fme-wrap">

    <!-- ── Top bar ────────────────────────────────────────── -->
    <div class="fme-topbar">
        <div class="fme-topbar-brand">
            <span class="fme-topbar-icon" aria-hidden="true">
                <img src="data:image/png;base64,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" width="16" height="16" alt="" style="width:16px;height:16px;object-fit:contain;" />
            </span>
            <span class="fme-topbar-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span>
            <?php if ( $fme_version ) : ?>
            <span class="fme-version-badge">v<?php echo esc_html( $fme_version ); ?></span>
            <?php endif; ?>
        </div>
        <div class="fme-topbar-links">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=footnotes-help' ) ); ?>"><?php esc_html_e( 'Help', 'footnotes-made-easy' ); ?></a>
            <a href="https://altvisewp.com/docs/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'footnotes-made-easy' ); ?></a>
        </div>
    </div>

    <div class="fme-pro-coming-soon">

        <!-- ── Hero ─────────────────────────────────────── -->
        <div class="fme-cs-hero">
            <div class="fme-cs-hero__badge">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><path d="M6 0l1.5 4.5H12L8.25 7.5 9.75 12 6 9 2.25 12l1.5-4.5L0 4.5h4.5z"/></svg>
                <?php esc_html_e( 'Coming July 30, 2026', 'footnotes-made-easy' ); ?>
            </div>
            <h1 class="fme-cs-hero__title">
                <?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?>
                <span class="fme-cs-hero__pro"><?php esc_html_e( 'Pro', 'footnotes-made-easy' ); ?></span>
            </h1>
            <p class="fme-cs-hero__sub">
                <?php esc_html_e( 'Professional citations, a reusable footnote library, and a Gutenberg sidebar panel — built on top of the free plugin you already use.', 'footnotes-made-easy' ); ?>
            </p>

            <!-- Countdown -->
            <div class="fme-cs-countdown" id="fme-cs-countdown">
                <div class="fme-cs-countdown__unit">
                    <span class="fme-cs-countdown__num" id="fme-cd-days"><?php echo esc_html( $fme_days_left ); ?></span>
                    <span class="fme-cs-countdown__label"><?php esc_html_e( 'Days', 'footnotes-made-easy' ); ?></span>
                </div>
                <span class="fme-cs-countdown__sep">:</span>
                <div class="fme-cs-countdown__unit">
                    <span class="fme-cs-countdown__num" id="fme-cd-hours">00</span>
                    <span class="fme-cs-countdown__label"><?php esc_html_e( 'Hours', 'footnotes-made-easy' ); ?></span>
                </div>
                <span class="fme-cs-countdown__sep">:</span>
                <div class="fme-cs-countdown__unit">
                    <span class="fme-cs-countdown__num" id="fme-cd-mins">00</span>
                    <span class="fme-cs-countdown__label"><?php esc_html_e( 'Minutes', 'footnotes-made-easy' ); ?></span>
                </div>
                <span class="fme-cs-countdown__sep">:</span>
                <div class="fme-cs-countdown__unit">
                    <span class="fme-cs-countdown__num" id="fme-cd-secs">00</span>
                    <span class="fme-cs-countdown__label"><?php esc_html_e( 'Seconds', 'footnotes-made-easy' ); ?></span>
                </div>
            </div>

            <!-- Signup -->
            <div class="fme-cs-signup">
                <?php if ( $fme_already_signed ) : ?>
                <div class="fme-cs-success">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <?php esc_html_e( "You're already on the list! We'll notify you on launch day.", 'footnotes-made-easy' ); ?>
                </div>
                <?php else : ?>
                <p class="fme-cs-signup__label">
                    <?php esc_html_e( 'Get notified on launch day + an exclusive early bird discount:', 'footnotes-made-easy' ); ?>
                </p>
                <div id="fme-cs-form-wrap">
                    <form class="fme-cs-form" id="fme-cs-form">
                        <input type="email"
                               class="fme-cs-form__input"
                               id="fme-cs-email"
                               value="<?php echo esc_attr( $fme_admin_email ); ?>"
                               placeholder="<?php esc_attr_e( 'Enter your email address', 'footnotes-made-easy' ); ?>"
                               required>
                        <button type="submit" class="fme-cs-form__btn">
                            <?php esc_html_e( 'Notify me', 'footnotes-made-easy' ); ?>
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 7h12M7 1l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                    <p class="fme-cs-form__notice"><?php esc_html_e( 'No spam. Unsubscribe any time.', 'footnotes-made-easy' ); ?></p>
                </div>
                <div id="fme-cs-success" class="fme-cs-success" style="display:none;">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <?php esc_html_e( "You're on the list! We'll notify you on launch day.", 'footnotes-made-easy' ); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Features ──────────────────────────────────── -->
        <div class="fme-cs-section">
            <h2 class="fme-cs-section__title"><?php esc_html_e( "What's coming in Pro", 'footnotes-made-easy' ); ?></h2>
            <div class="fme-cs-features">

                <div class="fme-cs-feature">
                    <div class="fme-cs-feature__icon">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M9 12h6M9 16h6M9 8h6M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="fme-cs-feature__title"><?php esc_html_e( 'Academic Citations', 'footnotes-made-easy' ); ?></h3>
                    <p class="fme-cs-feature__desc"><?php esc_html_e( 'APA, MLA, and Chicago style formatting across 10 source types. Auto-fetch metadata from DOI or ISBN in seconds.', 'footnotes-made-easy' ); ?></p>
                </div>

                <div class="fme-cs-feature">
                    <div class="fme-cs-feature__icon">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 10h16M4 14h10M4 18h7M20 14l-3 3-1.5-1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="fme-cs-feature__title"><?php esc_html_e( 'Footnote Library', 'footnotes-made-easy' ); ?></h3>
                    <p class="fme-cs-feature__desc"><?php esc_html_e( 'Save footnotes once and reuse them across any post. Search, filter, and insert from a centralised library in seconds.', 'footnotes-made-easy' ); ?></p>
                </div>

                <div class="fme-cs-feature">
                    <div class="fme-cs-feature__icon">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="18" rx="1" stroke="currentColor" stroke-width="1.6"/><path d="M14 3h7v7h-7zM14 14h7v7h-7z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="fme-cs-feature__title"><?php esc_html_e( 'Gutenberg Sidebar', 'footnotes-made-easy' ); ?></h3>
                    <p class="fme-cs-feature__desc"><?php esc_html_e( 'Manage, edit, and insert footnotes directly from the block editor sidebar — without leaving your post.', 'footnotes-made-easy' ); ?></p>
                </div>

                <div class="fme-cs-feature">
                    <div class="fme-cs-feature__icon">
                        <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="fme-cs-feature__title"><?php esc_html_e( 'DOI & ISBN Auto-fetch', 'footnotes-made-easy' ); ?></h3>
                    <p class="fme-cs-feature__desc"><?php esc_html_e( 'Paste a DOI or ISBN and source metadata fills in automatically — title, author, publisher, year, and more.', 'footnotes-made-easy' ); ?></p>
                </div>

                <div class="fme-cs-feature">
                    <div class="fme-cs-feature__icon">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="fme-cs-feature__title"><?php esc_html_e( '10 Source Types', 'footnotes-made-easy' ); ?></h3>
                    <p class="fme-cs-feature__desc"><?php esc_html_e( 'Books, journals, websites, newspapers, films, theses, and more — every source type formatted correctly for every style.', 'footnotes-made-easy' ); ?></p>
                </div>

                <div class="fme-cs-feature">
                    <div class="fme-cs-feature__icon">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="fme-cs-feature__title"><?php esc_html_e( 'Priority Support', 'footnotes-made-easy' ); ?></h3>
                    <p class="fme-cs-feature__desc"><?php esc_html_e( 'Pro subscribers get direct support from the AltviseWP team — faster response times and dedicated help.', 'footnotes-made-easy' ); ?></p>
                </div>

            </div>
        </div>

        <!-- ── Pricing teaser ─────────────────────────────── -->
        <div class="fme-cs-pricing">
            <h2 class="fme-cs-pricing__title"><?php esc_html_e( 'Simple, transparent pricing', 'footnotes-made-easy' ); ?></h2>
            <p class="fme-cs-pricing__sub"><?php esc_html_e( 'Starting from $39/year. Annual and lifetime plans available.', 'footnotes-made-easy' ); ?></p>
            <div class="fme-cs-pricing__cards">
                <div class="fme-cs-pricing__card">
                    <div class="fme-cs-pricing__plan"><?php esc_html_e( 'Personal', 'footnotes-made-easy' ); ?></div>
                    <div class="fme-cs-pricing__price">$39<span>/year</span></div>
                    <div class="fme-cs-pricing__sites"><?php esc_html_e( '1 site', 'footnotes-made-easy' ); ?></div>
                </div>
                <div class="fme-cs-pricing__card fme-cs-pricing__card--featured">
                    <div class="fme-cs-pricing__badge"><?php esc_html_e( 'Most popular', 'footnotes-made-easy' ); ?></div>
                    <div class="fme-cs-pricing__plan"><?php esc_html_e( 'Professional', 'footnotes-made-easy' ); ?></div>
                    <div class="fme-cs-pricing__price">$79<span>/year</span></div>
                    <div class="fme-cs-pricing__sites"><?php esc_html_e( '3 sites', 'footnotes-made-easy' ); ?></div>
                </div>
            </div>
            <p class="fme-cs-pricing__lifetime"><?php esc_html_e( 'Lifetime plans also available from $99.', 'footnotes-made-easy' ); ?></p>
        </div>

    </div><!-- /.fme-pro-coming-soon -->

    <?php include __DIR__ . '/footer.php'; ?>

</div><!-- /.fme-wrap -->

<script>
( function() {
    var launch = new Date( '2026-07-30T00:00:00' ).getTime();

    function pad( n ) { return String( n ).padStart( 2, '0' ); }

    function tick() {
        var now  = Date.now();
        var diff = Math.max( 0, launch - now );
        var d    = Math.floor( diff / 86400000 );
        var h    = Math.floor( ( diff % 86400000 ) / 3600000 );
        var m    = Math.floor( ( diff % 3600000 )  / 60000 );
        var s    = Math.floor( ( diff % 60000 )    / 1000 );
        document.getElementById( 'fme-cd-days' ).textContent  = pad( d );
        document.getElementById( 'fme-cd-hours' ).textContent = pad( h );
        document.getElementById( 'fme-cd-mins' ).textContent  = pad( m );
        document.getElementById( 'fme-cd-secs' ).textContent  = pad( s );
    }

    tick();
    setInterval( tick, 1000 );

    // Email form — only runs if form is present (not shown when already subscribed)
    var csForm = document.getElementById( 'fme-cs-form' );
    if ( csForm ) {
    csForm.addEventListener( 'submit', function( e ) {
        e.preventDefault();
        var email  = document.getElementById( 'fme-cs-email' ).value.trim();
        var btn    = this.querySelector( 'button[type="submit"]' );
        if ( ! email ) return;

        // Loading state
        btn.disabled     = true;
        btn.innerHTML    = '<span class="fme-cs-btn-spinner"></span> Sending...';
        btn.style.opacity = '0.85';
        btn.style.cursor  = 'not-allowed';

    var mcUrl = 'https://altvisewp.us4.list-manage.com/subscribe/post?u=edd56a2e64d1ab3af251e7353&id=427b81c751&f_id=00d66deaf0';

        fetch( mcUrl, {
            method:  'POST',
            mode:    'no-cors',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    'EMAIL=' + encodeURIComponent( email ) + '&tags=fme-pro-coming-soon',
        } ).then( function() {
            document.getElementById( 'fme-cs-form-wrap' ).style.display = 'none';
            document.getElementById( 'fme-cs-success' ).style.display   = 'flex';
            // Record subscription in user meta so we show "already subscribed" on next visit
            fetch( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'action=fme_record_waitlist&nonce=<?php echo esc_js( wp_create_nonce( 'fme_waitlist_nonce' ) ); ?>',
            } );
        } );
    } );
    } // end if csForm
} )();
</script>
