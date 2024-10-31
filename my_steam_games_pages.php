<?php

/* 
 * GENERATE PAGES
 */

/*
 * About Page
 */
function my_steam_games_page_main() {
	include 'my_steam_games_page_main.php';
}

/*
 * Settings Page
 */
function my_steam_games_page_settings() {
	include 'my_steam_games_page_settings.php';
}

/*
 * Adds the option page for admin menu
 */
function my_steam_games_add_menu() {
	global $submenu, $wpdb, $import_menu;
	add_option("my_steam_games_api_key","");
	add_option("my_steam_games_cache_duration","3600");	
	add_option("my_steam_games_icon","large");	
	add_option("my_steam_games_appid","yes");
	add_option("my_steam_games_playtime","yes");	
	add_option("my_steam_games_sort","name-ASC");	
	add_option("my_steam_games_per_page","25");	
	add_option("my_steam_games_shoplink","yes");	
	add_option("my_steam_games_show_count","yes");
	add_option("my_steam_games_default_css","yes");	
	
	add_option("my_steam_games_ignore","");	
	add_option("my_steam_games_steamid","");	
	add_option("my_steam_games_version","0.1");	
	
	add_action( 'admin_menu' , 'admin_menu_new_items' );

	add_menu_page(__('Overview', 'google_routeplaner'), __('My Steam Games', 'my_steam_games'), 8, 'my_steam_games.php', 'my_steam_games_page_main', WP_PLUGIN_URL . '/my-steam-games/images/my_steam_games_icon16.png');
	add_submenu_page('my_steam_games.php', __('Settings', 'my_steam_games'), __('Settings', 'my_steam_games'), 8, 'my_steam_games_settings', 'my_steam_games_page_settings');

}

add_action('admin_menu', 'my_steam_games_add_menu');
?>