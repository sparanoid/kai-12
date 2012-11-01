<?php
/**
 * Twenty Twelve Kai functions and definitions
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Kai
 * @since Twenty Twelve Kai 1.0
 */

/**
 * Customize footer text
 *
 * @since Twenty Twelve Kai 1.0
 */
function add_twentytwelve_credits() {
  echo 'Theme: <a href="http://postholic.com/rsrc/twentytwelve-kai/">Twenty Twelve Kai</a><span class="sep">.</span> ';
}

add_action('twentytwelve_credits', 'add_twentytwelve_credits');

/**
 * Override Twenty Twelve stylesheet loading behavier and load Twenty Twelve Kai stylesheet
 *
 * @since Twenty Twelve Kai 1.1
 */
function twentytwelve_scripts_styles_override() {
   wp_enqueue_style( 'twentytwelve-style', get_template_directory_uri() . '/style.css' );
   wp_enqueue_style( 'twentytwelve-kai-style', get_stylesheet_directory_uri() . '/core.css' );
 }

add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles_override' );

/**
 * Override default excerpt length
 *
 * @since Twenty Twelve Kai 1.1.2
 */
function twentytwelve_kai_custom_excerpt_length( $length ) {
  return 360;
}

add_filter( 'excerpt_length', 'twentytwelve_kai_custom_excerpt_length', 999 );

/**
 * Initialize the update checker
 *
 * @since Twenty Twelve Kai 1.1.1
 */
require 'includes/theme-update-checker.php';
$twentytwelve_kai_update_checker = new ThemeUpdateChecker(
  'twentytwelve-kai',
  'http://sparanoid.com/lab/wordpress/twentytwelve-kai.json'
);

/**
 * Add theme support for infinity scroll
 *
 * @since Twenty Twelve Kai 1.1.3
 */
function twenty_twelve_kai_infinite_scroll_init() {
  add_theme_support( 'infinite-scroll', array(
    'container'      => 'content',
    'footer_widgets' => false
) );
}
add_action( 'after_setup_theme', 'twenty_twelve_kai_infinite_scroll_init' );
?>