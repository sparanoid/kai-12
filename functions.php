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
 * Initialize the update checker
 *
 * @since Kai 12 1.2.1
 */
if (file_exists('includes/theme-update-checker.php')) {
  require 'includes/theme-update-checker.php';
  $kai12_update_checker = new ThemeUpdateChecker(
    'kai-12',
    'https://sparanoid.com/lab/wordpress/kai-12.json'
  );
}

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
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since Kai 1.2.14
 */
class Kai12_Customize {
  /**
   * This hooks into 'customize_register' (available as of WP 3.4) and allows
   * you to add new sections and controls to the Theme Customize screen.
   *
   * Note: To enable instant preview, we have to actually write a bit of custom
   * javascript. See live_preview() for more.
   *
   * @see add_action('customize_register',$func)
   * @param \WP_Customize_Manager $wp_customize
   * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
   * @since Kai 12 1.2.14
   */
  public static function register ( $wp_customize ) {
    // 1. Define a new section (if desired) to the Theme Customizer
    $wp_customize->add_section( 'kai12_options',
      array(
        'title'       => __( 'Kai 12 Options', 'kai-12' ), // Visible title of section
        'priority'    => 35, // Determines what order this appears in
        'capability'  => 'edit_theme_options', // Capability needed to tweak
        'description' => __('Allows you to customize some example settings for Kai 12.', 'kai-12'), // Descriptive tooltip
      )
    );

    // 2. Register new settings to the WP database...
    $wp_customize->add_setting( 'text_color', // No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
      array(
        'default'    => '#080c1b', // Default setting/value to save
        'type'       => 'theme_mod', // Is this an 'option' or a 'theme_mod'?
        'capability' => 'edit_theme_options', // Optional. Special permissions for accessing this setting.
        'transport'  => 'refresh', // What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      )
    );
    $wp_customize->add_setting( 'link_color',
      array(
        'default'    => '#4f3fbb',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport'  => 'refresh',
      )
    );
    $wp_customize->add_setting( 'code_color',
      array(
        'default'    => '#f77123',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport'  => 'refresh',
      )
    );
    $wp_customize->add_setting( 'background_color',
      array(
        'default'    => '#e6e7e8',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport'  => 'refresh',
      )
    );
    $wp_customize->add_setting( 'container_color',
      array(
        'default'    => '#fff',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport'  => 'refresh',
      )
    );

    // 3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
    $wp_customize->add_control( new WP_Customize_Color_Control( // Instantiate the color control class
      $wp_customize, // Pass the $wp_customize object (required)
      'kai12_text_color', // Set a unique ID for the control
      array(
        'label'     => __( 'Text Color', 'kai-12' ), // Admin-visible name of the control
        'settings'  => 'text_color', // Which setting to load and manipulate (serialized is okay)
        'priority'  => 10, // Determines the order this control appears in for the specified section
        'section'   => 'colors', // ID of the section this control should render in (can be one of yours, or a WordPress default section)
      )
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control(
      $wp_customize,
      'kai12_link_color',
      array(
        'label'     => __( 'Link Color', 'kai-12' ),
        'settings'  => 'link_color',
        'priority'  => 10,
        'section'   => 'colors',
      )
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control(
      $wp_customize,
      'kai12_code_color',
      array(
        'label'     => __( 'Code Color', 'kai-12' ),
        'settings'  => 'code_color',
        'priority'  => 10,
        'section'   => 'colors',
      )
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control(
      $wp_customize,
      'kai12_background_color',
      array(
        'label'     => __( 'Background Color', 'kai-12' ),
        'settings'  => 'background_color',
        'priority'  => 10,
        'section'   => 'colors',
      )
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control(
      $wp_customize,
      'kai12_container_color',
      array(
        'label'     => __( 'Container Color', 'kai-12' ),
        'settings'  => 'container_color',
        'priority'  => 10,
        'section'   => 'colors',
      )
    ) );
  }

  /**
   * This will output the custom WordPress settings to the live theme's WP head.
   *
   * Used by hook: 'wp_head'
   *
   * @see add_action('wp_head',$func)
   * @since Kai 12 1.2.14
   */
  public static function header_output() {
    ?>
    <style type="text/css" id="kai-12-customizer-colors">
      :root {
        <?php self::generate_css('--text-color', 'text_color'); ?>
        <?php self::generate_css('--link-color', 'link_color'); ?>
        <?php self::generate_css('--code-color', 'code_color'); ?>
        <?php self::generate_css('--container-color', 'container_color'); ?>
        <?php self::generate_css('--background-color', 'background_color', '#'); ?>
      }
    </style>
    <?php
  }

  /**
   * This outputs the javascript needed to automate the live settings preview.
   * Also keep in mind that this function isn't necessary unless your settings
   * are using 'transport'=>'postMessage' instead of the default 'transport'
   * => 'refresh'
   *
   * Used by hook: 'customize_preview_init'
   *
   * @see add_action('customize_preview_init',$func)
   * @since Kai 12 1.2.14
   */
  public static function live_preview() {
    wp_enqueue_script(
        'kai-12-themecustomizer', // Give the script a unique ID
        get_template_directory_uri() . '/assets/js/theme-customizer.js', // Define the path to the JS file
        array(  'jquery', 'customize-preview' ), // Define dependencies
        '', // Define a version (optional)
        true // Specify whether to put in footer (leave this true)
    );
  }

  public static function hex2hsl($hex) {
    $hex = str_split(ltrim($hex, '#'), 2);
    // convert the rgb values to the range 0-1
    $rgb = array_map(function($part) {
      return hexdec($part) / 255;
    }, $hex);

    // find the minimum and maximum values of r, g and b
    $min = min($rgb);
    $max = max($rgb);

    // calculate the luminace value by adding the max and min values and divide by 2
    $l = ($min + $max) / 2;
    if ($max === $min) {
      $h = $s = 0;
    } else {
      if ($l < 0.5) {
        $s = ($max - $min) / ($max + $min);
      } elseif ($l > 0.5) {
        $s = ($max - $min) / (2 - $max - $min);
      }
      if ($max === $rgb[0]) {
        $h = ($rgb[1]- $rgb[2]) / ($max -$min);
      } elseif ($max === $rgb[1]) {
        $h = 2 + ($rgb[2]- $rgb[0]) / ($max -$min);
      } elseif ($max === $rgb[2]) {
        $h = 4 + ($rgb[0]- $rgb[1]) / ($max -$min);
      }
      $h = $h * 60;
      if ($h < 0) {
        $h = $h + 360;
      }
    }
    return array($h, $s, $l);
  }

  /**
   * This will generate a line of CSS for use in header output. If the setting
   * ($mod_name) has no defined value, the CSS will not be output.
   *
   * @uses get_theme_mod()
   * @param string $selector CSS selector
   * @param string $style The name of the CSS *property* to modify
   * @param string $mod_name The name of the 'theme_mod' option to fetch
   * @param string $prefix Optional. Anything that needs to be output before the CSS property
   * @param string $postfix Optional. Anything that needs to be output after the CSS property
   * @param bool $echo Optional. Whether to print directly to the page (default: true).
   * @return string Returns a single line of CSS with selectors and a property.
   * @since Kai 12 1.2.14
   */
  public static function generate_css_block( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
    $return = '';
    $mod = get_theme_mod($mod_name);
    if ( ! empty( $mod ) ) {
      $return = sprintf('%s { %s: %s; }',
        $selector,
        $style,
        $prefix.$mod.$postfix
      );
      if ( $echo ) {
        echo $return;
      }
    }
    return $return;
  }
  public static function generate_css( $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
    $return = '';
    $mod = get_theme_mod($mod_name);
    $color_hue = self::hex2hsl($mod)[0];
    $color_sat = self::hex2hsl($mod)[1] * 100 . '%';
    $color_light = self::hex2hsl($mod)[2] * 100 . '%';
    if ( ! empty( $mod ) ) {
      // $return = sprintf("%s: %s;\n", $style, $prefix.$mod.$postfix);
      $return = "
        {$style}: {$prefix}{$mod}{$postfix};
        {$style}-hue: {$color_hue};
        {$style}-sat: {$color_sat};
        {$style}-light: {$color_light};
      ";
      if ( $echo ) {
        echo $return;
      }
    }
    return $return;
  }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'Kai12_Customize' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'Kai12_Customize' , 'header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'Kai12_Customize' , 'live_preview' ) );
