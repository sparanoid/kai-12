<?php
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
function kai12_customize_register ( $wp_customize ) {
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
      'sanitize_callback' => 'sanitize_hex_color',
      'transport'  => 'refresh', // What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );
  $wp_customize->add_setting( 'link_color',
    array(
      'default'    => '#4f3fbb',
      'type'       => 'theme_mod',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
      'transport'  => 'refresh',
    )
  );
  $wp_customize->add_setting( 'code_color',
    array(
      'default'    => '#ce621a',
      'type'       => 'theme_mod',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
      'transport'  => 'refresh',
    )
  );
  $wp_customize->add_setting( 'bg_color',
    array(
      'default'    => '#e6e7e8',
      'type'       => 'theme_mod',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
      'transport'  => 'refresh',
    )
  );
  $wp_customize->add_setting( 'container_color',
    array(
      'default'    => '#fff',
      'type'       => 'theme_mod',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
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
    'kai12_bg_color',
    array(
      'label'     => __( 'Background Color', 'kai-12' ),
      'settings'  => 'bg_color',
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
add_action( 'customize_register' , 'kai12_customize_register' );

/**
 * This will output the custom WordPress settings to the live theme's WP head.
 *
 * Used by hook: 'wp_head'
 *
 * @see add_action('wp_head',$func)
 * @since Kai 12 1.2.14
 */
function kai12_customize_header_output() {
  ?>
  <style type="text/css" id="kai-12-customizer-colors">
    :root {
      <?php kai12_generate_css('--text-color', 'text_color'); ?>
      <?php kai12_generate_css('--link-color', 'link_color'); ?>
      <?php kai12_generate_css('--code-color', 'code_color'); ?>
      <?php kai12_generate_css('--container-color', 'container_color'); ?>
      <?php kai12_generate_css('--background-color', 'bg_color'); ?>
    }
  </style>
  <?php
}
add_action( 'wp_head' , 'kai12_customize_header_output' );

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
function kai12_customize_live_preview() {
  wp_enqueue_script(
      'kai-12-themecustomizer', // Give the script a unique ID
      get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
      array(  'jquery', 'customize-preview' ), // Define dependencies
      '', // Define a version (optional)
      true // Specify whether to put in footer (leave this true)
  );
}
add_action( 'customize_preview_init' , 'kai12_customize_live_preview' );

function kai12_hex2hsl($hex) {
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
 * @param string $style The name of the CSS *property* to modify
 * @param string $mod_name The name of the 'theme_mod' option to fetch
 * @param string $prefix Optional. Anything that needs to be output before the CSS property
 * @param string $postfix Optional. Anything that needs to be output after the CSS property
 * @param bool $echo Optional. Whether to print directly to the page (default: true).
 * @return string Returns a single line of CSS with selectors and a property.
 * @since Kai 12 1.2.14
 */
function kai12_generate_css( $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
  $return = '';
  $mod = get_theme_mod($mod_name);
  $color_hue = kai12_hex2hsl($mod)[0];
  $color_sat = kai12_hex2hsl($mod)[1] * 100 . '%';
  $color_light = kai12_hex2hsl($mod)[2] * 100 . '%';
  if ( ! empty( $mod ) ) {
    // $return = sprintf("%s: %s;\n", $style, $prefix.$mod.$postfix);
    $return = "
      {$style}: {$prefix}{$mod}{$postfix};
      {$style}-h: {$color_hue};
      {$style}-s: {$color_sat};
      {$style}-l: {$color_light};
    ";
    if ( $echo ) {
      echo esc_html($return);
    }
  }
  return $return;
}
