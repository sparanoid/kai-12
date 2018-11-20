<?php
/**
 * Kai 12 functions and definitions
 *
 * @package WordPress
 * @subpackage Kai12
 * @since Kai 12 1.0
 */

/**
 * Customize footer text
 *
 * @since Kai 12 1.0
 */
function add_kai12_credits() {
  echo '<a href="https://sparanoid.com/work/kai/">Kai 12</a><span class="sep"> - </span> ';
}

add_action('twentytwelve_credits', 'add_kai12_credits');

/**
 * Override Twenty Twelve stylesheet loading behavier and load Kai 12 stylesheet
 *
 * @since Kai 12 1.1
 */
function kai12_scripts_styles_override() {
  wp_enqueue_style( 'kai-12-style', get_stylesheet_directory_uri() . '/app.css' );
}

add_action( 'wp_enqueue_scripts', 'kai12_scripts_styles_override' );

/**
 * Disable custom web font from Google, it's slow and it sucks
 *
 * @since Kai 12 1.2.10
 */
function kai12_remove_custom_fonts() {
  wp_dequeue_style( 'twentytwelve-fonts' );
}
add_action( 'wp_enqueue_scripts', 'kai12_remove_custom_fonts', 11 );

/**
 * Override default excerpt length
 *
 * @since Kai 12 1.1.2
 */
function kai12_custom_excerpt_length( $length ) {
  return 360;
}

add_filter( 'excerpt_length', 'kai12_custom_excerpt_length', 999 );

/**
 * Override default Jetpack Infinite Scroll footer
 *
 * @since Kai 12 1.1.6
 */
function kai12_infinite_scroll_credit() {
  return '<a href="https://sparanoid.com/work/kai/">Kai 12</a>';
}

add_filter( 'infinite_scroll_credit', 'kai12_infinite_scroll_credit' );

/**
 * Override default header settings when header text is hidden
 *
 * @since Kai 12 1.2.5
 */
function kai12_custom_header_setup() {
  $args = array(
    'wp-head-callback' => 'kai12_header_style',
  );

  add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'kai12_custom_header_setup' );

function kai12_header_style() {
  if ( ! display_header_text() ) : ?>
  <style type="text/css" id="kai-12-header-css">
    .site-header hgroup {
      display: none;
    }
  </style>
  <?php endif; ?>
<?php }

/**
 * Initialize the Infinite Scroll for Jetpack
 *
 * @since Kai 12 1.2.13
 */
function kai12_infinite_scroll_init() {
  add_theme_support( 'infinite-scroll', array(
    'container'      => 'content',
  ) );
}
add_action( 'after_setup_theme', 'kai12_infinite_scroll_init' );

/**
 * Unregister default ugly gallery inline styles injected into the body
 *
 * @since Kai 12 1.2.13
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Remove custom background from parent theme
 *
 * @since Kai 12 1.2.14
 */
function remove_custom_background_from_parent_theme() {
  remove_theme_support( 'custom-background' );
}
add_action( 'after_setup_theme', 'remove_custom_background_from_parent_theme', 11 );

/**
 * Initialize theme customizer
 *
 * @since Kai 12 1.2.14
 */
require get_stylesheet_directory() . '/includes/customizer.php';
