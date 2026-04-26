<?php
/**
 * Uninstaller
 *
 * Removes all data stored by Footnotes Made Easy when the plugin
 * is deleted via the WordPress admin. Covers:
 *
 *  - swas_footnote_options         — main settings (options table)
 *  - fme_rating_banner             — per-user rating-banner state (usermeta)
 *  - fme_banner_seeded_version     — per-user banner version flag (usermeta)
 *
 * @package footnotes-made-easy
 * @since   1.0
 */

// Bail if WordPress did not trigger this uninstall.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// ── 1. Plugin settings ────────────────────────────────────────
delete_option( 'swas_footnote_options' );

// ── 2. Per-user meta added by the rating banner ───────────────
// Passing 0 as user_id and true as $delete_all removes the meta key
// from every user in one query.
delete_metadata( 'user', 0, 'fme_rating_banner',         '', true );
delete_metadata( 'user', 0, 'fme_banner_seeded_version', '', true );
