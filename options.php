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
    </div><!-- /.fme-topbar -->

    <!-- ── Single form wrapping all tabs ────────────── -->
    <form method="post" id="fme-settings-form">

        <?php wp_nonce_field( 'footnotes-nonce', 'footnotes_nonce' ); ?>
        <input type="hidden" name="save_footnotes_made_easy_options" value="1">

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
                            <textarea name="exclude_urls" id="exclude_urls" rows="5"><?php echo esc_textarea( $this->current_options['exclude_urls'] ); ?></textarea>
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

            <!-- ── Bottom save button ─────────────────────── -->
            <div class="fme-form-footer">
                <button type="submit" name="save_options" value="1" class="fme-save-btn">
                    <svg viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                    <?php esc_html_e( 'Save Changes', 'footnotes-made-easy' ); ?>
                </button>
            </div>

        </div><!-- /.fme-content -->

    </form><!-- /#fme-settings-form -->

</div><!-- /#fme-settings-wrap -->

<script>
(function () {

    /* ── Tab switching ──────────────────────────────── */
    var tabs = {
        display:   { title: '<?php echo esc_js( __( 'Display settings', 'footnotes-made-easy' ) ); ?>',   sub: '<?php echo esc_js( __( 'Control how footnote identifiers, links, and back-links appear on the front end.', 'footnotes-made-easy' ) ); ?>' },
        behaviour: { title: '<?php echo esc_js( __( 'Behaviour settings', 'footnotes-made-easy' ) ); ?>', sub: '<?php echo esc_js( __( 'Configure how footnotes are processed and rendered by WordPress.', 'footnotes-made-easy' ) ); ?>' },
        suppress:  { title: '<?php echo esc_js( __( 'Suppress settings', 'footnotes-made-easy' ) ); ?>',  sub: '<?php echo esc_js( __( 'Choose where on your site footnotes should not appear.', 'footnotes-made-easy' ) ); ?>' },
        advanced:  { title: '<?php echo esc_js( __( 'Advanced settings', 'footnotes-made-easy' ) ); ?>',  sub: '<?php echo esc_js( __( 'Modify footnote delimiter tags — changes require updating all existing posts.', 'footnotes-made-easy' ) ); ?>' }
    };

    var btnEls  = document.querySelectorAll('.fme-tab-btn');
    var titleEl = document.getElementById('fme-tab-title');
    var subEl   = document.getElementById('fme-tab-sub');

    btnEls.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-tab');
            btnEls.forEach(function (b) { b.classList.remove('fme-active'); });
            btn.classList.add('fme-active');
            document.querySelectorAll('.fme-tab-panel').forEach(function (p) { p.classList.remove('fme-active'); });
            document.getElementById('fme-panel-' + id).classList.add('fme-active');
            titleEl.textContent = tabs[id].title;
            subEl.textContent   = tabs[id].sub;
        });
    });

    /* ── Auto-dismiss saved notice after 4 s ───────── */
    var notice = document.getElementById('fme-notice-saved');
    if (notice) {
        setTimeout(function () {
            notice.classList.add('fme-notice-hiding');
            // Remove from flow after transition ends
            notice.addEventListener('transitionend', function () {
                notice.style.display = 'none';
            }, { once: true });
        }, 4000);
    }

    /* ── Dismiss rating banner ──────────────────────── */
    var dismissBtn = document.getElementById('fme-dismiss-banner');
    if (dismissBtn) {
        dismissBtn.addEventListener('click', function () {
            var banner = document.getElementById('fme-rating-banner');
            if (banner) { banner.classList.add('fme-dismissed'); }
        });
    }

}());
</script>
