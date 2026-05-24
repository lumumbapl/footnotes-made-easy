<?php
/**
 * Tools Page — Footnotes Made Easy
 *
 * Reset settings and uninstall data controls.
 *
 * @package footnotes-made-easy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$fme_version  = isset( $this ) ? get_plugin_data( plugin_dir_path( dirname( __FILE__ ) ) . 'footnotes-made-easy.php', false, false )['Version'] ?? '' : '';
$preserve_on  = get_option( 'fme_preserve_settings_on_uninstall', '0' ) === '1';
?>
<div class="wrap fme-wrap">

    <!-- Topbar -->
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
            <a href="https://alvise.com/docs/plugins/footnotes-made-easy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'footnotes-made-easy' ); ?></a>
        </div>
    </div>

    <?php if ( isset( $_GET['reset'] ) ) : ?>
    <div class="fme-notice fme-notice-success fme-notice-autodismiss" id="fme-saved-notice">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><path d="M7 0a7 7 0 100 14A7 7 0 007 0zm3.293 4.793l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L5.586 6.672l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
        <?php esc_html_e( 'Settings have been reset to defaults.', 'footnotes-made-easy' ); ?>
        <button type="button" class="fme-notice-close" aria-label="<?php esc_attr_e( 'Dismiss', 'footnotes-made-easy' ); ?>">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['preserve_saved'] ) ) : ?>
    <div class="fme-notice fme-notice-success fme-notice-autodismiss" id="fme-saved-notice">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><path d="M7 0a7 7 0 100 14A7 7 0 007 0zm3.293 4.793l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L5.586 6.672l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
        <?php esc_html_e( 'Uninstall preference saved.', 'footnotes-made-easy' ); ?>
        <button type="button" class="fme-notice-close" aria-label="<?php esc_attr_e( 'Dismiss', 'footnotes-made-easy' ); ?>">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['import'] ) ) :
        $import_status = $_GET['import'];
        $import_ok     = $import_status === 'success';
        $import_partial = $import_status === 'partial';
        $import_msg = $import_ok
            ? __( 'Settings imported successfully.', 'footnotes-made-easy' )
            : sanitize_text_field( urldecode( $_GET['import_message'] ?? __( 'Import failed.', 'footnotes-made-easy' ) ) );
        $notice_type = $import_ok ? 'success' : ( $import_partial ? 'warning' : 'error' );
    ?>
    <div class="fme-notice fme-notice-<?php echo esc_attr( $notice_type ); ?> fme-notice-autodismiss" id="fme-saved-notice">
        <?php if ( $import_ok ) : ?>
        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><path d="M7 0a7 7 0 100 14A7 7 0 007 0zm3.293 4.793l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L5.586 6.672l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
        <?php endif; ?>
        <?php echo esc_html( $import_msg ); ?>
        <button type="button" class="fme-notice-close" aria-label="<?php esc_attr_e( 'Dismiss', 'footnotes-made-easy' ); ?>">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>
    <?php endif; ?>

    <div class="fme-settings-grid">

        <!-- MAIN COLUMN -->
        <div class="fme-settings-main">

            <!-- ── Export / Import ───────────────────────── -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Export / Import settings', 'footnotes-made-easy' ); ?></h3>
                <table class="fme-form-table">

                    <!-- Export -->
                    <tr>
                        <th><?php esc_html_e( 'Export', 'footnotes-made-easy' ); ?></th>
                        <td>
                            <p class="description" style="margin-bottom:14px;">
                                <?php
                                if ( defined( 'FME_PRO_VERSION' ) && class_exists( 'FME_Pro_License' ) && FME_Pro_License::is_active() ) {
                                    esc_html_e( 'Download all plugin settings (free + Pro) as a JSON file. Use it to back up or transfer settings to another site.', 'footnotes-made-easy' );
                                } else {
                                    esc_html_e( 'Download your plugin settings as a JSON file. Use it to back up or transfer settings to another site.', 'footnotes-made-easy' );
                                    echo ' <strong>' . esc_html__( 'Activate Pro to also export citation and Pro settings.', 'footnotes-made-easy' ) . '</strong>';
                                }
                                ?>
                            </p>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                                <?php wp_nonce_field( 'fme_export_settings_nonce', 'fme_export_settings_nonce' ); ?>
                                <input type="hidden" name="action" value="fme_export_settings">
                                <button type="submit" class="button">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14" style="margin-right:4px;vertical-align:middle;"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    <?php esc_html_e( 'Download settings file', 'footnotes-made-easy' ); ?>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Import -->
                    <tr>
                        <th><?php esc_html_e( 'Import', 'footnotes-made-easy' ); ?></th>
                        <td>
                            <p class="description" style="margin-bottom:14px;">
                                <?php esc_html_e( 'Upload a previously exported settings file to restore or transfer settings. This will overwrite your current settings.', 'footnotes-made-easy' ); ?>
                            </p>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                                  enctype="multipart/form-data"
                                  id="fme-import-form">
                                <?php wp_nonce_field( 'fme_import_settings_nonce', 'fme_import_settings_nonce' ); ?>
                                <input type="hidden" name="action" value="fme_import_settings">
                                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                    <input type="file"
                                           name="fme_import_file"
                                           id="fme_import_file"
                                           accept=".json"
                                           style="font-size:13px;">
                                    <button type="button" class="button" id="fme-import-trigger">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14" style="margin-right:4px;vertical-align:middle;"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-4.707a1 1 0 000 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 13.586V6a1 1 0 10-2 0v7.586l-1.293-1.293a1 1 0 00-1.414 0z" clip-rule="evenodd"/></svg>
                                        <?php esc_html_e( 'Import settings', 'footnotes-made-easy' ); ?>
                                    </button>
                                </div>
                            </form>

                            <!-- Import confirmation modal -->
                            <div class="fme-modal-overlay" id="fme-import-modal" role="dialog" aria-modal="true" aria-labelledby="fme-import-modal-title">
                                <div class="fme-modal">
                                    <div class="fme-modal__icon" aria-hidden="true">
                                        <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <h3 class="fme-modal__title" id="fme-import-modal-title">
                                        <?php esc_html_e( 'Import settings?', 'footnotes-made-easy' ); ?>
                                    </h3>
                                    <p class="fme-modal__desc">
                                        <?php esc_html_e( 'This will overwrite your current settings with the ones from the imported file. This action cannot be undone.', 'footnotes-made-easy' ); ?>
                                    </p>
                                    <div class="fme-modal__actions">
                                        <button type="button" class="fme-modal__cancel" id="fme-import-modal-cancel">
                                            <?php esc_html_e( 'Cancel', 'footnotes-made-easy' ); ?>
                                        </button>
                                        <button type="button" class="fme-modal__confirm" id="fme-import-modal-confirm">
                                            <?php esc_html_e( 'Yes, import settings', 'footnotes-made-easy' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>

            <!-- ── Reset Settings ─────────────────────────── -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Reset settings', 'footnotes-made-easy' ); ?></h3>
                <table class="fme-form-table">
                    <tr>
                        <th><?php esc_html_e( 'Factory reset', 'footnotes-made-easy' ); ?></th>
                        <td>
                            <p class="description" style="margin-bottom:14px;">
                                <?php esc_html_e( 'This will reset all plugin settings back to their default values. Your posts and content will not be affected.', 'footnotes-made-easy' ); ?>
                            </p>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="fme-reset-form">
                                <?php wp_nonce_field( 'fme_reset_settings_nonce', 'fme_reset_settings_nonce' ); ?>
                                <input type="hidden" name="action" value="fme_reset_settings">
                                <button type="button" class="button fme-reset-btn" id="fme-reset-trigger">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                                    <?php esc_html_e( 'Reset to defaults', 'footnotes-made-easy' ); ?>
                                </button>
                            </form>

                            <!-- Reset confirmation modal -->
                            <div class="fme-modal-overlay" id="fme-reset-modal" role="dialog" aria-modal="true" aria-labelledby="fme-modal-title">
                                <div class="fme-modal">
                                    <div class="fme-modal__icon" aria-hidden="true">
                                        <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <h3 class="fme-modal__title" id="fme-modal-title">
                                        <?php esc_html_e( 'Reset all settings?', 'footnotes-made-easy' ); ?>
                                    </h3>
                                    <p class="fme-modal__desc">
                                        <?php esc_html_e( 'This will restore all settings to their factory defaults. Your posts and footnote content will not be affected. This action cannot be undone.', 'footnotes-made-easy' ); ?>
                                    </p>
                                    <div class="fme-modal__actions">
                                        <button type="button" class="fme-modal__cancel" id="fme-modal-cancel">
                                            <?php esc_html_e( 'Cancel', 'footnotes-made-easy' ); ?>
                                        </button>
                                        <button type="button" class="fme-modal__confirm" id="fme-modal-confirm">
                                            <?php esc_html_e( 'Yes, reset settings', 'footnotes-made-easy' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- ── Uninstall / Data ───────────────────────── -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Data on uninstall', 'footnotes-made-easy' ); ?></h3>
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <?php wp_nonce_field( 'fme_preserve_settings_nonce', 'fme_preserve_settings_nonce' ); ?>
                    <input type="hidden" name="action" value="fme_save_preserve_settings">
                    <table class="fme-form-table">
                        <tr>
                            <th>
                                <label for="fme_preserve_settings">
                                    <?php esc_html_e( 'Preserve settings', 'footnotes-made-easy' ); ?>
                                </label>
                            </th>
                            <td>
                                <label class="fme-toggle">
                                    <input type="checkbox"
                                           id="fme_preserve_settings"
                                           name="fme_preserve_settings"
                                           value="1"
                                           <?php checked( $preserve_on ); ?>>
                                    <span class="fme-toggle-slider"></span>
                                </label>
                                <p class="description" style="margin-top:8px;">
                                    <?php esc_html_e( 'When enabled, all settings are kept when the plugin is uninstalled. When disabled, all settings are permanently deleted on uninstall.', 'footnotes-made-easy' ); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <div class="fme-form-footer">
                        <button type="submit" class="fme-save-btn">
                            <svg viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            <?php esc_html_e( 'Save', 'footnotes-made-easy' ); ?>
                        </button>
                    </div>
                </form>
            </div>

        </div><!-- /.fme-settings-main -->

        <?php include dirname( __FILE__ ) . '/sidebar.php'; ?>

        </div><!-- /.fme-settings-grid -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->
