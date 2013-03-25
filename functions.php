<?php
/**
 * Kai 12 functions and definitions
 *
 * @package WordPress
 * @subpackage Kai_12
 * @since Kai 12 1.0
 */

/**
 * Customize footer text
 *
 * @since Kai 12 1.0
 */
function add_twentytwelve_credits() {
  echo '<a href="http://sparanoid.com/work/kai/">Kai 12</a><span class="sep"> - </span> ';
}

add_action('twentytwelve_credits', 'add_twentytwelve_credits');

/**
 * Override Twenty Twelve stylesheet loading behavier and load Kai 12 stylesheet
 *
 * @since Kai 12 1.1
 */
function twentytwelve_scripts_styles_override() {
  wp_enqueue_style( 'twentytwelve-style', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'twentytwelve-kai-style', get_stylesheet_directory_uri() . '/core.css' );
}

add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles_override' );

/**
 * Override default excerpt length
 *
 * @since Kai 12 1.1.2
 */
function kai_12_custom_excerpt_length( $length ) {
  return 360;
}

add_filter( 'excerpt_length', 'kai_12_custom_excerpt_length', 999 );

/**
 * Override default Jetpack Infinite Scroll footer
 *
 * @since Kai 12 1.1.6
 */
function kai_12_infinite_scroll_credit() {
  return '<a href="http://sparanoid.com/work/kai/">Kai 12</a>';
}

add_filter( 'infinite_scroll_credit', 'kai_12_infinite_scroll_credit' );
?>