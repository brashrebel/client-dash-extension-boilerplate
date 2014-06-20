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
	private $pre = 'Laser';
	// Set this to be your tab name
	private $tabname = 'My Tab';
	// Set the tab slug
	private $tab = 'my-tab';
	// Set this to the page you want your tab to appear on (account, help and reports exist in Client Dash)
	private $page = 'account';

	// Setup all the fun
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'notices' ) );
		add_filter( 'cd_tabs', array( $this, 'add_tab' ) );
		add_action( 'cd_'. $this->page .'_'. $this->tab .'_tab', array( $this, 'tab_contents' ) );
	}

	// Notices for if CD is not active
	public function notices() {
		if ( !is_plugin_active( 'client-dash/client-dash.php' ) ) { ?>
		<div class="error">
			<?php echo $this->plugin; ?> requires <a href="http://w.org/plugins/client-dash">Client Dash</a>.
			Please install and activate <b>Client Dash</b> to continue using.
		</div>
		<?php
		}
	}

	// Add the new tab
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
$mycdextension = new MyCDExtension;