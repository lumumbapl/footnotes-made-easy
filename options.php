<?php
/**
 * General Options Page — Redesigned UI
 *
 * Modern tabbed settings interface for Footnotes Made Easy.
 * All form field names, nonce fields, and save logic are unchanged.
 *
 * @package footnotes-made-easy
 * @since   1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="fme-settings-wrap">

    <!-- ── Top navigation bar ───────────────────────── -->
    <div class="fme-topbar">
        <span class="fme-brand">
            <span class="fme-brand-icon">
                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4h14v2H3zm0 5h9v2H3zm0 5h11v2H3z"/>
                </svg>
            </span>
            <span class="fme-brand-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span>
        </span>

        <button type="button" class="fme-tab-btn fme-active" data-tab="display">
            <svg viewBox="0 0 20 20"><path d="M2 4h16v2H2zm0 5h10v2H2zm0 5h13v2H2z"/></svg>
            <?php esc_html_e( 'Display', 'footnotes-made-easy' ); ?>
        </button>
        <button type="button" class="fme-tab-btn" data-tab="behaviour">
            <svg viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm1 11H9V9h2v4zm0-6H9V5h2v2z"/></svg>
            <?php esc_html_e( 'Behaviour', 'footnotes-made-easy' ); ?>
        </button>
        <button type="button" class="fme-tab-btn" data-tab="suppress">
            <svg viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
            <?php esc_html_e( 'Suppress', 'footnotes-made-easy' ); ?>
        </button>
        <button type="button" class="fme-tab-btn" data-tab="advanced">
            <svg viewBox="0 0 20 20"><path d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/></svg>
            <?php esc_html_e( 'Advanced', 'footnotes-made-easy' ); ?>
        </button>
        <button type="button" class="fme-tab-btn" data-tab="about">
            <svg viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
            <?php esc_html_e( 'About', 'footnotes-made-easy' ); ?>
        </button>
    </div><!-- /.fme-topbar -->

    <!-- ── Single form wrapping all tabs ────────────── -->
    <form method="post" id="fme-settings-form">

        <?php wp_nonce_field( 'footnotes-nonce', 'footnotes_nonce' ); ?>
        <input type="hidden" name="save_footnotes_made_easy_options" value="1">
        <input type="hidden" name="fme_active_tab" id="fme-active-tab-input" value="<?php echo esc_attr( isset( $_POST['fme_active_tab'] ) ? sanitize_key( $_POST['fme_active_tab'] ) : 'display' ); ?>">

        <div class="fme-content">

            <!-- Rating banner -->
            <div class="fme-rating-banner" id="fme-rating-banner">
                <span class="fme-rating-heart">🩷</span>
                <div class="fme-rating-text">
                    <?php
                    printf(
                        /* translators: %1$s: plugin name, %2$s: star icons */
                        esc_html__( 'Enjoying %1$s? Please leave a %2$s rating to support continued development. Thanks a bunch!', 'footnotes-made-easy' ),
                        '<strong>Footnotes Made Easy</strong>',
                        '<span class="fme-rating-stars">★★★★★</span>'
                    );
                    ?>
                </div>
                <a class="fme-rate-btn"
                   href="https://wordpress.org/support/plugin/footnotes-made-easy/reviews/#new-post"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?php esc_html_e( 'Rate Plugin', 'footnotes-made-easy' ); ?>
                </a>
                <button type="button" class="fme-dismiss-btn" id="fme-dismiss-banner" title="<?php esc_attr_e( 'Dismiss', 'footnotes-made-easy' ); ?>">×</button>
            </div>

            <!-- Saved notice — auto-dismisses after 4s via JS -->
            <?php if ( ! empty( $_POST['save_options'] ) && check_admin_referer( 'footnotes-nonce', 'footnotes_nonce' ) ) : ?>
            <div class="fme-notice-saved" id="fme-notice-saved">
                <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <?php esc_html_e( 'Settings saved successfully.', 'footnotes-made-easy' ); ?>
            </div>
            <?php endif; ?>

            <!-- Page header — title + subtitle only, no top save button -->
            <div class="fme-page-header">
                <div>
                    <h2 class="fme-page-title" id="fme-tab-title"><?php esc_html_e( 'Display settings', 'footnotes-made-easy' ); ?></h2>
                    <p class="fme-page-sub" id="fme-tab-sub"><?php esc_html_e( 'Control how footnote identifiers, links, and back-links appear on the front end.', 'footnotes-made-easy' ); ?></p>
                </div>
            </div>

            <!-- ════════════════════════════════════════
                 TAB: Display
            ════════════════════════════════════════ -->
            <div id="fme-panel-display" class="fme-tab-panel fme-active">

                <!-- Card: Identifier -->
                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Footnote identifier', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Identifier format', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Defines how the link to the footnote is displayed. The outer text will not be linked.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <div class="fme-inline-inputs">
                                <input type="text" name="pre_identifier"
                                       value="<?php echo esc_attr( $this->current_options['pre_identifier'] ); ?>"
                                       placeholder="[" />
                                <input type="text" name="inner_pre_identifier"
                                       value="<?php echo esc_attr( $this->current_options['inner_pre_identifier'] ); ?>"
                                       placeholder="(" />
                                <select name="list_style_type">
                                    <?php foreach ( $this->styles as $key => $val ) : ?>
                                    <option value="<?php echo esc_attr( $key ); ?>"
                                        <?php selected( $this->current_options['list_style_type'], $key ); ?>>
                                        <?php echo esc_html( $val ); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="inner_post_identifier"
                                       value="<?php echo esc_attr( $this->current_options['inner_post_identifier'] ); ?>"
                                       placeholder=")" />
                                <input type="text" name="post_identifier"
                                       value="<?php echo esc_attr( $this->current_options['post_identifier'] ); ?>"
                                       placeholder="]" />
                            </div>
                        </div>
                    </div>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Symbol', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Used only when symbol is chosen as the list style.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <input type="text" name="list_style_symbol" class="fme-input-md"
                                   value="<?php echo esc_attr( $this->current_options['list_style_symbol'] ); ?>" />
                            <span class="fme-small-hint"><?php esc_html_e( "It's best to avoid this unless you have very few footnotes per post.", 'footnotes-made-easy' ); ?></span>
                        </div>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Superscript', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Show identifier as superscript in post content.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="superscript" <?php checked( $this->current_options['superscript'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>
                </div><!-- /.fme-card -->

                <!-- Card: Back-links -->
                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Back-links', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Back-link style', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint">
                                <?php
                                echo esc_html( sprintf(
                                    /* translators: %s: example back-link character */
                                    __( 'Affects how back-links after each footnote look. A good character is %s. Leave all blank to remove back-links.', 'footnotes-made-easy' ),
                                    '&#8617; (↩)'
                                ) );
                                ?>
                            </div>
                        </div>
                        <div class="fme-field-input">
                            <div class="fme-inline-inputs">
                                <input type="text" name="pre_backlink"
                                       value="<?php echo esc_attr( $this->current_options['pre_backlink'] ); ?>"
                                       placeholder="[" />
                                <input type="text" name="backlink" class="fme-input-sm"
                                       value="<?php echo esc_attr( $this->current_options['backlink'] ); ?>"
                                       placeholder="&#8617;" />
                                <input type="text" name="post_backlink"
                                       value="<?php echo esc_attr( $this->current_options['post_backlink'] ); ?>"
                                       placeholder="]" />
                            </div>
                        </div>
                    </div>
                </div><!-- /.fme-card -->

                <!-- Card: Header & Footer -->
                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Footnotes header &amp; footer', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Header text', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Content displayed before the footnotes at the bottom of the post.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <textarea name="pre_footnotes" rows="3"><?php echo esc_textarea( $this->current_options['pre_footnotes'] ); ?></textarea>
                        </div>
                    </div>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Footer text', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Content displayed after the footnotes at the bottom of the post.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <textarea name="post_footnotes" rows="3"><?php echo esc_textarea( $this->current_options['post_footnotes'] ); ?></textarea>
                        </div>
                    </div>
                </div><!-- /.fme-card -->

                <!-- Card: Tooltips -->
                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Tooltips', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Pretty tooltips', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Uses jQuery UI to display styled tooltips on hover.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="pretty_tooltips" id="pretty_tooltips"
                                   <?php checked( $this->current_options['pretty_tooltips'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>
                </div><!-- /.fme-card -->

            </div><!-- /#fme-panel-display -->


            <!-- ════════════════════════════════════════
                 TAB: Behaviour
            ════════════════════════════════════════ -->
            <div id="fme-panel-behaviour" class="fme-tab-panel">

                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Footnote processing', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Combine identical footnotes', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Identical footnotes across a post will be merged into one entry.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="combine_identical_notes" id="combine_identical_notes"
                                   <?php checked( $this->current_options['combine_identical_notes'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Execution priority', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Controls the order in which this plugin runs relative to others. Changing this may affect other plugins. Default is 11.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <input type="text" name="priority" id="priority" class="fme-input-num"
                                   value="<?php echo esc_attr( $this->current_options['priority'] ); ?>" />
                        </div>
                    </div>
                </div><!-- /.fme-card -->

            </div><!-- /#fme-panel-behaviour -->


            <!-- ════════════════════════════════════════
                 TAB: Suppress
            ════════════════════════════════════════ -->
            <div id="fme-panel-suppress" class="fme-tab-panel">

                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Where to suppress footnotes', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Home page', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Do not render footnotes on the site home page.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_home" id="no_display_home"
                                   <?php checked( $this->current_options['no_display_home'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Post / page previews', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Suppress when displaying a preview of a post or page.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_preview" id="no_display_preview"
                                   <?php checked( $this->current_options['no_display_preview'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Search results', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Suppress footnotes on search result pages.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_search" id="no_display_search"
                                   <?php checked( $this->current_options['no_display_search'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'RSS / Atom feeds', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Suppress footnotes in all syndication feeds.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_feed" id="no_display_feed"
                                   <?php checked( $this->current_options['no_display_feed'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Any kind of archive', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Suppress on all archive page types.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_archive" id="no_display_archive"
                                   <?php checked( $this->current_options['no_display_archive'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Category archives', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Suppress specifically on category archive pages.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_category" id="no_display_category"
                                   <?php checked( $this->current_options['no_display_category'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                    <div class="fme-toggle-row">
                        <div class="fme-toggle-label">
                            <div class="fme-toggle-label-text"><?php esc_html_e( 'Date-based archives', 'footnotes-made-easy' ); ?></div>
                            <div class="fme-toggle-label-hint"><?php esc_html_e( 'Suppress on day, month, and year archive pages.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <label class="fme-toggle-switch">
                            <input type="checkbox" name="no_display_date" id="no_display_date"
                                   <?php checked( $this->current_options['no_display_date'], true ); ?> />
                            <span class="fme-toggle-track"></span>
                            <span class="fme-toggle-thumb"></span>
                        </label>
                    </div>

                </div><!-- /.fme-card -->

                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Exclude specific URLs', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Excluded URLs or paths', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Enter URLs or paths where footnotes should be completely disabled (one per line). Both full URLs and path slugs are accepted. Footnote shortcodes on these pages will be silently removed.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <textarea name="exclude_urls" id="exclude_urls" rows="5"><?php echo esc_textarea( $this->current_options['exclude_urls'] ?? '' ); ?></textarea>
                            <span class="fme-small-hint"><?php esc_html_e( 'Examples: /about-us/ or https://yoursite.com/private-page/', 'footnotes-made-easy' ); ?></span>
                        </div>
                    </div>
                </div><!-- /.fme-card -->

            </div><!-- /#fme-panel-suppress -->


            <!-- ════════════════════════════════════════
                 TAB: Advanced
            ════════════════════════════════════════ -->
            <div id="fme-panel-advanced" class="fme-tab-panel">

                <div class="fme-warning-banner">
                    <svg class="fme-warning-icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="fme-warning-text">
                        <?php esc_html_e( 'Changing these settings will alter how footnotes are parsed. You will need to manually update all existing posts with footnotes for them to continue working correctly after saving.', 'footnotes-made-easy' ); ?>
                    </p>
                </div>

                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Footnote delimiters', 'footnotes-made-easy' ); ?></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Opening tag', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Characters that mark the beginning of a footnote in post content.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <input type="text" name="footnotes_open" id="footnotes_open" class="fme-input-md"
                                   value="<?php echo esc_attr( $this->current_options['footnotes_open'] ); ?>" />
                        </div>
                    </div>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Closing tag', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Characters that mark the end of a footnote in post content.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <input type="text" name="footnotes_close" id="footnotes_close" class="fme-input-md"
                                   value="<?php echo esc_attr( $this->current_options['footnotes_close'] ); ?>" />
                        </div>
                    </div>
                </div><!-- /.fme-card -->

            </div><!-- /#fme-panel-advanced -->

            <!-- ════════════════════════════════════════
                 TAB: About
            ════════════════════════════════════════ -->
            <div id="fme-panel-about" class="fme-tab-panel">

                <?php
                // ── Stats ──────────────────────────────────────────────────
                $fme_open  = preg_quote( $this->current_options['footnotes_open'],  '/' );
                $fme_close = preg_quote( $this->current_options['footnotes_close'], '/' );

                $posts_with_footnotes = 0;
                $pages_with_footnotes = 0;
                $total_footnotes      = 0;

                $fme_query = new WP_Query( array(
                    'post_type'      => array( 'post', 'page' ),
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                ) );

                if ( $fme_query->have_posts() ) {
                    foreach ( $fme_query->posts as $fme_post_id ) {
                        $fme_content = get_post_field( 'post_content', $fme_post_id );
                        $fme_count   = preg_match_all(
                            '/' . $fme_open . '.*?' . $fme_close . '/s',
                            $fme_content
                        );
                        if ( $fme_count > 0 ) {
                            $total_footnotes += $fme_count;
                            if ( get_post_type( $fme_post_id ) === 'page' ) {
                                $pages_with_footnotes++;
                            } else {
                                $posts_with_footnotes++;
                            }
                        }
                    }
                }
                wp_reset_postdata();

                $fme_total_posts = wp_count_posts( 'post' )->publish;
                $fme_total_pages = wp_count_posts( 'page' )->publish;

                // ── Version checks ─────────────────────────────────────────
                $fme_plugin_data    = get_file_data(
                    plugin_dir_path( __FILE__ ) . 'footnotes-made-easy.php',
                    array( 'Version' => 'Version' )
                );
                $fme_plugin_version = $fme_plugin_data['Version'] ?? '—';
                $fme_wp_version     = get_bloginfo( 'version' );

                $fme_plugin_latest  = get_site_transient( 'update_plugins' );
                $fme_plugin_slug    = 'footnotes-made-easy/footnotes-made-easy.php';
                $fme_plugin_update  = isset( $fme_plugin_latest->response[ $fme_plugin_slug ] );
                $fme_plugin_uptodate = ! $fme_plugin_update;

                $fme_core_updates   = get_site_transient( 'update_core' );
                $fme_wp_uptodate    = true;
                if ( isset( $fme_core_updates->updates ) && is_array( $fme_core_updates->updates ) ) {
                    foreach ( $fme_core_updates->updates as $fme_upd ) {
                        if ( isset( $fme_upd->response ) && $fme_upd->response === 'upgrade' ) {
                            $fme_wp_uptodate = false;
                            break;
                        }
                    }
                }
                ?>

                <!-- Metric cards -->
                <div class="fme-about-metrics">
                    <div class="fme-about-metric">
                        <p class="fme-about-metric-label"><?php esc_html_e( 'Posts with footnotes', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-about-metric-value"><?php echo esc_html( $posts_with_footnotes ); ?></p>
                        <p class="fme-about-metric-desc"><?php echo esc_html( sprintf( __( 'out of %d posts', 'footnotes-made-easy' ), $fme_total_posts ) ); ?></p>
                    </div>
                    <div class="fme-about-metric">
                        <p class="fme-about-metric-label"><?php esc_html_e( 'Pages with footnotes', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-about-metric-value"><?php echo esc_html( $pages_with_footnotes ); ?></p>
                        <p class="fme-about-metric-desc"><?php echo esc_html( sprintf( __( 'out of %d pages', 'footnotes-made-easy' ), $fme_total_pages ) ); ?></p>
                    </div>
                    <div class="fme-about-metric">
                        <p class="fme-about-metric-label"><?php esc_html_e( 'Total footnotes', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-about-metric-value"><?php echo esc_html( $total_footnotes ); ?></p>
                        <p class="fme-about-metric-desc"><?php esc_html_e( 'across all content', 'footnotes-made-easy' ); ?></p>
                    </div>
                </div>

                <!-- Content stats + Version status -->
                <div class="fme-about-grid">

                    <div class="fme-card">
                        <h3 class="fme-card-title"><?php esc_html_e( 'Footnoted content', 'footnotes-made-easy' ); ?></h3>

                        <div class="fme-about-stat-row">
                            <div class="fme-about-stat-left">
                                <span class="fme-about-stat-icon fme-about-icon-purple">
                                    <svg viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.396 0 2.7.37 3.84 1.02A7.968 7.968 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                                </span>
                                <span class="fme-about-stat-label"><?php esc_html_e( 'Pages with footnotes', 'footnotes-made-easy' ); ?></span>
                            </div>
                            <div class="fme-about-stat-right">
                                <span class="fme-about-stat-value"><?php echo esc_html( $pages_with_footnotes ); ?></span>
                                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" class="fme-about-link"><?php esc_html_e( 'Manage pages', 'footnotes-made-easy' ); ?></a>
                            </div>
                        </div>

                        <div class="fme-about-stat-row fme-about-stat-last">
                            <div class="fme-about-stat-left">
                                <span class="fme-about-stat-icon fme-about-icon-teal">
                                    <svg viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM5 13a1 1 0 00-.117 1.993L5 15h10a1 1 0 00.117-1.993L15 13H5z"/></svg>
                                </span>
                                <span class="fme-about-stat-label"><?php esc_html_e( 'Posts with footnotes', 'footnotes-made-easy' ); ?></span>
                            </div>
                            <div class="fme-about-stat-right">
                                <span class="fme-about-stat-value"><?php echo esc_html( $posts_with_footnotes ); ?></span>
                                <a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>" class="fme-about-link"><?php esc_html_e( 'Manage posts', 'footnotes-made-easy' ); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="fme-card">
                        <h3 class="fme-card-title"><?php esc_html_e( 'Version status', 'footnotes-made-easy' ); ?></h3>

                        <div class="fme-about-version-row">
                            <span class="fme-about-check <?php echo $fme_plugin_uptodate ? 'fme-about-check-ok' : 'fme-about-check-warn'; ?>">
                                <?php if ( $fme_plugin_uptodate ) : ?>
                                    <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <?php else : ?>
                                    <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <?php endif; ?>
                            </span>
                            <div class="fme-about-version-info">
                                <p class="fme-about-version-title">
                                    <?php echo $fme_plugin_uptodate
                                        ? esc_html__( 'Footnotes Made Easy is up to date', 'footnotes-made-easy' )
                                        : esc_html__( 'Footnotes Made Easy update available', 'footnotes-made-easy' ); ?>
                                </p>
                                <p class="fme-about-version-sub">
                                    <?php echo esc_html( sprintf( __( 'You are running version %s', 'footnotes-made-easy' ), $fme_plugin_version ) ); ?>
                                </p>
                            </div>
                            <?php if ( ! $fme_plugin_uptodate ) : ?>
                            <a href="<?php echo esc_url( admin_url( 'plugins.php?plugin_status=upgrade' ) ); ?>"
                               class="fme-update-btn"><?php esc_html_e( 'Update now', 'footnotes-made-easy' ); ?></a>
                            <?php endif; ?>
                            <span class="fme-about-badge <?php echo $fme_plugin_uptodate ? 'fme-about-badge-green' : 'fme-about-badge-amber'; ?>">
                                <?php echo esc_html( $fme_plugin_version ); ?>
                            </span>
                        </div>

                        <div class="fme-about-version-row fme-about-version-last">
                            <span class="fme-about-check <?php echo $fme_wp_uptodate ? 'fme-about-check-ok' : 'fme-about-check-warn'; ?>">
                                <?php if ( $fme_wp_uptodate ) : ?>
                                    <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <?php else : ?>
                                    <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <?php endif; ?>
                            </span>
                            <div class="fme-about-version-info">
                                <p class="fme-about-version-title">
                                    <?php echo $fme_wp_uptodate
                                        ? esc_html__( 'WordPress is up to date', 'footnotes-made-easy' )
                                        : esc_html__( 'WordPress update available', 'footnotes-made-easy' ); ?>
                                </p>
                                <p class="fme-about-version-sub">
                                    <?php echo esc_html( sprintf( __( 'You are running version %s', 'footnotes-made-easy' ), $fme_wp_version ) ); ?>
                                </p>
                            </div>
                            <?php if ( ! $fme_wp_uptodate ) : ?>
                            <a href="<?php echo esc_url( admin_url( 'update-core.php' ) ); ?>"
                               class="fme-update-btn"><?php esc_html_e( 'Update now', 'footnotes-made-easy' ); ?></a>
                            <?php endif; ?>
                            <span class="fme-about-badge <?php echo $fme_wp_uptodate ? 'fme-about-badge-green' : 'fme-about-badge-amber'; ?>">
                                <?php echo esc_html( $fme_wp_version ); ?>
                            </span>
                        </div>
                    </div>

                </div><!-- /.fme-about-grid -->

                <!-- Video tutorials -->
                <div class="fme-card fme-about-section">
                    <div class="fme-card-title-row">
                        <h3 class="fme-card-title"><?php esc_html_e( 'Video tutorials', 'footnotes-made-easy' ); ?></h3>
                        <a href="#" class="fme-more-tutorials-btn"><?php esc_html_e( 'More tutorials', 'footnotes-made-easy' ); ?></a>
                    </div>

                    <?php
                    /*
                     * Tutorial list.
                     * Each entry needs only: video_id, title, duration, level.
                     * The thumbnail is fetched directly from YouTube's image CDN —
                     * no API key required. Format used: mqdefault (320×180, 16:9,
                     * always available). Switch to hqdefault (480×360) for crisper
                     * images if you prefer; it occasionally has black bars on older
                     * uploads, so mqdefault is the safer default.
                     *
                     * To add more videos just append items to this array.
                     * Three videos display in a responsive grid (3-col → 2-col → 1-col).
                     */
                    $fme_tutorials = array(
                        array(
                            'video_id' => 'LuXMb8Hz4tc',
                            'title'    => __( 'Getting started with Footnotes Made Easy', 'footnotes-made-easy' ),
                            'duration' => '5:24',
                            'level'    => __( 'Beginner', 'footnotes-made-easy' ),
                        ),
                        array(
                            'video_id' => 'dQw4w9WgXcQ',
                            'title'    => __( 'Customising footnote styles & identifiers', 'footnotes-made-easy' ),
                            'duration' => '8:11',
                            'level'    => __( 'Intermediate', 'footnotes-made-easy' ),
                        ),
                        array(
                            'video_id' => 'dQw4w9WgXcQ',
                            'title'    => __( 'Using pretty tooltips & advanced options', 'footnotes-made-easy' ),
                            'duration' => '6:47',
                            'level'    => __( 'Intermediate', 'footnotes-made-easy' ),
                        ),
                    );
                    ?>

                    <div class="fme-about-tutorials" id="fme-tutorials-track">
                            <?php foreach ( $fme_tutorials as $fme_tut ) : ?>
                            <div class="fme-about-tutorial"
                                 data-video="<?php echo esc_attr( $fme_tut['video_id'] ); ?>"
                                 data-title="<?php echo esc_attr( $fme_tut['title'] ); ?>"
                                 role="button"
                                 tabindex="0"
                                 aria-label="<?php echo esc_attr( sprintf( __( 'Play: %s', 'footnotes-made-easy' ), $fme_tut['title'] ) ); ?>">
                                <div class="fme-about-thumb">
                                    <img src="<?php echo esc_url( 'https://img.youtube.com/vi/' . $fme_tut['video_id'] . '/mqdefault.jpg' ); ?>"
                                         alt="<?php echo esc_attr( $fme_tut['title'] ); ?>"
                                         loading="lazy"
                                         width="320"
                                         height="180" />
                                    <span class="fme-about-play" aria-hidden="true">
                                        <svg viewBox="0 0 20 20"><path d="M6.3 2.84A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.27l9.344-5.891a1.5 1.5 0 000-2.538L6.3 2.841z"/></svg>
                                    </span>
                                </div>
                                <div class="fme-about-tut-info">
                                    <p class="fme-about-tut-title"><?php echo esc_html( $fme_tut['title'] ); ?></p>
                                    <p class="fme-about-tut-meta"><?php echo esc_html( $fme_tut['duration'] . ' · ' . $fme_tut['level'] ); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                    </div><!-- /.fme-tutorials -->

                </div><!-- /.fme-card tutorials -->

                <!-- Documentation / Support / GitHub -->
                <div class="fme-about-links">

                    <a href="https://wordpress.org/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer" class="fme-about-link-card">
                        <span class="fme-about-link-icon fme-about-icon-purple">
                            <svg viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.396 0 2.7.37 3.84 1.02A7.968 7.968 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        </span>
                        <div>
                            <p class="fme-about-link-title"><?php esc_html_e( 'Documentation', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-about-link-sub"><?php esc_html_e( 'Guides, settings reference & examples', 'footnotes-made-easy' ); ?></p>
                        </div>
                        <span class="fme-about-link-arrow">↗</span>
                    </a>

                    <a href="https://wordpress.org/support/plugin/footnotes-made-easy" target="_blank" rel="noopener noreferrer" class="fme-about-link-card">
                        <span class="fme-about-link-icon fme-about-icon-teal">
                            <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                        </span>
                        <div>
                            <p class="fme-about-link-title"><?php esc_html_e( 'Support forum', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-about-link-sub"><?php esc_html_e( 'Ask questions & get help from the community', 'footnotes-made-easy' ); ?></p>
                        </div>
                        <span class="fme-about-link-arrow">↗</span>
                    </a>

                    <a href="https://github.com/lumumbapl/footnotes-made-easy" target="_blank" rel="noopener noreferrer" class="fme-about-link-card">
                        <span class="fme-about-link-icon fme-about-icon-coral">
                            <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/></svg>
                        </span>
                        <div>
                            <p class="fme-about-link-title"><?php esc_html_e( 'GitHub repository', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-about-link-sub"><?php esc_html_e( 'View source, report issues & contribute', 'footnotes-made-easy' ); ?></p>
                        </div>
                        <span class="fme-about-link-arrow">↗</span>
                    </a>

                </div><!-- /.fme-about-links -->

            </div><!-- /#fme-panel-about -->

            <!-- Video modal -->
            <div id="fme-video-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:99999;align-items:center;justify-content:center;">
                <div style="background:#111;border-radius:12px;overflow:hidden;width:640px;max-width:92vw;position:relative;">
                    <button id="fme-video-close" type="button" style="position:absolute;top:10px;right:12px;background:rgba(255,255,255,0.15);border:none;color:#fff;font-size:16px;width:28px;height:28px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:10;">✕</button>
                    <div style="position:relative;padding-bottom:56.25%;height:0;">
                        <iframe id="fme-video-iframe" src="" style="position:absolute;inset:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>
                    </div>
                    <p id="fme-video-title" style="padding:12px 16px;font-size:13px;font-weight:500;color:#fff;margin:0;"></p>
                </div>
            </div>

            <div class="fme-form-footer">
                <button type="submit" name="save_options" value="1" class="fme-save-btn">
                    <svg viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                    <?php esc_html_e( 'Save Changes', 'footnotes-made-easy' ); ?>
                </button>
            </div>

        </div><!-- /.fme-content -->

    </form><!-- /#fme-settings-form -->

</div><!-- /#fme-settings-wrap -->
