<?php
/**
 * Plugin Name: Advanced Settings for Jetpack
 * Plugin URI: http://jetpack.com
 * Description: Add links to Advanced Jetpack Settings in your dashboard.
 * Author: Jeremy Herve
 * Version: 1.1.0
 * Author URI: https://jeremy.hu
 * License: GPL2+
 * Text Domain: advanced-settings-for-jetpack
 * Domain Path: /languages/
 *
 * @package Advanced Settings for Jetpack
 */

use Automattic\Jetpack\Status;

/**
 * Add a new Jetpack submenu item linking to the old Module list.
 *
 * @since 1.0.0
 */
function advanced_settings_jetpack_submenu() {
	if ( ! class_exists( 'Jetpack' ) ) {
		return;
	}

	// Only show the page when the site is connected or in offline mode.
	if (
		Jetpack::is_connection_ready()
		|| ( new Status() )->is_offline_mode()
	) {
		add_submenu_page(
			'jetpack',
			esc_html__( 'Advanced Settings', 'advanced-settings-for-jetpack' ),
			esc_html__( 'Advanced Settings', 'advanced-settings-for-jetpack' ),
			'jetpack_manage_modules',
			'jetpack_modules',
			admin_url( 'admin.php?page=jetpack_modules' ),
			null,
			6
		);
	}
}
add_action( 'jetpack_admin_menu', 'advanced_settings_jetpack_submenu', 11 );

/**
 * Add an Advanced Settings link in the Jetpack Dashboard.
 *
 * @since 1.0.0
 */
function advanced_settings_jetpack_dashlink() {
	wp_enqueue_script( 'jetpack-advanced-settings', plugins_url( 'append-link.js', __FILE__ ), array( 'jquery' ), '1.1.0' );

	$link_info = array(
		'href' => 'admin.php?page=jetpack_modules',
		'text' => esc_html__( 'Advanced Settings', 'advanced-settings-for-jetpack' ),
	);
	wp_localize_script( 'jetpack-advanced-settings', 'advanced_link', $link_info );
}
add_action( 'admin_footer', 'advanced_settings_jetpack_dashlink', 20 );
