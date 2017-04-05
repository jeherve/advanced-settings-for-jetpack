<?php
/**
 * Plugin Name: Advanced Settings for Jetpack
 * Plugin URI: http://jetpack.com
 * Description: Add links to Advanced Jetpack Settings in your dashboard.
 * Author: Jeremy Herve
 * Version: 1.0.0
 * Author URI: https://jeremy.hu
 * License: GPL2+
 * Text Domain: advanced-settings-for-jetpack
 * Domain Path: /languages/
 *
 * @package Advanced Settings for Jetpack
 */

/**
 * Add a new Jetpack submenu item linking to the old Module list.
 *
 * @since 1.0.0
 */
function advanced_settings_jetpack_submenu() {
	if ( ! class_exists( 'Jetpack' ) ) {
		return;
	}

	jetpack_require_lib( 'admin-pages/class.jetpack-settings-page' );
	$jetpack_settings = new Jetpack_Settings_Page;
	$jetpack_settings->add_actions();

	$hook = add_submenu_page(
		'jetpack',
		__( 'Advanced Settings', 'advanced-settings-for-jetpack' ),
		__( 'Advanced Settings', 'advanced-settings-for-jetpack' ),
		'jetpack_manage_modules',
		'jetpack_modules',
		array(
			$jetpack_settings,
			'render',
		)
	);

	// This uses a `don't show if not connected` class so we need to add these manually.
	add_action( "load-$hook",                array( $jetpack_settings, 'admin_help' ) );
	add_action( "load-$hook",                array( $jetpack_settings, 'admin_page_load' ) );
	add_action( "admin_head-$hook",          array( $jetpack_settings, 'admin_head' ) );
	add_action( "admin_print_styles-$hook",  array( $jetpack_settings, 'admin_styles' ) );
	add_action( "admin_print_scripts-$hook", array( $jetpack_settings, 'admin_scripts' ) );
}
add_action( 'jetpack_admin_menu', 'advanced_settings_jetpack_submenu', 11 );

/**
 * Add an Advanced Settings link in the Jetpack Dashboard.
 *
 * @since 1.0.0
 */
function advanced_settings_jetpack_dashlink() {
	wp_enqueue_script( 'jetpack-advanced-settings', plugins_url( 'append-link.js', __FILE__ ), array( 'jquery' ), '1.0.0' );

	$link_info = array(
		'href' => 'admin.php?page=jetpack_modules',
		'text' => esc_html__( 'Advanced Settings', 'advanced-settings-for-jetpack' ),
	);
	wp_localize_script( 'jetpack-advanced-settings', 'advanced_link', $link_info );
}
add_action( 'admin_footer', 'advanced_settings_jetpack_dashlink', 20 );
