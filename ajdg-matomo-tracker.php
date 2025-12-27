<?php
/*
Plugin Name: Matomo Tracker
Plugin URI: https://ajdg.solutions/product/matomo-tracker/
Author: Arnan de Gans
Author URI: https://www.arnan.me/
Description: The easiest way to track visitors in Matomo. No nonsense, just stats!
Version: 1.4
License: GPLv3

Text Domain: matomo-analytics
Domain Path: /languages

Requires at least: 5.8
Requires PHP: 8.0
Requires CP: 2.0
Tested CP: 2.6
Premium URI: https://ajdg.solutions/
GooseUp: compatible
*/

/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2020-2026 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

/*--- Load Files --------------------------------------------*/
include_once(plugin_dir_path(__FILE__).'/library/common.php');
include_once(plugin_dir_path(__FILE__).'/ajdg-matomo-tracker-functions.php');
/*-----------------------------------------------------------*/

/*--- Core --------------------------------------------------*/
register_activation_hook(__FILE__, 'ajdg_matomo_activate');
register_uninstall_hook(__FILE__, 'ajdg_matomo_deactivate');
add_action('init', 'ajdg_matomo_init');

if(!is_admin()) {
	$matomo_active = get_option('ajdg_matomo_active');
	if($matomo_active == 'yes') add_action('wp_footer', 'ajdg_matomo_tracker');
	$matomo_feed_clicks = get_option('ajdg_matomo_track_feed_clicks');
	if($matomo_feed_clicks == 'yes') add_filter('post_link', 'ajdg_matomo_feed_clicks');
	$matomo_feed_impressions = get_option('ajdg_matomo_track_feed_impressions');
	if($matomo_feed_impressions == 'yes') add_filter('the_content', 'ajdg_matomo_feed_impressions');
}

if(is_admin()) {
	ajdg_matomo_check_config();
	/*--- Dashboard ---------------------------------------------*/
	add_action('admin_menu', 'ajdg_matomo_dashboard_menu');
	add_action('admin_print_styles', 'ajdg_matomo_dashboard_styles');
	add_action('admin_notices', 'ajdg_matomo_notifications_dashboard');
	add_filter('plugin_row_meta', 'ajdg_matomo_meta_links', 10, 2);
	/*--- Actions -----------------------------------------------*/
	if(isset($_POST['matomo_save_settings'])) add_action('init', 'ajdg_matomo_save_settings');
}
/*-----------------------------------------------------------*/

/*-------------------------------------------------------------
 Name:      ajdg_matomo_dashboard_menu
 Purpose:   Add pages to admin menus
-------------------------------------------------------------*/
function ajdg_matomo_dashboard_menu() {
	add_management_page('Matomo Tracker', 'Matomo Tracker', 'manage_options', 'ajdg-matomo-tracker', 'ajdg_matomo_dashboard');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_dashboard
 Purpose:   Admin general info page
-------------------------------------------------------------*/
function ajdg_matomo_dashboard() {
	$status = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);

	$current_user = wp_get_current_user();
	?>

	<div class="wrap">
		<h1><?php _e('Matomo Tracker', 'matomo-analytics'); ?></h1>

		<?php
		if($status > 0) ajdg_matomo_status($status);
		include("ajdg-matomo-tracker-dashboard.php");
		?>

		<br class="clear" />
	</div>
<?php
}
?>