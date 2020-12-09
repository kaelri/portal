<?php

// PORTAL EXAMPLE MODULE
// =====================

// SCRIPT
// ------

	add_action( 'wp_enqueue_scripts', 'portal_example_scripts' );

	function portal_example_scripts() {

		// App
		wp_enqueue_script( 'portal-example', plugin_dir_url( __FILE__ ) . 'example.js', ['portal', 'vue'] );

	}

// SHORTCODE
// ---------

	add_shortcode( 'portal-example', 'portal_example_html' );

	function portal_example_html($atts, $content = null) {
	    return '<article id="portal-example"></article>';
	}

// API METHOD: PING
// ----------------

	// Take some JSON data and send it back unchanged in order to verify connection & integrity.

	add_action( 'rest_api_init', function () {
		register_rest_route( 'portal/v1', '/ping', [
			'methods'  => 'POST',
			'callback' => function ( WP_REST_Request $request ) {

				$output = [
					'code'     => 'portal_example_success',
					'message'  => 'Working! The input was: ' . $request['myInput'],
					'data'     => $request['testData']
				];
		
				return $output;
		
			}
		]);
	});

// API METHOD: ERROR
// -----------------

	add_action( 'rest_api_init', function () {
		register_rest_route( 'portal/v1', '/error', [
			'methods'  => ['GET', 'POST'],
			'callback' => function( WP_REST_Request $request ) {

				$output = PortalCall::error([
					'code'    => 'portal_example_error',
					'message' => 'This is a simulated 500 error response.'
				]);

				return $output;
		
			}
		]);
	});
