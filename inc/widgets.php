<?php

/**
 * Class MyCDExtension_Widgets
 *
 * This is an optional class for adding widgets to the Client Dash Settings -> Widgets tab to be made available for use
 * on the dashboard.
 *
 * If you do NOT want to use custom widgets, free to delete this file and the
 * "include_once( "{$MyCDExtension::$_path}inc/widgets.php" );" line in the main boilerplate file.
 */
class MyCDExtension_Widgets extends ClientDash_Widgets_API {

	/**
	 * This is where all of the widgets to be created go.
	 *
	 * Simply modify this property's id (the array key), title, and description to your liking, and then if you want
	 * more, duplicate it, modify accordingly, and add it on to the array. Make sure the callbacks match the function
	 * used INSIDE OF THIS CLASS.
	 *
	 * Then use / create your callbacks and mimic the markup in the example provided in the callbacks below.
	 *
	 * NOTE: Callbacks SHOULD be in this class, but you are welcome to use a string instead of an array and call a
	 * function outside of this class. However, if you do so, you will lose access to the provided helper functions
	 * used below.
	 *
	 * Feel free to modify this example.
	 */
	public static $widgets = array(
		'boilerplate_widget' => array(
			'title'             => 'Boilerplate Widget',
			'description'       => 'My awesome boilerplate comes with this great widget.',
			//
			// __CLASS__ provides the name of the current class. This tells the widget creator that the function is
			// inside of this class.
			'callback'          => array( __CLASS__, 'boilerplate_widget_callback' ),
			'settings_callback' => array( __CLASS__, 'boilerplate_widget_settings_callback' ),
		)
	);

	/**
	 * The main construct function.
	 *
	 * Don't worry about messing with this function.
	 */
	public function __construct() {

		// Initialize our widgets
		add_action( 'widgets_init', array( $this, 'add_widgets' ), 100 );
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

		add_filter( 'pre_update_option_cd_active_widgets', array( $this, 'filter_active_widgets' ) );
	}

	/**
     * Removes the boilerplate widget from active widgets so it doesn't show on the CD widgets screen.
	 */
	function filter_active_widgets( $value ) {

		foreach ( $value as $widget_ID => $widget ) {

			// Break apart the ID
			preg_match_all( "/(.*)(-\d+)/", $widget_ID, $matches );
			$ID_base = $matches[1][0];

			if ( isset( self::$widgets[ $ID_base ] ) ) {

				unset( $value[ $widget_ID ] );
			}
		}

		return $value;
	}

	/**
	 * Adds all of your custom widget to the "Available Widgets" section on
	 * the Settings -> Widgets page.
	 *
	 * Don't worry about messing with this function.
	 */
	public function add_widgets() {

		global $ClientDash_Core_Page_Settings_Tab_Widgets;

		// Cycle through all of the widgets and add them
		foreach ( self::$widgets as $widget_ID => $widget ) {

			$args = array(
				'id'                => $widget_ID,
				'title'             => $widget['title'],
				'description'       => $widget['description'],
				'callback'          => $widget['callback'],
				'settings_callback' => $widget['settings_callback'],
				'cd_extension'      => '1', // IMPORTANT: Do NOT remove this.
			);

			// Calls on a function from the Client Dash widgets class object
			$ClientDash_Core_Page_Settings_Tab_Widgets->register_widget( $args );
		}
	}

	/**
	 * Adds the custom widgets to the dashboard so the callback can be grabbed.
	 */
	public function add_dashboard_widgets() {

		$sidebars = get_option( 'sidebars_widgets' );

		/**
		 * This allows the currently visible dashboard "sidebar" to be changed from the default.
		 *
		 * @since Client Dash 1.6.4
		 */
		$current_sidebar = apply_filters( 'cd_dashboard_widgets_sidebar', "cd-dashboard" );

		$widgets    = $sidebars[ $current_sidebar ];
		$widget_IDs = array();

		foreach ( $widgets as $widget_ID ) {

			// Break apart the ID
			preg_match_all( "/(.*)(-\d+)/", $widget_ID, $matches );
			$ID_base = $matches[1][0];

			$widget_IDs[ $widget_ID ] = $ID_base;
		}

		foreach ( $widget_IDs as $widget_ID => $widget_base ) {

			if ( ! isset( self::$widgets[ $widget_base ] ) ) {

				continue;
			}

			$widget_args = self::$widgets[ $widget_base ];

			wp_add_dashboard_widget( $widget_ID, $widget_args['title'], $widget_args['callback'] );
		}
	}

	/**
	 * This is the callback for the dashboard widget itself, as defined in the widget array[ 'callback' ]. Place here
	 * all HTML you want inside of the widget on the dashboard.
	 *
	 * Pro Tip: Use the function "self::get_field()" (as you can see below) to get all of the custom form data you may
	 * have created in the widget settings.
	 *
	 * Feel free to modify this example.
	 */
	public static function boilerplate_widget_callback( $null, $meta_box ) {

		// IMPORTANT: This line is necessary to retrieve the values
		$ID = $meta_box['id'];
		?>
        <h4><strong>Boilerplate Widget Values</strong></h4>

        <p>
            Text Field: <strong><?php echo self::get_field( $ID, 'text_field' ); ?></strong>
        </p>

        <p>
            Checkbox Field:
            <strong><?php echo self::get_field( $ID, 'checkbox_field' ) == '1' ? 'Checked' : ''; ?></strong>
        </p>

        <p>
            Text Area Field: <strong><?php echo self::get_field( $ID, 'textarea_field' ); ?></strong>
        </p>

        <p>
            Select Box Field: <strong><?php echo self::get_field( $ID, 'select_field' ); ?></strong>
        </p>

        <p>
            Custom Field: <strong><?php echo self::get_field( $ID, 'custom_field' ); ?></strong>
        </p>
		<?php
	}

	/**
	 * This is the callback for the widget settings, as defined in the widget array[ 'settings_callback' ]. Place here
	 * all of the form HTML you want to add for use in the Settings -> Widgets page.
	 *
	 * Pro Tip: Take full advantage of the supplied form input functions (as used below). If you want to make a custom
	 * form input, then simply use the "self::get_field_name()" function for supplying the input name. This ensures
	 * that data saving and retrieval can still be handled by Client Dash.
	 *
	 * Feel free to modify or add to this example, or even remove it.
	 */
	public static function boilerplate_widget_settings_callback( $ID ) {

		// NOTE: To find out how to use these fields, please visit the Client Dash documentation page.

		echo self::text_field(
			$ID,
			'text_field',
			'Boilerplate Text'
		);

		echo self::checkbox_field(
			$ID,
			'checkbox_field',
			'Boilerplate Checkbox',
			array(
				'title' => 'This is a custom title attr! Feel free to add as many attr\'s as you like!',
			)
		);

		echo self::textarea_field(
			$ID,
			'textarea_field',
			'Boilerplate Textarea'
		);

		echo self::select_field(
			$ID,
			'select_field',
			'Boilerplate Select Box',
			array(
				'Option 1' => 'option_1',
				'Option 2' => 'option_2',
				'Option 3' => 'option_3',
			),
			array(
				'class' => 'you-can-even-add-classes separated-by-spaces',
			)
		);

		/*
		 * A custom input field.
		 *
		 * NOTE: It is very important to only use get_field_ID once per input / label. This function generates a random,
		 * unique ID so that you can use it within the label's "for" and input's "id" and have them be unique to the
		 * rest of the page, but identical to each other. So call the function once, save it into a variable (as done
		 * below), and then use that in both places.
		 */
		$field_ID = self::get_field_ID( $ID, 'custom_field' );
		?>
        <p>
            <label for="<?php echo $field_ID; ?>">
                Custom Field
                <br/>
                <input type="text" id="<?php echo $field_ID; ?>"
                       name="<?php echo self::get_field_name( $ID, 'custom_field' ); ?>"
                       value="<?php echo self::get_field( $ID, 'custom_field' ); ?>"/>
            </label>
        </p>
		<?php
	}
}

add_action( 'wp_dashboard_setup', function () {
} );

// Instantiates the class. Do NOT remove this line or nothing will work.
new MyCDExtension_Widgets();
