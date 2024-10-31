=== My Steam Games ===
Contributors: Waaaghgasm
Donate link: http://steamcommunity.com/profiles/76561197979593044/wishlist/
Tags: steam, games, api, page
Requires at least: 3.0
Tested up to: 3.5.2
Stable tag: 0.1

Allows you to create a list of your steam games.

== Description ==

This plugin allows you to view your visitors a list of the [Steam](http://store.steampowered.com/) games you own.
You need an API key from Steam, available [here](http://steamcommunity.com/dev/apikey) that is bound to your
domain and allows Steam to identify your requests.

You can decide which games you don't want to show, if an icon should be shown and if you want to display the time
you have spent in each game. 


== Installation ==

1. Upload `my_steam_games` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Get your [SteamID64](http://steamidconverter.com/)
1. Create a new page and enter [my_steam_games=xxxxx]. Replace xxxxx with your SteamID64.
1. Publish the page and enjoy!

== Frequently Asked Questions ==

= Why should I use the cache? =
The Steam API is pretty slow, you don't want all your users to wait this long.

= What is the best value for the cache? =
This depends on how often you buy new games. 
3600 seconds (one hour) is good if you want the list to update often.
86400 seconds (one day) is good for the performance.

= Do I need to make my Steam profile public? =
Short answer: Yes!

== Screenshots ==

1. List of Steam Games


== Changelog ==

= 0.1 =
Plugin release
