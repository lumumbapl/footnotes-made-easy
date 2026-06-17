<?php
/**
 * Shared sidebar — used on all plugin pages (free and Pro).
 *
 * @package footnotes-made-easy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file; variables are prefixed with fme_.

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$fme_current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

$fme_pro_active = defined( 'FME_PRO_VERSION' )
    && function_exists( 'fmep_fs' )
    && fmep_fs()
    && fmep_fs()->is_paying();

$fme_show_upsell = class_exists( 'swas_wp_footnotes' )
    ? swas_wp_footnotes::show_upsell()
    : true;

// Citations tab URL for the settings tip link
$fme_citations_url = swas_wp_footnotes::get_admin_page_url( 'footnotes-settings#citations' );

// Contextual tips per page — HTML allowed for bold and links
$fme_tips = [
    'footnotes-settings'  => sprintf(
        /* translators: %s = link to Citations tab */
        __( 'You can use the <a href="%s"><strong>Citations</strong></a> tab to format footnotes in APA, MLA, or Chicago style — automatically, site-wide.', 'footnotes-made-easy' ),
        esc_url( $fme_citations_url )
    ),
    'fme-pro-library'     => __( 'You can save frequently cited sources once, then insert them into any post in seconds from the <strong>Footnotes Pro panel</strong> on the Gutenberg editor.', 'footnotes-made-easy' ),
    'footnotes-tools'     => sprintf(
        __( 'You can export your <strong>Footnotes Made Easy</strong> settings and reuse the same settings on another site in just one click.', 'footnotes-made-easy' )
    ),
    'footnotes-made-easy' => __( 'You can manage, reorder, and format all footnotes without leaving the editor — using the <strong>Footnotes Pro panel</strong> in Gutenberg.', 'footnotes-made-easy' ),
    'fme-pro-license'     => __( 'Your license unlocks Citations, Library, and the <strong>Footnotes Pro panel</strong> across your entire site.', 'footnotes-made-easy' ),
    'footnotes-help'      => __( 'Wrap any text in <strong>(( ))</strong> to create a footnote. Use the <strong>Footnotes Pro panel</strong> on the editor to add citations or insert from your library.', 'footnotes-made-easy' ),
];

$fme_tip = $fme_tips[ $fme_current_page ]
    ?? __( 'Wrap any text in <strong>(( ))</strong> to create a footnote anywhere in your posts.', 'footnotes-made-easy' );

$fme_allowed_tip_html = [
    'a'      => [ 'href' => [], 'target' => [], 'rel' => [] ],
    'strong' => [],
];
?>
<aside class="fme-settings-sidebar">

    <?php if ( $fme_pro_active ) : ?>
    <!-- Pro tip card -->
    <div class="fme-tip-card">
        <div class="fme-tip-card__icon" aria-hidden="true">💡</div>
        <h3 class="fme-tip-card__heading"><?php esc_html_e( 'Did you know?', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-tip-card__text"><?php echo wp_kses( $fme_tip, $fme_allowed_tip_html ); ?></p>
    </div>

    <?php elseif ( defined( 'FME_PRO_VERSION' ) && ! $fme_pro_active && is_multisite() && ! is_super_admin() ) : ?>
    <!-- Subsite notice — Pro installed but not licensed, contact network admin -->
    <div class="fme-tip-card" style="border-left-color:#f59e0b;background:#fffbeb;">
        <div class="fme-tip-card__icon" aria-hidden="true">🔒</div>
        <h3 class="fme-tip-card__heading" style="color:#78350f;"><?php esc_html_e( 'Pro not activated', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-tip-card__text" style="color:#78350f;"><?php esc_html_e( 'Footnotes Made Easy Pro is installed but not yet licensed. Please contact your network administrator to activate the license.', 'footnotes-made-easy' ); ?></p>
    </div>

    <?php elseif ( defined( 'FME_PRO_VERSION' ) && ! $fme_pro_active && $fme_show_upsell ) : ?>
    <!-- Pro installed but license inactive — prompt to activate -->
    <div class="fme-upgrade-card">
        <div class="fme-upgrade-card__icon" aria-hidden="true">🔑</div>
        <h3 class="fme-upgrade-card__heading"><?php esc_html_e( 'Activate your license', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-upgrade-card__text"><?php esc_html_e( 'Footnotes Made Easy Pro is installed. Activate your license to unlock Citations, Library, and the Gutenberg sidebar panel.', 'footnotes-made-easy' ); ?></p>
        <a href="#" class="fme-upgrade-card__btn activate-license-trigger footnotes-made-easy">
            <?php esc_html_e( 'Activate license', 'footnotes-made-easy' ); ?>
            <svg viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5h8M7 3.5l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>

    <?php elseif ( $fme_show_upsell ) : ?>
    <!-- Upgrade nudge — Pro not installed -->
    <div class="fme-upgrade-card">
        <div class="fme-upgrade-card__icon" aria-hidden="true">✦</div>
        <h3 class="fme-upgrade-card__heading"><?php esc_html_e( 'Upgrade to Pro', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-upgrade-card__text"><?php esc_html_e( 'Unlock Citations, a reusable Footnote Library, and a Gutenberg sidebar panel — all in one upgrade.', 'footnotes-made-easy' ); ?></p>
        <a href="<?php echo esc_url( swas_wp_footnotes::get_admin_page_url( 'footnotes-pro' ) ); ?>" class="fme-upgrade-card__btn">
            <?php esc_html_e( 'Learn more', 'footnotes-made-easy' ); ?>
            <svg viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5h8M7 3.5l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    <?php endif; ?>

    <!-- Review nudge -->
    <div class="fme-review-card">
        <div class="fme-review-card__icon" aria-hidden="true">
            <svg viewBox="0 0 20 20"><path d="M10 15s-7-4.5-7-9a5 5 0 0 1 7-4.58A5 5 0 0 1 17 6c0 4.5-7 9-7 9z"/></svg>
        </div>
        <h3 class="fme-review-card__heading"><?php esc_html_e( 'Enjoying Footnotes Made Easy?', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-review-card__text"><?php esc_html_e( 'A 5-star review on WordPress.org helps other writers and researchers find the plugin. It takes less than a minute!', 'footnotes-made-easy' ); ?></p>
        <div class="fme-review-card__stars" aria-hidden="true">
            <?php for ( $fme_star_i = 0; $fme_star_i < 5; $fme_star_i++ ) : ?>
            <svg viewBox="0 0 20 20"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8z"/></svg>
            <?php endfor; ?>
        </div>
        <a href="https://wordpress.org/support/plugin/footnotes-made-easy/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="fme-review-card__btn">
            <?php esc_html_e( 'Write a review', 'footnotes-made-easy' ); ?>
            <svg viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5h8M7 3.5l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>

</aside><!-- /.fme-settings-sidebar -->
