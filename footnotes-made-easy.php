<?php
/*
 * Plugin Name:       Footnotes Made Easy
 * Plugin URI:        https://lumumbas.blog/plugins/footnotes-made-easy/
 * Description:       Allows post authors to easily add and manage footnotes in posts.
 * Version:           4.0.0-beta.3
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Patrick Lumumba
 * Author URI:        https://lumumbas.blog
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       footnotes-made-easy
 * Domain Path:       /languages
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
 * MailerLite integration handler
 * This must be included before admin_enqueue_scripts hook
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/mailerlite-handler.php';

/**
 * Enqueue plugin styles
 * UPDATED: Changed path from 'css/options.css' to 'assets/css/options.css'
 */
function fme_enqueue_styles() {
    wp_enqueue_style( 
        'options-styles', 
        plugin_dir_url( __FILE__ ) . 'assets/css/options.css', 
        array(), 
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/options.css' ) 
    );
}
add_action( 'admin_enqueue_scripts', 'fme_enqueue_styles' );

/**
 * Custom admin footer text
 * 
 * @since 4.0.0
 */
function fme_custom_admin_footer( $text ) {
    $screen = get_current_screen();
    
    // Only show on the Footnotes Made Easy settings page
    if ( isset( $screen->id ) && strpos( $screen->id, 'footnotes-options-page' ) !== false ) {
        $rating_url = 'https://wordpress.org/support/plugin/footnotes-made-easy/reviews/?filter=5#new-post';
        $text = sprintf(
            __( 'If you like Footnotes Made Easy please leave us a %s rating. A huge thanks in advance!', 'footnotes-made-easy' ),
            '<a href="' . esc_url( $rating_url ) . '" target="_blank" rel="noopener noreferrer">★★★★★</a>'
        );
    }
    
    return $text;
}
add_filter( 'admin_footer_text', 'fme_custom_admin_footer' );

/**
 * Custom admin footer version
 * 
 * @since 4.0.0
 */
function fme_custom_admin_footer_version( $text ) {
    $screen = get_current_screen();
    
    // Only show on the Footnotes Made Easy settings page
    if ( isset( $screen->id ) && strpos( $screen->id, 'footnotes-options-page' ) !== false ) {
        $plugin_data = get_plugin_data( __FILE__ );
        $text = sprintf(
            __( 'Version %s', 'footnotes-made-easy' ),
            $plugin_data['Version']
        );
    }
    
    return $text;
}
add_filter( 'update_footer', 'fme_custom_admin_footer_version', 11 );

// Instantiate the class
$swas_wp_footnotes = new swas_wp_footnotes();

// Encapsulate in a class
class swas_wp_footnotes {

    // Declare the $styles property
    private $styles;

    private $current_options;
    private $default_options;

    const OPTIONS_VERSION = "6"; // Incremented when the options array changes

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
            'no_display_urls' => '',
            'combine_identical_notes' => true,
            'priority' => 11,
            'footnotes_open' => ' ((',
            'footnotes_close' => '))',
            'pretty_tooltips' => false,
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

        $footnotes_options = array();
        $post_array = $_POST;

        if ( !empty( $post_array[ 'save_options' ] ) && !empty( $post_array[ 'save_footnotes_made_easy_options' ] ) ) {
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
            $footnotes_options[ 'pre_footnotes' ] = $post_array[ 'pre_footnotes' ];
            $footnotes_options[ 'post_footnotes' ] = $post_array[ 'post_footnotes' ];
            $footnotes_options[ 'no_display_home' ] = ( array_key_exists( 'no_display_home', $post_array ) ) ? true : false;
            $footnotes_options[ 'no_display_preview' ] = ( array_key_exists( 'no_display_preview', $post_array) ) ? true : false;
            $footnotes_options[ 'no_display_archive' ] = ( array_key_exists( 'no_display_archive', $post_array ) ) ? true : false;
            $footnotes_options[ 'no_display_date' ] = ( array_key_exists( 'no_display_date', $post_array ) ) ? true : false;
            $footnotes_options[ 'no_display_category' ] = ( array_key_exists( 'no_display_category', $post_array ) ) ? true : false;
            $footnotes_options[ 'no_display_search' ] = ( array_key_exists( 'no_display_search', $post_array ) ) ? true : false;
            $footnotes_options[ 'no_display_feed' ] = ( array_key_exists( 'no_display_feed', $post_array ) ) ? true : false;
            $footnotes_options[ 'no_display_urls' ] = sanitize_textarea_field( $post_array[ 'no_display_urls' ] );
            $footnotes_options[ 'combine_identical_notes' ] = ( array_key_exists( 'combine_identical_notes', $post_array ) ) ? true : false;
            $footnotes_options[ 'priority' ] = sanitize_text_field( $post_array[ 'priority' ] );
            $footnotes_options[ 'footnotes_open' ] = sanitize_text_field( $post_array[ 'footnotes_open' ] );
            $footnotes_options[ 'footnotes_close' ] = sanitize_text_field( $post_array[ 'footnotes_close' ] );
            $footnotes_options[ 'pretty_tooltips' ] = ( array_key_exists( 'pretty_tooltips', $post_array ) ) ? true : false;

            update_option( 'swas_footnote_options', $footnotes_options );
            $this->current_options = $footnotes_options;
        }

        // Hook me up
        add_action( 'the_content', array( $this, 'process' ), $this->current_options[ 'priority' ] );
        add_action( 'admin_menu', array( $this, 'add_options_page' ) ); 		// Insert the Admin panel.
        add_action( 'wp_head', array( $this, 'insert_styles' ) );
        if ( $this->current_options[ 'pretty_tooltips' ] ) add_action( 'wp_enqueue_scripts', array( $this, 'tooltip_scripts' ) );

        add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );
        add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), 10, 2 );	
    }
	
	/**
	* Checks if the current URL is in the exclusion list
	*
	* @since	4.0.0
	*
	* @return	bool	True if current URL should be excluded
	*/
	private function is_url_excluded() {
		
		if ( empty( $this->current_options[ 'no_display_urls' ] ) ) {
			return false;
		}
		
		// Get current URL
		$current_url = home_url( $_SERVER['REQUEST_URI'] );
		
		// Parse the exclusion URLs (one per line)
		$excluded_urls = array_filter( array_map( 'trim', explode( "\n", $this->current_options[ 'no_display_urls' ] ) ) );
		
		foreach ( $excluded_urls as $excluded_url ) {
			// Support both full URLs and relative paths
			if ( strpos( $excluded_url, 'http' ) === 0 ) {
				// Full URL comparison
				if ( $current_url === $excluded_url ) {
					return true;
				}
			} else {
				// Relative path comparison
				$current_path = parse_url( $current_url, PHP_URL_PATH );
				if ( $current_path === $excluded_url || $current_path === '/' . ltrim( $excluded_url, '/' ) ) {
					return true;
				}
			}
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

		// Ensure post exists

		if ( !$post ) {
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
		if ( $this->is_url_excluded() ) $display = false;

		$footnotes = array();

		// Check for combine identical notes

		if ( $this->current_options[ 'combine_identical_notes' ] ) {

			$used_identifiers = array();

			for ( $iter = 0; $iter < count( $identifiers ); $iter++ ) {
				$identifier = $identifiers[ $iter ][ 2 ];
				if ( !array_key_exists( $identifier, $used_identifiers ) ) {
					$used_identifiers[ $identifier ] = $iter + $start_number;
				}
			}
		}

		// Create the footnotes and setup the links

		$num = $start_number;

		for ( $iter = 0; $iter < count( $identifiers ); $iter++ ) {
			$identifier = $identifiers[ $iter ][ 2 ];

			// if we're combining identical notes then check if this has already been added

			if ( $this->current_options[ 'combine_identical_notes' ] && array_key_exists( $identifier, $used_identifiers ) && $used_identifiers[ $identifier ] !== $num ) {
				$identifier_to_use = $used_identifiers[ $identifier ];
			} else {
				$identifier_to_use = $num;
				$footnotes[ $num ] = $identifier;
				$num++;
			}

			// Replace the link in the post

			$link = "";

			if ( $display ) {

				$id_id = "fn-" . $post->ID . "-" . $identifier_to_use;
				$id_num = "fnref-" . $post->ID . "-" . $identifier_to_use;
				$id_part = ' id="' . $id_num . '"';

				$link = $this->current_options[ 'pre_identifier' ];
				if ( 0 !== strlen( $link ) ) $link = '<sup>' . $link . '</sup>';

				$link .= '<sup' . $id_part . '>';
				$link .= $this->current_options[ 'inner_pre_identifier' ];
				$link .= '<a href="#' . $id_id . '">';

				if ( 'symbol' === $this->current_options[ 'list_style_type' ] ) {
					$link .= $this->convert_num( $identifier_to_use, $this->current_options[ 'list_style_type' ], count( $identifiers ) );
				} else {
					$link .= $identifier_to_use;
				}

				$link .= '</a>';
				$link .= $this->current_options[ 'inner_post_identifier' ] . '</sup>';

				$link .= $this->current_options[ 'post_identifier' ];
				if ( 0 !== strlen( $this->current_options[ 'post_identifier' ] ) ) $link = $link . '</sup>';

			}

			$data = substr_replace( $data, $link, strpos( $data, $identifiers[ $iter ][ 0 ] ), strlen( $identifiers[ $iter ][ 0 ] ) );

		}

		// Now add the footnotes to the bottom

		if ( $display && count( $footnotes ) !== 0 ) {

			$start = ( $start_number !== 1 ) ? 'start="' . $start_number . '" ' : '';
			$data = $data . $this->current_options[ 'pre_footnotes' ];

			if ( 'symbol' === $this->current_options[ 'list_style_type' ] ) {
				$data = $data . '<ul class="footnotes">';
			} else {
				$data = $data . '<ol ' . $start . 'class="footnotes">';
			}

			foreach ( $footnotes as $num => $note ) {
				$id_id = "fn-" . $post->ID . "-" . $num;
				$id_num = "fnref-" . $post->ID . "-" . $num;
				$data = $data . '<li id="' . $id_id . '">' . $note . $this->current_options[ 'pre_backlink' ] . '<a href="#' . $id_num . '">' . $this->current_options[ 'backlink' ] . '</a>' . $this->current_options[ 'post_backlink' ] . '</li>';
			}

			if ( 'symbol' === $this->current_options[ 'list_style_type' ] ) {
				$data = $data . '</ul>';
			} else {
				$data = $data . '</ol>';
			}

			$data = $data . $this->current_options[ 'post_footnotes' ];
		}

		return $data;
	}

	/**
	* Add Settings Link
	*
	* Add a Settings link to the plugin list
	*
	* @since	1.0
	*
	* @param	string  $links	Current links
	* @param	string  $file	File in use
	* @return	string			Links, now with settings added
	*/

	function add_settings_link( $links, $file ) {

		static $this_plugin;

		if ( empty( $this_plugin ) ) $this_plugin = plugin_basename( __FILE__ );

		if ( strpos( $file, 'footnotes-made-easy.php' ) !== false ) {
			$settings_link = '<a href="options-general.php?page=footnotes-options-page">' . __( 'Settings', 'footnotes-made-easy' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	/**
	* Plugin Meta
	*
	* Add meta links to plugin details
	*
	* @since	1.0
	*
	* @param	string  $links	Current links
	* @param	string  $file	File in use
	* @return	string			Links, now with settings added
	*/

	function plugin_meta( $links, $file ) {

		if ( strpos( $file, 'footnotes-made-easy.php' ) !== false ) {

			$links = array_merge( $links, array( '<a href="https://wordpress.org/support/plugin/footnotes-made-easy">' . __( 'Support', 'footnotes-made-easy' ) . '</a>' ) );

			$links = array_merge( $links, array( '<a href="https://github.com/lumumbapl">' . __( 'Github', 'footnotes-made-easy' ) . '</a>' ) );

			$links = array_merge( $links, array( '<a href="https://lumumbas.blog">' . __( 'Blog', 'footnotes-made-easy' ) . '</a>' ) );

			// rating link; tweak based on review at https://wordpress.org/support/view/plugin-reviews/footnotes-made-easy (5 starts as of 04/22/2021)

			$links[]='<a class="footnotes-made-easy-review" href="https://wordpress.org/support/view/plugin-reviews/footnotes-made-easy?rate=5#postform" target="_blank" title="If you have found this plugin useful please consider leaving a 5 star review." >
					<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
				</a>';

		echo '<style>.footnotes-made-easy-review span,.footnotes-made-easy-review span:hover{color:#ffb900}.footnotes-made-easy-review span:hover~span{color:#888}</style>';
	}
	return $links;
}
	/**
	* Options Page
	*
	* Get the options and display the page
	* UPDATED: Changed path from '/options.php' to '/includes/options.php'
	*
	* @since	1.0
	*/

	function footnotes_options_page() {

		$this->current_options = get_option( 'swas_footnote_options' );
		$new_setting = array();
		foreach ( $this->current_options as $key=>$setting ) {
			$new_setting[ $key ] = htmlentities( $setting );
		}
		$this->current_options = $new_setting;
		unset( $new_setting );
		// UPDATED PATH: Changed from dirname(__FILE__) . '/options.php'
		include ( dirname(__FILE__) . '/includes/options.php' );
	}

	/**
	* Add Options Help
	*
	* Add help tab to options screen
	*
	* @since	1.0
	*
	*/

	function footnotes_help() {

		global $footnotes_hook;
		$screen = get_current_screen();

		if ( $screen->id !== $footnotes_hook ) { return; }

		$screen -> add_help_tab( array( 'id' => 'footnotes-help-tab', 'title'	=> __( 'Help', 'footnotes-made-easy' ), 'content' => $this->add_help_content() ) );

		$screen -> set_help_sidebar( $this->add_sidebar_content() );

	}

	/**
	* Options Help
	*
	* Return help text for options screen
	*
	* @since	1.0
	*
	* @return	string	Help Text
	*/

	function add_help_content() {

		$help_text = '<p>' . __( 'This screen allows you to specify the default options for the Footnotes Made Easy plugin.', 'footnotes-made-easy' ) . '</p>';
		$help_text .= '<p>' . __( "The identifier is what appears when a footnote is inserted into your page contents. The back-link appear after each footnote, linking back to the identifier.", 'footnotes-made-easy' ) . '</p>';
		$help_text .= '<p>' . __( 'Remember to click the Save Changes button at the bottom of the screen for new settings to take effect.', 'footnotes-made-easy' ) . '</p></h4>';

		return $help_text;
	}

	/**
	* Options Help Sidebar
	*
	* Add a links sidebar to the options help
	*
	* @since	1.0
	*
	* @return	string	Help Text
	*/

	function add_sidebar_content() {

		$help_text = '<p><strong>' . __( 'For more information:', 'footnotes-made-easy' ) . '</strong></p>';
		$help_text .= '<p><a href="https://wordpress.org/plugins/footnotes-made-easy/">' . __( 'Instructions', 'footnotes-made-easy' ) . '</a></p>';
		$help_text .= '<p><a href="https://wordpress.org/support/plugin/footnotes-made-easy">' . __( 'Support Forum', 'footnotes-made-easy' ) . '</a></p></h4>';

		return $help_text;
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

		$footnotes_hook = add_options_page( __( 'Footnotes Made Easy', 'footnotes-made-easy' ), __( 'Footnotes Made Easy', 'footnotes-made-easy' ), 'manage_options', 'footnotes-options-page', array( $this, 'footnotes_options_page' ) );

		add_action( 'load-' . $footnotes_hook, array( $this, 'footnotes_help' ) );

	}
	/**
	* Insert additional CSS
	*
	* Add additional CSS to the page for the footnotes styling
	*
	* @since	1.0
	*/

	function insert_styles(){
		?>
		<style type="text/css">
			<?php if ( 'symbol' !== $this->current_options[ 'list_style_type' ] ): ?>
			ol.footnotes>li {list-style-type:<?php echo esc_html($this->current_options[ 'list_style_type' ]); ?>;}
			<?php endif; ?>
			<?php echo "ol.footnotes { color:#666666; }\nol.footnotes li { font-size:80%; }\n"; ?>
		</style>
		<?php
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
	* UPDATED: Changed paths from 'js/' and 'css/' to 'assets/js/' and 'assets/css/'
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
						'1.0.0',  // Version number
						true      // Load in footer
						);

	wp_enqueue_style( 
		'wp-footnotes-tt-style', 
		plugins_url( 'assets/css/tooltips.min.css' , __FILE__ ), 
		array(), 
		'1.0.0' 
	);
}
}