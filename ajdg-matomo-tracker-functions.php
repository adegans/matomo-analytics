<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2020-2026 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

/*-------------------------------------------------------------
 Name:      ajdg_matomo_activate
-------------------------------------------------------------*/
function ajdg_matomo_activate() {
	// Defaults
	update_option('ajdg_matomo_siteid', '');
	update_option('ajdg_matomo_siteurl', '');
	update_option('ajdg_matomo_active', 'no');
	update_option('ajdg_matomo_track_feed_clicks', 'no');
	update_option('ajdg_matomo_track_feed_impressions', 'no');
	update_option('ajdg_matomo_track_error_pages', 'no');
	update_option('ajdg_matomo_track_incognito', 'no');
	update_option('ajdg_matomo_heartbeat_enable', 'no');
	update_option('ajdg_matomo_wc_downloads', 'no');
	update_option('ajdg_matomo_high_accuracy', 'no');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_deactivate
-------------------------------------------------------------*/
function ajdg_matomo_deactivate() {
	// Delete data
	delete_option('ajdg_matomo_siteid');
	delete_option('ajdg_matomo_siteurl');
	delete_option('ajdg_matomo_active');
	delete_option('ajdg_matomo_track_feed_clicks');
	delete_option('ajdg_matomo_track_feed_impressions');
	delete_option('ajdg_matomo_track_error_pages');
	delete_option('ajdg_matomo_track_incognito');
	delete_option('ajdg_matomo_heartbeat_enable');
	delete_option('ajdg_matomo_wc_downloads');
	delete_option('ajdg_matomo_high_accuracy');
	delete_option('ajdg_matomo_hide_review'); // Obsolete in 1.4
	delete_option('ajdg_activate_matomo-analytics'); // Must match slug
	delete_transient('ajdg_update_matomo-analytics'); // Must match slug
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_init
-------------------------------------------------------------*/
function ajdg_matomo_init() {
	load_plugin_textdomain('matomo-analytics', false, 'matomo-analytics/languages');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_dashboard_styles
-------------------------------------------------------------*/
function ajdg_matomo_dashboard_styles() {
	wp_enqueue_style('ajdg-matomo-admin-stylesheet', plugins_url('library/dashboard.css', __FILE__));
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_save_settings
 Purpose:	Save your settings
-------------------------------------------------------------*/
function ajdg_matomo_save_settings() {
	if(wp_verify_nonce($_POST['matomo_nonce'],'matomo-analytics')) {
		$siteid = $siteurl = $track_active = $track_feed = $track_error_pages = $track_heartbeat = $track_high_accuracy = '';
		if(isset($_POST['matomo_siteid'])) $siteid = sanitize_text_field(trim($_POST['matomo_siteid'], "\t\n "));
		if(isset($_POST['matomo_siteurl'])) $siteurl = filter_var(trim($_POST['matomo_siteurl'], "\t\n "), FILTER_SANITIZE_URL);
		if(isset($_POST['matomo_tracker_active'])) $track_active = sanitize_text_field($_POST['matomo_tracker_active']);
		if(isset($_POST['matomo_feed_clicks'])) $track_feed_clicks = sanitize_text_field($_POST['matomo_feed_clicks']);
		if(isset($_POST['matomo_error_pages'])) $track_error_pages = sanitize_text_field($_POST['matomo_error_pages']);
		if(isset($_POST['matomo_incognito'])) $track_incognito = sanitize_text_field($_POST['matomo_incognito']);
		if(isset($_POST['matomo_heartbeat'])) $track_heartbeat = sanitize_text_field($_POST['matomo_heartbeat']);
		if(isset($_POST['matomo_wc_downloads'])) $track_wc_downloads = sanitize_text_field($_POST['matomo_wc_downloads']);
		if(isset($_POST['matomo_feed_impressions'])) $track_feed_impressions = sanitize_text_field($_POST['matomo_feed_impressions']);
		if(isset($_POST['matomo_accuracy'])) $track_high_accuracy = sanitize_text_field($_POST['matomo_accuracy']);

		// Cleanup
		$siteid = (is_numeric($siteid)) ? $siteid : '';

		$siteurl = str_ireplace(array('/index.php', '/matomo.php', '/piwik.php'), '', strtolower($siteurl)); // Remove files
		$siteurl = preg_replace('/\?idsite=\d/i', '', $siteurl); // Remove idsite parameter
		$siteurl = trim($siteurl, "\/"); // Remove trailing slashes
		$siteurl = (strlen($siteurl) > 0) ? $siteurl : '';

		$track_active = ($track_active == 'yes') ? 'yes' : 'no';
		$track_feed_clicks = ($track_feed_clicks == 'yes') ? 'yes' : 'no';
		$track_error_pages = ($track_error_pages == 'yes') ? 'yes' : 'no';
		$track_incognito = ($track_incognito == 'yes') ? 'yes' : 'no';
		$track_heartbeat = ($track_heartbeat == 'yes') ? 'yes' : 'no';
		$track_wc_downloads = ($track_wc_downloads == 'yes') ? 'yes' : 'no';
		$track_feed_impressions = ($track_feed_impressions == 'yes') ? 'yes' : 'no';
		$track_high_accuracy = ($track_high_accuracy == 'yes') ? 'yes' : 'no';

		// Process and response
		update_option('ajdg_matomo_siteid', $siteid);
		update_option('ajdg_matomo_siteurl', $siteurl);
		update_option('ajdg_matomo_active', $track_active);
		update_option('ajdg_matomo_track_feed_clicks', $track_feed_clicks);
		update_option('ajdg_matomo_track_error_pages', $track_error_pages);
		update_option('ajdg_matomo_track_incognito', $track_incognito);
		update_option('ajdg_matomo_heartbeat_enable', $track_heartbeat);
		update_option('ajdg_matomo_wc_downloads', $track_wc_downloads);
		update_option('ajdg_matomo_track_feed_impressions', $track_feed_impressions);
		update_option('ajdg_matomo_high_accuracy', $track_high_accuracy);

		ajdg_matomo_return('ajdg-matomo-tracker', 100);
		exit;
	} else {
		ajdg_matomo_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_check_config
 Purpose:	Reset missing options
-------------------------------------------------------------*/
function ajdg_matomo_check_config() {
	$siteid = get_option('ajdg_matomo_siteid');
	if(!$siteid) update_option('ajdg_matomo_siteid', '');

	$siteurl = get_option('ajdg_matomo_siteurl');
	if(!$siteurl) update_option('ajdg_matomo_siteurl', '');

	$track_active = get_option('ajdg_matomo_active');
	if(!$track_active) update_option('ajdg_matomo_active', 'no');

	$track_feed_click = get_option('ajdg_matomo_track_feed_clicks');
	if(!$track_feed_click) update_option('ajdg_matomo_track_feed_clicks', 'yes');

	$track_error_pages = get_option('ajdg_matomo_track_error_pages');
	if(!$track_error_pages) update_option('ajdg_matomo_track_error_pages', 'yes');

	$track_incognito = get_option('ajdg_matomo_track_incognito');
	if(!$track_incognito) update_option('ajdg_matomo_track_incognito', 'no');

	$heartbeat_enable = get_option('ajdg_matomo_heartbeat_enable');
	if(!$heartbeat_enable) update_option('ajdg_matomo_heartbeat_enable', 'no');

	$track_wc_downloads = get_option('ajdg_matomo_wc_downloads');
	if(!$track_wc_downloads) update_option('ajdg_matomo_wc_downloads', 'no');

	$track_feed_impressions = get_option('ajdg_matomo_track_feed_impressions');
	if(!$track_feed_impressions) update_option('ajdg_matomo_track_feed_impressions', 'no');

	$high_accuracy = get_option('ajdg_matomo_high_accuracy');
	if(!$high_accuracy) update_option('ajdg_matomo_high_accuracy', 'no');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_notifications_dashboard
 Purpose:	Show dashboard errors
-------------------------------------------------------------*/
function ajdg_matomo_notifications_dashboard() {
	$has_error = ajdg_matomo_has_error();
	if($has_error) {
		echo '<div class="ajdg-notification notice" style="">';
		echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-notification-message"><strong>Analytics for Matomo</strong> has detected '._n('one issue that requires', 'several issues that require', count($has_error), 'matomo-analytics').' '.__('your attention:', 'matomo-analytics').'<br />';
		foreach($has_error as $error => $message) {
			echo '&raquo; '.$message.'<br />';
		}
		echo '	<a href="'.admin_url('/tools.php?page=ajdg-matomo-tracker').'">'.__('Check your settings', 'matomo-analytics').'</a>!';
		echo '	</div>';
		echo '</div>';
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_has_error
 Purpose:	Output configuration errors
-------------------------------------------------------------*/
function ajdg_matomo_has_error() {
	$siteid = get_option('ajdg_matomo_siteid');
	$siteurl = get_option('ajdg_matomo_siteurl');
	$track_active = get_option('ajdg_matomo_active');

	if($track_active == 'yes' AND empty($siteid)) {
		$error['matomo_site_id'] = __('You activated the Matomo Analytics Tracker but the Site ID is not set.', 'matomo-analytics');
	}

	if($track_active == 'yes' AND empty($siteurl)) {
		$error['matomo_site_url'] = __('You activated the Matomo Analytics Tracker but the Site URL is not configured.', 'matomo-analytics');
	}

	$error = (isset($error) AND is_array($error)) ? $error : false;

	return $error;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_return
 Purpose:	Dashboard function for dashboard redirects
-------------------------------------------------------------*/
function ajdg_matomo_return($page, $status, $args = null) {

	if(strlen($page) > 0 AND ($status > 0 AND $status < 1000)) {
		$defaults = array(
			'status' => $status
		);
		$arguments = wp_parse_args($args, $defaults);
		$redirect = 'tools.php?page=' . $page . '&'.http_build_query($arguments);
	} else {
		$redirect = 'tools.php?page=ajdg-matomo-tracker';
	}

	wp_redirect($redirect);
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_status
 Purpose:	Dashboard function for saving settings
-------------------------------------------------------------*/
function ajdg_matomo_status($status) {

	switch($status) {
		case '100' :
			echo '<div class="updated"><p>'.__('Settings saved', 'matomo-analytics').'</p></div>';
		break;

		default :
			echo '<div class="error"><p>'.__('Unexpected error', 'matomo-analytics').'</p></div>';
		break;
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_nonce_error
-------------------------------------------------------------*/
function ajdg_matomo_nonce_error() {
	echo '	<h2 style="text-align: center;">'.__('Oh no! Something went wrong!', 'matomo-analytics').'</h2>';
	echo '	<p style="text-align: center;">'.__('WordPress was unable to verify the authenticity of the url you have clicked. Verify if the url used is valid or log in via your browser.', 'matomo-analytics').'</p>';
	echo '	<p style="text-align: center;">'.__('If you have received the url you want to visit via email, you are being tricked!', 'matomo-analytics').'</p>';
	echo '	<p style="text-align: center;">'.__('Contact support if the issue persists:', 'matomo-analytics').' <a href="https://support.ajdg.net/" title="AJdG Solutions Support" target="_blank">Support forum</a>.</p>';
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_useragent_filter
 Purpose:	Basic bot filter
-------------------------------------------------------------*/
function ajdg_matomo_useragent_filter($user_agent) {
	$blocked_user_agents = array(
		'bot', 'crawler', 'spider',
		'exabot', 'alexa', 'findlinks', 'ia_archiver', 'inktomi',
		'slurp', 'YahooSeeker', 'yahoo',
		'adsbot-google', 'googlebot', 'googleproducer', 'google-site-verification', 'google-test', 'mediapartners-google', 'feedfetcher-google',
		'baidu', 'yandex', 'yandex', 'YandexImages',
		'bingbot', 'bingpreview', 'msnbot',
		'duckduckgo', 'aolbuild',
		'sosospider', 'sosoimagespider', 'sogou', 'teoma',
		'facebookexternalhit', 'facebook',
		'TECNOSEEK', 'TechnoratiSnoop'
	);

	// You're a bot
	if(preg_match('/'.implode('|', $blocked_user_agents).'/i', $user_agent)) return false;

	// Or not...
	return true;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_feed_impressions
 Purpose:	Add a image tracker to RSS articles
-------------------------------------------------------------*/
function ajdg_matomo_feed_impressions($content) {
	global $post;
	if(is_feed()) {
		$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_USER_AGENT']))) : 'n/a';

		if(ajdg_matomo_useragent_filter($user_agent)) {
			$siteid = get_option('ajdg_matomo_siteid');
			$siteurl = get_option('ajdg_matomo_siteurl');

			$protocol = (is_ssl()) ? 'https://' : 'http://';
			$http_host = (isset($_SERVER['HTTP_HOST'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_HOST']))) : 'n/a';
			$request_url = (isset($_SERVER['REQUEST_URI'])) ? esc_url_raw($protocol.$http_host.$_SERVER['REQUEST_URI']) : 'n/a';

			$title = $post->post_name;
			$posturl = get_permalink($post->ID);

			if(strpos($request_url, get_bloginfo('rss2_url')) !== false) {
				$feed_type = get_bloginfo('rss2_url');
			} elseif(strpos($request_url, get_bloginfo('atom_url')) !== false) {
				$feed_type = get_bloginfo('atom_url');
			} else {
				$feed_type = home_url().'feed/';
			}

			$content .= '<img src="'.$siteurl.'/matomo.php?idsite='.$siteid.'&rec=1&url='.$posturl.'&action_name='.$title.'&urlref='.$feed_type.'&_rcn=feed_impression&_rck='.$title.'" style="border:0;width:0;height:0" width="0" height="0" alt="" />';
		}
	}

	return $content;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_feed_campaign
 Purpose:	Add a tracker to RSS articles
-------------------------------------------------------------*/
function ajdg_matomo_feed_clicks($permalink) {
	global $post;

	if(is_feed()) {
		$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_USER_AGENT']))) : 'n/a';

		if(ajdg_matomo_useragent_filter($user_agent)) {
			$protocol = (is_ssl()) ? 'https://' : 'http://';
			$http_host = (isset($_SERVER['HTTP_HOST'])) ? trim(strip_tags(htmlspecialchars($_SERVER['HTTP_HOST']))) : 'n/a';
			$request_url = (isset($_SERVER['REQUEST_URI'])) ? esc_url_raw($protocol.$http_host.$_SERVER['REQUEST_URI']) : 'n/a';

			if(strpos($request_url, get_bloginfo('rss2_url')) !== false) {
				$feed_type = 'RSS2';
			} elseif(strpos($request_url, get_bloginfo('atom_url')) !== false) {
				$feed_type = 'Atom';
			} elseif(strpos($request_url, get_bloginfo('comments_rss2_url')) !== false) {
				$feed_type = 'Comment';
			} elseif(strpos($request_url, get_bloginfo('comments_atom_url')) !== false) {
				$feed_type = 'Comment';
			} else {
				$feed_type = 'Other';
			}

			$separator = (strpos($permalink, '?') === false) ? '?' : '&';
			$permalink .= $separator.'mtm_campaign=feed_click&mtm_kwd='.$post->post_name.'&mtm_source='.$feed_type.'&mtm_medium=feed';
		}
	}

	return $permalink;
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_tracker
 Purpose:	The tracker added to your site
-------------------------------------------------------------*/
function ajdg_matomo_tracker() {
	$siteid = get_option('ajdg_matomo_siteid');
	$siteurl = get_option('ajdg_matomo_siteurl');

	$track_error_pages = get_option('ajdg_matomo_track_error_pages');
	$track_incognito = get_option('ajdg_matomo_track_incognito');
	$track_heartbeat = get_option('ajdg_matomo_heartbeat_enable');
	$track_wc_downloads = get_option('ajdg_matomo_wc_downloads');
	$track_high_accuracy = get_option('ajdg_matomo_high_accuracy');

	echo "<!-- Matomo -->\n";
	echo "<script type=\"text/javascript\">\n";
	echo "var _paq = window._paq || [];\n";
	if(is_404() AND $track_error_pages == 'yes') echo "_paq.push(['setDocumentTitle', '404/URL = ' + encodeURIComponent(document.location.pathname + document.location.search) + ' Referrer = ' + encodeURIComponent(document.referrer)]);\n";
	if($track_incognito == 'yes') echo "_paq.push(['setDoNotTrack', true]);\n";
	if($track_heartbeat == 'yes') echo "_paq.push(['enableHeartBeatTimer']);\n";
	if($track_wc_downloads == 'yes') echo "_paq.push(['setDownloadClasses', 'woocommerce-MyAccount-downloads-file']);\n";
	if($track_high_accuracy == 'yes') echo "_paq.push(['alwaysUseSendBeacon']);\n";
	echo "_paq.push(['trackPageView']);\n";
	echo "_paq.push(['enableLinkTracking']);\n";
	echo "(function() {\n";
	echo "\t_paq.push(['setTrackerUrl', '$siteurl/matomo.php']);\n";
	echo "\t_paq.push(['setSiteId', '$siteid']);\n";
	echo "\tvar d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];\n";
	echo "\tg.type='text/javascript'; g.async=true; g.defer=true; g.src='$siteurl/matomo.js'; s.parentNode.insertBefore(g,s);\n";
	echo "})();\n";
	echo "</script>\n";
	echo "<noscript><img src=\"$siteurl/matomo.php?idsite=$siteid&amp;rec=1\" style=\"border:0\" alt=\"\" /></noscript>\n";
	echo "<!-- /Matomo -->\n\n";
}

/*-------------------------------------------------------------
 Name:	  	ajdg_matomo_meta_links
 Purpose:	Extra links on the plugins dashboard page
-------------------------------------------------------------*/
function ajdg_matomo_meta_links($links, $file) {
	if($file !== 'matomo-analytics/ajdg-matomo-tracker.php') return $links;
	
	$links['ajdg-settings'] = sprintf('<a href="%s">%s</a>', admin_url('tools.php?page=ajdg-matomo-tracker'), __('Settings', 'matomo-analytics'));
	$links['ajdg-help'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://support.ajdg.net/knowledgebase.php', __('Support', 'matomo-analytics'));
	$links['ajdg-more'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/plugins/', __('More plugins', 'matomo-analytics'));

	return $links;
}

/*-------------------------------------------------------------
 Name:	  	ajdg_matomo_fetch_rss_feed
 Purpose:	RSS feed reader
-------------------------------------------------------------*/
function ajdg_matomo_fetch_rss_feed($url = '', $show_items = 6) {
	// Check for errors
	if(!is_numeric($show_items) OR $show_items < 1 OR $show_items > 20) {
		$show_items = 6;
	}

	$rss = fetch_feed($url);

	if(is_wp_error($rss)) {
		$feed_output = '<p>The feed could not be fetched.</p>';
	} else if(!$rss->get_item_quantity()) {
		$feed_output = '<p>The feed has no items or could not be read.</p>';
	} else {		
		// Prepare output
		$feed_output = '<ul>';
		foreach($rss->get_items(0, $show_items) as $item) {
			$link = $item->get_link();

			while(!empty($link) AND stristr($link, 'http') !== $link) {
				$link = substr($link, 1);
			}

			$link = esc_url(strip_tags($link));
			$title = esc_html(trim(strip_tags($item->get_title())));
			$date = $item->get_date('U');

			if(empty($title)) $title = __('Untitled');
			if($date) $date = ' <span class="rss-date">'.date_i18n(get_option('date_format'), $date).'</span>';

			$feed_output .= (empty($link)) ? "<li>$title<br /><em>{$date}</em></li>" : "<li><a class='rsswidget' href='$link'>$title</a><br /><em>{$date}</em></li>";
		}
		$feed_output .= '</ul>';
	}
	unset($rss);

	// Done!
	return $feed_output;
}
?>