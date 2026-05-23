<?php
/**
 * Dashboard Page — Footnotes Made Easy
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file included from within class method scope.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$fme_version = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php' )['Version'] ?? '';

// Count footnotes across posts
$fme_open  = preg_quote( $this->current_options['footnotes_open'],  '/' );
$fme_close = preg_quote( $this->current_options['footnotes_close'], '/' );

$fme_posts_with_footnotes = 0;
$fme_pages_with_footnotes = 0;
$fme_total_footnotes      = 0;

$fme_all_content = get_posts( [
    'post_type'      => [ 'post', 'page' ],
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
] );

foreach ( $fme_all_content as $fme_pid ) {
    $fme_content = get_post_field( 'post_content', $fme_pid );
    $fme_count   = preg_match_all( '/' . $fme_open . '.+?' . $fme_close . '/s', $fme_content );
    if ( $fme_count ) {
        $fme_total_footnotes += $fme_count;
        if ( get_post_type( $fme_pid ) === 'page' ) {
            $fme_pages_with_footnotes++;
        } else {
            $fme_posts_with_footnotes++;
        }
    }
}
?>
<div class="wrap fme-wrap">

    <!-- ── Top bar ──────────────────────────────────────────── -->
    <div class="fme-topbar">
        <div class="fme-topbar-brand">
            <span class="fme-topbar-icon" aria-hidden="true">
                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 4h14v2H3zm0 5h9v2H3zm0 5h11v2H3z"/></svg>
            </span>
            <span class="fme-topbar-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span>
            <?php if ( defined( 'FME_PRO_VERSION' ) && class_exists( 'FME_Pro_License' ) && FME_Pro_License::is_active() ) : ?>
            <span class="fme-version-badge fme-version-badge--pro">PRO</span>
            <?php elseif ( $fme_version ) : ?>
            <span class="fme-version-badge">v<?php echo esc_html( $fme_version ); ?></span>
            <?php endif; ?>
        </div>
        <div class="fme-topbar-links">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=footnotes-help' ) ); ?>"><?php esc_html_e( 'Help', 'footnotes-made-easy' ); ?></a>
            <a href="https://wordpress.org/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'footnotes-made-easy' ); ?></a>
        </div>
    </div>

    <!-- ── Two-column layout ────────────────────────────────── -->
    <div class="fme-dashboard">

        <!-- MAIN COLUMN -->
        <div class="fme-dashboard__main">

            <!-- Welcome strip -->
            <div class="fme-welcome">
                <div class="fme-welcome__text">
                    <h1 class="fme-welcome__heading"><?php esc_html_e( 'Welcome to Footnotes Made Easy', 'footnotes-made-easy' ); ?></h1>
                    <p class="fme-welcome__sub"><?php esc_html_e( 'Add clean, accessible footnotes to your posts and pages using simple double-parenthesis syntax — no shortcodes, no blocks needed.', 'footnotes-made-easy' ); ?></p>
                    <div class="fme-welcome__actions">
                        <button type="button" class="fme-welcome__btn-video" id="fme-watch-video-btn">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.4"/><path d="M5.5 4.5l4 2.5-4 2.5V4.5z" fill="currentColor"/></svg>
                            <?php esc_html_e( 'Watch Video', 'footnotes-made-easy' ); ?>
                        </button>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=footnotes-settings' ) ); ?>" class="fme-welcome__btn-settings">
                            <?php esc_html_e( 'Open Settings', 'footnotes-made-easy' ); ?>
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
                <div class="fme-welcome__graphic" aria-hidden="true">
                    <svg viewBox="0 0 180 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Document background -->
                        <rect x="16" y="12" width="148" height="106" rx="10" fill="#EEF2FF"/>
                        <!-- Title line -->
                        <rect x="30" y="28" width="100" height="8" rx="3" fill="#C7D2FE"/>
                        <!-- Body lines -->
                        <rect x="30" y="44" width="118" height="6" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="56" width="90" height="6" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="68" width="110" height="6" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="80" width="70" height="6" rx="2" fill="#C7D2FE"/>
                        <!-- Footnote divider -->
                        <rect x="30" y="96" width="40" height="2" rx="1" fill="#A5B4FC"/>
                        <!-- Footnote lines -->
                        <rect x="30" y="104" width="80" height="5" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="114" width="60" height="5" rx="2" fill="#C7D2FE"/>
                        <!-- Superscript badge -->
                        <circle cx="152" cy="30" r="14" fill="#534AB7"/>
                        <text x="152" y="35" text-anchor="middle" fill="white" font-size="12" font-weight="700" font-family="serif">¹</text>
                    </svg>
                </div>
            </div>

            <!-- Video modal -->
            <div id="fme-video-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999999;align-items:center;justify-content:center;">
                <div style="position:relative;background:#000;border-radius:10px;overflow:hidden;width:min(720px,92vw);box-shadow:0 24px 60px rgba(0,0,0,.5);">
                    <button id="fme-video-close" type="button" style="position:absolute;top:10px;right:10px;z-index:2;background:rgba(0,0,0,.55);border:none;color:#fff;border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px;line-height:1;">✕</button>
                    <div style="position:relative;padding-bottom:56.25%;height:0;">
                        <iframe id="fme-video-iframe" src="" style="position:absolute;inset:0;width:100%;height:100%;border:none;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="fme-stats-card">
                <div class="fme-card-head">
                    <span class="dashicons dashicons-chart-bar"></span>
                    <h3><?php esc_html_e( 'Site overview', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-stats-grid">
                    <div class="fme-stat-item">
                        <div class="fme-stat-number"><?php echo esc_html( number_format_i18n( $fme_total_footnotes ) ); ?></div>
                        <div class="fme-stat-label"><?php esc_html_e( 'Total footnotes', 'footnotes-made-easy' ); ?></div>
                    </div>
                    <div class="fme-stat-item">
                        <div class="fme-stat-number"><?php echo esc_html( number_format_i18n( $fme_posts_with_footnotes ) ); ?></div>
                        <div class="fme-stat-label"><?php esc_html_e( 'Posts', 'footnotes-made-easy' ); ?></div>
                    </div>
                    <div class="fme-stat-item">
                        <div class="fme-stat-number"><?php echo esc_html( number_format_i18n( $fme_pages_with_footnotes ) ); ?></div>
                        <div class="fme-stat-label"><?php esc_html_e( 'Pages', 'footnotes-made-easy' ); ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick-start -->
            <div class="fme-stats-card">
                <div class="fme-card-head">
                    <span class="dashicons dashicons-editor-help"></span>
                    <h3><?php esc_html_e( 'Quick start', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div style="padding: 20px;">
                    <p style="font-size:13px;color:#646970;margin:0 0 12px;line-height:1.6;">
                        <?php esc_html_e( 'Wrap any text in double parentheses anywhere in your post or page content:', 'footnotes-made-easy' ); ?>
                    </p>
                    <div class="fme-code-block" style="background:#f6f7f7;border:1px solid #e2e4e7;border-radius:6px;padding:14px 18px;font-family:'Courier New',monospace;font-size:13px;color:#1d2327;">
                        <?php echo esc_html( 'This is a sentence ' . $this->current_options['footnotes_open'] . 'and this is your footnote' . $this->current_options['footnotes_close'] . '.' ); ?>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:6px;padding:10px 14px;margin:12px 0 0;">
                        <svg style="width:14px;height:14px;fill:#D97706;flex-shrink:0;margin-top:1px;" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <p style="font-size:12px;color:#92400E;margin:0;line-height:1.6;">
                            <strong><?php esc_html_e( 'Important:', 'footnotes-made-easy' ); ?></strong>
                            <?php esc_html_e( 'Make sure you include a space before your opening double parentheses or the footnote will not work!', 'footnotes-made-easy' ); ?>
                        </p>
                    </div>
                    <p style="font-size:12px;color:#8c8f94;margin:10px 0 0;font-style:italic;">
                        <?php /* translators: %s: link to Settings → Advanced page */ printf( esc_html__( 'The opening and closing tags can be changed on the %s page.', 'footnotes-made-easy' ), '<a href="' . esc_url( admin_url( 'admin.php?page=footnotes-settings#advanced' ) ) . '">' . esc_html__( 'Settings → Advanced', 'footnotes-made-easy' ) . '</a>' ); ?>
                    </p>
                </div>
            </div>

        </div><!-- /.fme-dashboard__main -->

        <!-- SIDEBAR -->
        <aside class="fme-dashboard__sidebar">

            <!-- Help & resources -->
            <div class="fme-card">
                <div class="fme-card-head">
                    <span class="dashicons dashicons-sos"></span>
                    <h3><?php esc_html_e( 'Help &amp; resources', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-quicklinks">
                    <a href="https://wordpress.org/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Documentation', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="https://wordpress.org/support/plugin/footnotes-made-easy/" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Support forum', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="https://github.com/lumumbapl/footnotes-made-easy/issues" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Report a bug', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=footnotes-help' ) ); ?>" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Help page', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

            <!-- Review nudge -->
            <div class="fme-review-card">
                <div class="fme-review-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 20 20"><path d="M10 15s-7-4.5-7-9a5 5 0 0 1 7-4.58A5 5 0 0 1 17 6c0 4.5-7 9-7 9z"/></svg>
                </div>
                <h3 class="fme-review-card__heading"><?php esc_html_e( 'Enjoying Footnotes Made Easy?', 'footnotes-made-easy' ); ?></h3>
                <p class="fme-review-card__text"><?php esc_html_e( 'A 5-star review on WordPress.org helps other writers find the plugin. It takes less than a minute!', 'footnotes-made-easy' ); ?></p>
                <div class="fme-review-card__stars" aria-hidden="true">
                    <?php for ( $fme_i = 0; $fme_i < 5; $fme_i++ ) : ?>
                    <svg viewBox="0 0 20 20"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8z"/></svg>
                    <?php endfor; ?>
                </div>
                <a href="https://wordpress.org/support/plugin/footnotes-made-easy/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="fme-review-card__btn">
                    <?php esc_html_e( 'Write a review', 'footnotes-made-easy' ); ?>
                    <svg viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5h8M7 3.5l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

        </aside><!-- /.fme-dashboard__sidebar -->

    </div><!-- /.fme-dashboard -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->
