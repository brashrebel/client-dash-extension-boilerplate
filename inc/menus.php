<?php

/**
 * Class MyCDExtension_Menus
 *
 * This is an optional class for adding widgets to the Client Dash Settings -> Widgets
 * tab to be made available for use on the dashboard.
 *
 * If you do NOT want to use custom menus, free to delete this file and the
 * "include_once( "{$MyCDExtension::$_path}inc/menus.php" );" line in the main boilerplate file.
 */
class MyCDExtension_Menus extends ClientDash_Menus_API {

	/**
	 * All of the items to output in the group drop-down.
	 *
	 * This property will populate the "Group Drop-Down" (under "Available Items") area on the left side
	 * of the screen under Settings -> Menus.
	 *
	 * Currently, this boilerplate only adds one group drop-down area and uses this property to
	 * populate it.
	 *
	 * Pro Tip: If you only put one tab in the array, the tab selection (displayed over the available
	 * items) will not show.
	 *
	 * Feel free to modify this example.
	 */
	private static $group_items = array(
		'Tab 1' => array(
			'Item 1' => array(
				'url'  => '/options-general.php?page=cd_settings&tab=boilerplate',
				'icon' => 'dashicons-smiley',
			),
			// You can add a separator like this!
			'Test' => 'separator',
		),
		'Tab 2' => array(
			'Item 1' => array(
				'url'  => '/options-general.php?page=cd_settings&tab=boilerplate',
				'icon' => 'dashicons-smiley',
			),
		),
	);

	/**
	 * The main construct function.
	 *
	 * Don't worry about messing with this function.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'add_new_menu_groups' ) );
	}

	/**
	 * This is where you add new menu groups.
	 *
	 * In the example provided, we are adding one new menu group with the title "Boilerplate Group."
	 * This group will contain the menu items that are output inside of the "group_content" method
	 * inside of this class.
	 *
	 * Feel free to modify or add to this example, or even remove it.
	 */
	public function add_new_menu_groups() {

		self::add_menu_group( 'Boilerplate Group', array( __CLASS__, 'group_content' ) );
	}

	/**
	 * This is the callback function for the example group we've created.
	 *
	 * Inside of this you can add any HTML that you want, but it is recommended that you stick with just
	 * using the supplied "group_output()" function. This function takes an array of tabs and items (currently
	 * using the $group_items property from above) and outputs the menu items accordingly.
	 *
	 * Feel free to modify or add to this example, or even remove it.
	 */
	public static function group_content() {

		self::group_output( 'Boilerplate Group', self::$group_items );
	}
}

// Instantiates the class. Do NOT remove this line or nothing will work.
new MyCDExtension_Menus();