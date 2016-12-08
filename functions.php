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
function add_kai_12_credits() {
  echo '<a href="http://sparanoid.com/work/kai/">Kai 12</a><span class="sep"> - </span> ';
}

add_action('twentytwelve_credits', 'add_kai_12_credits');

/**
 * Override Twenty Twelve stylesheet loading behavier and load Kai 12 stylesheet
 *
 * @since Kai 12 1.1
 */
function kai_12_scripts_styles_override() {
  wp_enqueue_style( 'twentytwelve-style', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'kai-12-style', get_stylesheet_directory_uri() . '/app.css' );
}

add_action( 'wp_enqueue_scripts', 'kai_12_scripts_styles_override' );

/**
 * Disable custom web font from Google, it's slow and it sucks
 *
 * @since Kai 12 1.2.9
 */
function kai_12_remove_custom_fonts() {
  wp_dequeue_style( 'twentytwelve-fonts' );
}
add_action( 'wp_enqueue_scripts', 'kai_12_remove_custom_fonts', 11 );

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

/**
 * Override default header settings when header text is hidden
 *
 * @since Kai 12 1.2.5
 */
function kai_12_custom_header_setup() {
  $args = array(
    'wp-head-callback' => 'kai_12_header_style',
  );

  add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'kai_12_custom_header_setup' );

function kai_12_header_style() {
  if ( ! display_header_text() ) : ?>
  <style type="text/css" id="kai-12-header-css">
    .site-header hgroup {
      display: none;
    }
  </style>
  <?php endif; ?>
<?php }

/**
 * Initialize the update checker
 *
 * @since Kai 12 1.2.1
 */
require 'includes/theme-update-checker.php';
$kai_12_update_checker = new ThemeUpdateChecker(
  'kai-12',
  'http://sparanoid.com/lab/wordpress/kai-12.json'
);
?>
