<?php
/*
Plugin Name: Client Dash Extension Boilerplate
Description: Starting point for making an addon for Client Dash
Version: 0.1.3
Author: Kyle Maurer
Author URI: http://realbigmarketing.com/staff/kyle
*/

/**
 * The function to launch our plugin.
 *
 * This entire class is wrapped in this function because we have
 * to ensure that Client Dash has been loaded before our extension.
 *
 * NOTE: This function needs to be changed to whatever your extension
 * is. Also change it at the bottom under "add_action( 'cd_boilerplate'..."
 */
function cd_boilerplate() {
	if ( ! class_exists( 'ClientDash' ) ) {
		add_action( 'admin_notices', 'cdbp_notice' );
		return;
	}

	class MyCDExtension extends ClientDash {

		/*
		* These variables you can change
		*/
		// Define the plugin name
		public $plugin = 'My CD Extension';

		// Setup your prefix
		public $pre = 'cdbp';

		// Set this to be name of your content section
		private $section_name = 'Boilerplate Content';

		// Set the tab name
		private $tab = 'Boilerplate';

		// Set this to the page you want your tab to appear on (Account, Reports, Help, and Webmaster exist in Client Dash)
		private $page = 'Account';

		// The version of your extension. Keep this up to date!
		public $version = '0.1.3';

		/**
		* This constructor function sets up what happens when the plugin
		* is activated. It is where you'll place all your actions, filters
		* and other setup components.
		*/
		public function __construct() {

			// Register our styles
			add_action( 'admin_init', array( $this, 'register_styles' ) );

			// Add our styles conditionally
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );

			// Add our new content section
			$this->add_content_section(
				array(
					'name'     => $this->section_name,
					'page'     => $this->page,
					'tab'      => $this->tab,
					'callback' => array( $this, 'section_output' )
				)
			);
		}

		/**
		 * Register our styles.
		 */
		public function register_styles() {

			wp_register_style(
				$this->pre,
				plugin_dir_url( __FILE__ ) . 'style.css',
				null,
				$this->version
			);
		}

		/**
		 * Add our styles.
		 */
		public function add_styles() {
			$page = get_current_screen();
			$tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : null;

			if ( $page->id != 'dashboard_page_cd_' . $this->page && $tab != $this->tab ) {
				return;
			}

			wp_enqueue_style( $this->pre );
		}

		/**
		 * Our section output.
		 */
		public function section_output() {

			// CHANGE THIS
			echo 'This is where your new content block\'s content goes.';
		}
	}

	// Instantiate the class
	new MyCDExtension();

	/**
	 * Class MyCDExtension_Settings
	 *
	 * This is an optional class for adding a settings page to the Client
	 * Dash interface. If your extension does not need settings, delete
	 * this class.
	 */
	class MyCDExtension_Settings extends MyCDExtension {

		// Set up our settings section name
		private $section_name = 'Boilerplate Settings';
		// And tab name
		// NOTE: This tab name can be a settings tab that already
		// exists. It will then just add your settings to that tab
		private $tab = 'Boilerplate';

		/*
		* Now let's setup our options
		* You can change the strings to be more unique
		* If you change the variable names, you'll need to update the
		* references in the register_settings() and settings_display() functions
		*/
		// A checkbox option
		private $cb_option = '_checkbox';

		// A text field option
		private $text_option = '_text';

		// and a URL/text field option
		private $url_option = '_url';

		/**
		 * The main construct function.
		 */
		function __construct() {

			// Register the extension settings
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Add our content section
			$this->add_content_section(
				array(
					'name'     => $this->section_name,
					'page'     => 'Settings',
					'tab'      => $this->tab,
					'callback' => array( $this, 'settings_output' )
				)
			);
		}

		/**
		 * Register the extension's settings.
		 */
		public function register_settings() {

			register_setting(
				'cd_options_' . $this->translate_name_to_id( $this->tab ),
				$this->pre . $this->cb_option
			);

			register_setting(
				'cd_options_' . $this->translate_name_to_id( $this->tab ),
				$this->pre . $this->text_option,
				'esc_html' );

			register_setting(
				'cd_options_' . $this->translate_name_to_id( $this->tab ),
				$this->pre . $this->url_option,
				'esc_url_raw' );
		}

		/**
		 * Our settings content.
		 */
		public function settings_output() {

			$checkbox_option_name = $this->pre . $this->cb_option;
			$checkbox_option      = get_option( $checkbox_option_name );
			$text_option          = $this->pre . $this->text_option;
			$url_option           = $this->pre . $this->url_option;
			?>
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row"><h3><?php echo $this->plugin; ?> settings</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="<?php echo $checkbox_option_name; ?>">Checkbox setting</label>
					</th>
					<td><input type="hidden" name="<?php echo $checkbox_option_name; ?>" value="0"/>
						<input type="checkbox" name="<?php echo $checkbox_option_name; ?>"
						       id="<?php echo $checkbox_option_name; ?>"
						       value="1" <?php checked( '1', $checkbox_option ); ?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="<?php echo $text_option; ?>">Text setting</label>
					</th>
					<td><input type="text"
					           id="<?php echo $text_option; ?>"
					           name="<?php echo $text_option; ?>"
					           value="<?php echo get_option( $text_option ); ?>"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="<?php echo $url_option; ?>">URL setting</label>
					</th>
					<td><input type="text"
					           id="<?php echo $url_option; ?>"
					           name="<?php echo $url_option; ?>"
					           value="<?php echo get_option( $url_option ); ?>"/>
					</td>
				</tr>
				</tbody>
			</table>
		<?php
		}
	}

	new MyCDExtension_Settings();
}

add_action( 'plugins_loaded', 'cd_boilerplate' );

/**
 * Notices for if CD is not active (no need to change)
 */
function cdbp_notice() {

	?>
	<div class="error">
		<p>You have activated a plugin that requires <a href="http://w.org/plugins/client-dash">Client Dash</a>
			version 1.5 or greater.
			Please install and activate <b>Client Dash</b> to continue using.</p>
	</div>
<?php
}