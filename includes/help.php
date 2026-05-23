<?php
/**
 * Help Page — Footnotes Made Easy
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file included from within class method scope.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$fme_version = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php' )['Version'] ?? '';
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
    <div class="fme-help-grid">

        <!-- MAIN COLUMN -->
        <div class="fme-help-main">

            <!-- Quick start -->
            <div class="fme-help-section">
                <div class="fme-help-section__head">
                    <h3><?php esc_html_e( 'Quick start', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-help-section__body">
                    <div class="fme-faq-item">
                        <p class="fme-faq-a"><?php esc_html_e( 'Wrap your footnote text in double parentheses anywhere in your post or page content:', 'footnotes-made-easy' ); ?></p>
                        <div class="fme-code-block">This is a sentence with a footnote.((This is the footnote text.))</div>
                        <p class="fme-faq-a" style="margin-top:10px;"><?php esc_html_e( 'The footnote will be removed from the text and a numbered reference added in its place. A footnotes list appears at the bottom of the post.', 'footnotes-made-easy' ); ?></p>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="fme-help-section">
                <div class="fme-help-section__head">
                    <h3><?php esc_html_e( 'Frequently asked questions', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-help-section__body">
                    <?php
                    $fme_faqs = [
                        [
                            'q' => __( 'Can I use footnotes in page builders?', 'footnotes-made-easy' ),
                            'a' => __( 'Yes — as long as the page builder passes content through the_content filter, footnotes will be processed. Most classic and block-based builders support this.', 'footnotes-made-easy' ),
                        ],
                        [
                            'q' => __( 'How do I reference the same footnote twice?', 'footnotes-made-easy' ),
                            'a' => __( 'Use the ref: prefix: ((ref:1)) will link back to the first footnote in the post without creating a duplicate entry.', 'footnotes-made-easy' ),
                        ],
                        [
                            'q' => __( 'Can I start numbering from a number other than 1?', 'footnotes-made-easy' ),
                            'a' => __( 'Yes — place <!--startnum=5--> anywhere in your post content and footnote numbering will begin at 5.', 'footnotes-made-easy' ),
                        ],
                        [
                            'q' => __( 'Why are my footnotes not showing on the homepage?', 'footnotes-made-easy' ),
                            'a' => __( 'Check the Suppress tab in Settings — "Home page" suppression is enabled by default to keep archive pages clean.', 'footnotes-made-easy' ),
                        ],
                        [
                            'q' => __( 'How do I change the opening and closing delimiter?', 'footnotes-made-easy' ),
                            'a' => __( 'Go to Settings → Advanced. You can set any characters as delimiters. Note: changing them requires updating all existing posts.', 'footnotes-made-easy' ),
                        ],
                    ];
                    foreach ( $fme_faqs as $fme_faq ) : ?>
                    <div class="fme-faq-item">
                        <p class="fme-faq-q"><?php echo esc_html( $fme_faq['q'] ); ?></p>
                        <p class="fme-faq-a"><?php echo esc_html( $fme_faq['a'] ); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- More content coming soon -->
            <div class="fme-placeholder-card">
                <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <p class="fme-placeholder-card__title"><?php esc_html_e( 'More help content coming soon', 'footnotes-made-easy' ); ?></p>
                <p class="fme-placeholder-card__text"><?php esc_html_e( 'Step-by-step guides, video walkthroughs, and troubleshooting tips will live here. In the meantime, the support forum has you covered.', 'footnotes-made-easy' ); ?></p>
            </div>

        </div><!-- /.fme-help-main -->

        <!-- SIDEBAR -->
                <?php include dirname( __FILE__ ) . '/sidebar.php'; ?><!-- /.fme-help-sidebar -->

    </div><!-- /.fme-help-grid -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->
