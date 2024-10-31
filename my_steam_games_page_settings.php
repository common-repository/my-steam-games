 <div class="wrap my_steam_games">
   <div id="icon-my_steam_games" class="icon32"><br /></div><h2><?php _e('My Steam Games', 'my_steam_games'); ?> V<?php echo get_option("my_steam_games_version"); ?> &bull; <?php _e('Settings', 'my_steam_games'); ?></h2>
	<?php
	/*
	 * Save Settings
	 */
	if ('my_steam_games_settings_save' == $_POST['action'])
	{
		update_option("my_steam_games_api_key", $_POST['my_steam_games_api_key']);
		update_option("my_steam_games_ignore", $_POST['my_steam_games_ignore']);
		update_option("my_steam_games_cache_duration", $_POST['my_steam_games_cache_duration']);
		update_option("my_steam_games_icon", $_POST['my_steam_games_icon']);
		update_option("my_steam_games_appid", $_POST['my_steam_games_appid']);
		update_option("my_steam_games_playtime", $_POST['my_steam_games_playtime']);
		update_option("my_steam_games_sort", $_POST['my_steam_games_sort']);
		update_option("my_steam_games_per_page", $_POST['my_steam_games_per_page']);
		update_option("my_steam_games_shoplink", $_POST['my_steam_games_shoplink']);
		update_option("my_steam_games_show_count", $_POST['my_steam_games_show_count']);
		update_option("my_steam_games_default_css", $_POST['my_steam_games_default_css']);		
				
		$cache_dir = WP_PLUGIN_DIR . '/my-steam-games/cache/';
		if (is_dir($cache_dir)) {
			if ($dh = opendir($cache_dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file!="." AND $file !="..") {
						@unlink($cache_dir . $file);
					}
				}
				closedir($dh);
			}
		}
		
		echo '<div class="updated"><p>' . __('Settings saved!', 'my_steam_games') . '</p></div>';
	}
	?>

	<div id="poststuff"> 
	<form method="post" action="">

		<div class="postbox">
		   <h3><?php _e('Account settings', 'my_steam_games'); ?></h3>
		   <div class="inside">
				<table class="my_steam_settings" cellspacing="0" border="0">
					<tr>
						<td class="left"><label for="my_steam_games_api_key"><?php _e('API Key', 'my_steam_games'); ?></label></td>
						<td class="right"><input type="text" style="width: 50%;" name="my_steam_games_api_key" id="my_steam_games_api_key" value="<?php echo get_option("my_steam_games_api_key"); ?>" />					
						<br />
						<i><?php _e('You can get an API Key <a href="http://steamcommunity.com/dev/apikey">here</a>.', 'my_steam_games'); ?></i></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="postbox">
		   <h3><?php _e('Display settings', 'my_steam_games'); ?></h3>
		   <div class="inside">
				<table class="my_steam_settings" cellspacing="0" border="0">
					<tr>
						<td class="left"><label for="my_steam_games_cache_duration"><?php _e('Cache duration', 'my_steam_games'); ?></label></td>
						<td class="right"><input type="text" style="width: 50%;" name="my_steam_games_cache_duration" id="my_steam_games_cache_duration" value="<?php echo get_option("my_steam_games_cache_duration"); ?>" />					
						<br />
						<i><?php _e('How long should the list be cached (in seconds).', 'my_steam_games'); ?></i></td>
					</tr>
					<tr>
					<?php $cur_my_steam_games_icon = get_option("my_steam_games_icon"); ?>
						<td class="left"><?php _e('Display icons', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="radio" name="my_steam_games_icon" id="my_steam_games_icon_large" value="large"<?php if('large' == $cur_my_steam_games_icon) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_icon_large"><?php _e('Large', 'my_steam_games'); ?></label><br />
							<input type="radio" name="my_steam_games_icon" id="my_steam_games_icon_small" value="small"<?php if('small' == $cur_my_steam_games_icon) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_icon_small"><?php _e('Small', 'my_steam_games'); ?></label><br />
							<input type="radio" name="my_steam_games_icon" id="my_steam_games_icon_none" value="none"<?php if('none' == $cur_my_steam_games_icon) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_icon_none"><?php _e('None', 'my_steam_games'); ?></label>						
						</td>
					</tr>
					<tr>					
					<?php $cur_my_steam_games_appid = get_option("my_steam_games_appid"); ?>
						<td class="left"><?php _e('Display AppID', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="checkbox" name="my_steam_games_appid" id="my_steam_games_appid" value="yes"<?php if('yes' == $cur_my_steam_games_appid) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_appid"><?php _e('Yes', 'my_steam_games'); ?></label><br />
						</td>
					</tr>					
					<?php $cur_my_steam_games_playtime = get_option("my_steam_games_playtime"); ?>
						<td class="left"><?php _e('Display time played', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="checkbox" name="my_steam_games_playtime" id="my_steam_games_playtime" value="yes"<?php if('yes' == $cur_my_steam_games_playtime) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_playtime"><?php _e('Yes', 'my_steam_games'); ?></label><br />
						</td>
					</tr>
					<tr>
					<?php $cur_my_steam_games_shoplink = get_option("my_steam_games_shoplink"); ?>
						<td class="left"><?php _e('Link images and titles to Steam store page', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="checkbox" name="my_steam_games_shoplink" id="my_steam_games_shoplink" value="yes"<?php if('yes' == $cur_my_steam_games_shoplink) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_shoplink"><?php _e('Yes', 'my_steam_games'); ?></label><br />
						</td>
					</tr>
					<tr>					
					<?php $cur_my_steam_games_show_count = get_option("my_steam_games_show_count"); ?>
						<td class="left"><?php _e('Show total number of games', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="checkbox" name="my_steam_games_show_count" id="my_steam_games_show_count" value="yes"<?php if('yes' == $cur_my_steam_games_show_count) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_show_count"><?php _e('Yes', 'my_steam_games'); ?></label><br />
						</td>
					</tr>
					<tr>						
					<?php $cur_my_steam_games_default_css = get_option("my_steam_games_default_css"); ?>
						<td class="left"><?php _e('Use the default style from the plugin', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="checkbox" name="my_steam_games_default_css" id="my_steam_games_default_css" value="yes"<?php if('yes' == $cur_my_steam_games_default_css) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_default_css"><?php _e('Yes', 'my_steam_games'); ?></label><br />
							<br />
							<i><?php _e('You can theme the table in your themes stylesheet.', 'my_steam_games'); ?></i>
						</td>
					</tr>
					<tr>
					<?php $cur_my_steam_games_sort = get_option("my_steam_games_sort"); ?>
						<td class="left"><?php _e('Sort by', 'my_steam_games'); ?></td>
						<td class="right">
							<input type="radio" name="my_steam_games_sort" id="my_steam_games_sort_name-DESC" value="name-DESC"<?php if('name-DESC' == $cur_my_steam_games_sort) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_sort_name-DESC"><?php _e('Name DESC', 'my_steam_games'); ?></label><br />
							<input type="radio" name="my_steam_games_sort" id="my_steam_games_sort_name-ASC" value="name-ASC"<?php if('name-ASC' == $cur_my_steam_games_sort) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_sort_name-ASC"><?php _e('Name ASC', 'my_steam_games'); ?></label>	<br />
							<input type="radio" name="my_steam_games_sort" id="my_steam_games_sort_playtime_forever-DESC" value="playtime_forever-DESC"<?php if('playtime_forever-DESC' == $cur_my_steam_games_sort) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_sort_playtime_forever-DESC"><?php _e('Playtime DESC', 'my_steam_games'); ?></label> <br />
							<input type="radio" name="my_steam_games_sort" id="my_steam_games_sort_playtime_forever-ASC" value="playtime_forever-ASC"<?php if('playtime_forever-ASC' == $cur_my_steam_games_sort) { echo ' checked="checked"'; } ?> /> <label for="my_steam_games_sort_playtime_forever-ASC"><?php _e('Playtime ASC', 'my_steam_games'); ?></label>						
						</td>
					</tr>
					<tr>
						<td class="left"><label for="my_steam_games_per_page"><?php _e('Games per page', 'my_steam_games'); ?></label></td>
						<td class="right"><input type="text" style="width: 50%;" name="my_steam_games_per_page" id="my_steam_games_per_page" value="<?php echo get_option("my_steam_games_per_page"); ?>" />					
						<br />
						<i><?php _e('How many games should be displayed on one page?', 'my_steam_games'); ?></i></td>
					</tr>
				</table>				
			</div>
		</div>
		
		<div class="postbox">
		   <h3><?php _e('Ignore these games', 'my_steam_games'); ?></h3>
		   <div class="inside">
				<p><?php _e('Insert the AppIDs you do not want to show in your list. One per line!', 'my_steam_games'); ?></p>
				<p><textarea name="my_steam_games_ignore" style="height: 300px; width: 90%;" id="my_steam_games_ignore"><?php echo get_option("my_steam_games_ignore"); ?></textarea>					
				<br />
				<i><?php _e('The AppID is visible in the Steam store url of the game.', 'my_steam_games'); ?></i></p>
			</div>
		</div>
		
		
		<p><input type="submit" class="button-primary" value="<?php _e('Save settings', 'my_steam_games'); ?>" />
		<input name="action" value="my_steam_games_settings_save" type="hidden" /></p>
	   </form>
   </div>
</div>