<?php
/*
Plugin Name: Client Dash Extension Boilerplate
Description: Starting point for making an add-on for Client Dash.
Version: 0.2.1
Author: Kyle Maurer
Author URI: http://realbigmarketing.com/staff/kyle
*/

// FIXME Style not loading

// Change me! Change me to the function just below
if ( ! function_exists( 'cd_boilerplate' ) ) {

	/**
	 * The function to launch our plugin.
	 *
	 * This entire class is wrapped in this function because we have to ensure that Client Dash has been loaded before our
	 * extension.
	 *
	 * NOTE: This function needs to be changed to whatever your extension is. Also change it at the bottom under
	 * "add_action( 'plugins_loaded', cd_boilerplate' )". ALSO change just above "function_exists()".
	 *
	 * ALSO NOTE: You also need to change the function name "_cd_boilerplate_notice" to something else. Both way at the
	 * bottom, and also right here, under "add_action( 'admin_notices'..."
	 *
	 * Please and thank you.
	 */
	function cd_boilerplate() {
		if ( ! class_exists( 'ClientDash' ) ) {

			// Change me! Change me to the name of the notice function at the bottom
			add_action( 'admin_notices', '_cd_boilerplate_notice' );

			return;
		}

		/**
		 * Class MyCDExtension
		 *
		 * The main class for the extension. Be sure to rename this class something that is unique to your extension.
		 * Duplicate classes will break PHP.
		 */
		class MyCDExtension extends ClientDash {

			/**
			 * Your unique ID.
			 *
			 * This will be prefixed on many things throughout the plugin. So make it relatable to the plugin, but also
			 * unique so it will not be used by ANYTHING else. As an example, Client Dash's prefix is "cd".
			 *
			 * Feel free to modify this example.
			 */
			public static $ID = 'boilerplate';

			/**
			 * This is the page that you want your new tab to reside in. This page must be one of the four core Client Dash
			 * pages: Account, Reports, Help, or Webmaster.
			 *
			 * Feel free to modify this example.
			 */
			private static $page = 'Account';

			/**
			 * Your tab name.
			 *
			 * This is the name of the tab that your plugin's content section will reside in. You may set this to an
			 * existing tab name if you wish, in which case your plugin's content will appear in a new content section in
			 * the tab.
			 *
			 * Feel free to modify this example.
			 */
			private static $tab = 'Boilerplate';

			/**
			 * This is the settings tab name.
			 *
			 * All of your plugin settings will reside here. This may also be the name of an existing tab.
			 *
			 * Feel free to modify this example.
			 */
			public static $settings_tab = 'Boilerplate';

			/**
			 * This is the section name of your boilerplate.
			 *
			 * This will be the display name of the content section that this plugin's content resides in. If there is only
			 * one content section within the tab, the name will not show.
			 *
			 * Feel free to modify this example.
			 */
			private static $section_name = 'Boilerplate Content';

			/**
			 * This is the current version of your plugin. Keep it up to do date!
			 */
			public static $extension_version = '0.1.3';

			/**
			 * This is the path to the plugin.
			 *
			 * Private.
			 *
			 * Don't worry about messing with this property.
			 */
			public $_path;

			/**
			 * This is the url to the plugin.
			 *
			 * Private.
			 *
			 * Don't worry about messing with this property.
			 */
			public $_url;

			/**
			 * This constructor function sets up what happens when the plugin is activated. It is where you'll place all your
			 * actions, filters and other setup components.
			 *
			 * Don't worry about messing with this function.
			 */
			public function __construct() {

				// Register our styles
				add_action( 'admin_init', array( $this, 'register_styles' ) );

				// Add our styles conditionally
				add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );

				// Add our new content section
				$this->add_content_section(
					array(
						'name'     => self::$section_name,
						'tab'      => self::$tab,
						'page'     => self::$page,
						'callback' => array( $this, 'section_output' )
					)
				);

				// Set the plugin path
				$this->_path = plugin_dir_path( __FILE__ );

				// Set the plugin url
				$this->_url = plugins_url( '', __FILE__ );
			}

			/**
			 * Register our styles.
			 *
			 * Feel free to modify or add to this example.
			 */
			public function register_styles() {

				wp_register_style(
					self::$ID . '-style',
					$this->_url . 'style.css',
					null,
					self::$extension_version
				);
			}

			/**
			 * Add our styles.
			 *
			 * If you want the styles to show up on the entire back-end, simply remove all but:
			 * wp_enqueue_style( "$this->$ID-style" );
			 *
			 * Feel free to modify or add to this example.
			 */
			public function add_styles() {

				$page_ID         = self::translate_name_to_id( self::$page );
				$tab_ID          = self::translate_name_to_id( self::$tab );
				$settings_tab_ID = self::translate_name_to_id( self::$settings_tab );

				// Only add style if on extension tab or on extension settings tab
				if ( self::is_cd_page( $page_ID, $tab_ID ) || self::is_cd_page( 'cd_settings', $settings_tab_ID ) ) {
					wp_enqueue_style( self::$ID . '-style' );
				}
			}

			/**
			 * Our section output.
			 *
			 * This is where all of the content section content goes! Add anything you like to this function.
			 *
			 * Feel free to modify or add to this example.
			 */
			public function section_output() {

				// CHANGE THIS
				echo 'This is where your new content section\'s content goes.';
			}
		}

		// Instantiate the class
		$MyCDExtension = new MyCDExtension();

		// Include the file for your plugin settings. Simply remove or comment this line to disable the settings
		// Remove if you don't want settings
		include_once( "{$MyCDExtension->_path}inc/settings.php" );

		// Include the file for your plugin widget. Simply remove or comment this line to disable the widget
		// Remove if you don't want widgets
		include_once( "{$MyCDExtension->_path}inc/widgets.php" );

		// Include the file for your plugin menus. Simply remove or comment this line to disable the widget
		// Remove if you don't want menus
		include_once( "{$MyCDExtension->_path}inc/menus.php" );
	}

	// Change me! Change me to the name of the function at the top.
	add_action( 'plugins_loaded', 'cd_boilerplate' );
}

if ( ! function_exists( '_cd_boilerplate_notice' ) ) {
	/**
	 * Notices for if CD is not active.
	 *
	 * Change me! Change my name to something unique (and also change the add_action at the top of the file). And change
	 * the name in function_exists().
	 */
	function _cd_boilerplate_notice() {

		?>
		<div class="error">
			<p>You have activated a plugin that requires <a href="http://w.org/plugins/client-dash">Client Dash</a>
				version 1.6 or greater.
				Please install and activate <strong>Client Dash</strong> to continue using.</p>
		</div>
	<?php
	}
}