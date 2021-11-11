<?php
/**
 * @author  Brad Dalton
 * @link    https://wp.me/p1lTu0-iZL
 */

add_action( 'genesis_meta', 'execute_if_widget_active' );
function execute_if_widget_active() {

	if ( is_active_sidebar( 'hero' ) ) {

	add_action( 'wp_enqueue_scripts', 'hero_scroll_script' );

	add_action( 'genesis_after_header', 'hero_image_text_widget' );

	add_filter( 'body_class', 'hero_body_class' );

	}

}

function hero_body_class( $classes ) {

	$classes[] = 'hero-active';

	return $classes;

}

function hero_scroll_script() {

	wp_enqueue_script( 'scroll-class', get_stylesheet_directory_uri() . '/hero.js', array( 'jquery' ), CHILD_THEME_HANDLE, true );

}

function hero_image_text_widget() {

	genesis_widget_area( 'hero', array(
		'before' => '<div class="hero-image"><div class="image-section widget-area"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );

}

add_action( 'wp_enqueue_scripts', 'add_hero_image_inline_css' );
function add_hero_image_inline_css() {

//	$handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme'; Use with older versions of Genesis Sample theme

    $image = get_option( 'hero-image', sprintf( '%s/images/hero.jpg', get_stylesheet_directory_uri() ) );

    $unique_image = wp_get_attachment_image_url( get_post_meta( get_the_ID(), 'unique_hero', true ), 'full' );

    $ternary =  $unique_image ? $unique_image : $image;

	$background = sprintf( 'background-image: url(%s);', $ternary );

	$css = sprintf( '.hero-image { %s }', $background );

	if ( $css ) {
		wp_add_inline_style( CHILD_THEME_HANDLE, $css );
    }

}


/**
 *
 * @author  Brad Dalton
 * @link    https://wp.me/p1lTu0-h9I
 */

add_filter( 'body_class', 'masonry_body_class' );
function masonry_body_class( $classes ) {

	$classes[] = 'masonry-archive';

	return $classes;

}

add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );


remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
add_action( 'genesis_before_content', 'genesis_do_author_title_description', 15 );


remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'masonry_image', 2 );

function masonry_image() {

$img = genesis_get_image( array( 'format' => 'html', 'size' => 'masonry-image', 'attr' => array( 'class' => 'masonry-image aligncenter' ) ) );
printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );

}

/*remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_entry_header', 'genesis_post_meta', 5 );*/

remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
add_action( 'genesis_after_content', 'genesis_posts_nav' );

/*remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_entry_header', 'genesis_post_info', 1 );*/

// THIS FILTER GETS CUSTOM EXCERPTS
add_filter( 'genesis_pre_get_option_content_archive','set_content' );
function set_content() {
	return 'excerpts';
}
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter( $post_info ) {
    $post_info = '[post_date]';
    return $post_info;

}
/** Customize the post meta function - Crunchify Tips*/
add_filter( 'genesis_post_meta', 'crunchify_post_meta_filter' );
function crunchify_post_meta_filter($post_meta) {
if ( !is_page() && !is_home() && !is_front_page() && !is_archive()) {
	 $post_meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged: "]';
 return $post_meta;
}}

// Add Read More Link to Custom Excerpts
function excerpt_read_more_link($output) {
 global $post;
 return $output . '<a class="more-link" href="'. get_permalink($post->ID) . '">Read More</a>';
}
add_filter('the_excerpt', 'excerpt_read_more_link');

add_action( 'wp_enqueue_scripts', 'masonry_files' );
function masonry_files() {

wp_enqueue_script( 'masonry-options', get_stylesheet_directory_uri().'/masonry/masonry-options.js' , array('jquery-masonry'), '1.0', true );

wp_enqueue_style( 'masonry-styles', get_stylesheet_directory_uri() . '/masonry/masonry.css', array(), CHILD_THEME_VERSION );

}


genesis();
