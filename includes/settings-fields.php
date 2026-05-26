<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals -- Partial included within class method scope.
// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified by caller.
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
 * Settings fields partial — Display, Behaviour, Suppress, Advanced tabs only.
 * Included by both the regular Settings page and the Permissions (Subsites) page.
 * Requires $this->current_options to be set by the caller.
 */
?>
<div class="fme-settings-tabs">
    <div id="fme-tabs-nav" class="fme-inner-tabs" role="tablist">
        <button type="button" class="fme-inner-tab fme-active" data-tab="display"><?php esc_html_e( 'Display', 'footnotes-made-easy' ); ?></button>
        <button type="button" class="fme-inner-tab" data-tab="behaviour"><?php esc_html_e( 'Behaviour', 'footnotes-made-easy' ); ?></button>
        <button type="button" class="fme-inner-tab" data-tab="suppress"><?php esc_html_e( 'Suppress', 'footnotes-made-easy' ); ?></button>
        <button type="button" class="fme-inner-tab" data-tab="advanced"><?php esc_html_e( 'Advanced', 'footnotes-made-easy' ); ?></button>
    </div>

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
                    <h3 class="fme-card-title"><?php esc_html_e( 'Exclude specific URLs', 'footnotes-made-easy' ); ?> <span class="fme-badge-new"><?php esc_html_e( 'new', 'footnotes-made-easy' ); ?></span></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Excluded URLs or paths', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Enter URLs or paths where footnotes should be completely disabled (one per line). Both full URLs and path slugs are accepted. Footnote shortcodes on these pages will be silently removed.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <textarea name="exclude_urls" id="exclude_urls" rows="5" placeholder="/about-us/&#10;/private-page/&#10;https://yoursite.com/members/"><?php echo esc_textarea( $this->current_options['exclude_urls'] ?? '' ); ?></textarea>
                            <span class="fme-small-hint"><?php esc_html_e( 'Examples: /about-us/ or https://yoursite.com/private-page/', 'footnotes-made-easy' ); ?></span>
                        </div>
                    </div>
                </div><!-- /.fme-card -->

                <div class="fme-card">
                    <h3 class="fme-card-title"><?php esc_html_e( 'Exclude specific categories', 'footnotes-made-easy' ); ?> <span class="fme-badge-new"><?php esc_html_e( 'new', 'footnotes-made-easy' ); ?></span></h3>

                    <div class="fme-field-row">
                        <div class="fme-field-label">
                            <?php esc_html_e( 'Excluded categories', 'footnotes-made-easy' ); ?>
                            <div class="fme-field-hint"><?php esc_html_e( 'Enter category slugs or IDs where footnotes should be suppressed (one per line). Applies to individual posts belonging to these categories, not just the category archive page.', 'footnotes-made-easy' ); ?></div>
                        </div>
                        <div class="fme-field-input">
                            <textarea name="exclude_categories" id="exclude_categories" rows="5" placeholder="news&#10;tutorials&#10;42"><?php echo esc_textarea( $this->current_options['exclude_categories'] ?? '' ); ?></textarea>
                            <span class="fme-small-hint"><?php esc_html_e( 'Examples: news or 42 (numeric ID)', 'footnotes-made-easy' ); ?></span>
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

</div><!-- /.fme-settings-tabs -->
