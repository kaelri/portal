<?php

class Portal {

	public static $plugin_path;
	public static $plugin_url;

	public static $data       = [];
	public static $do_spinner = false;

	public static function setup( string $plugin_file ) {

		self::$plugin_path = plugin_dir_path ( $plugin_file );
		self::$plugin_url  = plugin_dir_url  ( $plugin_file );

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts'  ] );

	}

	// GET & SET DATA
	// --------------

	public static function set_data( ...$args ) {

		$new_data = null;

		if ( empty($args) ) {
			return new WP_Error('no-data', __FUNCTION__ . ' received no arguments. Provide either an array of key-value pairs, or two arguments for $key and $value.');
		}

		if ( count($args) === 1 && is_array($args[0]) ) {

			$new_data = $args[0];

		} elseif ( count($args) === 2 && is_string( $args[0]) ) {

			$new_data = [ $args[0] => $args[1] ];
			
		}
		
		if ( empty($input) ) {
			return new WP_Error('bad-data', __FUNCTION__ . ' received bad data. Provide either an array of key-value pairs, or two arguments for $key and $value.');
		}

		self::$data = array_merge( self::$data, $new_data );

		return true;

	}

	public static function has_data( string $key = '' ) {

		if ( empty($key) ) {
			
			$has_data = empty( self::$data );

		} else {

			$has_data = array_key_exists( $key, self::$data );
		}

		return (bool) $has_data;

	}

	public static function get_data( string $key = '' ) {

		if ( empty($key) ) {
			
			$data = self::$data;

		} elseif ( !array_key_exists( $key, self::$data ) ) {

			$data = new WP_Error('key-does-not-exist', 'Portal does not have any data with this key.');

		} else {

			$data = self::$data[ $key ] ?? null;

		}

		return $data;

	}

	// OUTPUT
	// ------

	public static function enqueue_scripts() {

		$timestamp = filemtime( self::$plugin_path . 'js/portal.js' );

		wp_enqueue_script( 'portal', self::$plugin_url . 'js/portal.js', ['jquery'], $timestamp );

		if ( !array_key_exists( 'restURL', self::$data ) ) {
			self::$data[ 'restURL' ] = home_url('/wp-json/');
		}

		wp_localize_script( 'portal', 'portalInitialData', self::$data );

	}

	// LOADING SPINNER (OPTIONAL)
	// --------------------------

	public static function enable_spinner( bool $do_html = true, bool $do_css = true  ) {

		if ( self::$do_spinner ) return;

		self::$do_spinner = true;

		if ( $do_css  ) add_action( 'wp_enqueue_scripts', [ __CLASS__, 'do_spinner_css'  ] );
		if ( $do_html ) add_action( 'wp_footer',          [ __CLASS__, 'do_spinner_html' ] );

	}

	public static function do_spinner_html() {

		?><div id="portal-spinner"><i class="fas fa-cog fa-spin"></i></div><?php

	}

	public static function do_spinner_css() {

		$timestamp = filemtime( self::$plugin_path . 'css/portal-spinner.css' );

		wp_enqueue_style( 'portal-spinner', self::$plugin_url . 'css/portal-spinner.css', false, $timestamp );

	}

}
