<?php

add_theme_support( 'title-tag' );
add_theme_support( 'menus' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_post_type_support('page', 'excerpt');


/***************************** Register menus *************************************/

register_nav_menus( array(
	'main-navigation' => __( 'Main Navigation', 'sjoerd' ),
	'secondary-navigation' => __( 'Secondary Navigation', 'sjoerd' ),
	'social-menu' => __( 'Social Menu', 'sjoerd' ),
	'footer-links' => __( 'Footer Links', 'sjoerd' ),
) );


/*************************** Enqueue Styles **********************************/



function theme_styles() {

	$filename = get_stylesheet_directory() . '/styles/app.css';
	$timestamp = filemtime($filename);

	wp_enqueue_style('sjoerd-styles', get_template_directory_uri() . '/styles/app.css', NULL, $timestamp, 'all' );
	wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Heebo:400,900&display=swap', NULL, NULL, 'all' );


}

add_action( 'wp_enqueue_scripts', 'theme_styles', 99 );

function admin_styles() {

	$filename = get_stylesheet_directory() . '/styles/admin.css';
	$timestamp = filemtime($filename);
	wp_enqueue_style('admin-styles', get_template_directory_uri() . '/styles/admin.css', NULL, $timestamp, 'all' );

}

add_action('admin_enqueue_scripts', 'admin_styles');

/*************************** Replace api.url with the correct frontpage URL *********************************/

function custom_frontend_url( $permalink, $post ) { 
	$custom_permalink = str_replace( home_url(), 'https://dekopsewaard.nl',  $permalink );

	return $custom_permalink; 
}; 
			
add_filter( 'page_link', 'custom_frontend_url', 10, 2 ); 
add_filter( 'post_link', 'custom_frontend_url', 10, 2 );
add_filter( 'post_type_link', 'custom_frontend_url', 10, 2 );

/*************************** Remove wordpress functionality **********************************/

remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

remove_filter ('the_excerpt', 'wpautop');
remove_filter ('the_content', 'wpautop');
remove_filter('term_description','wpautop');

function remove_admin_menus() {

	remove_menu_page( 'edit-comments.php' ); // Comments
	remove_menu_page( 'edit.php' ); // Standard post

}
add_action( 'admin_menu', 'remove_admin_menus' );


function remove_default_post_type_menu_bar( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'new-post' ); // Default post from admin bar 
}
add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 );


function remove_draft_widget(){
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );

/*************************** Custom Post Types *********************************/

// Register Nieuws Custom Post Type 
function cpt_nieuws() {

	$labels = array(
			'name'                  => _x( 'Nieuws', 'Post Type General Name', 'sjoerd' ),
			'singular_name'         => _x( 'Nieuws', 'Post Type Singular Name', 'sjoerd' ),
			'menu_name'             => __( 'Nieuws', 'sjoerd' ),
			'name_admin_bar'        => __( 'Nieuws', 'sjoerd' ),
			'archives'              => __( 'Item Archives', 'sjoerd' ),
			'attributes'            => __( 'Item Attributes', 'sjoerd' ),
			'parent_item_colon'     => __( 'Parent Item:', 'sjoerd' ),
			'all_items'             => __( 'All Items', 'sjoerd' ),
			'add_new_item'          => __( 'Add New Item', 'sjoerd' ),
			'add_new'               => __( 'Add New', 'sjoerd' ),
			'new_item'              => __( 'New Item', 'sjoerd' ),
			'edit_item'             => __( 'Edit Item', 'sjoerd' ),
			'update_item'           => __( 'Update Item', 'sjoerd' ),
			'view_item'             => __( 'View Item', 'sjoerd' ),
			'view_items'            => __( 'View Items', 'sjoerd' ),
			'search_items'          => __( 'Search Item', 'sjoerd' ),
			'not_found'             => __( 'Not found', 'sjoerd' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sjoerd' ),
			'featured_image'        => __( 'Featured Image', 'sjoerd' ),
			'set_featured_image'    => __( 'Set featured image', 'sjoerd' ),
			'remove_featured_image' => __( 'Remove featured image', 'sjoerd' ),
			'use_featured_image'    => __( 'Use as featured image', 'sjoerd' ),
			'insert_into_item'      => __( 'Insert into item', 'sjoerd' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'sjoerd' ),
			'items_list'            => __( 'Items list', 'sjoerd' ),
			'items_list_navigation' => __( 'Items list navigation', 'sjoerd' ),
			'filter_items_list'     => __( 'Filter items list', 'sjoerd' ),
	);
	$rewrite = array(
			'slug'                  => 'nieuws',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
	);
	$args = array(
			'label'                 => __( 'Nieuws', 'sjoerd' ),
			'description'           => __( 'Nieuws items', 'sjoerd' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', ),
			'show_in_graphql' 		=> true,
			'hierarchical'          => false,
			'graphql_single_name' => 'Nieuws',
      		'graphql_plural_name' => 'Nieuwsitems',
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-media-document',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => 'nieuws',
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
	);
	register_post_type( 'nieuws', $args );

}
add_action( 'init', 'cpt_nieuws', 0 );

/*************************** Form Submissions Custom Post types *********************************/

// Register Nieuws Custom Post Type 
function cpt_inspraak_form() {

	$labels = array(
			'name'                  => _x( 'Inspraak', 'Post Type General Name', 'sjoerd' ),
			'singular_name'         => _x( 'Inspraak', 'Post Type Singular Name', 'sjoerd' ),
			'menu_name'             => __( 'Inspraak Form', 'sjoerd' ),
			//'name_admin_bar'        => __( 'Inspraak', 'sjoerd' ),
			'archives'              => __( 'Inspraak Archives', 'sjoerd' ),
			'attributes'            => __( 'Inspraak Attributes', 'sjoerd' ),
			'parent_item_colon'     => __( 'Parent Item:', 'sjoerd' ),
			'all_items'             => __( 'All Items', 'sjoerd' ),
			'add_new_item'          => __( 'Add New Item', 'sjoerd' ),
			'add_new'               => __( 'Add New', 'sjoerd' ),
			'new_item'              => __( 'New Item', 'sjoerd' ),
			'edit_item'             => __( 'Edit Item', 'sjoerd' ),
			'update_item'           => __( 'Update Item', 'sjoerd' ),
			'view_item'             => __( 'View Item', 'sjoerd' ),
			'view_items'            => __( 'View Items', 'sjoerd' ),
			'search_items'          => __( 'Search Item', 'sjoerd' ),
			'not_found'             => __( 'Geen inspraak submissies gevonden', 'sjoerd' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sjoerd' ),
			'featured_image'        => __( 'Featured Image', 'sjoerd' ),
			'set_featured_image'    => __( 'Set featured image', 'sjoerd' ),
			'remove_featured_image' => __( 'Remove featured image', 'sjoerd' ),
			'use_featured_image'    => __( 'Use as featured image', 'sjoerd' ),
			'insert_into_item'      => __( 'Insert into item', 'sjoerd' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'sjoerd' ),
			'items_list'            => __( 'Items list', 'sjoerd' ),
			'items_list_navigation' => __( 'Items list navigation', 'sjoerd' ),
			'filter_items_list'     => __( 'Filter items list', 'sjoerd' ),
	);

	$args = array(
			'label'                 => __( 'Inspraak', 'sjoerd' ),
			'description'           => __( 'Submissies via het inspraak formulier', 'sjoerd' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'custom-fields', ),
			'show_in_graphql' 		=> false,
			'hierarchical'          => false,
			//'graphql_single_name' => 'inspraak',
      		//'graphql_plural_name' => 'inspraak_submissies',
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-media-document',
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			//'rewrite'               => $rewrite,
			'capability_type'       => 'post',
			'show_in_rest'          => false,
	);
	register_post_type( 'inspraak', $args );

}
add_action( 'init', 'cpt_inspraak_form', 0 );

/*************************** Add Inspraak mutation *************************************************/
// https://github.com/alexander-young/gatsby-wordpress-contact-form/

add_action('graphql_register_types', function () {

	register_graphql_mutation('createInspraakSubmission', [
		'inputFields' => [
			'rating' => [
				'type' => 'Number',
				'description' => 'Rating',
			],
			'voornaam' => [
				'type' => 'String',
				'description' => 'First Name',
			],
			'achternaam' => [
				'type' => 'String',
				'description' => 'Last Name',
			],
			'email' => [
				'type' => 'String',
				'description' => 'user email',
			],
			'woonplaats' => [
				'type' => 'String',
				'description' => 'user city',
			],
			'opmerkingen' => [
				'type' => 'String',
				'description' => 'User Message',
			],
		],
		'outputFields' => [
			'success' => [
				'type' => 'Boolean',
				'description' => 'Whether or not data was stored successfully',
				'resolve' => function ($payload, $args, $context, $info) {
					return isset($payload['success']) ? $payload['success'] : null;
				}
			],
			'data' => [
				'type' => 'String',
				'description' => 'Payload of submitted fields',
				'resolve' => function ($payload, $args, $context, $info) {
					return isset($payload['data']) ? $payload['data'] : null;
				}
			]
		],
		'mutateAndGetPayload' => function ($input, $context, $info) {

			if (!class_exists('ACF')) return [
				'success' => false,
				'data' => 'ACF is not installed'
			];

			$sanitized_data = [];
			$errors = [];
			$acceptable_fields = [
				'rating' => 'field_5ed8b371d7e0c',
				'voornaam' => 'field_5ed8b302d7e07',
				'achternaam' => 'field_5ed8b325d7e08',
				'email' => 'field_5ed8b32ed7e09',
				'woonplaats' => 'field_5ed8b351d7e0a',
				'opmerkingen' => 'field_5ed8b360d7e0b',
			];

			foreach ($acceptable_fields as $field_key => $acf_key) {
				if (!empty($input[$field_key])) {
					$sanitized_data[$field_key] = sanitize_text_field($input[$field_key]);
				} else {
					$errors[] = $field_key . ' was not filled out.';
				}
			}

			if (!empty($errors)) return [
				'success' => false,
				'data' => $errors
			];

			$form_submission = wp_insert_post([
				'post_type' => 'inspraak',
				'post_title' => $sanitized_data['email'],
			], true);

			// Send confirmation email - https://developer.wordpress.org/reference/functions/wp_mail/

			//add_filter('wp_mail_content_type', 'set_html_content_type');

			if (empty($errors)) wp_mail(
				$sanitized_data['email'],
				'Bevestiging van uw reactie',
				'<div style="padding:40px;background-color:#D9E5F1;"><h1 style="color:#1F5C9D;">Nogmaals bedankt voor uw reactie.</h1> <strong style="color:#1F5C9D;font-size:110%;">Hieronder vindt u een overzicht die u ungevuld heeft op de website.</strong><br /><br /><p style="color:#1F5C9D;font-size:110%;"><strong style="color:#1F5C9D;font-size:110%;>Naam:</strong><br />' . $sanitized_data['voornaam'] . ' ' . $sanitized_data['achternaam'] . '<br /><br /><strong style="color:#1F5C9D;font-size:110%;>Woonplaats:</strong><br />' . $sanitized_data['woonplaats'] . '<br /><br /><strong style="color:#1F5C9D;font-size:110%;>Waardering:</strong><br /> ' . $sanitized_data['rating'] . ' uit 5' . '<br /><br /><strong style="color:#1F5C9D;font-size:110%;>Uw opmerking / idee:</strong><br />' . $sanitized_data['opmerkingen'] . '</p></div>',
				array('Content-Type: text/html; charset=UTF-8', 'From: De Kopse Waard <info@dekopsewaard.nl>', 'Bcc: dekopsewaard@elburg.nl')
			);	

			//remove_filter('wp_mail_content_type', 'set_html_content_type');

			if (is_wp_error($form_submission)) return [
				'success' => false,
				'data' => $form_submission->get_error_message()
			];

			foreach ($acceptable_fields as $field_key => $acf_key) {
				update_field($acf_key, $sanitized_data[$field_key], $form_submission);
			}

			return [
				'success' => true,
				'data' => json_encode($sanitized_data)
			];

		}
	]);

});

/*************************** Restrict gutenberg blocks *********************************/


function restrict_blocks( $allowed_blocks, $post ) {
	
	if( is_user_logged_in() ) {
		
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
	
		if( in_array( strtolower('Administrator'), $roles ) )
		// if( in_array( strtolower('Editor'), $roles ) )
			$allowed_blocks = array(
				'core/heading',
				'core/image',
				'core/paragraph',
				'core/quote',
				'core/embedyoutube',
				'acf/hero',
				'acf/textimage',
				'acf/kaart',
				'acf/googlemap',
				'acf/nieuwsbrief',
				'acf/latestpost',
				'acf/fullimage',
				'acf/vrijecontent',
				'acf/blockslider',
				'acf/form',
				'acf/footer',
				'acf/button'

			);
			return $allowed_blocks;
		}

	}	
	
	// add_filter( 'allowed_block_types', 'restrict_blocks', 10, 2);


/*************************** Custom Gutenberg Blocks *********************************/


function register_acf_block_types() {
		
    acf_register_block_type(array(
        'name'              => 'hero',
        'title'             => __('Hero Block'),
        'description'       => __('The hero block used at the top of most pages.'),
        'render_template'   => 'gutenberg-blocks/hero.php',
        'category'          => 'common',
		'icon'              => 'desktop',
		'mode' 				=> 'edit',
        'keywords'          => array( 'hero', 'header', 'acf' ),
	));
	
    acf_register_block_type(array(
        'name'              => 'textimage',
        'title'             => __('Text & Image'),
        'description'       => __('A text block with an (slider) image.'),
        'render_template'   => 'gutenberg-blocks/text_image.php',
        'category'          => 'common',
		'icon'              => 'screenoptions',
		'mode' 				=> 'edit',
        'keywords'          => array( 'image', 'text', 'acf' ),
	));
	
    acf_register_block_type(array(
        'name'              => 'kaart',
        'title'             => __('Kaart'),
        'description'       => __('Kaart van de Kopse Waard'),
        'render_template'   => 'gutenberg-blocks/kaart.php',
        'category'          => 'common',
		'icon'              => 'admin-site-alt',
		'mode' 				=> 'edit',
        'keywords'          => array( 'kaart', 'map', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'googlemap',
        'title'             => __('Google Map'),
        'description'       => __('Google map van de omgeving'),
        'render_template'   => 'gutenberg-blocks/googlemap.php',
        'category'          => 'common',
		'icon'              => 'admin-site',
		'mode' 				=> 'edit',
        'keywords'          => array( 'google map', 'kaart', 'map', 'acf' ),
	));
	
    acf_register_block_type(array(
        'name'              => 'nieuwsbrief',
        'title'             => __('Nieuwsbrief'),
        'description'       => __('Nieuwsbrief activatie block'),
        'render_template'   => 'gutenberg-blocks/nieuwsbrief.php',
        'category'          => 'common',
		'icon'              => 'email-alt',
		'mode' 				=> 'edit',
        'keywords'          => array( 'nieuwsbrief', 'text', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'latestpost',
        'title'             => __('Latest Posts'),
        'description'       => __('Overzicht met de laatste posts'),
        'render_template'   => 'gutenberg-blocks/latestpost.php',
        'category'          => 'common',
		'icon'              => 'media-spreadsheet',
		'mode' 				=> 'edit',
        'keywords'          => array( 'nieuws', 'text', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'fullimage',
        'title'             => __('Full-width Image'),
        'description'       => __('Plaatje over de volledige breedte'),
        'render_template'   => 'gutenberg-blocks/fullimage.php',
        'category'          => 'common',
		'icon'              => 'format-image',
		'mode' 				=> 'edit',
        'keywords'          => array( 'image', 'full', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'vrijecontent',
        'title'             => __('Vrije Content'),
        'description'       => __('Content block over de volledige breedte met vrije content'),
        'render_template'   => 'gutenberg-blocks/vrijecontent.php',
        'category'          => 'common',
		'icon'              => 'video-alt3',
		'mode' 				=> 'edit',
        'keywords'          => array( 'content', 'vrij', 'full', 'text', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'blockslider',
        'title'             => __('Block Slider'),
        'description'       => __('Plaatje en tekst met blok slider aan onderkant.'),
        'render_template'   => 'gutenberg-blocks/text_block_slider.php',
        'category'          => 'common',
		'icon'              => 'video-alt3',
		'mode' 				=> 'edit',
        'keywords'          => array( 'content', 'vrij', 'full', 'text', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'form',
        'title'             => __('Form Block'),
        'description'       => __('Een block met een formulier.'),
        'render_template'   => 'gutenberg-blocks/form.php',
        'category'          => 'common',
		'icon'              => 'format-aside',
		'mode' 				=> 'edit',
        'keywords'          => array( 'content', 'vrij', 'full', 'text', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'footer',
        'title'             => __('Footer'),
        'description'       => __('The website footer'),
        'render_template'   => 'gutenberg-blocks/footer.php',
        'category'          => 'common',
		'icon'              => 'editor-table',
		'mode' 				=> 'edit',
        'keywords'          => array( 'footer', 'bottom', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'button',
        'title'             => __('Button'),
        'description'       => __('Add a button'),
        'render_template'   => 'gutenberg-blocks/button.php',
        'category'          => 'common',
		'icon'              => 'share-alt2',
		'mode' 				=> 'edit',
        'keywords'          => array( 'button', 'acf' ),
	));

	acf_register_block_type(array(
        'name'              => 'downloads',
        'title'             => __('Downloads'),
        'description'       => __('Add a downloads section'),
        'render_template'   => 'gutenberg-blocks/button.php',
        'category'          => 'common',
		'icon'              => 'download',
		'mode' 				=> 'edit',
        'keywords'          => array( 'downloads', 'acf' ),
	));
	

}

// Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'register_acf_block_types');
}


/*************************** Return NULL instead of empty acf field *********************************/
/* https://www.gatsbyjs.org/packages/gatsby-source-wordpress/#graphql-error---unknown-field-on-acf */

if (!function_exists('acf_nullify_empty')) {
    /**
     * Return `null` if an empty value is returned from ACF.
     *
     * @param mixed $value
     * @param mixed $post_id
     * @param array $field
     *
     * @return mixed
     */
    function acf_nullify_empty($value, $post_id, $field) {
        if (empty($value)) {
            return null;
        }
        return $value;
    }
}

add_filter('acf/format_value', 'acf_nullify_empty', 100, 3);


/*************************** Add ACF Option page *********************************/

function register_acf_options_pages() {

    // check function exists
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    // register options page
    $my_options_page = acf_add_options_page(
        array(
            'page_title'      => __( 'Options Page' ),
            'menu_title'      => __( 'Options Page' ),
            'menu_slug'       => 'options-page',
            'capability'      => 'edit_posts',
            'show_in_graphql' => true,
        )
    );
}

add_action( 'acf/init', 'register_acf_options_pages' );