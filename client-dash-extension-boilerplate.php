<?php
/*
Plugin Name: Client Dash Extension Boilerplate
Description: Starting point for making an addon for Client Dash
Version: 0.1
Author: Kyle Maurer
Author URI: http://realbigmarketing.com/staff/kyle
*/

class MyCDExtension {

	/*
	* These variables you can change
	*/
	// Define the plugin name
	private $plugin = 'My CD Extension';
	// Setup your prefix
	private $pre = 'cdeb';
	// Set this to be your tab name
	private $tabname = 'My Tab';
	// Set the tab slug
	private $tab = 'my-tab';
	// Set this to the page you want your tab to appear on (account, help and reports exist in Client Dash)
	private $page = 'account';

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
	// A URL/text field option
	private $url_option = '_url';

	/*
	* This constructor function sets up what happens when the plugin
	* is activated. It is where you'll place all your actions, filters
	* and other setup components.
	*/
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'notices' ) );
		add_filter( 'cd_tabs', array( $this, 'add_tab' ) );
		add_action( 'cd_'. $this->page .'_'. $this->tab .'_tab', array( $this, 'tab_contents' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'cd_settings_general_tab', array( $this, 'settings_display' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_styles') );
	}

	public function register_styles() {
		wp_register_style( $this->pre , plugin_dir_url(__FILE__).'style.css' );
		$page = get_current_screen();
		$tab = $_GET['tab'];

		if ( $page->id != $this->page && $tab != $this->tab )
			return;

		wp_enqueue_style( $this->pre );
	}

	// Notices for if CD is not active (no need to change)
	public function notices() {
		if ( !is_plugin_active( 'client-dash/client-dash.php' ) ) { ?>
		<div class="error">
			<p><?php echo $this->plugin; ?> requires <a href="http://w.org/plugins/client-dash">Client Dash</a>.
			Please install and activate <b>Client Dash</b> to continue using.</p>
		</div>
		<?php
		}
	}

	// Register settings
	public function register_settings() {
		register_setting( 'cd_options_general', $this->pre.$this->cb_option );
		register_setting( 'cd_options_general', $this->pre.$this->text_option, 'esc_html' );
		register_setting( 'cd_options_general', $this->pre.$this->url_option, 'esc_url_raw' );
	}

	// Add settings to General tab
	public function settings_display() {
		$checkbox_option_name = $this->pre.$this->cb_option;
		$checkbox_option = get_option( $checkbox_option_name );
		$text_option = $this->pre.$this->text_option;
		$url_option = $this->pre.$this->url_option;
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
				<td><input type="hidden" name="<?php echo $checkbox_option_name; ?>" value="0" />
					<input type="checkbox" name="<?php echo $checkbox_option_name; ?>" id="<?php echo $checkbox_option_name; ?>" value="1" <?php checked( '1', $checkbox_option); ?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $text_option; ?>">Text setting</label>
				</th>
				<td><input type="text" 
					id="<?php echo $text_option; ?>" 
					name="<?php echo $text_option; ?>" 
					value="<?php echo get_option( $text_option ); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $url_option; ?>">URL setting</label>
				</th>
				<td><input type="text" 
					id="<?php echo $url_option; ?>" 
					name="<?php echo $url_option; ?>" 
					value="<?php echo get_option( $url_option ); ?>" />
				</td>
			</tr>
		</tbody>
	</table>
	<?php }

	// Add the new tab (no need to change)
	public function add_tab( $tabs ) {
	$tabs[$this->page][$this->tabname] = $this->tab;
	return $tabs;
	}

	// Insert the tab contents
	public function tab_contents() {
		// CHANGE THIS
		echo 'This is where your tab content goes.';
	}
}
// Instantiate the class
$mycdextension = new MyCDExtension;