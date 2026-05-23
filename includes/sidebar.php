<?php
/**
 * Shared sidebar — used on all plugin pages (free and Pro).
 *
 * @package footnotes-made-easy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<aside class="fme-settings-sidebar">

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
        <p class="fme-review-card__text"><?php esc_html_e( 'A 5-star review on WordPress.org helps other writers and researchers find the plugin. It takes less than a minute!', 'footnotes-made-easy' ); ?></p>
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

</aside><!-- /.fme-settings-sidebar -->
