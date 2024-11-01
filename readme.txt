=== WP Tuit ===
Contributors: lucianohpcv
Tags: twitter, tweet, tuit
Requires at least: 2.8
Tested up to: 2.8.4
Stable tag: 1.0.2

Shows your lastest tweets in your WordPress blog

== Description ==

Show your lastest tweets in your WordPress blog.

You can choose between a widget or editing your theme and include a function

== Installation ==

1. Upload `wp-tuit.php` to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

= Settings =
1. Go to 'Settings', 'WP Tuit' and set your Twitter username
2. Go to 'Appearance', 'Editor' and put `<?php if (function_exists('wp_tuit')) wp_tuit(); ?>` wherever you want to appear your lastest tweet. This just shows one tweet, no matter how many tweets you'd set.
3. The other option, if you don't want to edit your theme, is to use the widget. Go to 'Appearance', 'Widgets' and place the WP Tuit widget somewhere in your sidebar (or footerbar, or headerbar, wherever you have your widget bar). This shows the amount of tweets you'd set in the WP Tuit settings.
4. You also can edit your theme and put the `wp_tuit_widget()` function wherever you want. Just write this: `<?php if (function_exists('wp_tuit_widget')) wp_tuit_widget(); ?>`.

== Frequently Asked Questions ==

= Can I change the style of the list or paragraph? =

Yes. Just add .wp-tuit to your stylesheet and set your desired styles: `p.wp-tuit` for style the paragraph and `ul.wp-tuit` for style the list.

= Your plugin sucks =

Oh, really? Where's yours?

= You don't speak English, do you? =
Nop... sorry if you don't understand something I wrote.

= I have other question =
Just go to my blog, click in 'Acerca de mi' and fill the form (name, email, subject, message and captcha). Altough I don't speak English fluently, I can read it perfectly, so the 95% of the times I'll understand what you're writing me.

== Changelog ==

= 1.0.2 =
* Changed: removed some unnecessary code
* Fixed an error in readme.txt

= 1.0.1 =
* Changed: two variables were wrong

= 1.0 =
* Changed: The # now links to the status
* Added: The widget title links to your Twitter user
* Changed: less code :P

= 0.9.4 =
* Added: widget :D
* Added: Two separated functions to show your latest tweets. One for show only one, and the other used for the WP Tuit widget. You can also use the WP Tuit widget function to place it wherever you want in your theme

= 0.9 =
* Added: If shows 1 (one) tweet, set it as paragraph (`<p>`). If shows more than one tweet (>=2), set it as a list (`<ul>`)

= 0.3 =
* Fixed a couple errors
* Added: option to set how many tweets to show

= 0.1 =
* First version. Nothing to put in here :P

== Screenshots ==

1. WP Tuit Settings page
2. WP Tuit in my blog