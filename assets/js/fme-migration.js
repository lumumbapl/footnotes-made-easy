/**
 * FME Migration Tool — Admin JS
 *
 * Handles the Migrate Footnotes UI on the Tools page:
 * - Mode tab switching
 * - Source format selector (show/hide custom inputs)
 * - Dry run scan
 * - Backup creation and download
 * - Batch migration with progress bar
 * - Rollback
 *
 * @package footnotes-made-easy
 * @since   3.3.0
 */
( function () {
	'use strict';

	// ── DOM refs ──────────────────────────────────────────────────────────────

	var root            = document.getElementById( 'fme-migration-tool' );
	if ( ! root ) return;

	var modeTabBtns     = root.querySelectorAll( '.fme-migration-tab' );
	var modePanels      = root.querySelectorAll( '.fme-migration-mode' );
	var sourceSelect    = root.getElementById ? root.getElementById( 'fme-migration-source' ) : document.getElementById( 'fme-migration-source' );
	var customWrap      = document.getElementById( 'fme-migration-custom-wrap' );
	var scanBtn         = document.getElementById( 'fme-migration-scan' );
	var scanResult      = document.getElementById( 'fme-migration-scan-result' );
	var runBtn          = document.getElementById( 'fme-migration-run' );
	var progressWrap    = document.getElementById( 'fme-migration-progress' );
	var progressBar     = document.getElementById( 'fme-migration-bar' );
	var progressLabel   = document.getElementById( 'fme-migration-progress-label' );
	var completionWrap  = document.getElementById( 'fme-migration-completion' );
	var rollbackSection = document.getElementById( 'fme-migration-rollback' );
	var rollbackBtn     = document.getElementById( 'fme-migration-rollback-btn' );
	var downloadBtn     = document.getElementById( 'fme-migration-download-btn' );

	// State
	var state = {
		mode       : 'change',   // 'change' | 'migrate'
		backupKey  : '',
		dryRunDone : false,
		totalPosts : 0,
	};

	// ── Helpers ───────────────────────────────────────────────────────────────

	function post( action, data, done, fail ) {
		var body = new FormData();
		body.append( 'action', action );
		body.append( 'nonce',  fmeMigration.nonce );
		Object.keys( data ).forEach( function ( k ) { body.append( k, data[ k ] ); } );

		fetch( fmeMigration.ajaxUrl, { method: 'POST', body: body } )
			.then( function ( r ) { return r.json(); } )
			.then( function ( r ) {
				if ( r.success ) { done( r.data ); } else { fail( r.data || fmeMigration.i18n.error ); }
			} )
			.catch( function () { fail( fmeMigration.i18n.error ); } );
	}

	function setProgress( pct, label ) {
		progressBar.style.width   = pct + '%';
		progressBar.textContent   = pct + '%';
		progressLabel.textContent = label;
	}

	function showSection( el ) { el.style.display = ''; }
	function hideSection( el ) { el.style.display = 'none'; }

	function getSourceDelimiters() {
		var sourceVal = document.getElementById( 'fme-migration-source' ).value;
		if ( sourceVal === 'custom' ) {
			return {
				open  : document.getElementById( 'fme-migration-custom-open' ).value,
				close : document.getElementById( 'fme-migration-custom-close' ).value,
			};
		}
		var opt = document.getElementById( 'fme-migration-source' ).options;
		for ( var i = 0; i < opt.length; i++ ) {
			if ( opt[ i ].value === sourceVal ) {
				return {
					open  : opt[ i ].dataset.open,
					close : opt[ i ].dataset.close,
				};
			}
		}
		return { open: '', close: '' };
	}

	function getDelimiters() {
		if ( state.mode === 'change' ) {
			return {
				src_open  : document.getElementById( 'fme-migration-old-open' ).value,
				src_close : document.getElementById( 'fme-migration-old-close' ).value,
				tgt_open  : fmeMigration.currentOpen,
				tgt_close : fmeMigration.currentClose,
			};
		}
		var src = getSourceDelimiters();
		return {
			src_open  : src.open,
			src_close : src.close,
			tgt_open  : fmeMigration.currentOpen,
			tgt_close : fmeMigration.currentClose,
		};
	}

	// ── Mode tabs ─────────────────────────────────────────────────────────────

	modeTabBtns.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			state.mode = btn.dataset.mode;
			modeTabBtns.forEach( function ( b ) { b.classList.toggle( 'fme-migration-tab--active', b === btn ); } );
			modePanels.forEach( function ( p ) { p.style.display = p.dataset.mode === state.mode ? '' : 'none'; } );
			resetUI();
		} );
	} );

	// ── Source selector ───────────────────────────────────────────────────────

	document.getElementById( 'fme-migration-source' ).addEventListener( 'change', function () {
		customWrap.style.display = this.value === 'custom' ? '' : 'none';
		resetUI();
	} );

	// ── Scan (dry run) ────────────────────────────────────────────────────────

	scanBtn.addEventListener( 'click', function () {
		var delims = getDelimiters();
		scanBtn.disabled     = true;
		scanBtn.textContent  = fmeMigration.i18n.scanning;
		scanResult.innerHTML = '';

		post( 'fme_migration_scan', {
			src_open  : delims.src_open,
			src_close : delims.src_close,
		}, function ( data ) {
			scanBtn.disabled    = false;
			scanBtn.textContent = fmeMigration.i18n.scan;

			if ( 0 === data.matched_posts ) {
				scanResult.innerHTML = '<p class="fme-migration-notice fme-migration-notice--info">' +
					fmeMigration.i18n.noMatches + '</p>';
				runBtn.disabled = true;
				return;
			}

			state.dryRunDone = true;
			state.totalPosts = data.found_posts;

			var html = '<div class="fme-migration-summary">' +
				'<span>' + fmeMigration.i18n.postsFound.replace( '%d', data.matched_posts ) + '</span>' +
				'<span>' + fmeMigration.i18n.instancesFound.replace( '%d', data.total_instances ) + '</span>' +
				'</div>';

			if ( data.samples.length ) {
				html += '<p class="fme-migration-samples-label">' + fmeMigration.i18n.samples + '</p><ul class="fme-migration-samples">';
				data.samples.forEach( function ( s ) {
					html += '<li><strong>' + fmeMigration.escHtml( s.post_title ) + '</strong> &mdash; ' +
						fmeMigration.i18n.footnoteCount.replace( '%d', s.count ) + '<br>' +
						'<span class="fme-migration-excerpt">' + fmeMigration.escHtml( s.excerpt ) + '</span></li>';
				} );
				html += '</ul>';
			}

			scanResult.innerHTML = html;
			runBtn.disabled = false;

		}, function ( err ) {
			scanBtn.disabled    = false;
			scanBtn.textContent = fmeMigration.i18n.scan;
			scanResult.innerHTML = '<p class="fme-migration-notice fme-migration-notice--error">' + err + '</p>';
		} );
	} );

	// ── Run migration ─────────────────────────────────────────────────────────

	runBtn.addEventListener( 'click', function () {
		if ( ! state.dryRunDone ) return;

		runBtn.disabled  = true;
		scanBtn.disabled = true;

		var delims = getDelimiters();

		// Step 1: create backup
		scanResult.innerHTML = '<p class="fme-migration-notice fme-migration-notice--info">' + fmeMigration.i18n.creatingBackup + '</p>';

		post( 'fme_migration_backup', {
			src_open  : delims.src_open,
			src_close : delims.src_close,
		}, function ( data ) {
			state.backupKey = data.key;

			// Enable download
			downloadBtn.href = fmeMigration.ajaxUrl +
				'?action=fme_migration_download&key=' + encodeURIComponent( data.key ) +
				'&nonce=' + encodeURIComponent( fmeMigration.nonce );
			showSection( document.getElementById( 'fme-migration-download-wrap' ) );

			// Step 2: run batches
			showSection( progressWrap );
			setProgress( 0, fmeMigration.i18n.starting );
			runBatch( delims, 0, 0, 0, 0, 0 );

		}, function ( err ) {
			scanResult.innerHTML = '<p class="fme-migration-notice fme-migration-notice--error">' + err + '</p>';
			runBtn.disabled  = false;
			scanBtn.disabled = false;
		} );
	} );

	function runBatch( delims, offset, totalProcessed, totalUpdated, totalInstances, totalErrors ) {
		post( 'fme_migration_batch', {
			src_open  : delims.src_open,
			src_close : delims.src_close,
			tgt_open  : delims.tgt_open,
			tgt_close : delims.tgt_close,
			offset    : offset,
		}, function ( data ) {
			var processed  = totalProcessed + data.processed;
			var updated    = totalUpdated   + data.updated;
			var instances  = totalInstances + data.instances;
			var errors     = totalErrors    + data.errors;
			var foundPosts = data.found_posts || state.totalPosts;
			var pct        = foundPosts ? Math.min( 100, Math.round( ( offset + data.processed ) / foundPosts * 100 ) ) : 100;

			setProgress( pct, fmeMigration.i18n.processing
				.replace( '%1$d', offset + data.processed )
				.replace( '%2$d', foundPosts ) );

			if ( data.has_more ) {
				runBatch( delims, offset + data.processed, processed, updated, instances, errors );
			} else {
				setProgress( 100, fmeMigration.i18n.done );
				showCompletion( updated, instances, errors );
			}
		}, function ( err ) {
			hideSection( progressWrap );
			scanResult.innerHTML = '<p class="fme-migration-notice fme-migration-notice--error">' + err + '</p>';
			runBtn.disabled  = false;
			scanBtn.disabled = false;
		} );
	}

	function showCompletion( updated, instances, errors ) {
		hideSection( progressWrap );
		var html = '<p class="fme-migration-notice fme-migration-notice--success">' +
			fmeMigration.i18n.complete
				.replace( '%1$d', updated )
				.replace( '%2$d', instances ) + '</p>';
		if ( errors ) {
			html += '<p class="fme-migration-notice fme-migration-notice--error">' +
				fmeMigration.i18n.errors.replace( '%d', errors ) + '</p>';
		}
		completionWrap.innerHTML = html;
		showSection( completionWrap );
		if ( state.backupKey ) {
			showSection( rollbackSection );
		}
	}

	// ── Rollback ──────────────────────────────────────────────────────────────

	rollbackBtn.addEventListener( 'click', function () {
		if ( ! state.backupKey ) return;
		if ( ! window.confirm( fmeMigration.i18n.rollbackConfirm ) ) return;

		rollbackBtn.disabled    = true;
		rollbackBtn.textContent = fmeMigration.i18n.rollingBack;

		post( 'fme_migration_rollback', {
			key: state.backupKey,
		}, function ( data ) {
			rollbackSection.innerHTML = '<p class="fme-migration-notice fme-migration-notice--success">' +
				fmeMigration.i18n.rollbackDone.replace( '%d', data.restored ) + '</p>';
			hideSection( completionWrap );
			state.backupKey = '';
		}, function ( err ) {
			rollbackBtn.disabled    = false;
			rollbackBtn.textContent = fmeMigration.i18n.rollback;
			rollbackSection.insertAdjacentHTML( 'beforeend',
				'<p class="fme-migration-notice fme-migration-notice--error">' + err + '</p>' );
		} );
	} );

	// ── Reset UI ──────────────────────────────────────────────────────────────

	function resetUI() {
		state.dryRunDone = false;
		state.backupKey  = '';
		scanResult.innerHTML     = '';
		completionWrap.innerHTML = '';
		runBtn.disabled          = true;
		hideSection( progressWrap );
		hideSection( completionWrap );
		hideSection( rollbackSection );
		hideSection( document.getElementById( 'fme-migration-download-wrap' ) );
	}

} )();

// Minimal escHtml helper attached to the localization object
if ( typeof fmeMigration !== 'undefined' ) {
	fmeMigration.escHtml = function ( str ) {
		return String( str )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' );
	};
}
