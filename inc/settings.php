<?php

/**
 * Class MyCDExtension_Settings
 *
 * If you do NOT want to use custom widgets, free to delete this file and the
 * "include_once( "{$MyCDExtension::$_path}inc/settings.php" );" line in the main boilerplate file.
 */
class MyCDExtension_Settings extends ClientDash_Settings_API {

	/**
	 * This is the section name of your boilerplate settings.
	 *
	 * This will be the display name of the content section that this plugin's settings reside in. If there is only one
	 * content section within the tab, the name will not show.
	 *
	 * Feel free to modify this example with your own.
	 */
	private $section_name = 'Boilerplate Settings';

	/**
	 * This is the settings tab name.
	 *
	 * All of your plugin settings will reside here. This may also be the name of an existing tab.
	 *
	 * IMPORTANT: This must match exactly the $settings_tab property in the main plugin file.
	 *
	 * Feel free to modify this example with your own.
	 */
	private $settings_tab = 'Boilerplate';

	/**
	 * This is the ID for the plugin. It's very important that this matches the ID in the base file.
	 *
	 * Feel free to modify this example with your own.
	 */
	private $ID = 'boilerplate';

	/**
	 * Use this property to create settings for the page.
	 *
	 * Follow the example to either modify, remove, or add more settings to the page.
	 *
	 * Feel free to modify, add to, or remove this example with your own.
	 */
	private $settings = array(
		'text' => array(
			'name' => 'Text Field',
			'ID' => 'text_field',
			'atts' => array(
				'title' => 'You can add custom atts here, as many as you want!',
			),
		),
		'checkbox' => array(
			'name' => 'Checkbox Field',
			'ID' => 'checkbox_field',
		),
		'textarea' => array(
			'name' => 'Text Area Field',
			'ID' => 'textarea_field',
			'atts' => array(
				'class' => 'you-can-even-add-classes separated-by-spaces',
			),
		),
		'select' => array(
			'name' => 'Select Box Field',
			'ID' => 'select_field',
			'options' => array(
				'Option 1' => 'option_1',
				'Option 2' => 'option_2',
				'Option 3' => 'option_3',
			),
		),
	);

	/**
	 * The ID of this settings page. Will be set in __construct().
	 *
	 * Don't worry about messing with this property.
	 */
	private $_settings_ID;

	/**
	 * The main construct function.
	 *
	 * This function sets up the $_settings_ID property, adds the action for registering all settings, and adds the
	 * main content section. There's no need to modify this function, but if you absolutely must, please be careful.
	 *
	 * Don't worry about messing with this function.
	 */
	function __construct() {

		// Set the ID
		$this->_settings_ID = "{$this->ID}_settings";

		// Register the extension settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add our content section
		$this->add_content_section(
			array(
				'name'     => $this->section_name,
				'tab'      => $this->settings_tab,
				'page'     => 'Settings',
				'callback' => array( $this, 'settings_output' )
			)
		);
	}

	/**
	 * Register the extension's settings.
	 *
	 * This method will automatically register all settings that were set in the $settings property above. If you want
	 * to add individual, custom settings to be registered, simply follow the example directly below the foreach loop.
	 *
	 * IMPORTANT: Any setting that appears on this page MUST be registered.
	 *
	 * Feel free to modify, add to, or remove this example with your own.
	 */
	public function register_settings() {

		// Register all settings
		foreach ( $this->settings as $setting ) {
			self::register_field( "cd_options_$this->ID", $this->_settings_ID, $setting['ID'] );
		}

		// Custom field example
		self::register_field( "cd_options_$this->ID", $this->_settings_ID, 'custom_text_field' );
	}

	/**
	 * Our settings content.
	 *
	 * This is where all of the HTML output will be managed. It is currently using a pre-made loop to automatically
	 * output all of the settings provided in the $settings property from above, but if you'd like to make it more
	 * custom, go right ahead.
	 *
	 * Pro Tip: In order to easily add more input fields (but not by modifying the $settings property above), use any
	 * of the API functions provided that are outlined in the Client Dash documentation. Such as:
	 *
	 * text_field(), checkbox_field(), textarea_field(), select_field().
	 *
	 * Feel free to modify, add to, or remove this example with your own.
	 */
	public function settings_output() {
		?>
		<h3>Boilerplate Settings</h3>

		<?php
		// Opens the standard WP form table.
		echo self::open_form_table();

		// FIXME checkbox not saving

		/*
		 * This loop cycles through all of the settings that have been added to $this->settings and outputs them
		 * accordingly.
		 *
		 * If you would like, you may delete this entire loop and input your own settings using the Client Dash Settings
		 * API. This API is in the Client Dash documentation page.
		 */
		foreach ( $this->settings as $setting_type => $setting ) {

			if ( ! isset( $setting['ID'] ) ) {
				self::error_nag( "ERROR: $setting[name] cannot be displayed because it's missing the ID" );
				continue;
			}

			// Setup args
			$args = array(
				$this->_settings_ID,
				$setting['ID'],
				isset( $setting['name'] ) ? $setting['name'] : null,
				isset( $setting['atts'] ) ? $setting['atts'] : [ ],
			);

			// Select box has extra param
			if ( $setting_type == 'select' ) {
				if ( ! isset( $setting['options'] ) ) {
					self::error_nag( "ERROR: $setting[name] cannot be displayed because it's missing options" );
					continue;
				}
				$args[4] = $args[3];
				$args[3] = $setting['options'];
			}

			echo call_user_func_array( __CLASS__ . "::{$setting_type}_field", $args );
		}

		// Example setting using the API
		echo self::text_field( $this->_settings_ID, 'custom_text_field', 'Custom Text Field' );

		// Closes the form table
		echo self::close_form_table();
		?>
	<?php
	}
}

new MyCDExtension_Settings();