<?php
/**
 * Plugin Name:	Ads Invalid Click Protection
 * Plugin URI:	http://www.impactpixel.co/AICP
 * Description:	The plugin protects your Adsense account from being banned due to invalid click activity. It hides Adsense ads for a defined time after a number of clicks by a user. Happy using :)
 * Version:		1.0
 * Author:		Impactpixel
 * Author URI:	http://www.impactpixel.co/
 * License: GPL2
 *
 * Copyright 2020 Impactpixel .
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 */

/**
 * Restrict direct access to the file, for security purpose.
 */
defined('ABSPATH') or die('You can not access it directly');


/**
 * class Ads_Invalid_Click_Protection
 * Handles plugin functionality
 */
class Ads_Invalid_Click_Protection {

	public static $group = 'Ads-invalid-click-protection';

	/**
	 * Class Constructor
	 * Initiates the plugin functionality
	 * by attaching callbacks to WordPress hooks
	 */
	function __construct() {
		$plugin = plugin_basename(__FILE__); 
		register_activation_hook(__FILE__, [$this, 'activate']);
		add_action('admin_init', [$this, 'redirect']);
		add_action('admin_init', [$this, 'register_settings']);
		add_action('admin_menu', [$this, 'admin_menu']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue']);
		add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
		add_filter('plugin_action_links_'.$plugin, [$this, 'settings_link']);
	}
	
	/**
	 * Function to register settings fields
	 **/
	function register_settings() {
		register_setting( static::$group, 'cfm_aicp_active' );
		register_setting( static::$group, 'cfm_aicp_limit' );
		register_setting( static::$group, 'cfm_aicp_duration' );
		register_setting( static::$group, 'cfm_aicp_delay' );
		register_setting( static::$group, 'cfm_aicp_cookie_name' );
	}

	/**
	 * Function adds a new menu item
	 * "Adsense Invalid Click Protection" in WP admin menu
	 * as a child of Settings menu
	 **/
	function admin_menu() {
		add_options_page('Ads Invalid Click Protection', 'Ads Invalid Click Protection', 'administrator', 'Ads_invalid_click_protection', [$this, 'admin_page']);
	}
	
	/**
	 * Function for Displaying Settings Fields
	 * In Plugin Admin Page
	 **/
	function admin_page() {
		$active	= get_option('cfm_aicp_active', 1);
		$limit	= get_option('cfm_aicp_limit', 2);
		$duration = get_option('cfm_aicp_duration', 3);
		$delay 	= get_option('cfm_aicp_delay', 200);
		$cookie = get_option('cfm_aicp_cookie_name', 'aicpAdClickCookie');
		?>
			<div class="wrap">
				<form method="POST" action="options.php">
					<?php
						settings_fields( static::$group );
						do_settings_sections( static::$group );
					?>
					<div class="container">
						<div class="row">
								<div class="--navbar col-sm-12">
								  <button class="--btn-clicked">AICP</button>
								</div>
								<div class="col-sm-7">
									<div class="--nav-edit --panel">
											<div class="switch__container">
											  <input id="switch-shadow" class="switch switch--shadow" type="checkbox" name="cfm_aicp_active" value="1" <?php checked($active, 1); ?>>
											  <label for="switch-shadow"></label>
											</div>
										<input type="submit" name="submit" id="submit" class="--save" value="Save">
									</div>
									<div class="--panel">
									   
										<div class="form-group">
										  <label for="exampleInputEmail1">Clicks</label>
										  <input type="number" class="form-control" min="0" name="cfm_aicp_limit" value="<?php echo $limit; ?>" required>
										  <small id="emailHelp" class="form-text text-muted">Ad Click Limit</small>
										</div>
										<div class="form-group">
										  <label for="exampleInputPassword1">Hours</label>
										  <input type="number" class="form-control" id="exampleInputPassword1"  name="cfm_aicp_duration" value="<?php echo $duration; ?>" min="0" required>
										  <small id="emailHelp" class="form-text text-muted">Visitor Ban Duration</small>
										</div>
										<div class="form-group">
										  <label for="exampleInputPassword1">Milliseconds</label>
										  <input type="number" class="form-control" id="exampleInputPassword1"  name="cfm_aicp_delay" value="<?php echo $delay; ?>" min="0" required>
										  <small id="emailHelp" class="form-text text-muted">Ad Serving Delay</small>
										</div>
										<input type="hidden" name="cfm_aicp_cookie_name" class="cookie-name-field" value="<?php echo $cookie; ?>">
										<button class="btn btn-detete btn-delete-my-cookie">Delete my cookie</button>
										<button class="btn btn-detete btn-delete-all-cookies">Delete all cookies</button>
									 
									</div>
								</div> 
								<div class="col-sm-5">
										<div class="--panel">
										 <div class="video-container">
											<video width="100%" controls>
											  <source src="<?php echo plugins_url('assets/media/exp.mp4', __FILE__ ); ?>" type="video/mp4">
											  Your browser does not support HTML video.
											</video>
										 </div>
										  <div class="card"  >
											<div class="card-body">
											  <h5 class="card-title">AICP</h5>
											  <h6 class="card-subtitle mb-2 text-muted">AdSense Invalid Click Protector</h6>
											  <p class="card-text">The plugin protects your Adsense account from being banned due to invalid click activity. It hides Adsense ads for a defined time after a number of clicks by a user. Happy using :)</p>
											  <h6 class="card-subtitle mb-2 text-muted">Get the full version :</h6>
											  <a href="https://www.impact-pixel.com" target="_blank" class="card-link">www.impact-pixel.com</a>
											</div>
										  </div>
										</div>
								</div> 
						</div>
					</div>
				</form>
			</div>
		<?php
	}
	
	/**
	 * Embeds Plugin JS Script into website
	 * before Head closing tag
	 **/
	function enqueue() {
		$active	= intval( get_option('cfm_aicp_active', 1) );
		if ($active == 1) {
			$limit	= get_option('cfm_aicp_limit', 2);
			$duration = get_option('cfm_aicp_duration', 3);
			$delay 	= get_option('cfm_aicp_delay', 200);
			$cookie = get_option('cfm_aicp_cookie_name', 'aicpAdClickCookie');
			$aicpconfig = array(
								'cookie'	=> $cookie,
								'limit'		=> $limit,
								'duration'	=> $duration,
								'delay'		=> $delay,
							);
			wp_enqueue_script( 'cmf-aicp-js', plugins_url('assets/js/script.js', __FILE__ ), array('jquery'), null, false);
			wp_localize_script( 'cmf-aicp-js', 'aicpConfig', $aicpconfig );
		}
	}
	
	/**
	 * Embeds plugin JS and CSS files for
	 * plugin settings page
	 **/
	function admin_enqueue() {
		$screen = get_current_screen();
		if ($screen->id == "settings_page_Ads_invalid_click_protection") {
			wp_enqueue_script( 'cmf-aicp-admin-js', plugins_url('assets/js/admin.js', __FILE__ ), array('jquery'), null, false);
			wp_enqueue_style( 'cmf-aicp-bootstrap-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__  ) );
			wp_enqueue_style( 'cmf-aicp-admin-css', plugins_url( 'assets/css/admin.css', __FILE__  ) );
		}
	}
	
	/**
	 * The function adds settings link
	 * to plugins page
	 **/
	function settings_link( $links ) { 
	  $new = '<a href="options-general.php?page=Ads_invalid_click_protection">Settings</a>'; 
	  array_unshift($links, $new); 
	  return $links;
	}
	
	/**
	 * Function to be called when plugin
	 * is activated to do necessary things
	 **/
	function activate() {
		add_option('cfm_aicp_activation_redirect', true);
	}
	
	/**
	 * Redirect to plugin settings page
	 * when plugin is activated
	 **/
	function redirect() {
		if (get_option('cfm_aicp_activation_redirect', false)) {
			delete_option('cfm_aicp_activation_redirect');
			wp_redirect("options-general.php?page=Ads_invalid_click_protection");
			exit;
		}
	}

}

/**
 * Check if Class Exists
 * Initiate an object of the class
 **/
if( class_exists('Ads_Invalid_Click_Protection')){
	$adsinvalidclickprotection = new Ads_Invalid_Click_Protection();
}