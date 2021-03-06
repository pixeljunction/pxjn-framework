<?php

/* load the widgets template file function */
load_template( get_template_directory() . '/functions/widgets.php' );

/* load the custom header */
//load_template( get_template_directory() . '/functions/custom-header.php' );

/***************************************************************
* Function pxjn_menus()
* Defines menus to be used in the theme
***************************************************************/
function pxjn_menus() {
	
	/* register a main nav menu - can be repeated for other menus */
	register_nav_menu( 'primary', __( 'Primary Menu' ) );

}

/* set the $content_width for things such as video embeds usually width of themes 'content' div */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/***************************************************************
* Function pxjn_theme_setup()
* Theme setup function for changing options for media etc. and
* adding theme support
***************************************************************/
function pxjn_theme_setup() {
			
	/* adds featured image support */
	add_theme_support( 'post-thumbnails' );
	/* images sizes can be added here using - http://codex.wordpress.org/Function_Reference/add_image_size */
	
	/* add your nav menus function to the 'init' action hook */
	add_action( 'init', 'pxjn_menus' );
	
	/* add your sidebars function to the 'widgets_init' action hook */
	add_action( 'widgets_init', 'pxjn_register_widgets' );
	
	/* adds the editor stylesheet */
	add_editor_style( 'editor-style.css' );
	
	/* Add default posts and comments RSS feed links to head */
	add_theme_support( 'automatic-feed-links' );
	
	/***************************************************************
	/* theme defaults are set and message outputted
	***************************************************************/
	
	/* first we check to see if our default theme settings have been applied */
	$pxjn_the_theme_status = get_option( 'theme_setup_status' );
	
	/* if the theme has not yet been used we want to run our default settings */
	if ( $pxjn_the_theme_status !== '1' ) {
	
		/* setup default wordPress settings */
		$pxjn_core_settings = array(
			'image_default_link_type' => '', // images link to nothing by default
			'thumbnail_crop' => 1, // set wordpress to crop thumbnails always
			'users_can_register' => 0, // user can not register for this site
			'blog_public' => 1 // make sure that the site is not blocked from search engines
		);
		
		/* loop through each of the above, updating the option each time */
		foreach ( $pxjn_core_settings as $k => $v ) {
			update_option( $k, $v );
		}
		
		/* done - now register a setting so we don't do this each time */
		update_option( 'theme_setup_status', '1' );
		
		/* display an admin message to let the user know what is going on. */
		$pxjn_msg = '
		<div class="error">
			<p>The ' . get_option( 'current_theme' ) . ' theme has changed your WordPress default <a href="' . admin_url() . 'options-general.php" title="See Settings">settings</a>. These changes were made to optimise WordPress for the ' . get_option( 'current_theme' ) . ' theme.</p>
		</div>';
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $pxjn_msg, '"' ) . '";' ) );
	}
	
	/* else if the theme is being reactived show a different message */
	elseif ( $pxjn_the_theme_status === '1' and isset( $_GET['activated'] ) ) {
		$pxjn_msg = '
		<div class="updated">
			<p>The ' . get_option( 'current_theme' ) . ' theme was successfully re-activated.</p>
		</div>';
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $pxjn_msg, '"' ) . '";' ) );
	}
	
} // end pxjn_theme_setup function

add_action( 'after_setup_theme', 'pxjn_theme_setup', 10 );

/***************************************************************
* Function pxjn_scripts_styles()
* Enqueue the necessary scripts and styles for the theme
***************************************************************/
function pxjn_scripts_styles() {
	
	/* enqueue the main theme stylesheet */
	wp_enqueue_style( 'pxjn_style', get_stylesheet_uri() );
	
	/* enqueue the navigation js for smaller screens menu fix */
	wp_enqueue_script( 'pxjn_navigation', get_template_directory_uri() . '/scripts/navigation.js', array(), '20120206', true );
	
	/* if this is single post view, comments are open and threaded comments enable - enqueue comments reply script */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

add_action( 'wp_enqueue_scripts', 'pxjn_scripts_styles' );