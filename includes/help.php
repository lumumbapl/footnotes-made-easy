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

$fme_version    = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php', false, false )['Version'] ?? '';
$fme_pro_active  = defined( 'FME_PRO_VERSION' ) && class_exists( 'FME_Pro_License' ) && FME_Pro_License::is_active();
$fme_show_upsell = class_exists( 'swas_wp_footnotes' ) ? swas_wp_footnotes::show_upsell() : true;
?>
<div class="wrap fme-wrap">

    <!-- Topbar -->
    <div class="fme-topbar">
        <div class="fme-topbar-brand">
            <span class="fme-topbar-icon" aria-hidden="true">
                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 4h14v2H3zm0 5h9v2H3zm0 5h11v2H3z"/></svg>
            </span>
            <span class="fme-topbar-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span>
            <?php if ( $fme_pro_active ) : ?>
            <span class="fme-version-badge fme-version-badge--pro">PRO</span>
            <?php elseif ( $fme_version ) : ?>
            <span class="fme-version-badge">v<?php echo esc_html( $fme_version ); ?></span>
            <?php endif; ?>
        </div>
        <div class="fme-topbar-links">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=footnotes-help' ) ); ?>"><?php esc_html_e( 'Help', 'footnotes-made-easy' ); ?></a>
            <a href="https://alvise.com/docs/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'footnotes-made-easy' ); ?></a>
        </div>
    </div>

    <div class="fme-settings-grid">

        <!-- MAIN COLUMN -->
        <div class="fme-settings-main">

            <!-- Quick start -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Quick start', 'footnotes-made-easy' ); ?></h3>
                <p class="description"><?php esc_html_e( 'Wrap any text in double parentheses anywhere in your post or page content:', 'footnotes-made-easy' ); ?></p>
                <div class="fme-code-block">This is a sentence with a footnote.<span class="fme-code-marker">((This is the footnote text.))</span></div>
                <p class="description" style="margin-top:10px;"><?php esc_html_e( 'The plugin removes the marker from the text, adds a numbered reference in its place, and appends a footnotes list at the bottom of the post.', 'footnotes-made-easy' ); ?></p>
            </div>

            <!-- How it works -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'How it works', 'footnotes-made-easy' ); ?></h3>
                <div class="fme-help-steps">
                    <div class="fme-help-step">
                        <div class="fme-help-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        </div>
                        <div>
                            <p class="fme-help-step__title"><?php esc_html_e( '1. Write content', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-help-step__desc"><?php esc_html_e( 'Add (( )) markers anywhere in your post or page.', 'footnotes-made-easy' ); ?></p>
                        </div>
                    </div>
                    <div class="fme-help-step__arrow" aria-hidden="true">→</div>
                    <div class="fme-help-step">
                        <div class="fme-help-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="fme-help-step__title"><?php esc_html_e( '2. Plugin processes', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-help-step__desc"><?php esc_html_e( 'Markers are replaced with numbered references on save.', 'footnotes-made-easy' ); ?></p>
                        </div>
                    </div>
                    <div class="fme-help-step__arrow" aria-hidden="true">→</div>
                    <div class="fme-help-step">
                        <div class="fme-help-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="fme-help-step__title"><?php esc_html_e( '3. Output rendered', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-help-step__desc"><?php esc_html_e( 'A footnotes list appears at the bottom of the post.', 'footnotes-made-easy' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings overview -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Settings overview', 'footnotes-made-easy' ); ?></h3>
                <table class="fme-form-table">
                    <?php
                    $fme_tabs = [
                        [ 'label' => __( 'Display', 'footnotes-made-easy' ),    'desc' => __( 'Control how footnote markers look — numbering style, superscript, brackets, and the footnotes list label.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Behaviour', 'footnotes-made-easy' ),  'desc' => __( 'Set how footnotes behave — tooltips, back-links, and whether to combine identical footnotes.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Suppress', 'footnotes-made-easy' ),   'desc' => __( 'Choose where footnotes should not appear — home page, archives, search results, and more.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Advanced', 'footnotes-made-easy' ),   'desc' => __( 'Change opening and closing delimiters, and control plugin priority in the content filter.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Citations', 'footnotes-made-easy' ),  'desc' => __( 'Format footnotes as structured citations in APA, MLA, or Chicago style. Set the default citation style site-wide.', 'footnotes-made-easy' ), 'pro' => true ],
                    ];
                    foreach ( $fme_tabs as $fme_tab ) :
                        // Hide Citations row on subsites where upsell is not shown
                        if ( $fme_tab['pro'] && ! $fme_show_upsell && ! $fme_pro_active ) continue;
                    ?>
                    <tr>
                        <th style="width:140px;">
                            <span class="fme-help-tab-badge">
                                <?php echo esc_html( $fme_tab['label'] ); ?>
                                <?php if ( $fme_tab['pro'] ) : ?>
                                <span class="fme-badge-pro">PRO</span>
                                <?php endif; ?>
                            </span>
                        </th>
                        <td><p class="description" style="margin:0;"><?php echo esc_html( $fme_tab['desc'] ); ?></p></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Pro features -->
            <?php if ( ! $fme_pro_active && $fme_show_upsell ) : ?>
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Pro features', 'footnotes-made-easy' ); ?></h3>
                <div class="fme-help-pro-grid">
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Citations', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'APA, MLA, and Chicago. 10 source types. Auto-fetch metadata from DOI or ISBN.', 'footnotes-made-easy' ); ?></p>
                    </div>
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Library', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'Save footnotes once and reuse them across all posts in seconds.', 'footnotes-made-easy' ); ?></p>
                    </div>
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Gutenberg panel', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'Manage and format all footnotes from the editor sidebar without leaving the post.', 'footnotes-made-easy' ); ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=footnotes-pro' ) ); ?>" class="button button-primary fme-help-pro-cta">
                    <?php esc_html_e( 'Upgrade to Footnotes Made Easy Pro', 'footnotes-made-easy' ); ?> →
                </a>
            </div>
            <?php endif; ?>

        </div><!-- /.fme-settings-main -->

        <!-- SIDEBAR -->
        <aside class="fme-settings-sidebar">

            <!-- Get help card -->
            <div class="fme-help-links-card">
                <div class="fme-help-links-card__head">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <h3><?php esc_html_e( 'Help &amp; resources', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-quicklinks">
                    <a href="https://alvise.com/docs/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
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
                    <a href="#" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Frequently asked questions', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

            <?php include dirname( __FILE__ ) . '/sidebar.php'; ?>

        </aside>

    </div><!-- /.fme-settings-grid -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->
