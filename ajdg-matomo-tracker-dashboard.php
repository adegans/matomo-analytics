<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2020-2026 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

$siteid = get_option('ajdg_matomo_siteid');
$siteurl = get_option('ajdg_matomo_siteurl');
$track_active = get_option('ajdg_matomo_active');
$track_feed_clicks = get_option('ajdg_matomo_track_feed_clicks');
$track_error_pages = get_option('ajdg_matomo_track_error_pages');
$track_incognito = get_option('ajdg_matomo_track_incognito');
$track_heartbeat = get_option('ajdg_matomo_heartbeat_enable');
$track_wc_downloads = get_option('ajdg_matomo_wc_downloads');
$track_feed_impressions = get_option('ajdg_matomo_track_feed_impressions');
$track_high_accuracy = get_option('ajdg_matomo_high_accuracy');
?>

<div id="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder">
		<div id="left-column" class="ajdg-postbox-container">

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('Settings', 'matomo-analytics'); ?></h2>
				<div id="report-form" class="ajdg-postbox-content">

					<form name="report" id="post" method="post" action="tools.php?page=ajdg-matomo-tracker">
						<?php wp_nonce_field('matomo_nonce','matomo_nonce'); ?>

						<h2><?php _e('Required options', 'matomo-analytics'); ?></h2>
						<p><label for="matomo_siteid"><strong><?php _e('Your Matomo site ID:', 'matomo-analytics'); ?></strong><br /><input tabindex="1" name="matomo_siteid" type="text" class="search-input" style="width:100%;" value="<?php echo $siteid;?>" autocomplete="off" /><br /><small><?php _e('This is a number provided by your Matomo server.', 'matomo-analytics'); ?></small></label></p>

						<p><label for="matomo_siteurl"><strong><?php _e('Your matomo server address:', 'matomo-analytics'); ?></strong><br /><input tabindex="2" name="matomo_siteurl" type="text" class="search-input" style="width:100%;" value="<?php echo $siteurl;?>" autocomplete="off" /><br /><small><?php _e('The url to your Matomo server. You can also enter your cloud hosted Matomo address. <b>Examples:</b> https://matomo.yourdomain.com or https://yourdomain.com/matomo or https://yourname.matomo.cloud.', 'matomo-analytics'); ?></small></label></p>

						<p><label for="matomo_tracker_active"><strong><?php _e('Enable tracking:', 'matomo-analytics'); ?></strong> <select name="matomo_tracker_active" tabindex="3">
							<option <?php echo ($track_active == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_active == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Enabling this places the Matomo tracking code in the footer of your website.', 'matomo-analytics'); ?></small></label></p>

						<h2><?php _e('Additional options', 'matomo-analytics'); ?></h2>
						<p><label for="matomo_error_pages"><strong><?php _e('Monitor 404 pages:', 'matomo-analytics'); ?></strong> <select name="matomo_error_pages" tabindex="4">
							<option <?php echo ($track_error_pages == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_error_pages == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Monitor which urls redirect to a 404 error page. Discover which page the visitor tried to reach and where they came from. Look for "404/URL" items in the Behaviour > Page Titles section. This can help you find and fix old/invalid urls.', 'matomo-analytics'); ?></small></label></p>

						<p><label for="matomo_feed_clicks"><strong><?php _e('Record feed clickthrough:', 'matomo-analytics'); ?></strong> <select name="matomo_feed_clicks" tabindex="5">
							<option <?php echo ($track_feed_clicks == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_feed_clicks == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Record when visitors click through to your website from your RSS and Atom feeds. Look for the "feed_click" campaign in Acquisition > Campaigns. Includes a basic bot filter.', 'matomo-analytics'); ?></small></label></p>

						<h2><?php _e('Privacy options', 'matomo-analytics'); ?></h2>
						<p><label for="matomo_incognito"><strong><?php _e('Respect DoNotTrack:', 'matomo-analytics'); ?></strong> <select name="matomo_incognito" tabindex="7">
							<option <?php echo ($track_incognito == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_incognito == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Do not track visitors who sent a DoNotTrack request (incognito mode). Setting this to "Yes" will respect DoNotTrack requests but results in less accurate stats. Selecting "No" may break some (unenforceable) privacy laws in certain countries. If you choose to ignore DoNotTrack, you may want to anonymize the recorded data in your Matomo settings as a courtesy.', 'matomo-analytics'); ?></small></label></p>

						<p><label for="matomo_heartbeat"><strong><?php _e('Track time on site:', 'matomo-analytics'); ?></strong> <select name="matomo_heartbeat" tabindex="8">
							<option <?php echo ($track_heartbeat == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_heartbeat == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Periodically check on visitors to see if they are still there. This results in time visitors spent on pages is recorded with greater accuracy. Time spent on pages is visible on many dashboards throughout Matomo.', 'matomo-analytics'); ?></small></label></p>

						<h2><?php _e('Advanced options', 'matomo-analytics'); ?></h2>
						<p><em><?php _e('You probably do <u>not</u> need these.', 'matomo-analytics'); ?></em></p>

						<p><label for="matomo_wc_downloads"><strong><?php _e('Track downloads in WooCommerce:', 'matomo-analytics'); ?></strong> <select name="matomo_wc_downloads" tabindex="9">
							<option <?php echo ($track_wc_downloads == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_wc_downloads == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Tracks downloads for WooCommerce downloadable products that get downloaded via the users account/dashboard. Downloads show up in Behavior > Downloads. The url is unformatted because WooCommerce does not provide a way to do so.', 'matomo-analytics'); ?></small></label></p>

						<p><label for="matomo_feed_impressions"><strong><?php _e('Record feed views:', 'matomo-analytics'); ?></strong> <select name="matomo_feed_impressions" tabindex="10">
							<option <?php echo ($track_feed_impressions == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_feed_impressions == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Record when readers view your posts through your RSS and Atom feeds. Pageviews are counted as regular pageviews and as a campaign named "feed_impression" in Acquisition > Campaigns. Includes a basic bot filter. Does not track excerpts. <b>Caution:</b> Using this feature may offset your pageviews.', 'matomo-analytics'); ?></small></label></p>

						<p><label for="matomo_accuracy"><strong><?php _e('Track in high accuracy mode:', 'matomo-analytics'); ?></strong> <select name="matomo_accuracy" tabindex="11">
							<option <?php echo ($track_high_accuracy == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
							<option <?php echo ($track_high_accuracy == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
						</select><br /><small><?php _e('Track in a fast polling mode for higher accuracy for certain stats. This is useful if your outbound clicks and similar stats seem inconclusive. <b>Caution:</b> Using this feature may increase CPU usage on the visitors browser and on your server.', 'matomo-analytics'); ?></small></label></p>

						<p class="submit">
							<input tabindex="1000" type="submit" name="matomo_save" class="button-primary" value="<?php _e('Save options', 'matomo-analytics'); ?>" />
						</p>

					</form>

				</div>
			</div>

		</div>
		<div id="right-column" class="ajdg-postbox-container">

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('Matomo Tracker', 'matomo-analytics'); ?></h2>
				<div id="general" class="ajdg-postbox-content">
					<p><strong><?php _e('Get help with Matomo Tracker', 'matomo-analytics'); ?></strong></p>
					<p><?php _e('Use the buttons below if you have any questions about using Matomo Tracker. I am always happy to help!', 'matomo-analytics'); ?></p>

					<p><a class="button-primary" href="https://ajdg.solutions/product/support-ticket/" target="_blank" title="<?php _e('Buy support ticket', 'matomo-analytics'); ?>"><?php _e('Buy a support ticket', 'matomo-analytics'); ?></a> <a class="button-primary" href="https://support.ajdg.net/knowledgebase.php" target="_blank" title="<?php _e('Knowledgebase', 'matomo-analytics'); ?>"><?php _e('Knowledgebase', 'matomo-analytics'); ?></a> <a class="button-secondary" href="https://wordpress.org/support/plugin/matomo-analytics/" target="_blank" title="<?php _e('Forum on wordpress.org', 'matomo-analytics'); ?>"><?php _e('Forum on wordpress.org', 'matomo-analytics'); ?></a></p>

					<p><strong><?php _e('Support Matomo Tracker', 'matomo-analytics'); ?></strong></p>
					<p><?php _e('Consider writing a review or making a donation if you like the plugin or if you find the plugin useful. Thanks for your support!', 'matomo-analytics'); ?></p>

					<p><a class="button-primary" href="https://ajdg.solutions/product/token-of-thanks/" target="_blank" title="<?php _e('Support me with a token of thanks', 'matomo-analytics'); ?>"><?php _e('Gift a token of thanks', 'matomo-analytics'); ?></a> <a class="button-secondary" href="https://wordpress.org/support/plugin/matomo-analytics/reviews?rate=5#postform" target="_blank" title="<?php _e('Write review on wordpress.org', 'matomo-analytics'); ?>"><?php _e('Write review on wordpress.org', 'matomo-analytics'); ?></a></p>

					<p><strong><?php _e('Plugins and services', 'matomo-analytics'); ?></strong></p>
					<table width="100%">
						<tr>
							<td width="50%">
								<div class="ajdg-sales-widget" style="display: inline-block; margin-right:2%;">
									<a href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/monetize-your-site.jpg", __FILE__); ?>" alt="AdRotate Professional" width="228" height="120"></div></a>
									<a href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><div class="title"><?php _e('AdRotate Professional', 'matomo-analytics'); ?></div></a>
									<div class="sub_title"><?php _e('WordPress Plugin', 'matomo-analytics'); ?></div>
									<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><?php _e('Starting at', 'matomo-analytics'); ?> &euro; 49,-</a></div>
									<hr>
									<div class="description"><?php _e('Place any kind of advert including those from Google Adsense or affiliate links on your WordPress and ClassicPress website.', 'matomo-analytics'); ?></div>
								</div>
							</td>
							<td>
								<div class="ajdg-sales-widget" style="display: inline-block;">
									<a href="https://ajdg.solutions/plugins/" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/more-plugins.jpg", __FILE__); ?>" alt="AJdG Solutions Plugins" width="228" height="120"></div></a>
									<a href="https://ajdg.solutions/plugins/" target="_blank"><div class="title"><?php _e('All my plugins', 'matomo-analytics'); ?></div></a>
									<div class="sub_title"><?php _e('WordPress and ClassicPress', 'matomo-analytics'); ?></div>
									<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/plugins/" target="_blank"><?php _e('View now', 'matomo-analytics'); ?></a></div>
									<hr>
									<div class="description"><?php _e('Excellent plugins for WordPres, ClassicPress, WooCommerce and bbPress. Most of them are completely FREE to use!', 'matomo-analytics'); ?></div>
								</div>
							</td>
						</tr>
					</table>

				</div>
			</div>

			<div class="ajdg-postbox">
				<h2 class="ajdg-postbox-title"><?php _e('News & Updates', 'matomo-analytics'); ?></h2>
				<div id="news" class="ajdg-postbox-content">
					<p><a href="http://ajdg.solutions/feed/" target="_blank" title="Subscribe to the AJdG Solutions RSS feed!" class="button-primary"><i class="icn-rss"></i><?php _e('Subscribe via RSS feed', 'matomo-analytics'); ?></a> <em><?php _e('No account required!', 'matomo-analytics'); ?></em></p>

					<?php wp_widget_rss_output(array(
						'url' => 'http://ajdg.solutions/feed/',
						'items' => 5,
						'show_summary' => 1,
						'show_author' => 0,
						'show_date' => 1)
					); ?>
				</div>
			</div>

		</div>
	</div>
</div>
<center><small><?php _e('Arnan de Gans and "Matomo Tracker" are not affiliated with Matomo. For support with Matomo itself check out their website.', 'matomo-analytics'); ?></small></center>