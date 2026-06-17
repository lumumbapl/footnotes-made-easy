<?php
/**
 * FME Migration Engine
 *
 * Handles scanning and converting footnote markers in post content —
 * either changing delimiters within FME, or migrating from other plugins.
 *
 * @package footnotes-made-easy
 * @since   3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FME_Migration_Engine {

	// ── Known source formats ──────────────────────────────────────────────────

	/**
	 * Returns known third-party plugin source formats.
	 *
	 * @return array<string, array{label: string, open: string, close: string, type: string}>
	 */
	public static function known_formats(): array {
		return [
			'easy-footnotes'   => [
				'label' => 'Easy Footnotes',
				'open'  => '[efn_note]',
				'close' => '[/efn_note]',
				'type'  => 'shortcode',
			],
			'footnotes-mci'    => [
				'label' => 'Footnotes (MCI)',
				'open'  => '((',
				'close' => '))',
				'type'  => 'delimiter',
			],
			'wp-footnotes'     => [
				'label' => 'WP Footnotes',
				'open'  => '(( ',
				'close' => ' ))',
				'type'  => 'delimiter',
			],
			'inline-footnotes' => [
				'label' => 'Inline Footnotes',
				'open'  => '[fn]',
				'close' => '[/fn]',
				'type'  => 'shortcode',
			],
			'generic-shortcode' => [
				'label' => 'Generic [footnote] shortcode',
				'open'  => '[footnote]',
				'close' => '[/footnote]',
				'type'  => 'shortcode',
			],
			'custom'           => [
				'label' => 'Custom (define below)',
				'open'  => '',
				'close' => '',
				'type'  => 'custom',
			],
		];
	}

	// ── Regex builder ─────────────────────────────────────────────────────────

	/**
	 * Build a regex pattern that matches content between open/close markers.
	 * Handles both shortcode-style and delimiter-style formats.
	 *
	 * @param string $open  Opening marker (raw, not regex-escaped).
	 * @param string $close Closing marker (raw, not regex-escaped).
	 * @return string PCRE pattern (no delimiters).
	 */
	public static function build_pattern( string $open, string $close ): string {
		$o = preg_quote( trim( $open ),  '/' );
		$c = preg_quote( trim( $close ), '/' );
		// Lazy match between markers; allow any content including newlines
		return $o . '(.*?)' . $c;
	}

	// ── Dry run ───────────────────────────────────────────────────────────────

	/**
	 * Scan posts without writing. Returns counts and a sample of matches.
	 *
	 * @param string $src_open   Source opening marker.
	 * @param string $src_close  Source closing marker.
	 * @param int    $offset     Pagination offset (batch of 50).
	 * @return array{
	 *   scanned: int,
	 *   matched_posts: int,
	 *   total_instances: int,
	 *   samples: array,
	 *   has_more: bool,
	 * }
	 */
	public static function dry_run( string $src_open, string $src_close, int $offset = 0 ): array {
		$batch_size = 50;
		$pattern    = '/' . self::build_pattern( $src_open, $src_close ) . '/s';

		$query = new WP_Query( [
			'post_type'      => [ 'post', 'page' ],
			'post_status'    => [ 'publish', 'draft', 'private', 'future' ],
			'posts_per_page' => $batch_size,
			'offset'         => $offset,
			'fields'         => 'all',
			'no_found_rows'  => false,
		] );

		$scanned        = 0;
		$matched_posts  = 0;
		$total_instances = 0;
		$samples        = [];

		foreach ( $query->posts as $post ) {
			$scanned++;
			$count = preg_match_all( $pattern, $post->post_content, $matches );
			if ( $count ) {
				$matched_posts++;
				$total_instances += $count;
				if ( count( $samples ) < 5 ) {
					$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 20, '…' );
					$samples[] = [
						'post_id'    => $post->ID,
						'post_title' => $post->post_title ?: __( '(no title)', 'footnotes-made-easy' ),
						'excerpt'    => $excerpt,
						'count'      => $count,
					];
				}
			}
		}

		return [
			'scanned'         => $scanned,
			'matched_posts'   => $matched_posts,
			'total_instances' => $total_instances,
			'samples'         => $samples,
			'has_more'        => ( $offset + $batch_size ) < $query->found_posts,
			'found_posts'     => (int) $query->found_posts,
		];
	}

	// ── Backup ────────────────────────────────────────────────────────────────

	/**
	 * Write a backup of all affected post content to a temp file in uploads.
	 * Stores the file path in a 24-hour transient and returns the download key.
	 *
	 * @param string $src_open  Source opening marker.
	 * @param string $src_close Source closing marker.
	 * @return array{ key: string, url: string, post_count: int }|WP_Error
	 */
	public static function create_backup( string $src_open, string $src_close ) {
		$pattern = '/' . self::build_pattern( $src_open, $src_close ) . '/s';
		$posts   = self::get_all_affected_posts( $src_open, $src_close );

		if ( is_wp_error( $posts ) ) {
			return $posts;
		}

		$backup = [
			'created_at'  => current_time( 'mysql' ),
			'src_open'    => $src_open,
			'src_close'   => $src_close,
			'post_count'  => count( $posts ),
			'posts'       => [],
		];

		foreach ( $posts as $post ) {
			$backup['posts'][] = [
				'ID'           => $post->ID,
				'post_title'   => $post->post_title,
				'post_content' => $post->post_content,
				'post_status'  => $post->post_status,
			];
		}

		// Write to uploads/fme-backups/
		$upload_dir = wp_upload_dir();
		$backup_dir = trailingslashit( $upload_dir['basedir'] ) . 'fme-backups';

		if ( ! wp_mkdir_p( $backup_dir ) ) {
			return new WP_Error( 'backup_dir_failed', __( 'Could not create backup directory.', 'footnotes-made-easy' ) );
		}

		// Write an .htaccess to block direct access
		$htaccess = $backup_dir . '/.htaccess';
		if ( ! file_exists( $htaccess ) ) {
			file_put_contents( $htaccess, "deny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}

		$key      = wp_generate_uuid4();
		$filename = 'fme-backup-' . gmdate( 'Y-m-d-His' ) . '-' . substr( $key, 0, 8 ) . '.json';
		$filepath = $backup_dir . '/' . $filename;

		$written = file_put_contents( $filepath, wp_json_encode( $backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

		if ( false === $written ) {
			return new WP_Error( 'backup_write_failed', __( 'Could not write backup file.', 'footnotes-made-easy' ) );
		}

		// Store key → filepath mapping for 24 hours
		set_transient( 'fme_migration_backup_' . $key, $filepath, DAY_IN_SECONDS );
		// Also store for rollback reference
		set_transient( 'fme_migration_last_backup_key', $key, DAY_IN_SECONDS );

		// Download URL goes through a nonce-protected AJAX handler, not direct file URL
		return [
			'key'        => $key,
			'post_count' => count( $posts ),
		];
	}

	// ── Migration batch ───────────────────────────────────────────────────────

	/**
	 * Process a batch of posts — replace source markers with target markers.
	 *
	 * @param string $src_open  Source opening marker.
	 * @param string $src_close Source closing marker.
	 * @param string $tgt_open  Target opening marker.
	 * @param string $tgt_close Target closing marker.
	 * @param int    $offset    Batch offset.
	 * @return array{
	 *   processed: int,
	 *   updated: int,
	 *   instances: int,
	 *   errors: int,
	 *   has_more: bool,
	 *   found_posts: int,
	 * }
	 */
	public static function run_batch(
		string $src_open,
		string $src_close,
		string $tgt_open,
		string $tgt_close,
		int    $offset = 0
	): array {
		$batch_size = 20;
		$pattern    = '/' . self::build_pattern( $src_open, $src_close ) . '/s';
		$replace    = $tgt_open . '$1' . $tgt_close;

		$query = new WP_Query( [
			'post_type'      => [ 'post', 'page' ],
			'post_status'    => [ 'publish', 'draft', 'private', 'future' ],
			'posts_per_page' => $batch_size,
			'offset'         => $offset,
			'no_found_rows'  => false,
		] );

		$processed   = 0;
		$updated     = 0;
		$instances   = 0;
		$errors      = 0;

		foreach ( $query->posts as $post ) {
			$processed++;
			$count   = preg_match_all( $pattern, $post->post_content );
			if ( ! $count ) {
				continue;
			}

			$new_content = preg_replace( $pattern, $replace, $post->post_content );
			if ( null === $new_content ) {
				$errors++;
				continue;
			}

			$result = wp_update_post( [
				'ID'           => $post->ID,
				'post_content' => $new_content,
			], true );

			if ( is_wp_error( $result ) ) {
				$errors++;
			} else {
				$updated++;
				$instances += $count;
			}
		}

		return [
			'processed'   => $processed,
			'updated'     => $updated,
			'instances'   => $instances,
			'errors'      => $errors,
			'has_more'    => ( $offset + $batch_size ) < $query->found_posts,
			'found_posts' => (int) $query->found_posts,
		];
	}

	// ── Rollback ──────────────────────────────────────────────────────────────

	/**
	 * Restore post content from a backup file.
	 *
	 * @param string $key Backup key stored in transient.
	 * @return array{ restored: int, errors: int }|WP_Error
	 */
	public static function rollback( string $key ) {
		$filepath = get_transient( 'fme_migration_backup_' . sanitize_key( $key ) );

		if ( ! $filepath || ! file_exists( $filepath ) ) {
			return new WP_Error( 'backup_not_found', __( 'Backup not found or has expired (24-hour limit).', 'footnotes-made-easy' ) );
		}

		$json   = file_get_contents( $filepath ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$backup = json_decode( $json, true );

		if ( ! $backup || empty( $backup['posts'] ) ) {
			return new WP_Error( 'backup_invalid', __( 'Backup file is invalid or empty.', 'footnotes-made-easy' ) );
		}

		$restored = 0;
		$errors   = 0;

		foreach ( $backup['posts'] as $post_data ) {
			$result = wp_update_post( [
				'ID'           => (int) $post_data['ID'],
				'post_content' => $post_data['post_content'],
			], true );

			if ( is_wp_error( $result ) ) {
				$errors++;
			} else {
				$restored++;
			}
		}

		// Clean up after successful rollback
		if ( 0 === $errors ) {
			@unlink( $filepath ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			delete_transient( 'fme_migration_backup_' . $key );
			delete_transient( 'fme_migration_last_backup_key' );
		}

		return [
			'restored' => $restored,
			'errors'   => $errors,
		];
	}

	// ── Helpers ───────────────────────────────────────────────────────────────

	/**
	 * Fetch all posts containing at least one match — used for backup.
	 *
	 * @param string $src_open  Source opening marker.
	 * @param string $src_close Source closing marker.
	 * @return WP_Post[]|WP_Error
	 */
	private static function get_all_affected_posts( string $src_open, string $src_close ) {
		$pattern = '/' . self::build_pattern( $src_open, $src_close ) . '/s';
		$offset  = 0;
		$batch   = 100;
		$posts   = [];

		do {
			$query = new WP_Query( [
				'post_type'      => [ 'post', 'page' ],
				'post_status'    => [ 'publish', 'draft', 'private', 'future' ],
				'posts_per_page' => $batch,
				'offset'         => $offset,
				'no_found_rows'  => false,
			] );

			foreach ( $query->posts as $post ) {
				if ( preg_match( $pattern, $post->post_content ) ) {
					$posts[] = $post;
				}
			}

			$offset    += $batch;
			$has_more   = $offset < $query->found_posts;
		} while ( $has_more );

		return $posts;
	}

	/**
	 * Sanitise and validate a delimiter string for use in regex.
	 * Returns WP_Error if empty or if it produces an invalid pattern.
	 *
	 * @param string $delimiter Raw user input.
	 * @return string|WP_Error Sanitised delimiter or error.
	 */
	public static function validate_delimiter( string $delimiter ) {
		$delimiter = sanitize_text_field( wp_unslash( $delimiter ) );

		if ( '' === $delimiter ) {
			return new WP_Error( 'empty_delimiter', __( 'Delimiter cannot be empty.', 'footnotes-made-easy' ) );
		}

		// Test that the delimiter produces a valid regex pattern
		$test = @preg_match( '/' . preg_quote( $delimiter, '/' ) . '/s', '' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( false === $test ) {
			return new WP_Error( 'invalid_delimiter', __( 'Delimiter produces an invalid pattern.', 'footnotes-made-easy' ) );
		}

		return $delimiter;
	}

	/**
	 * Serve a backup file download. Exits after sending.
	 *
	 * @param string $key Backup key.
	 */
	public static function serve_backup_download( string $key ): void {
		$filepath = get_transient( 'fme_migration_backup_' . sanitize_key( $key ) );

		if ( ! $filepath || ! file_exists( $filepath ) ) {
			wp_die( esc_html__( 'Backup not found or has expired.', 'footnotes-made-easy' ) );
		}

		$filename = basename( $filepath );
		header( 'Content-Type: application/json' );
		header( 'Content-Disposition: attachment; filename="' . esc_attr( $filename ) . '"' );
		header( 'Content-Length: ' . filesize( $filepath ) );
		header( 'Pragma: no-cache' );
		readfile( $filepath ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
		exit;
	}
}
