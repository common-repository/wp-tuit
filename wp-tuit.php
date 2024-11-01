<?php
/*
Plugin Name: WP Tuit
Plugin URI: http://www.luxiano.com.ar/wp-tuit/
Description: Creates a function to write your lastest tweet in your WordPress blog
Version: 1.0.2
Author: Luxiano
Author URI: http://www.luxiano.com.ar
*/

// for version control and installation
define('wptuit_VERSION', '1.0.2');
	
function wp_tuit_wp($T) {
	global $wptuit_cache, $wptuit_username, $wptuit_statuslink, $wptuit_tweets;
	include_once(ABSPATH . WPINC . '/feed.php');
	add_filter( 'wp_feed_cache_transient_lifetime', create_function('$a', "return $wptuit_cache;") );
	if ($wptuit_username == '') { $wptuit_username = 'WPTuit'; }
	if ($wptuit_tweets == 0 || preg_match("/\D/",$wptuit_tweets)) { $wptuit_tweets = 1; }
	
	if ($T == "") : //for ONE tweet
	$feed = fetch_feed("http://twitter.com/statuses/user_timeline/" . $wptuit_username . ".rss?count=1");
	$maxitems = $feed->get_item_quantity(1);
	$rss_tweet = $feed->get_items(0, $maxitems);
	endif; //end for ONE tweet
	
	if ($T == "widget") : //for widget
	$feed = fetch_feed("http://twitter.com/statuses/user_timeline/" . $wptuit_username . ".rss?count=$wptuit_tweets");
	$maxitems = $feed->get_item_quantity($wptuit_tweets);
	$rss_tweet = $feed->get_items(0, $maxitems);
	
	echo '	<ul class="wp-tuit">'."\n";
	endif; //end for widget
		
	//preparing the exit
	foreach ( $rss_tweet as $item ) :
		$tuit = $item->get_title();
		$tuit = str_ireplace($wptuit_username.': ', '', $tuit);
		$tuit = make_clickable($tuit);
		$tuit = preg_replace('/(^|\s)@(\w+)/','<a href="http://twitter.com/\2">\1@\2</a>',$tuit);
		$tuit = preg_replace('/(^|\s)#(\w+)/',' <a href="http://search.twitter.com/search?q=%23\2">\1#\2</a>',$tuit);
		$tuit_status = $item->get_link(); //status link
    	
	//put a # with link to twitter user?, define tweets output
		if ($T == "") : //for ONE tweet
		$prefix = '<p class="wp-tuit">';
		if ($wptuit_statuslink == 1){
			$suffix = " <a href=\"$tuit_status\">&#35;</a></p>";
		}
		else {
			$suffix = "</p>";
		}
		endif; //end for ONE tweet
		
		if ($T == "widget") : //for widget
			$prefix = "<li>";
			if ($wptuit_statuslink == 1){
				$suffix = " <a href=\"$tuit_status\">&#35;</a></li>\n";
			}
			else {
				$suffix = "</li>\n";
			}
		endif; // end for widget
		
		echo stripslashes($prefix) . $tuit . stripslashes($suffix);

	endforeach;
		if ($T == "widget") {echo '	</ul>'."\n";}

} //end wp_tuit_wp()

//functions to show tweets
function wp_tuit() {
	wp_tuit_wp("");
}

function wp_tuit_widget() {
	wp_tuit_wp("widget");
}
	
	  // try to always get the values from the database
	$wptuit_version = get_option(wptuit_version);
	$wptuit_cache = get_option(wptuit_cache);
	$wptuit_username = get_option(wptuit_username);
	$wptuit_statuslink = get_option(wptuit_statuslink);
	$wptuit_tweets = get_option(wptuit_tweets);
	
	// if the database value returns empty use defaults
	if($wptuit_version != wptuit_VERSION) 
	{
		$wptuit_version = wptuit_VERSION; update_option('wptuit_version', wptuit_VERSION);
		$wptuit_cache = '300'; update_option('wptuit_cache', $wptuit_cache);
		$wptuit_username = 'WPTuit'; update_option('wptuit_username', $wptuit_username);
		$wptuit_statuslink = '1'; update_option('wptuit_statuslink', $wptuit_statuslink);
		$wptuit_tweets = '1'; update_option('wptuit_tweets', $wptuit_tweets);
	}
	
	function wptuit_pages() {
	    add_options_page('WP Tuit Settings', 'WP Tuit', 8, 'wp-tuit', 'wptuit_settings');
	}
	
	//print options page
	function wptuit_settings() 
	{
     	global $wptuit_version, $wptuit_cache, $wptuit_username, $wptuit_statuslink, $wptuit_tweets;
 
     	// if settings are updated
		if(isset($_POST['update_wptuit'])) 
		{
			if(isset($_POST['wptuit_cache'])) {
				update_option('wptuit_cache', $_POST['wptuit_cache']);
				$wptuit_cache = $_POST['wptuit_cache'];
			}
			if(isset($_POST['wptuit_username'])) {
				update_option('wptuit_username', $_POST['wptuit_username']);
				$wptuit_username = $_POST['wptuit_username'];
			}
			if(isset($_POST['wptuit_statuslink'])) {
				update_option('wptuit_statuslink', $_POST['wptuit_statuslink']);
				$wptuit_statuslink = $_POST['wptuit_statuslink'];
			}
			if(isset($_POST['wptuit_tweets'])) {
				update_option('wptuit_tweets', $_POST['wptuit_tweets']);
				$wptuit_tweets = $_POST['wptuit_tweets'];
			}
		}
		
		// if the user clicks the uninstall button, clean all options and show good-bye message
		if(isset($_POST['uninstall_wptuit'])) 
		{
			delete_option(wptuit_cache);
			delete_option(wptuit_username);
			delete_option(wptuit_statuslink);
			delete_option(wptuit_tweets);
			delete_option(wptuit_version);
			echo '<div class="wrap"><h2>Good Bye!</h2><p>All WP Tuit settings were removed and you can now go to the <a href="plugins.php">plugin menu</a> and deactivate it.</p><h3>Thank you for using WP Tuit '.$wptuit_version.'!</h3><p style="text-align:right"><small>if this happend by accident, <a href="options-general.php?page=wp-tuit">click here</a> to reinstall</small></p></div>';
						
		} 
		else // show the menu
		{
			$wptuit_version = get_option(wptuit_version);
			$wptuit_cache = get_option(wptuit_cache);
			$wptuit_username = get_option(wptuit_username);
			$wptuit_statuslink = get_option(wptuit_statuslink);
			$wptuit_tweets = get_option(wptuit_tweets);
			?>
			<div class="wrap">
				<h2>WP Tuit Settings</h2>
				<small style="display:block;text-align:right">Version: <?php echo $wptuit_version; ?></small>
				<form method="post" action="options-general.php?page=wp-tuit">
					<input type="hidden" name="update_wptuit" value="true" />
			
					<table class="form-table">
				
						<tr valign="top">
							<th scope="row">Twitter username</th>
							<td>
								<input type="text" value="<?php echo $wptuit_username; ?>" name="wptuit_username" id="wptuit_username" />
								<label for="wptuit_username">http://twitter.com/<strong><em>username</em></strong></label>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row">Tweets to show</th>
							<td>
								<input type="text" value="<?php echo $wptuit_tweets; ?>" name="wptuit_tweets" id="wptuit_tweets" />
								<label for="wptuit_tweets">Default = 1</label>
							</td>
						</tr>
			
						<tr valign="top">
							<th scope="row">Cache time</th>
							<td>
								<input type="text" value="<?php echo $wptuit_cache; ?>" name="wptuit_cache" id="wptuit_cache" />
								<label for="wptuit_cache">In seconds, default = 300 (5 minutes)</label>
							</td>
						</tr>
			
						<tr valign="top">
							<th scope="row">Put &#35; linking to your status?</th>
							<td>
								Yes <input type="radio" value="1" <?php if ($wptuit_statuslink == 1) echo "checked"; ?> name="wptuit_statuslink" id="wptuit_statuslink" />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								No  <input type="radio" value="0" <?php if ($wptuit_statuslink == 0) echo "checked"; ?> name="wptuit_statuslink" id="wptuit_statuslink" />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label for="wptuit_statuslink">Put a &#35; linking to your Twitter status at the end of your tweet?</label>
							</td>
						</tr>
	
					</table>
					
					<p>Go to Appearance &raquo; Editor and put <code>&lt;?php if (function_exists('wp_tuit')) wp_tuit(); ?&gt;</code> in your template, wherever you want to appear your lastest tweet. This just shows one tweet, no matter how many tweets you'd set.</p>
					<p>The other option, if you don't want to edit your theme, is to use the widget. Go to Appearance &raquo; Widgets and place the WP Tuit widget somewhere in your sidebar (or footerbar, or headerbar, wherever you have your widget bar). This shows the amount of tweets you'd set in the WP Tuit settings.</p>
					<p>You also can edit your theme and put the <code>wp_tuit_widget()</code> function wherever you want. Just write this: <code>&lt;?php if (function_exists('wp_tuit_widget')) wp_tuit_widget(); ?&gt;</code></p>
					
					<p class="submit"><input type="submit" name="Submit" value="Save changes" /></p>
			
				</form>
			
			
				<h2>Uninstall</h2>
				
				<p>For clear the settings before you deactivate the plugin. This will erase the settings used by WP Tuit from the database, so you won't have to worry about the junk ;).</p>
				
				<form method="post" action="options-general.php?page=wp-tuit">
					<input type="hidden" name="uninstall_wptuit" value="true" />
					<p class="submit"><input type="submit" name="Submit" value="Clear settings" /></p>
				</form>
			</div>
<?php
		}
		
	}//function wptuit_settings end
	
	function wptuit_widget_register() {
		if ( function_exists('register_sidebar_widget') ) :
		
		function wptuit_widget($args) {
		extract($args);
		global $wptuit_username;
		$wptuit_widget_options = get_option('wptuit_widget');
		?>
			<?php echo $before_widget; ?>
				<?php echo $before_title . "<a href=\"http://twitter.com/$wptuit_username\" class=\"wp-tuit\">" . $wptuit_widget_options['wptuit_title'] . "</a>" . $after_title; ?>
					<?php if (function_exists('wp_tuit_widget')) wp_tuit_widget(); ?>
			<?php echo $after_widget; ?>
	<?php
	}
	
	function wptuit_widget_control() {
		$wptuit_widget_options = $wptuit_widget_newoptions = get_option('wptuit_widget');
		if ( $_POST["wptuit-submit"] ) {
			$wptuit_widget_newoptions['wptuit_title'] = strip_tags(stripslashes($_POST["wptuit_title"]));
			if ( empty($wptuit_widget_newoptions['wptuit_title']) ) $wptuit_widget_newoptions['wptuit_title'] = 'WP Tuit';
		}
		if ( $wptuit_widget_options != $wptuit_widget_newoptions ) {
			$wptuit_widget_options = $wptuit_widget_newoptions;
			update_option('wptuit_widget', $wptuit_widget_options);
		}
		$wptuit_widget_title = htmlspecialchars($wptuit_widget_options['wptuit_title'], ENT_QUOTES);
	?>
				<p>
				<label for="wptuit_title"><?php _e('Title:'); ?>
				<input style="width: 250px;" id="wptuit_title" name="wptuit_title" type="text" value="<?php echo $wptuit_widget_title; ?>" /></label>
				</p>
				<input type="hidden" id="wptuit-submit" name="wptuit-submit" value="1" />
	<?php
	}
	register_sidebar_widget('WP Tuit', 'wptuit_widget');
	register_widget_control('WP Tuit', 'wptuit_widget_control', '270');
	endif;
	}
	add_action('admin_menu', 'wptuit_pages');
	add_action('widgets_init', 'wptuit_widget_register');
?>