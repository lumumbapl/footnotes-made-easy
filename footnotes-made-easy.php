<?php
/*
 * Plugin Name:       Footnotes Made Easy
 * Plugin URI:        https://lumumbas-blog.co.ke/plugins/footnotes-made-easy/
 * Description:       Allows post authors to easily add and manage footnotes in posts.
 * Version:           3.2.0-beta.6
 * Requires at least: 4.6
 * Requires PHP:      7.4
 * Author:            Patrick Lumumba
 * Author URI:        https://lumumbas-blog.co.ke
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       footnotes-made-easy
*/

/**
* Footnotes Made Easy
*
* Easily add footnotes to a post
*
* @package	footnotes-made-easy
* @since	1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 * Enqueue plugin admin styles and scripts — only on our plugin pages.
 *
 * We match on the $_GET['page'] query var — the only value that is
 * guaranteed to be correct on every WP version and host, because it is
 * the raw slug we registered with add_menu_page / add_submenu_page.
 * Screen-ID and $hook string derivation has proven unreliable for
 * custom top-level menus across environments.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function fme_enqueue_styles( $hook ) {
    $fme_pages = array(
        'footnotes-made-easy',
        'footnotes-settings',
        'footnotes-help',
        'footnotes-tools',
    );

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- $_GET['page'] is a routing parameter, not form data.
    $current_page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

    if ( ! in_array( $current_page, $fme_pages, true ) ) {
        return;
    }

    $css_path = plugin_dir_path( __FILE__ ) . 'assets/css/admin-settings.css';
    $js_path  = plugin_dir_path( __FILE__ ) . 'assets/js/admin-settings.js';

    wp_enqueue_style(
        'fme-admin-styles',
        plugin_dir_url( __FILE__ ) . 'assets/css/admin-settings.css',
        array(),
        file_exists( $css_path ) ? filemtime( $css_path ) : '1.0'
    );

    wp_enqueue_script(
        'fme-admin-settings',
        plugin_dir_url( __FILE__ ) . 'assets/js/admin-settings.js',
        array(),
        file_exists( $js_path ) ? filemtime( $js_path ) : '1.0',
        true // load in footer
    );

    wp_localize_script( 'fme-admin-settings', 'fmeSettings', array(
        'tabs' => array(
            'display'   => array(
                'title' => esc_html__( 'Display settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Control how footnote identifiers, links, and back-links appear on the front end.', 'footnotes-made-easy' ),
            ),
            'behaviour' => array(
                'title' => esc_html__( 'Behaviour settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Configure how footnotes are processed and rendered by WordPress.', 'footnotes-made-easy' ),
            ),
            'suppress'  => array(
                'title' => esc_html__( 'Suppress settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Choose where on your site footnotes should not appear.', 'footnotes-made-easy' ),
            ),
            'advanced'  => array(
                'title' => esc_html__( 'Advanced settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Modify footnote delimiter tags — changes require updating all existing posts.', 'footnotes-made-easy' ),
            ),
            'about'     => array(
                'title' => esc_html__( 'About', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Plugin stats, version status, tutorials, and resources.', 'footnotes-made-easy' ),
            ),
        ),
        'postedTab'  => isset( $_POST['fme_active_tab'] ) ? sanitize_key( wp_unslash( $_POST['fme_active_tab'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Tab state only; nonce is verified in save_options().
    ) );
}
add_action( 'admin_enqueue_scripts', 'fme_enqueue_styles' );



// Instantiate the class
$swas_wp_footnotes = new swas_wp_footnotes(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

// Encapsulate in a class
class swas_wp_footnotes {

    // Declare the $styles property
    private $styles;

    private $current_options;
    private $default_options;

    const OPTIONS_VERSION = "5"; // Incremented when the options array changes

    // Constructor
    function __construct() {

        // Define the implemented option styles
        $this->styles = array(
            'decimal' => '1,2...10',
            'decimal-leading-zero' => '01, 02...10',
            'lower-alpha' => 'a,b...j',
            'upper-alpha' => 'A,B...J',
            'lower-roman' => 'i,ii...x',
            'upper-roman' => 'I,II...X',
            'symbol' => 'Symbol'
        );

        // Define default options
        $this->default_options = array(
            'superscript' => true,
            'pre_backlink' => ' [',
            'backlink' => '&#8617;',
            'post_backlink' => ']',
            'pre_identifier' => '',
            'inner_pre_identifier' => '',
            'list_style_type' => 'decimal',
            'list_style_symbol' => '&dagger;',
            'inner_post_identifier' => '',
            'post_identifier' => '',
            'pre_footnotes' => '',
            'post_footnotes' => '',
            'no_display_home' => false,
            'no_display_preview' => false,
            'no_display_archive' => false,
            'no_display_date' => false,
            'no_display_category' => false,
            'no_display_search' => false,
            'no_display_feed' => false,
            'combine_identical_notes' => true,
            'priority' => 11,
            'footnotes_open' => ' ((',
            'footnotes_close' => '))',
            'pretty_tooltips' => false,
            'exclude_urls' => '',
            'exclude_categories' => '',
            'version' => self::OPTIONS_VERSION
        );

        // Get the current settings or setup some defaults if needed
        $this->current_options = get_option( 'swas_footnote_options' );
        if ( ! $this->current_options ) {		
            $this->current_options = $this->default_options;
            update_option( 'swas_footnote_options', $this->current_options );
        } else {
            // Set any unset options
            if ( !isset( $this->current_options[ 'version' ] ) || $this->current_options[ 'version' ] !== self::OPTIONS_VERSION) {
                foreach ( $this->default_options as $key => $value ) {
                    if ( !isset( $this->current_options[ $key ] ) ) {
                        $this->current_options[ $key ] = $value;
                    }
                }
                $this->current_options[ 'version' ] = self::OPTIONS_VERSION;
                update_option( 'swas_footnote_options', $this->current_options );
            }
        }

        // SECURITY FIX: Move options processing to admin_init hook instead of constructor
        // This ensures it only runs in admin context with proper authentication
        add_action( 'admin_init', array( $this, 'save_options' ) );
        add_action( 'admin_post_fme_reset_settings',         array( $this, 'handle_reset_settings' ) );
        add_action( 'admin_post_fme_save_preserve_settings', array( $this, 'handle_preserve_settings' ) );
        add_action( 'admin_post_fme_export_settings',        array( $this, 'handle_export_settings' ) );
        add_action( 'admin_post_fme_import_settings',        array( $this, 'handle_import_settings' ) );

        // Hook me up
        add_action( 'the_content', array( $this, 'process' ), $this->current_options[ 'priority' ] );
        add_action( 'admin_menu', array( $this, 'add_options_page' ) ); 		// Insert the Admin panel.
        add_action( 'wp_head', array( $this, 'insert_styles' ) );
        if ( $this->current_options[ 'pretty_tooltips' ] ) add_action( 'wp_enqueue_scripts', array( $this, 'tooltip_scripts' ) );

        add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );
        add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), 10, 2 );

        add_filter( 'admin_footer_text', array( $this, 'remove_footer_text' ) );
        add_filter( 'update_footer', array( $this, 'remove_footer_version' ), 11 );
        add_action( 'admin_notices', array( $this, 'suppress_other_notices' ), 1 );


    }

    /**
     * Save Options - SECURITY FIX
     * 
     * Process and save plugin options with proper security checks
     * 
     * @since 3.0.8
     */
    function save_options() {
        // SECURITY FIX: Only process if all security requirements are met:
        // 1. User must be in admin area
        // 2. User must have manage_options capability
        // 3. Request must be POST with our specific save flags
        // 4. Nonce must be valid (CSRF protection)
        if ( ! is_admin() ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // SECURITY FIX: Check for save flags and verify nonce BEFORE accessing $_POST
        if ( empty( $_POST[ 'save_options' ] ) || empty( $_POST[ 'save_footnotes_made_easy_options' ] ) ) {
            return;
        }

        if ( ! check_admin_referer( 'footnotes-nonce', 'footnotes_nonce' ) ) {
            return;
        }

        // Now it's safe to access POST data
        $post_array = $_POST;

        // Now it's safe to process the options
        $footnotes_options = array();

        $footnotes_options[ 'superscript' ] = ( array_key_exists( 'superscript', $post_array ) ) ? true : false;
        $footnotes_options[ 'pre_backlink' ] = sanitize_text_field( $post_array[ 'pre_backlink' ] );
        $footnotes_options[ 'backlink' ] = sanitize_text_field( $post_array[ 'backlink' ] );
        $footnotes_options[ 'post_backlink' ] = sanitize_text_field( $post_array[ 'post_backlink' ] );
        $footnotes_options[ 'pre_identifier' ] = sanitize_text_field( $post_array[ 'pre_identifier' ] );
        $footnotes_options[ 'inner_pre_identifier' ] = sanitize_text_field( $post_array[ 'inner_pre_identifier' ] );
        $footnotes_options[ 'list_style_type' ] = sanitize_text_field( $post_array[ 'list_style_type' ] );
        $footnotes_options[ 'inner_post_identifier' ] = sanitize_text_field( $post_array[ 'inner_post_identifier' ] );
        $footnotes_options[ 'post_identifier' ] = sanitize_text_field( $post_array[ 'post_identifier' ] );
        $footnotes_options[ 'list_style_symbol' ] = sanitize_text_field( $post_array[ 'list_style_symbol' ] );
        
        // SECURITY FIX: Sanitize HTML content fields to prevent XSS
        $footnotes_options[ 'pre_footnotes' ] = wp_kses_post( $post_array[ 'pre_footnotes' ] );
        $footnotes_options[ 'post_footnotes' ] = wp_kses_post( $post_array[ 'post_footnotes' ] );
        
        $footnotes_options[ 'no_display_home' ] = ( array_key_exists( 'no_display_home', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_preview' ] = ( array_key_exists( 'no_display_preview', $post_array) ) ? true : false;
        $footnotes_options[ 'no_display_archive' ] = ( array_key_exists( 'no_display_archive', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_date' ] = ( array_key_exists( 'no_display_date', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_category' ] = ( array_key_exists( 'no_display_category', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_search' ] = ( array_key_exists( 'no_display_search', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_feed' ] = ( array_key_exists( 'no_display_feed', $post_array ) ) ? true : false;
        $footnotes_options[ 'combine_identical_notes' ] = ( array_key_exists( 'combine_identical_notes', $post_array ) ) ? true : false;
        $footnotes_options[ 'priority' ] = sanitize_text_field( $post_array[ 'priority' ] );
        $footnotes_options[ 'footnotes_open' ] = sanitize_text_field( $post_array[ 'footnotes_open' ] );
        $footnotes_options[ 'footnotes_close' ] = sanitize_text_field( $post_array[ 'footnotes_close' ] );
        $footnotes_options[ 'pretty_tooltips' ] = ( array_key_exists( 'pretty_tooltips', $post_array ) ) ? true : false;
        $footnotes_options[ 'exclude_urls' ] = sanitize_textarea_field( $post_array[ 'exclude_urls' ] ?? '' );
        $footnotes_options[ 'exclude_categories' ] = sanitize_textarea_field( $post_array[ 'exclude_categories' ] ?? '' );

        update_option( 'swas_footnote_options', $footnotes_options );
        $this->current_options = $footnotes_options;
    }
	
	/**
	 * Check if the current URL is excluded from footnote processing
	 *
	 * @since 3.2.0
	 *
	 * @return bool True if the current page should be excluded
	 */
	function is_excluded_url() {

		$exclude_urls = $this->current_options[ 'exclude_urls' ] ?? '';
		if ( empty( trim( $exclude_urls ) ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$current_path = isset( $_SERVER[ 'REQUEST_URI' ] ) ? parse_url( sanitize_url( wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) ), PHP_URL_PATH ) : '';
		$current_path = untrailingslashit( $current_path );
		if ( empty( $current_path ) ) {
			$current_path = '/';
		}

		$lines = explode( "\n", $exclude_urls );
		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( empty( $line ) ) {
				continue;
			}

			// If it looks like a full URL, extract the path component
			if ( false !== strpos( $line, '://' ) ) {
				$parsed = parse_url( $line, PHP_URL_PATH );
				$line = $parsed ? $parsed : $line;
			}

			// If no leading slash, treat as a bare slug
			if ( '/' !== substr( $line, 0, 1 ) ) {
				$line = '/' . $line;
			}

			$line = untrailingslashit( $line );

			if ( strpos( $current_path, $line ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the current post belongs to an excluded category
	 *
	 * @since 3.2.0
	 *
	 * @return bool True if the post's categories intersect the exclusion list
	 */
	function is_excluded_category() {

		$exclude_categories = $this->current_options[ 'exclude_categories' ] ?? '';
		if ( empty( trim( $exclude_categories ) ) ) {
			return false;
		}

		$lines = explode( "\n", $exclude_categories );
		$slugs = array();
		$ids   = array();

		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( empty( $line ) ) {
				continue;
			}
			if ( ctype_digit( $line ) ) {
				$ids[] = (int) $line;
			} else {
				$slugs[] = $line;
			}
		}

		if ( ! empty( $ids ) && has_category( $ids ) ) {
			return true;
		}

		if ( ! empty( $slugs ) && has_category( $slugs ) ) {
			return true;
		}

		return false;
	}
	
	/**
	* Searches the text and extracts footnotes
	*
	* Adds the identifier links and creats footnotes list
	*
	* @since	1.0
	*
	* @param	$data	string	The content of the post
	* @return			string 	The new content with footnotes generated
	*/

	function process( $data ) {

		global $post;
		
		// check against post existing before processing
		if( ! $post ) {
			return $data;
		}

		// Check for and setup the starting number

		$start_number = ( 1 === preg_match( "|<!\-\-startnum=(\d+)\-\->|", $data,$start_number_array ) ) ? $start_number_array[ 1 ] : 1;

		// Regex extraction of all footnotes (or return if there are none)

		if ( ! preg_match_all( "/(" . preg_quote( $this->current_options[ 'footnotes_open' ], "/" ) . ")(.*)(" . preg_quote( $this->current_options[ 'footnotes_close' ], "/" ) . ")/Us", $data, $identifiers, PREG_SET_ORDER ) ) {
			return $data;
		}

		// Check whether we are displaying them or not

		$display = true;
		if ( $this->current_options[ 'no_display_home' ] && is_home() ) $display = false;
		if ( $this->current_options[ 'no_display_archive' ] && is_archive() ) $display = false;
		if ( $this->current_options[ 'no_display_date' ] && is_date() ) $display = false;
		if ( $this->current_options[ 'no_display_category' ] && is_category() ) $display = false;
		if ( $this->current_options[ 'no_display_search' ] && is_search() ) $display = false;
		if ( $this->current_options[ 'no_display_feed' ] && is_feed() ) $display = false;
		if ( $this->current_options[ 'no_display_preview' ] && is_preview() ) $display = false;
		if ( $this->is_excluded_url() ) $display = false;
		if ( $this->is_excluded_category() ) $display = false;

		$footnotes = array();

		// Check if this post is using a different list style to the settings

		if ( get_post_meta( $post->ID, 'footnote_style', true ) && array_key_exists( get_post_meta( $post->ID, 'footnote_style', true ), $this->styles ) ) {
			$style = get_post_meta( $post->ID, 'footnote_style', true );
		} else {
			$style = $this->current_options[ 'list_style_type' ];
		}

		// Create 'em

		for ( $i = 0; $i < count( $identifiers ); $i++ ){

			// Look for ref: and replace in identifiers array

			if ( 'ref:' === substr( $identifiers[ $i ][ 2 ], 0, 4 ) ){
				$ref = ( int )substr( $identifiers[ $i ][ 2 ],4 );
				$identifiers[ $i ][ 'text' ] = $identifiers[ $ref-1 ][ 2 ];
			}else{
				$identifiers[ $i ][ 'text' ] = $identifiers[ $i ][ 2 ];
			}

			// if we're combining identical notes check if we've already got one like this & record keys

			if ( $this->current_options[ 'combine_identical_notes' ] ){
				for ( $j = 0; $j < count( $footnotes ); $j++ ){
					if ( $footnotes[ $j ][ 'text' ] === $identifiers[ $i ][ 'text' ] ){
						$identifiers[ $i ][ 'use_footnote' ] = $j;
						$footnotes[ $j ][ 'identifiers' ][] = $i;
						break;
					}
				}
			}

			if ( !isset( $identifiers[ $i ][ 'use_footnote' ] ) ){

				// Add footnote and record the key

				$identifiers[ $i ][ 'use_footnote' ] = count( $footnotes );
				$footnotes[ $identifiers[ $i ][ 'use_footnote' ] ][ 'text' ] = $identifiers[ $i ][ 'text' ];
				$footnotes[ $identifiers[ $i ][ 'use_footnote' ] ][ 'symbol' ] = ( $style === 'symbol' ) ? $this->convert_num( count( $footnotes ), $style, count( $identifiers ) ) : '';
				$footnotes[ $identifiers[ $i ][ 'use_footnote' ] ][ 'identifiers' ][] = $i;
			}
		}

		// Footnotes and identifiers are stored in the array

		$use_full_link = false;
		if ( is_feed() ) $use_full_link = true;

		if ( is_preview() ) $use_full_link = false;

		// Display identifiers

		foreach ( $identifiers as $key => $value ) {
			$id_id = "identifier_" . ( $key + 1 ) . "_" . $post->ID;
			$id_num = ( $style === 'decimal' ) ? $value[ 'use_footnote' ] + $start_number : $this->convert_num( $value[ 'use_footnote' ] + $start_number, $style, count( $footnotes ) );
			$id_href = ( ( $use_full_link ) ? get_permalink( $post->ID ) : '' ) . "#footnote_" . ( $value[ 'use_footnote' ] + $start_number ) . "_" . $post->ID;
			$id_title = str_replace( '"', "&quot;", htmlentities( html_entity_decode( wp_strip_all_tags( $value[ 'text' ] ), ENT_QUOTES, 'UTF-8' ), ENT_QUOTES, 'UTF-8' ) );
			$id_replace = esc_html( $this->current_options[ 'pre_identifier' ] ) . '<a href="' . esc_url( $id_href ) . '" id="' . esc_attr( $id_id ) . '" class="footnote-link footnote-identifier-link" title="' . esc_attr( $id_title ) . '">' . esc_html( $this->current_options[ 'inner_pre_identifier' ] ) . esc_html( (string) $id_num ) . esc_html( $this->current_options[ 'inner_post_identifier' ] ) . '</a>' . esc_html( $this->current_options[ 'post_identifier' ] );
			if ( $this->current_options[ 'superscript' ] ) $id_replace = '<sup>' . $id_replace . '</sup>';
			if ( $display ) $data = substr_replace( $data, $id_replace, strpos( $data,$value[ 0 ] ), strlen( $value[ 0 ] ) );
			else $data = substr_replace( $data, '', strpos( $data, $value[ 0 ] ), strlen( $value[ 0 ] ) );
		}

		// Display footnotes

		$start = ( $start_number !== 1 ) ? 'start="' . $start_number . '" ' : '';
		
		// SECURITY FIX: Escape output to prevent XSS
		$footnotes_markup = wp_kses_post( $this->current_options[ 'pre_footnotes' ] );
		
		$footnotes_markup = $footnotes_markup . '<ol ' . $start . 'class="footnotes">';

		foreach ( $footnotes as $key => $value ) {
			$footnotes_markup = $footnotes_markup . '<li id="footnote_' . ( $key + $start_number ) . '_' . $post->ID . '" class="footnote"';
			if ( 'symbol' === $style ) {
				$footnotes_markup = $footnotes_markup . ' value="' . esc_attr( $value[ 'symbol' ] ) . '"';
			}
			$footnotes_markup = $footnotes_markup . '>';
			if ( 'symbol' === $style ) {
				$footnotes_markup = $footnotes_markup . esc_html( $value[ 'symbol' ] ) . ' ';
			}
			$footnotes_markup = $footnotes_markup . wp_kses_post( $value[ 'text' ] );
			if ( ! is_feed() ) {
				foreach ( $value[ 'identifiers' ] as $identifier ) {
					$footnotes_markup = $footnotes_markup . '<span class="footnote-back-link-wrapper">' . esc_html( $this->current_options[ 'pre_backlink' ] ) . '<a href="' . esc_url( ( ( $use_full_link ) ? get_permalink( $post->ID ) : '' ) . '#identifier_' . ( $identifier + 1 ) . '_' . $post->ID ) . '" class="footnote-link footnote-back-link">' . esc_html( $this->current_options[ 'backlink' ] ) . '</a>' . esc_html( $this->current_options[ 'post_backlink' ] ) . '</span>';
				}
			}
			$footnotes_markup = $footnotes_markup . '</li>';
		}
		
		// SECURITY FIX: Escape output to prevent XSS
		$footnotes_markup = $footnotes_markup . '</ol>' . wp_kses_post( $this->current_options[ 'post_footnotes' ] );

		if ( $display ) {
			$data = $data . $footnotes_markup;
		}

		return $data;
	}

	/**
	* Plugion Meta Links
	*
	* Add links to plugin meta line
	*
	* @since	1.0
	*
	* @param	string  $links	Current links
	* @param	string  $file	File in use
	* @return   string			Links, now with settings added
	*/

	function plugin_meta( $links, $file ) {
		return $links;
	}

	/**
	* Add Settings Link
	*
	* Add a link to the options page from the plugins list
	*
	* @since	1.0
	*
	* @param	string  $links	Current links
	* @param	string  $file	File in use
	* @return   string			Links, now with settings added
	*/

	function add_settings_link( $links, $file ) {

		static $this_plugin;

		if ( empty( $this_plugin ) ) { $this_plugin = plugin_basename( __FILE__ ); }

		if ( $file === $this_plugin ) {
			$settings_link = '<a href="admin.php?page=footnotes-made-easy" style="font-weight: 700; color: #534AB7;">' . __( 'Settings', 'footnotes-made-easy' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		
		return $links;
	}

	/**
	* Options Page
	*
	* Get the options and display the page
	*
	* @since	1.0
	*/

	function footnotes_options_page() {

		$this->current_options = get_option( 'swas_footnote_options' );
		// Backfill any keys that may be missing from older saved option sets
		foreach ( $this->default_options as $key => $value ) {
			if ( ! array_key_exists( $key, $this->current_options ) ) {
				$this->current_options[ $key ] = $value;
			}
		}
		$new_setting = array();
		foreach ( $this->current_options as $key=>$setting ) {
			$new_setting[ $key ] = htmlentities( $setting );
		}
		$this->current_options = $new_setting;
		unset( $new_setting );
		include( dirname( __FILE__ ) . '/includes/settings.php' );
	}

	/**
	* Remove Help Tabs
	*
	* Removes the WP contextual help tab and screen options
	* from our settings page for a cleaner UI.
	*
	* @since 3.1.1
	*/
	function remove_help_tabs() {
		$screen = get_current_screen();
		if ( $screen ) {
			$screen->remove_help_tabs();
		}
	}


	/**
	* Add to Admin
	*
	* Add the options page to the admin menu
	*
	* @since	1.0
	*/

	function add_options_page() {

		global $footnotes_hook;

		// Standalone top-level menu item, positioned after Posts (Posts = 5)
		$footnotes_hook = add_menu_page(
			__( 'Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Footnotes', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-made-easy',
			array( $this, 'footnotes_dashboard_page' ),
			'dashicons-editor-ol',
			6 // just after Posts (5) and before Media (10)
		);

		// Dashboard submenu (mirrors the top-level entry)
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Dashboard — Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Dashboard', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-made-easy',
			array( $this, 'footnotes_dashboard_page' )
		);

		// Settings submenu
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Footnotes Settings — Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Footnotes Settings', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-settings',
			array( $this, 'footnotes_options_page' )
		);

		add_action( 'load-' . $footnotes_hook, array( $this, 'remove_help_tabs' ) );

		// Tools and Help register at priority 30 so Pro (priority 20) inserts Library between Settings and Tools
		add_action( 'admin_menu', array( $this, 'add_secondary_menu_pages' ), 30 );
	}

	function add_secondary_menu_pages() {
		// Tools submenu
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Tools — Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Tools', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-tools',
			array( $this, 'footnotes_tools_page' )
		);

		// Help submenu
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Help — Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Help', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-help',
			array( $this, 'footnotes_help_page' )
		);

	}

	/**
	* Dashboard Page
	*
	* Renders the Dashboard subpage (About tab content)
	*
	* @since 3.2.0
	*/
	function footnotes_dashboard_page() {
		$this->current_options = get_option( 'swas_footnote_options' );
		foreach ( $this->default_options as $key => $value ) {
			if ( ! array_key_exists( $key, $this->current_options ) ) {
				$this->current_options[ $key ] = $value;
			}
		}
		$new_setting = array();
		foreach ( $this->current_options as $key => $setting ) {
			$new_setting[ $key ] = htmlentities( $setting );
		}
		$this->current_options = $new_setting;
		unset( $new_setting );
		include( dirname( __FILE__ ) . '/includes/dashboard.php' );
	}

	/**
	* Help Page
	*
	* Renders the Help subpage
	*
	* @since 3.2.0
	*/
	function footnotes_help_page() {
		include( dirname( __FILE__ ) . '/includes/help.php' );
	}

	function footnotes_tools_page() {
		include( dirname( __FILE__ ) . '/includes/tools.php' );
	}

	/**
	* Insert additional CSS
	*
	* Add additional CSS to the page for the footnotes styling
	*
	* @since	1.0
	*/

	function insert_styles(){
		$css = "ol.footnotes { color:#666666; }\nol.footnotes li { font-size:80%; }\n";
		if ( 'symbol' !== $this->current_options[ 'list_style_type' ] ) {
			$css .= 'ol.footnotes>li {list-style-type:' . esc_attr( $this->current_options[ 'list_style_type' ] ) . ';}';
		}
		wp_add_inline_style( 'wp-block-library', $css );
	}

	/**
	* Convert number
	*
	* Convert number to a specific style
	*
	* @since	1.0
	*
	* @param	$num	string	The number to be converted
	* @param	$style	string	The style of output required
	* @param	$total	string	The total length
	* @return			string 	The converted number
	*/

	function convert_num ( $num, $style, $total ){

		switch ( $style ) {
			case 'decimal-leading-zero' :
				$width = max( 2, strlen( $total ) );
				return sprintf( "%0{$width}d", $num );
			case 'lower-roman' :
				return $this->roman( $num, 'lower' );
			case 'upper-roman' :
				return $this->roman( $num );
			case 'lower-alpha' :
				return $this->alpha( $num, 'lower' );
			case 'upper-alpha' :
				return $this->alpha( $num );
			case 'symbol' :
				$sym = '';
				for ( $i = 0; $i < $num; $i++ ) {
					$sym .= $this->current_options[ 'list_style_symbol' ];
				}
				return $sym;
		}
	}

	/**
	* Convert to a roman numeral
	*
	* Convert a provided number into a roman numeral
	*
	* @since	1.0
	*
	* @param	int		$num	The number to convert.
	* @param	string	$case	Upper or lower case.
	* @return	string			The roman numeral
	*/

	function roman( $num, $case= 'upper' ){

		$num = ( int ) $num;
		$conversion = array(
							'M' => 1000,
							'CM' => 900,
							'D' => 500,
							'CD' => 400,
							'C' => 100,
							'XC' => 90,
							'L' => 50,
							'XL' => 40,
							'X' => 10,
							'IX' => 9,
							'V' => 5,
							'IV' => 4,
							'I' => 1
							);
		$roman = '';

		foreach ( $conversion as $r => $d ){
			$roman .= str_repeat( $r, ( int )( $num / $d ) );
			$num %= $d;
		}

		return ( $case === 'lower' ) ? strtolower( $roman ) : $roman;
	}

	function alpha( $num, $case='upper' ){
		$j = 1;
		for ( $i = 'A'; $i <= 'ZZ'; $i++ ){
			if ( $j === $num ){
				if ( 'lower' === $case )
					return strtolower( $i );
				else
					return $i;
			}
			$j++;
		}

	}

	/**
	* Tooltip Scripts
	*
	* Add scripts and CSS for pretty tooltips
	*
	* @since	1.0
	*/

	function tooltip_scripts() {

		wp_enqueue_script(
							'wp-footnotes-tooltips',
							plugins_url( 'assets/js/tooltips.min.js' , __FILE__ ),
							array(
									'jquery',
									'jquery-ui-widget',
									'jquery-ui-tooltip',
									'jquery-ui-core',
									'jquery-ui-position'
								),
							'3.0.8',
							true
							);

		wp_enqueue_style( 'wp-footnotes-tt-style', plugins_url( 'assets/css/tooltips.min.css' , __FILE__ ), array(), '3.0.8' );
	}

	/**
	 * Handle export settings.
	 * Outputs a JSON file download containing all plugin settings.
	 * Pro settings are included if Pro is active and license is valid.
	 */
	function handle_export_settings() {
		check_admin_referer( 'fme_export_settings_nonce', 'fme_export_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}

		$data = array(
			'plugin'   => 'footnotes-made-easy',
			'version'  => self::OPTIONS_VERSION,
			'exported' => gmdate( 'Y-m-d H:i:s' ),
			'settings' => array(
				'free' => get_option( 'swas_footnote_options', array() ),
			),
		);

		// Include Pro settings if Pro is active and licensed
		if ( defined( 'FME_PRO_VERSION' ) && class_exists( 'FME_Pro_License' ) && FME_Pro_License::is_active() ) {
			$data['settings']['pro'] = array(
				'fme_pro_citation_style'            => get_option( 'fme_pro_citation_style', 'apa' ),
				'fme_preserve_settings_on_uninstall' => get_option( 'fme_preserve_settings_on_uninstall', '0' ),
			);
		}

		$filename = 'footnotes-made-easy-settings-' . gmdate( 'Y-m-d' ) . '.json';
		$json     = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . strlen( $json ) );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Handle import settings.
	 * Reads a JSON file and writes known option keys to the database.
	 */
	function handle_import_settings() {
		check_admin_referer( 'fme_import_settings_nonce', 'fme_import_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}

		$redirect_base = admin_url( 'admin.php?page=footnotes-tools' );

		// Check file was uploaded
		if ( empty( $_FILES['fme_import_file']['tmp_name'] ) ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'No file uploaded.', 'footnotes-made-easy' ) ) );
			exit;
		}

		$file = $_FILES['fme_import_file'];

		// Validate file type
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		if ( $ext !== 'json' ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'Invalid file type. Please upload a .json file.', 'footnotes-made-easy' ) ) );
			exit;
		}

		// Read and decode
		$raw  = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$data = json_decode( $raw, true );

		// Validate structure
		if ( ! $data || ( $data['plugin'] ?? '' ) !== 'footnotes-made-easy' ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'Invalid settings file. This file was not exported from Footnotes Made Easy.', 'footnotes-made-easy' ) ) );
			exit;
		}

		$imported = 0;

		// Import free settings — only write known keys
		if ( ! empty( $data['settings']['free'] ) && is_array( $data['settings']['free'] ) ) {
			$allowed_keys = array_keys( $this->default_options );
			$current      = get_option( 'swas_footnote_options', array() );
			foreach ( $allowed_keys as $key ) {
				if ( isset( $data['settings']['free'][ $key ] ) ) {
					$current[ $key ] = $data['settings']['free'][ $key ];
				}
			}
			update_option( 'swas_footnote_options', $current );
			$imported++;
		}

		// Import Pro settings — only if Pro is active and licensed
		if ( ! empty( $data['settings']['pro'] ) ) {
			if (
				defined( 'FME_PRO_VERSION' ) &&
				class_exists( 'FME_Pro_License' ) &&
				FME_Pro_License::is_active()
			) {
				$pro         = $data['settings']['pro'];
				$allowed_pro = array( 'fme_pro_citation_style', 'fme_preserve_settings_on_uninstall' );
				foreach ( $allowed_pro as $key ) {
					if ( isset( $pro[ $key ] ) ) {
						update_option( $key, sanitize_text_field( $pro[ $key ] ) );
					}
				}
				$imported++;
			} else {
				// Pro settings present in file but Pro not active — warn the user
				wp_safe_redirect( $redirect_base . '&import=partial&import_message=' . urlencode( __( 'Free settings imported successfully. The file also contains Pro settings which were skipped — activate Footnotes Made Easy Pro to import them.', 'footnotes-made-easy' ) ) );
				exit;
			}
		}

		if ( $imported === 0 ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'No settings were found in the file.', 'footnotes-made-easy' ) ) );
			exit;
		}

		wp_safe_redirect( $redirect_base . '&import=success' );
		exit;
	}

	/**
	 * Handle reset settings form submission.
	 * Replaces all options with plugin defaults.
	 */
	function handle_reset_settings() {
		check_admin_referer( 'fme_reset_settings_nonce', 'fme_reset_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}
		update_option( 'swas_footnote_options', $this->default_options );
		// Also reset Pro citation style if Pro is active
		if ( defined( 'FME_PRO_VERSION' ) ) {
			delete_option( 'fme_pro_citation_style' );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=footnotes-tools&reset=1' ) );
		exit;
	}

	/**
	 * Handle preserve settings toggle form submission.
	 */
	function handle_preserve_settings() {
		check_admin_referer( 'fme_preserve_settings_nonce', 'fme_preserve_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}
		$preserve = isset( $_POST['fme_preserve_settings'] ) ? '1' : '0';
		update_option( 'fme_preserve_settings_on_uninstall', $preserve );
		wp_safe_redirect( admin_url( 'admin.php?page=footnotes-tools&preserve_saved=1' ) );
		exit;
	}

	/**
	* Remove Footer Text
	*
	* Removes the default WordPress admin footer text on the plugin's settings page
	*
	* @since	3.1.4
	*
	* @param	string	$footer_text	The existing footer text
	* @return	string					Empty string on our settings page, unchanged elsewhere
	*/

	function remove_footer_text( $footer_text ) {
		if ( $this->is_our_page() ) {
			return '';
		}
		return $footer_text;
	}

	/**
	* Remove Footer Version
	*
	* Removes the default WordPress version text on the plugin's settings page
	*
	* @since	3.1.4
	*
	* @param	string	$footer_version	The existing footer version text
	* @return	string					Empty string on our settings page, unchanged elsewhere
	*/

	function remove_footer_version( $footer_version ) {
		if ( $this->is_our_page() ) {
			return '';
		}
		return $footer_version;
	}

	/**
	 * Suppress admin notices from other plugins on our pages.
	 * Fires on admin_notices before other plugins output their notices.
	 */
	function suppress_other_notices() {
		if ( ! $this->is_our_page() ) {
			return;
		}
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'network_admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}

	/**
	 * Returns true when the current request is one of our admin pages.
	 * Uses $_GET['page'] which is always reliable on admin pages.
	 */
	private function is_our_page() {
		if ( ! is_admin() ) {
			return false;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$our_pages = array(
			'footnotes-made-easy',
			'footnotes-settings',
			'footnotes-help',
			'footnotes-tools',
			'fme-pro-library',
			'fme-pro-license',
		);
		return in_array( $page, $our_pages, true );
	}

}