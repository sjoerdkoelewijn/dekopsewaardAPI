<?php

class microCopy {
    /**
     * The theme name
     */
    protected $theme_name;

    /**
     * The theme version
     */
    protected $theme_version;

    /**
     * The constructor
     */

    public function __construct( $theme_name, $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		// We hook into init and not admin_init to make front aware of this
		// settings.
		add_action( 'init', array( $this, 'register_settings' ) );
	}



	public function settings_api_init() {
		add_settings_section(
			'socials',
			'Socials',
			// In class context pass an array with $this and the method name
			// to retrieve callback function.
			array( $this, 'socials_callback_function' ),
			// Our Socials setting will be set under the General tab.
			'general'
		);

		add_settings_field(
			'twitter',
			'Twitter',
			array( $this, 'setting_callback_function' ),
			'general',
			// Display this setting under our newly declared section right
			// above.
			'socials',
			// Extra arguments used in callback function.
			array(
				'name'  => 'twitter',
				'label' => 'Twitter',
			)
		);
	}


	public function socials_callback_function() {
		echo '<p>Socials urls</p>';
	}

	public function setting_callback_function( $args ) {
		// Ugly, I know 😔.
		echo '<input name="' . esc_attr( $args['name'] ) . '" type="text" value="' . esc_attr( get_option( $args['name'] ) ) . '" class="regular-text code" placeholder="' . esc_attr( $args['label'] ) . ' URL" />'; 
		echo ' ' . esc_attr( $args['label'] ) . ' URL';
	}


	public function register_settings() {
		$args = array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
			// Extra argument for WPGraphQL.
			'show_in_graphql'   => true,
			'show_in_rest'      => true,
		);

		register_setting( 'general', 'twitter', $args );
	}


}