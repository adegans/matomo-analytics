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

<form name="matomo-analytics" method="post">
<?php wp_nonce_field('matomo-analytics','matomo_nonce'); ?>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e("Required Settings", 'matomo-analytics'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="matomo_siteid"><strong><?php _e('Your Matomo site ID:', 'matomo-analytics'); ?></strong><br /><input tabindex="1" name="matomo_siteid" type="text" class="search-input" style="width:100%;" value="<?php echo $siteid;?>" autocomplete="off" /><br /><small><?php _e('This is a number provided by your Matomo server.', 'matomo-analytics'); ?></small></label></p>
	
				<p><label for="matomo_siteurl"><strong><?php _e('Your matomo server address:', 'matomo-analytics'); ?></strong><br /><input tabindex="2" name="matomo_siteurl" type="text" class="search-input" style="width:100%;" value="<?php echo $siteurl;?>" autocomplete="off" /><br /><small><?php _e('The url to your Matomo server. You can also enter your cloud hosted Matomo address. <b>Examples:</b> https://matomo.yourdomain.com or https://yourdomain.com/matomo or https://yourname.matomo.cloud.', 'matomo-analytics'); ?></small></label></p>
	
				<p><label for="matomo_tracker_active"><strong><?php _e('Enable tracking:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_tracker_active" tabindex="3">
					<option <?php echo ($track_active == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_active == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Enabling this places the Matomo tracking code in the footer of your website.', 'matomo-analytics'); ?></small></label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e("Additional options", 'matomo-analytics'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="matomo_error_pages"><strong><?php _e('Monitor 404 pages:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_error_pages" tabindex="4">
					<option <?php echo ($track_error_pages == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_error_pages == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Monitor which urls redirect to a 404 error page. Discover which page the visitor tried to reach and where they came from. Look for "404/URL" items in the Behaviour > Page Titles section. This can help you find and fix old/invalid urls.', 'matomo-analytics'); ?></small></label></p>

				<p><label for="matomo_feed_clicks"><strong><?php _e('Record feed clickthrough:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_feed_clicks" tabindex="5">
					<option <?php echo ($track_feed_clicks == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_feed_clicks == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Record when visitors click through to your website from your RSS and Atom feeds. Look for the "feed_click" campaign in Acquisition > Campaigns. Includes a basic bot filter.', 'matomo-analytics'); ?></small></label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e("Privacy options", 'matomo-analytics'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="matomo_incognito"><strong><?php _e('Respect DoNotTrack:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_incognito" tabindex="7">
					<option <?php echo ($track_incognito == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_incognito == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Do not track visitors who sent a DoNotTrack request (incognito mode). Setting this to "Yes" will respect DoNotTrack requests but results in less accurate stats. Selecting "No" may break some (unenforceable) privacy laws in certain countries. If you choose to ignore DoNotTrack, you may want to anonymize the recorded data in your Matomo settings as a courtesy.', 'matomo-analytics'); ?></small></label></p>

				<p><label for="matomo_heartbeat"><strong><?php _e('Track time on site:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_heartbeat" tabindex="8">
					<option <?php echo ($track_heartbeat == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_heartbeat == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Periodically check on visitors to see if they are still there. This results in time visitors spent on pages is recorded with greater accuracy. Time spent on pages is visible on many dashboards throughout Matomo.', 'matomo-analytics'); ?></small></label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e("Advanced options", 'matomo-analytics'); ?></h2>
			<div class="ajdg-box-content">

				<p><em><?php _e('You probably do <u>not</u> need these.', 'matomo-analytics'); ?></em></p>

				<p><label for="matomo_wc_downloads"><strong><?php _e('Track downloads in WooCommerce:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_wc_downloads" tabindex="9">
					<option <?php echo ($track_wc_downloads == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_wc_downloads == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Tracks downloads for WooCommerce downloadable products that get downloaded via the users account/dashboard. Downloads show up in Behavior > Downloads. The url is unformatted because WooCommerce does not provide a way to do so.', 'matomo-analytics'); ?></small></label></p>

				<p><label for="matomo_feed_impressions"><strong><?php _e('Record feed views:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_feed_impressions" tabindex="10">
					<option <?php echo ($track_feed_impressions == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_feed_impressions == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Record when readers view your posts through your RSS and Atom feeds. Pageviews are counted as regular pageviews and as a campaign named "feed_impression" in Acquisition > Campaigns. Includes a basic bot filter. Does not track excerpts. <b>Caution:</b> Using this feature may offset your pageviews.', 'matomo-analytics'); ?></small></label></p>

				<p><label for="matomo_accuracy"><strong><?php _e('Track in high accuracy mode:', 'matomo-analytics'); ?></strong> <span class="alignright"><select name="matomo_accuracy" tabindex="11">
					<option <?php echo ($track_high_accuracy == 'no') ? 'selected' : '';  ?> value="no"><?php _e('No', 'matomo-analytics'); ?></option>
					<option <?php echo ($track_high_accuracy == 'yes') ? 'selected' : '';  ?> value="yes"><?php _e('Yes', 'matomo-analytics'); ?></option>
				</select></span>
				<br /><small><?php _e('Track in a fast polling mode for higher accuracy for certain stats. This is useful if your outbound clicks and similar stats seem inconclusive. <b>Caution:</b> Using this feature may increase CPU usage on the visitors browser and on your server.', 'matomo-analytics'); ?></small></label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<p class="submit">
			  	<input type="submit" name="matomo_save_settings" class="button-primary" value="<?php _e("Save settings", 'matomo-analytics'); ?>" tabindex="1000" />
			</p>
		</div>

	</div>
	<div class="ajdg-box-one">

		<?php include_once(__DIR__.'/ajdg-matomo-sidebar.php'); ?>

	</div>
</div>
<center><small><?php _e('Arnan de Gans and "Matomo Tracker" are not affiliated with Matomo. For support with Matomo itself check out their website.', 'matomo-analytics'); ?></small></center>

</form>
