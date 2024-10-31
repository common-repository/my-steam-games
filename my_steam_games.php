<?php
/*
Plugin Name: My Steam Games
Plugin URI: http://www.waaaghgasm.de
Description: Allows you to create a list of your steam games.
Version: 0.1
Author: Waaaghgasm
Author URI: http://www.waaaghgasm.de
Min WP Version: 3.2
*/



/*
 * Load Language
 */
load_plugin_textdomain('my_steam_games', WP_PLUGIN_DIR . '/my-steam-games/', dirname(plugin_basename(__FILE__)) . '/languages/');


/*
 * Uninstall plugin
 */	
function my_steam_games_uninstall() {
	global $wpdb;
	
	if (function_exists('is_multisite') && is_multisite()) {
		$old_blog = $wpdb->blogid;
		// Get all blog ids
		$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
		foreach ($blogids as $blog_id) {
			switch_to_blog($blog_id);
			my_steam_games_uninstall_delete();
		}
		switch_to_blog($old_blog);
		return;	
	} 
	my_steam_games_uninstall_delete();	
}

function my_steam_games_uninstall_delete() {
	global $wpdb;
	
	delete_option('my_steam_games_api_key');
	delete_option('my_steam_games_ignore');
	delete_option('my_steam_games_steamid');
}

if ( function_exists('register_uninstall_hook') )
    register_uninstall_hook(__FILE__, 'my_steam_games_uninstall_delete');


/*
 * Add new Multisite Blog
 */	
add_action( 'wpmu_new_blog', 'my_steam_games_new_blog', 10, 6); 		
 
function my_steam_games_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;
 
	if (is_plugin_active_for_network('my_steam_games/my_steam_games.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		my_steam_games_install_create();
		switch_to_blog($old_blog);
	}
}

/*
 * Search for plugin code and replace
 */	
function my_steam_games_output($data) {
	if(!preg_match_all("/\[my_steam_games=([0-9]*)\]/", $data, $matches)) {
		return $data;
	} else {
		foreach($matches[1] as $match) {
			$games = my_steam_games_build_list($match);
			$data = str_replace("[my_steam_games=" . $match . "]", $games, $data);
		}
		return $data;
	}
}

function my_steam_games_object_to_array($obj) {
    if(is_object($obj)) $obj = (array) $obj;
    if(is_array($obj)) {
        $new = array();
        foreach($obj as $key => $val) {
            $new[$key] = my_steam_games_object_to_array($val);
        }
    }
    else $new = $obj;
    return $new;       
}

function my_steam_games_sort(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}

function my_steam_games_check_cache($steam_id) {
	$cache_file = WP_PLUGIN_DIR . '/my-steam-games/cache/cache_' . $steam_id . '.txt';
	$cache_duration = (int) get_option("my_steam_games_cache_duration");
	if(file_exists($cache_file)) {
		if(filemtime($cache_file) > (time() -  $cache_duration)) {
			$cache_content = file_get_contents($cache_file);
			return $cache_content;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function my_steam_games_multipage ($limit, $count, $page = 1) {
	$pagi = '';

	if(!$page) {
		$page = 1;
	}

	$pagi .= '<div class="my_steam_games_pagination"><strong>' . __('Page:', 'my_steam_games') . '</strong> ';
	$numofpages = $count / $limit;  
	
	$parts = parse_url($_SERVER['REQUEST_URI']);	
	$current_page = $parts['path'];
	
	if(is_array($_GET)) {
		foreach($_GET as $key => $value) {
			if('gamepage' !== $key) {
				if(isset($query)) {
					$query = '&' . $key . '=' . $value;
				} else {
					$query = '?' . $key . '=' . $value;
				}
			}
		}
	}
	
	for($i = 1; $i <= $numofpages; $i++){  
		if($i == $page){  
			$pagi .= '<span>' . $i . '</span> ';  
		} else {  
			$pagi .= '<a href="' . $current_page;
			if(isset($query)) {
				$pagi .= $query . '&';
			} else {
				$pagi .= '?';
			}
			$pagi .= 'gamepage=' . $i . '">' . $i . '</a> ';  
		}  
	} 

	if(($count % $limit) !== 0){  
		if($i == $page){  
			$pagi .= '<span>' . $i . '</span> ';  
		} else {  
			$pagi .= '<a href="' . $current_page;
			if(isset($query)) {
				$pagi .= $query . '&';
			} else {
				$pagi .= '?';
			}
			$pagi .= 'gamepage=' . $i . '">' . $i . '</a> ';  
		}  
	}
	$pagi .= '</div>';
	
	return $pagi;
}

/*
 * Output map
 */	
function my_steam_games_build_list($steam_id) {
	$sort = explode("-", get_option("my_steam_games_sort"));
	$sorter = constant('SORT_' . $sort[1]);
	
	$my_steam_games_show_count = get_option('my_steam_games_show_count');

	if($results = my_steam_games_check_cache($steam_id)) {

	} else {
		$api_key = get_option("my_steam_games_api_key");
		
		require_once( ABSPATH . 'wp-includes/class-snoopy.php');
		
		$snoopy = new Snoopy;

		$snoopy->fetch("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $api_key . "&steamid=" . $steam_id . "&format=json&include_played_free_games=1&include_appinfo=1");
		$results = $snoopy->results;
		
		$cache_file = WP_PLUGIN_DIR . '/my-steam-games/cache/cache_' . $steam_id . '.txt';
		file_put_contents($cache_file, $results);
		
	}
		
		$json = json_decode($results);
		$json = my_steam_games_object_to_array($json);
		

		my_steam_games_sort($json['response']['games'], $sort[0], $sorter);

		
		$game_filter = array();
		$ignore_games = get_option("my_steam_games_ignore");
		$ignore_games = explode("\n", $ignore_games);
		if(is_array($ignore_games)) {
			foreach($ignore_games as $ignored_game) {
				if('' !== trim($ignored_game)) {
					$game_filter[] = $ignored_game;
				}
			}
		}
		
				
		
		if(is_array($json['response']['games'])) {
		
			$my_steam_games_playtime = get_option("my_steam_games_playtime"); 
			$my_steam_games_appid = get_option("my_steam_games_appid"); 
			$my_steam_games_icon = get_option("my_steam_games_icon"); 
			$my_steam_games_shoplink = get_option("my_steam_games_shoplink"); 
			
			$games .= '<table class="my_steam_games" cellspacing="0" cellpadding="0" style="width: 98%;">
				<thead>
					<tr>';
						if('none' !== $my_steam_games_icon) {
							$games .= '<th>' . __('Icon', 'my_steam_games') . '</th>';
						}
						$games .= '<th>' . __('Name', 'my_steam_games') . '</th>';
						if('yes' == $my_steam_games_playtime) {
							$games .= '<th>' . __('Time played', 'my_steam_games') . '</th>';
						}
					$games .= '</tr>			
				</thead>
				<tfoot>
					<tr>';
						if('none' !== $my_steam_games_icon) {
							$games .= '<th>' . __('Icon', 'my_steam_games') . '</th>';
						}
						$games .= '<th>' . __('Name', 'my_steam_games') . '</th>';
						if('yes' == $my_steam_games_playtime) {
							$games .= '<th>' . __('Time played', 'my_steam_games') . '</th>';
						}
					$games .= '</tr>			
				</tfoot>
				<tbody>';
			
			if(!empty($_GET['gamepage'])) {
				$gamepage = $_GET['gamepage'];
			} else {
				$gamepage = 1;
			}

			$num = 1;
			$game_count = 0;
			$max_games = (int) get_option("my_steam_games_per_page");
			foreach($json['response']['games'] as $game) {
				if(!in_array($game['appid'], $game_filter)) {
					$game_count++;
					if($num > $max_games * $gamepage) {
					
					} elseif ($num < $max_games * ($gamepage - 1) + 1) {
						$num++;
					} else {
						$game['name'] = trim(utf8_decode(utf8_encode($game['name'])));
						
						$games .= '<tr class="';
						if($num&1) { $games .= 'even'; } else { $games .= 'odd'; }
						$games .= '">';
						
							if('none' !== $my_steam_games_icon && 'yes' == $my_steam_games_shoplink) {
								$games .= '<td><a href="http://store.steampowered.com/app/' . $game['appid'] . '/">';
							} elseif ('none' !== $my_steam_games_icon) {
								$games .= '<td>';
							}
													
							switch($my_steam_games_icon) {
								case 'large':
									$games .= '<img src="http://media.steampowered.com/steamcommunity/public/images/apps/' . $game['appid'] . '/' . $game['img_logo_url'] . '.jpg"></td>';
									break;
								case 'small':
									$games .= '<img src="http://media.steampowered.com/steamcommunity/public/images/apps/' . $game['appid'] . '/' . $game['img_icon_url'] . '.jpg"></td>';
									break;
							}
							
							if('none' !== $my_steam_games_icon && 'yes' == $my_steam_games_shoplink) {
								$games .= '</a>';
							}
							
							if('yes' == $my_steam_games_shoplink) {
								$games .= '<td><a href="http://store.steampowered.com/app/' . $game['appid'] . '/"><strong>' . $game['name'] . '</strong></a>';
							} else {
								$games .= '<td><strong>' . $game['name'] . '</strong>';
							}
							
							if('yes' == $my_steam_games_appid) {						
								$games .= '<br />AppID: ' . $game['appid'];
							}
							
							$games .= '</td>';
							if('yes' == $my_steam_games_playtime) {
								$games .= '<td>' . sprintf(__('%s hours', 'my_steam_games'), ceil($game['playtime_forever'] / 60)) . '</td>';
							}
							$games .= '</tr>';	
						$num++;
					}
				}		
			}
			$games .= '</tbody>
			</table>';
			
			$pagination = my_steam_games_multipage ($max_games, $game_count, $gamepage);
			
			$games = $pagination . $games . $pagination;
			
			if('yes' == $my_steam_games_show_count) {
				$games = '<p class="my_steam_games_total">' . sprintf(__('%s games total.', 'my_steam_games'), $game_count) . '</p>' . $games;
			}

			
		} else {
			$games = '<p class="error">' .  __('Could not get game list.', 'my_steam_games') . '</p>';
		}
	

	return $games;
}



/*
 * Content Filter
 */	
if( function_exists('add_filter') ) {
	add_filter('the_content', 'my_steam_games_output'); 
}

function my_steam_games_admin_head() {
	echo '
	<style type="text/css" media="screen">
	@import url("' . WP_PLUGIN_URL . '/my-steam-games/my_steam_games.css");
	</style>';
}
add_action('admin_head', 'my_steam_games_admin_head');

function my_steam_games_head() {
	if('yes' == get_option("my_steam_games_default_css")) {
		echo '
		<style type="text/css" media="screen">
		@import url("' . WP_PLUGIN_URL . '/my-steam-games/my_steam_games.css");
		</style>';
	}
}
add_action('wp_head', 'my_steam_games_head');

/*
 * Include pages
 */	
require_once(WP_PLUGIN_DIR . '/my-steam-games/my_steam_games_pages.php');


?>