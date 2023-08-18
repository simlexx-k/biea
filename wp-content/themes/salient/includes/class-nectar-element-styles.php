<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}


/**
* Nectar Dynamic Element Styles.
*
* @since 11.1
*/

class NectarElDynamicStyles {

  private static $instance;

  public static $element_css  = array();
  public static $elements_arr = array(
    'vc_row',
    'vc_row_inner',
    'vc_column',
    'vc_column_inner',
    'icon',
    'nectar_icon',
    'nectar_post_grid',
    'nectar_category_grid',
    'image_with_animation',
    'nectar_highlighted_text',
    'nectar_scrolling_text',
    'nectar_rotating_words_title',
    'fancy_box',
    'nectar_flip_box',
    'divider',
    'nectar_gmap',
    'nectar_cta',
    'nectar_btn',
    'nectar_cascading_images',
    'nectar_horizontal_list_item',
    'nectar_icon_list',
    'nectar_icon_list_item',
    'nectar_gradient_text',
    'nectar_image_with_hotspots',
    'nectar_video_player_self_hosted',
    'pricing_column',
    'carousel',
    'item',
    'tabbed_section',
    'tab',
    'button',
  );

  public static $using_fullscreen_rows = false;
  public static $theme_colors = array();

  public function __construct() {

    add_action( 'wp_ajax_nectar_frontend_builder_generate_styles', array( $this, 'nectar_frontend_builder_generate_styles' ) );
  }


  /**
  * Used to regenerate styles when using the frontend editor.
  *
  */
  public static function nectar_frontend_builder_generate_styles() {

    if( !wp_verify_nonce( $_POST['_vcnonce'], 'vc-nonce-vc-admin-nonce' ) || true !== current_user_can('edit_pages') ) {
      exit();
    }

    if ( isset( $_POST['nectar_page_content'] ) ) {

      self::$element_css = array();

      $content = htmlspecialchars( stripslashes( $_POST['nectar_page_content'] ),  ENT_NOQUOTES );

      self::generate_el_styles($content);

      echo self::remove_duplicate_rules(self::$element_css);

    }

    exit();

  }


  /**
  * Removes duplicate css rules.
  *
  */
  public static function remove_duplicate_rules($rules) {

    if( is_array($rules) ) {

      $rules = array_unique($rules);
      usort($rules, array( 'NectarElDynamicStyles', 'order_media_queries' ));
      $css_rules = '';

      foreach($rules as $rule) {
        $css_rules .= $rule;
      }

      return $css_rules;

    }

    return '';

  }
  
  /**
  * Callback for usort to ensure that mobile media 
  * queries stay at the bottom.
  *
  */
  public static function order_media_queries($a, $b) {
    if ($a == $b) {
        return 0;
    }
    
    if( substr($a,0,41) == '@media only screen and (max-width: 690px)') {
      return 1;
    }
    
    if( substr($b,0,41) == '@media only screen and (max-width: 690px)') {
      return -1;
    }
    
    return 0;

  }


  /**
  * Calls all functions to handle styles for
  * elements on page and obscured in templates
  *
  * @see nectar/helpers/dynamic-styles.php location where called.
  */
  public static function generate_styles($post_content) {

    global $post;

    $page_full_screen_rows = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows', true ) : '';
    if( 'on' === $page_full_screen_rows ) {
      self::$using_fullscreen_rows = true;
    }

    // Generate All.
    self::generate_el_styles($post_content);
    self::generate_templatera_el_styles($post_content);

    // Output if styles exist.
    if( count(self::$element_css) > 0 ) {

      $rules = self::remove_duplicate_rules(self::$element_css);
      return $rules;
    }

    return '';

  }



  /**
  * Generates dynamic CSS of elements on current page.
  */
  public static function generate_el_styles($post_content) {

    // Regular Pages.
    if(!empty($post_content) ) {

      self::get_theme_colors();

      foreach( self::$elements_arr as $element ) {

        if ( preg_match_all( '/\['.$element.'(\s.*?)?\]/s', $post_content, $matches, PREG_SET_ORDER ) )  {

          if (!empty($matches)) {

            foreach ($matches as $shortcode) {

              // Output CSS.
              self::element_css($shortcode);

            } // End Single Element Item Loop.

          } // End Not Empty Matches.

        } // End Preg Match.

      } // End Element Loop.

    }

  }


  /**
  * Locates the content of a templatera template and generates dynamic CSS.
  */
  public static function generate_templatera_el_styles($post_content) {

    // Global shortcodes.
    preg_match_all( '/\[templatera(\s.*?)?\]/s', $post_content, $templatera_shortcode_match, PREG_SET_ORDER  );
    preg_match_all( '/\[nectar_global_section(\s.*?)?\]/s', $post_content, $nectar_global_section_match, PREG_SET_ORDER  );

    $global_template_shortcode_match = array_merge($templatera_shortcode_match, $nectar_global_section_match);

    if( !empty($global_template_shortcode_match) ) {

      foreach( $global_template_shortcode_match as $shortcode ) {

        if( strpos($shortcode[0],'[') !== false && strpos($shortcode[0],']') !== false ) {
          $shortcode_inner = substr($shortcode[0], 1, -1);
        } else {
          $shortcode_inner = $shortcode[0];
        }

        $atts = shortcode_parse_atts( $shortcode_inner );

        if( isset($atts['id']) ) {

          $template_ID = (int) $atts['id'];
          if( 0 !== $template_ID ) {
            $templatera_content_query = get_post($template_ID);

            if( isset($templatera_content_query->post_content) && !empty($templatera_content_query->post_content) ) {
              self::generate_styles($templatera_content_query->post_content);
            }
          }

        }

      } // End global section shortcode Loop.

    } // End found Templatera.


  }

  /**
  * Stores all theme colors.
  */
  public static function get_theme_colors() {

    $nectar_options = get_nectar_theme_options();

    $colors = array(
      array(
        'type' => 'regular',
        'stored_name' => 'accent-color',
        'display_name' => 'accent-color',
        'color_code' => ''
      ),
      array(
        'type' => 'regular',
        'stored_name' => 'extra-color-1',
        'display_name' => 'extra-color-1',
        'color_code' => ''
      ),
      array(
        'type' => 'regular',
        'stored_name' => 'extra-color-2',
        'display_name' => 'extra-color-2',
        'color_code' => ''
      ),
      array(
        'type' => 'regular',
        'stored_name' => 'extra-color-3',
        'display_name' => 'extra-color-3',
        'color_code' => ''
      ),
      array(
        'type' => 'gradient',
        'stored_name' => 'extra-color-gradient',
        'display_name' => 'extra-color-gradient-1',
        'color_code' => array(
          'from' => '',
          'to' => ''
        )
      ),
      array(
        'type' => 'gradient',
        'stored_name' => 'extra-color-gradient-2',
        'display_name' => 'extra-color-gradient-2',
        'color_code' => array(
          'from' => '',
          'to' => ''
        )
      )
    );

    $theme_colors_stored = array();

    foreach( $colors as $color ) {

      if( isset($nectar_options[$color['stored_name']]) ) {

        $c = $nectar_options[$color['stored_name']];

        // Gradient.
        if( 'gradient' === $color['type'] ) {

          $theme_colors_stored[$color['display_name']] = array(
            'gradient' => array(
              'from' => isset($c['from']) ? $c['from'] : '',
              'to' => isset($c['to']) ? $c['to'] : ''
            ),
            'name' => $color['display_name']
          );

        }
        // Regular
        else {
          $theme_colors_stored[$color['display_name']] = array(
            'color' => $c,
            'name' => $color['display_name']
          );
        }

      } // Color is set in theme options.

    } // End color loop.

    self::$theme_colors = $theme_colors_stored;

  }


  /**
  * Locates and returns a single theme color
  */
  public static function locate_color($color_name) {

    if( isset( self::$theme_colors[$color_name] ) ) {

      if( isset( self::$theme_colors[$color_name]['color'] ) &&
          !empty( self::$theme_colors[$color_name]['color'] ) ) {

        return self::$theme_colors[$color_name];

      }
      else if( isset( self::$theme_colors[$color_name]['gradient'] ) &&
               isset( self::$theme_colors[$color_name]['gradient']['to'] ) &&
               !empty( self::$theme_colors[$color_name]['gradient']['to'] ) ) {

        return self::$theme_colors[$color_name];

      }

    }

    return false;

  }


  /**
  * Outputs the dynamic CSS for a specific element passed to it.
  */
  public static function element_css($shortcode) {

    if( !isset($shortcode[0]) ) {
      return;
    }

    if( strpos($shortcode[0],'[') !== false && strpos($shortcode[0],']') !== false ) {
      $shortcode_inner = substr($shortcode[0], 1, -1);
    } else {
      $shortcode_inner = $shortcode[0];
    }

    $override = '!important';
    $devices  = array(
      'tablet' => '999px',
      'phone' => '690px'
    );


    // Row Element.
    if ( false !== strpos($shortcode[0],'[vc_row ') || false !== strpos($shortcode[0],'[vc_row_inner ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

      $row_selector = ( false !== strpos($shortcode[0],'[vc_row ') ) ? '.vc_row' : '.vc_row.inner_row';
      $row_span12_selector = ( false !== strpos($shortcode[0],'[vc_row ') ) ? '.row_col_wrap_12' : '.row_col_wrap_12_inner';

      $fullscreen_rows_bypass = ( false !== strpos($shortcode[0],'[vc_row ') && true === self::$using_fullscreen_rows ) ? true : false;

      // Inner Row specific.
      if ( false !== strpos($shortcode[0],'[vc_row_inner ') ) {


        //// DESKTOP ONLY.
        if( isset($atts['min_width_desktop']) && strlen($atts['min_width_desktop']) > 0 ) {
          self::$element_css[] = $row_selector.'.min_width_desktop_'. esc_attr( self::percent_unit_type_class($atts['min_width_desktop']) ) .' {
            min-width: '.esc_attr( self::percent_unit_type($atts['min_width_desktop']) ).';
          }';
        }

        //// DEVICES.
        foreach( $devices as $device => $media_query ) {

          if( isset($atts['min_width_'.$device]) && strlen($atts['min_width_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              body '.$row_selector.'.min_width_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_width_'.$device]) ) .' {
              min-width: '.esc_attr( self::percent_unit_type($atts['min_width_'.$device]) ).';
            } }';

          }

        }

      } // End Inner Row specific.

      // Parent Row specific.
      if( false !== strpos($shortcode[0],'[vc_row ') ) {

        //// Border Radius.
        if( isset($atts['row_border_radius']) && !empty($atts['row_border_radius']) && 'none' !== $atts['row_border_radius'] ) {

          $br_applies_to = ( isset($atts['row_border_radius_applies']) && !empty($atts['row_border_radius_applies']) ) ? $atts['row_border_radius_applies'] : 'bg';

          // Applies to Row.
          if( 'inner' === $br_applies_to ) {
            self::$element_css[] = '.wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="inner"] .row_col_wrap_12 {
              border-radius: '.esc_attr($atts['row_border_radius']).';
            }';
          }

          // Applies to BG.
          if( 'bg' === $br_applies_to ) {
            self::$element_css[] = '.wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="bg"] > .row-bg-wrap,
            .wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="bg"] > .nectar-video-wrap,
            .wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="bg"] > .nectar-parallax-scene {
              border-radius: '.esc_attr($atts['row_border_radius']).';
            }';
          }

        } // End Border Radius.


        //// Shape Divider Height.
        $shape_divider_tablet = (isset($atts['shape_divider_height_tablet']) && strlen($atts['shape_divider_height_tablet']) > 0) ? $atts['shape_divider_height_tablet'] : false;
        $shape_divider_phone = (isset($atts['shape_divider_height_phone']) && strlen($atts['shape_divider_height_phone']) > 0) ? $atts['shape_divider_height_phone'] : false;

        if( $shape_divider_tablet ) {
          self::$element_css[] = '@media only screen and (max-width: 999px) {
            .wpb_row.shape_divider_tablet_'.esc_attr( self::percent_unit_type_class($shape_divider_tablet) ).' .nectar-shape-divider-wrap {
              height: '.esc_attr( self::percent_unit_type($shape_divider_tablet) ).'!important;
            }
          }';
        }
        if( $shape_divider_phone ) {
          self::$element_css[] = '@media only screen and (max-width: 690px) {
            .wpb_row.shape_divider_phone_'.esc_attr( self::percent_unit_type_class($shape_divider_phone) ).' .nectar-shape-divider-wrap {
              height: '.esc_attr( self::percent_unit_type($shape_divider_phone) ).'!important;
            }
          }';
        }

        // Parallax mouse scene.
        if( isset($atts['mouse_based_parallax_bg']) && 'true' === $atts['mouse_based_parallax_bg'] ) {

          self::$element_css[] = '.wpb_row .nectar-parallax-scene{
            position:absolute;
            top:0;
            left:0;
            margin-bottom:0;
            padding-bottom:0;
          	margin-left: 0;
            overflow:hidden;
            width:100%;
            height:100%;
            z-index:1;
            -webkit-backface-visibility:hidden;
            backface-visibility:hidden;
            -webkit-transform:translate3d(0px,0px,0px);
            transform:translate3d(0px,0px,0px);
            -webkit-transform-style:preserve-3d;
            transform-style:preserve-3d
          }
          .wpb_row.full-width-content .nectar-parallax-scene{
            margin-left: 0;
          }
          .wpb_row .nectar-parallax-scene li:first-child {
            position: relative;
          }
          .wpb_row .nectar-parallax-scene li{
            height:100%;
            width:100%;
            position: absolute;
            top: 0;
            left: 0;
            display: block;
          }
          .wpb_row .nectar-parallax-scene div{
            margin-left:-10%;
            top:-10%;
            min-height:100%;
            width:120%;
            height:120%;
            background-size:cover;
            margin-bottom:0;
            max-width:none;
            position:relative;
            -webkit-backface-visibility:hidden;
            backface-visibility:hidden;
            -webkit-transform:translate3d(0px,0px,0px);
            transform:translate3d(0px,0px,0px);
            -webkit-transform-style:preserve-3d;
            transform-style:preserve-3d
          }';

          if( isset($atts['scene_position']) && 'center' === $atts['scene_position'] ) {
            self::$element_css[] = '.wpb_row .nectar-parallax-scene[data-scene-position="center"] div{
                background-position:center
              }';
          }
          if( isset($atts['scene_position']) && 'top' === $atts['scene_position'] ) {
            self::$element_css[] = '
              .wpb_row .nectar-parallax-scene[data-scene-position="top"] div{
                background-position:center top
              }';
          }
          if( isset($atts['scene_position']) && 'bottom' === $atts['scene_position'] ) {
            self::$element_css[] = '.wpb_row .nectar-parallax-scene[data-scene-position="bottom"] div{
                background-position:center bottom
              }';
          }

        }

      } // End Parent Row specific.


      // Shape Divider.
      if( isset($atts['enable_shape_divider']) && 'true' === $atts['enable_shape_divider'] ) {

        self::$element_css[] = '
        .nectar-shape-divider-wrap {
          position: absolute;
          top: auto;
          bottom: 0;
          left: 0;
          right: 0;
          width: 100%;
          height: 150px;
          z-index: 3;
          transform: translateZ(0);
        }
        .post-area.span_9 .nectar-shape-divider-wrap {
          overflow: hidden;
        }
        .nectar-shape-divider-wrap[data-front="true"] {
          z-index: 50;
        }
        .nectar-shape-divider-wrap[data-style="waves_opacity"] svg path:first-child {
          opacity: 0.6;
        }

        .nectar-shape-divider-wrap[data-style="curve_opacity"] svg path:nth-child(1),
        .nectar-shape-divider-wrap[data-style="waves_opacity_alt"] svg path:nth-child(1) {
          opacity: 0.15;
        }
        .nectar-shape-divider-wrap[data-style="curve_opacity"] svg path:nth-child(2),
        .nectar-shape-divider-wrap[data-style="waves_opacity_alt"] svg path:nth-child(2) {
          opacity: 0.3;
        }
        .nectar-shape-divider {
          width: 100%;
          left: 0;
          bottom: -1px;
          height: 100%;
          position: absolute;
        }
        .nectar-shape-divider-wrap.no-color .nectar-shape-divider {
          fill: #fff;
        }
        @media only screen and (max-width: 999px) {
          .nectar-shape-divider-wrap:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 75%;
          }
          .nectar-shape-divider-wrap[data-style="clouds"]:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 55%;
          }
        }
        @media only screen and (max-width: 690px) {
          .nectar-shape-divider-wrap:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 33%;
          }
          .nectar-shape-divider-wrap[data-style="clouds"]:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 33%;
          }
        }

        #ajax-content-wrap .nectar-shape-divider-wrap[data-height="1"] .nectar-shape-divider,
        #ajax-content-wrap .nectar-shape-divider-wrap[data-height="1px"] .nectar-shape-divider {
        	height: 1px;
        }';

        // Shape Divider Position.
        if( isset($atts['shape_divider_position']) && 'top' === $atts['shape_divider_position'] ||
            isset($atts['shape_divider_position']) && 'both' === $atts['shape_divider_position'] ) {
          self::$element_css[] = '.nectar-shape-divider-wrap[data-position="top"] {
            top: -1px;
            bottom: auto;
          }
          .nectar-shape-divider-wrap[data-position="top"] {
            transform: rotate(180deg)
          }';
        }

        // Shape Divider Cloud Style.
        if( isset($atts['shape_type']) && 'clouds' === $atts['shape_type'] ) {
          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-shape-divider-wrap[data-style="clouds"] .nectar-shape-divider {
              min-width: 1700px;
            }
          }
          @media only screen and (max-width: 999px) {
            .nectar-shape-divider-wrap[data-style="clouds"] .nectar-shape-divider {
              min-width: 800px;
            }
          }
          @media only screen and (max-width: 690px) {
            .nectar-shape-divider-wrap[data-style="clouds"] .nectar-shape-divider {
              min-width: 690px;
            }
          }';
        }

        // Shape Divider Fan Style.
        if( isset($atts['shape_type']) && 'fan' === $atts['shape_type'] ) {

          self::$element_css[] = '.nectar-shape-divider-wrap[data-style="fan"] svg {
            width: 102%;
            left: -1%;
          }
          .nectar-shape-divider-wrap[data-style="fan"] svg polygon:nth-child(2) {
            opacity: 0.15;
          }
          .nectar-shape-divider-wrap[data-style="fan"] svg rect {
            opacity: 0.3;
          }';

        }

        // Shape Divider Mountain Style.
        if( isset($atts['shape_type']) && 'mountains' === $atts['shape_type'] ) {
            self::$element_css[] = '.nectar-shape-divider-wrap[data-style="mountains"] svg path:first-child {
            opacity: 0.1;
          }
          .nectar-shape-divider-wrap[data-style="mountains"] svg path:nth-child(2) {
            opacity: 0.12;
          }
          .nectar-shape-divider-wrap[data-style="mountains"] svg path:nth-child(3) {
            opacity: 0.18;
          }
          .nectar-shape-divider-wrap[data-style="mountains"] svg path:nth-child(4) {
            opacity: 0.33;
          }';

        }


      } // End shape divider.

      // DESKTOP SPECIFIC
      //// Left Padding.
      if( true !== $fullscreen_rows_bypass ) {

        if( isset($atts['left_padding_desktop']) && strlen($atts['left_padding_desktop']) > 0 ) {
          self::$element_css[] = '#ajax-content-wrap ' . $row_selector.'.left_padding_'. esc_attr( self::percent_unit_type_class($atts['left_padding_desktop']) ) . ' ' . $row_span12_selector .' {
            padding-left: '.esc_attr( self::percent_unit_type($atts['left_padding_desktop']) ).';
          } ';
        }
        //// Right Padding.
        if( isset($atts['right_padding_desktop']) && strlen($atts['right_padding_desktop']) > 0 ) {
          self::$element_css[] = '#ajax-content-wrap ' . $row_selector.'.right_padding_'. esc_attr( self::percent_unit_type_class($atts['right_padding_desktop']) ) . ' ' . $row_span12_selector .' {
            padding-right: '.esc_attr( self::percent_unit_type($atts['right_padding_desktop']) ).';
          } ';
        }

      }

      // Device Loop.
      foreach( $devices as $device => $media_query ) {

        // Padding.
        if( true !== $fullscreen_rows_bypass ) {

          //// Top.
          if( isset($atts['top_padding_'.$device]) && strlen($atts['top_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$row_selector.'.top_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['top_padding_'.$device]) ) .' {
              padding-top: '.esc_attr( self::percent_unit_type($atts['top_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }

          //// Bottom.
          if( isset($atts['bottom_padding_'.$device]) && strlen($atts['bottom_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$row_selector.'.bottom_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['bottom_padding_'.$device]) ) .' {
              padding-bottom: '.esc_attr( self::percent_unit_type($atts['bottom_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }


          //// Left.
          if( isset($atts['left_padding_'.$device]) && strlen($atts['left_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { #ajax-content-wrap '.$row_selector.'.left_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['left_padding_'.$device]) ) . ' ' . $row_span12_selector .' {
              padding-left: '.esc_attr( self::percent_unit_type($atts['left_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }
          //// Right.
          if( isset($atts['right_padding_'.$device]) && strlen($atts['right_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { #ajax-content-wrap '.$row_selector.'.right_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['right_padding_'.$device]) ) . ' ' . $row_span12_selector .' {
              padding-right: '.esc_attr( self::percent_unit_type($atts['right_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }

        }

        // Transform.
        $transform_vals = '';
        $transform_x    = false;
        $transform_y    = false;

        //// Translate X.
        if( isset($atts['translate_x_'.$device]) && strlen($atts['translate_x_'.$device]) > 0 ) {

          $transform_vals .= 'translateX('. esc_attr( self::percent_unit_type($atts['translate_x_'.$device]) ).') ';
          $transform_x = true;

        }
        //// Translate Y.
        if( isset($atts['translate_y_'.$device]) && strlen($atts['translate_y_'.$device]) > 0 ) {

          $transform_vals .= 'translateY('. esc_attr( self::percent_unit_type($atts['translate_y_'.$device]) ).')';
          $transform_y = true;

        }

        if( !empty($transform_vals) ) {

          // X only.
          if( false === $transform_y && false !== $transform_x ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              '.$row_selector.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .' {
              -webkit-transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
              transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
            } }';

          }
          // Y only.
          else if ( false !== $transform_y && false === $transform_x ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              '.$row_selector.'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' {
              -webkit-transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
              transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
            } }';

          }
          // X and Y.
          else if( false !== $transform_y && false !== $transform_x ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              '.$row_selector.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' {
              -webkit-transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
              transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
            } }';

          }

        } // endif not empty transform vals.

      } // End foreach device loop.


    } // End Row Element.



    // Column Element.
    else if( false !== strpos($shortcode[0],'[vc_column ') || false !== strpos($shortcode[0],'[vc_column_inner ') ) {


      $atts = shortcode_parse_atts($shortcode_inner);

      $col_selector = ( false !== strpos($shortcode[0],'[vc_column ') ) ? '.wpb_column' : '.wpb_column.child_column';

      //// DESKTOP ONLY.
      if( isset($atts['right_margin']) && strlen($atts['right_margin']) > 0 ) {
        self::$element_css[] = $col_selector.'.right_margin_'. esc_attr( self::percent_unit_type_class($atts['right_margin']) ) .' {
          margin-right: '.esc_attr( self::percent_unit_type($atts['right_margin']) ). esc_attr( $override ).';
        }';
      }
      if( isset($atts['left_margin']) && strlen($atts['left_margin']) > 0 ) {
        self::$element_css[] = $col_selector.'.left_margin_'. esc_attr( self::percent_unit_type_class($atts['left_margin']) ) .' {
          margin-left: '.esc_attr( self::percent_unit_type($atts['left_margin']) ). esc_attr( $override ).';
        }';
      }
      if( isset($atts['column_element_spacing']) && 'default' !== $atts['column_element_spacing'] ) {
        self::$element_css[] = $col_selector.'.el_spacing_'. esc_attr( $atts['column_element_spacing']) .' > .vc_column-inner > .wpb_wrapper > div{
          margin-bottom: '.esc_attr( $atts['column_element_spacing'] ).';
        }';
      }

      // Column Border.
      $column_border_params = array(
        'border_left' => 'border-left-width',
        'border_top' => 'border-top-width',
        'border_right' => 'border-right-width',
        'border_bottom' => 'border-bottom-width'
      );
      //$column_border_inner_or_outer = ( isset($atts['border_location']) && 'include_margin' == $atts['border_location'] ) ? '' : ' > .vc_column-inner ';
      $column_border_inner_or_outer = ' > .vc_column-inner ';

      // Advanced Border Styling.
      if( isset($atts['border_type']) && 'advanced' === $atts['border_type'] ) {

        // Border width.
        foreach( $column_border_params as $param => $css_prop) {

          if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {

            self::$element_css[] = $col_selector . '.' . $param . '_desktop_' . esc_attr( self::percent_unit_type_class($atts[$param.'_desktop']) ) . $column_border_inner_or_outer . ' {
              '. $css_prop .': '.esc_attr( self::percent_unit_type($atts[$param.'_desktop']) ).';
            }';
          }

        } // end column border param loop.

        // Border color.
        if( isset($atts['column_border_color']) && strlen($atts['column_border_color']) > 0 ) {
          $border_color = ltrim($atts['column_border_color'],'#');
          self::$element_css[] = $col_selector . '.border_color_' . esc_attr( $border_color )  . $column_border_inner_or_outer . ' {
            border-color: #'.esc_attr($border_color).';
          }';
        }

        // Border style.
        if( isset($atts['column_border_radius']) && 'none' !== $atts['column_border_radius'] ) {
          $atts['column_border_style'] = 'solid';
        }
        if( isset($atts['column_border_style']) && strlen($atts['column_border_style']) > 0 ) {
          self::$element_css[] = $col_selector . '.border_style_' . esc_attr( $atts['column_border_style'] )  . $column_border_inner_or_outer . ' {
            border-style: '.esc_attr($atts['column_border_style']).';
          }';
        }

      } // end using advanced border style.

      //// DEVICES.
      foreach( $devices as $device => $media_query ) {

        // Margin.

        //// Top.
        if( isset($atts['top_margin_'.$device]) && strlen($atts['top_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.top_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['top_margin_'.$device]) ) .' {
            margin-top: '.esc_attr( self::percent_unit_type($atts['top_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }

        //// Right.
        if( isset($atts['right_margin_'.$device]) && strlen($atts['right_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.right_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['right_margin_'.$device]) ) .' {
            margin-right: '.esc_attr( self::percent_unit_type($atts['right_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }

        //// Left.
        if( isset($atts['left_margin_'.$device]) && strlen($atts['left_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.left_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['left_margin_'.$device]) ) .' {
            margin-left: '.esc_attr( self::percent_unit_type($atts['left_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }

        //// Bottom.
        if( isset($atts['bottom_margin_'.$device]) && strlen($atts['bottom_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.bottom_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['bottom_margin_'.$device]) ) .' {
            margin-bottom: '.esc_attr( self::percent_unit_type($atts['bottom_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }


        // Padding.
        if( isset($atts['column_padding_'.$device]) && strlen($atts['column_padding_'.$device]) > 0
        && $atts['column_padding_'.$device] !== 'inherit' && $atts['column_padding_'.$device] !== 'no-extra-padding' ) {

          $padding_number = preg_replace("/[^0-9\.]/", '', $atts['column_padding_'.$device]);
          $leading_zero   = ( $padding_number < 10) ? '0' : '';

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .wpb_row '.$col_selector.'.padding-'.esc_attr($padding_number).'-percent_'.$device.' > .vc_column-inner {
            padding: calc('.$media_query.' * 0.'.$leading_zero . esc_attr($padding_number).');
          } }';

        }

        // Border width
        if( isset($atts['border_type']) && 'advanced' === $atts['border_type'] ) {
          foreach( $column_border_params as $param => $css_prop) {

            if( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0 ) {

              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .wpb_row '.$col_selector . '.' . $param . '_'.$device.'_' . esc_attr( self::percent_unit_type_class($atts[$param.'_'.$device]) ) . $column_border_inner_or_outer . ' {
                '. $css_prop .': '.esc_attr( self::percent_unit_type($atts[$param.'_'.$device]) ).';
              }}';
            }

          } // End column border param loop.

        } // End using advanced border style.

      } // END DEVICE LOOP.

      // Border Radius.
      if( isset($atts['column_border_radius']) && !empty($atts['column_border_radius']) && 'none' !== $atts['column_border_radius'] ) {
        self::$element_css[] = '
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-bg-overlay-wrap,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-image-bg-wrap[data-bg-animation="zoom-out-reveal"],
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-image-bg-wrap .column-image-bg,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-image-bg-wrap[data-n-parallax-bg="true"] {
          border-radius: '.esc_attr($atts['column_border_radius']).';
        }';
      }

      // Column BG Position
      if( isset($atts['background_image_position']) && !empty($atts['background_image_position']) ) {
        self::$element_css[] = '.column-image-bg-wrap[data-bg-pos="'.esc_attr($atts['background_image_position']).'"] .column-image-bg {
          background-position: '.esc_attr($atts['background_image_position']).';
        }';
      }

      // PARENT COLUMN SPECIFIC.
      if ( false !== strpos($shortcode[0],'[vc_column ') ) {

        //// Max width.

        ////// Desktop.
        if( isset($atts['max_width_desktop']) && strlen($atts['max_width_desktop']) > 0 ) {
          self::$element_css[] = '.wpb_column.max_width_desktop_'. esc_attr( self::percent_unit_type_class($atts['max_width_desktop']) ) .' {
            max-width: '.esc_attr( self::percent_unit_type($atts['max_width_desktop']) ).';
          }';
        }

        ////// Devices.
        foreach( $devices as $device => $media_query ) {

          if( isset($atts['max_width_'.$device]) && strlen($atts['max_width_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body .wpb_column.max_width_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['max_width_'.$device]) ) .' {
              max-width: '.esc_attr( self::percent_unit_type($atts['max_width_'.$device]) ).';
            } }';

          }

        }

      } // END PARENT COLUMN SPECIFIC.

    } // End Column Element.


    // Icon Element.
    else if ( false !== strpos($shortcode[0],'[nectar_icon ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

      // Custom coloring.
      if( true === self::custom_color_bool('icon_color', $atts) ) {

        if( isset($atts['icon_style']) && !empty($atts['icon_style']) ) {

          $icon_color = ltrim($atts['icon_color_custom'],'#');


            // Default style.
            if( 'default' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="default"] .icon_color_custom_'.esc_attr($icon_color).' i {
                color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
            }

            // Border Basic style.
            if( 'border-basic' === $atts['icon_style'] || 'border-animation' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap .icon_color_custom_'.esc_attr($icon_color).' i {
                color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="border-basic"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).',
              .nectar_icon_wrap[data-style="border-animation"]:not([data-draw="true"]) .nectar_icon.icon_color_custom_'.esc_attr($icon_color).',
              .nectar_icon_wrap[data-style="border-animation"][data-draw="true"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':hover {
                border-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
            }

            // Border animation style.
            if( 'border-animation' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"]:not([data-draw="true"]) .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':hover {
                background-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
            }

            // Shadow BG style.
            if( 'shadow-bg' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).',
              .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':after {
                background-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':before {
                box-shadow: 0px 15px 28px #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).' .svg-icon-holder svg path {
                stroke: #fff!important;
              }';
            }

            // Soft BG style.
            if( 'soft-bg' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).' i {
                color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':before {
                background-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
            }

            // SVG.
            self::$element_css[] = '.nectar_icon_wrap .nectar_icon.icon_color_custom_'.esc_attr($icon_color).' .svg-icon-holder[data-color] svg path {
              stroke: #'.esc_attr($icon_color). esc_attr( $override ).';
            }';


          }

        } // Endif using custom coloring.

        // Color scheme coloring.
        else {

          $color = self::locate_color(strtolower($atts['icon_color']));

          if( isset($atts['icon_style']) && !empty($atts['icon_style']) ) {

            // Gradient Coloring.
            if( $color && isset($color['gradient']) ) {

              // Shadow bg
              if( 'shadow-bg' === $atts['icon_style'] ) {

                self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:after {
                  background: '.esc_attr($color['gradient']['to']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }
                .nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  box-shadow: 0px 15px 28px '. esc_attr($color['gradient']['from']) .'; opacity: 0.3;
                }';
              }
              // Soft bg
              else if( 'soft-bg' === $atts['icon_style'] ) {

                self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  background: '.esc_attr($color['gradient']['to']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
              }
              // Border Animation
              else if( 'border-animation' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"]:before {
                  background: '.esc_attr($color['gradient']['to']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
              }

            } // end gradient coloring.

            // Regular Coloring.
            else if( $color && isset($color['color']) ) {

              // Shadow Bg.
              if( 'shadow-bg' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:after {
                  background-color: '.esc_attr($color['color']).';
                }
                .nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  box-shadow: 0px 15px 28px '. esc_attr($color['color']) .'; opacity: 0.3;
                }';
              }
              // Soft Bg.
              else if( 'soft-bg' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  background-color: '.esc_attr($color['color']).';
                }';
              }
              // Border Animation.
              else if( 'border-animation' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"]:not([data-draw="true"]) .nectar_icon:hover {
                  background-color: '.esc_attr($color['color']).';
                }
                .nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"]:not([data-draw="true"]) .nectar_icon,
                .nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"][data-draw="true"]:hover .nectar_icon {
                  border-color: '.esc_attr($color['color']).';
                }';
              }
              // Border Basic.
              else if( 'border-basic' === $atts['icon_style'] ) {

                self::$element_css[] = '.nectar_icon_wrap[data-style="border-basic"][data-color="'.esc_attr($color['name']).'"] .nectar_icon {
                   border-color: '.esc_attr($color['color']).';
                }';

              }

            } // end regular coloring.

          } // icon style is set.

        }

        // Icon Padding.
        if( isset($atts['icon_padding']) && '0px' !== $atts['icon_padding'] ) {
          self::$element_css[] = '.nectar_icon_wrap[data-padding="'.esc_attr($atts['icon_padding']).'"] .nectar_icon {
            padding: '.esc_attr($atts['icon_padding']).';
          }';
        }

        // Border Thickness.
        if( isset($atts['icon_border_thickness']) && !empty($atts['icon_border_thickness']) ) {
          self::$element_css[] = '.nectar_icon_wrap[data-border-thickness="'.esc_attr($atts['icon_border_thickness']).'"] .nectar_icon {
            border-width: '.esc_attr($atts['icon_border_thickness']).';
          }';
        }

      } // End Icon Element.


      // Nectar CTA Element.
      else if ( false !== strpos($shortcode[0],'[nectar_cta ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['btn_style']) && 'next-section' !== $atts['btn_style'] ) {

          if( isset($atts['button_color_hover']) && !empty($atts['button_color_hover']) ) {

            $btn_color = ltrim($atts['button_color_hover'],'#');

            // Gradient colors will overlay color on pseudo for transition
            if( isset($atts['button_color']) && 'extra-color-gradient-1' === $atts['button_color'] ||
                isset($atts['button_color']) && 'extra-color-gradient-2' === $atts['button_color'] ) {
              self::$element_css[] = '.nectar-cta.hover_color_'.esc_attr($btn_color).' .link_wrap:before {
                background-color: #'.esc_attr($btn_color).';
              }';
            }
            // Regular colored btns
            else {
              self::$element_css[] = '.nectar-cta.hover_color_'.esc_attr($btn_color).' .link_wrap:hover {
                background-color: #'.esc_attr($btn_color). esc_attr( $override ).';
              }';
            }


          }

        }

        // Custom Alignment.
        if( isset($atts['alignment_tablet']) && 'default' !== $atts['alignment_tablet'] ) {
            self::$element_css[] = '@media only screen and (max-width: 999px) {
              .nectar-cta.alignment_tablet_'. esc_attr( $atts['alignment_tablet'] ) .' {
              text-align: '. esc_attr( $atts['alignment_tablet'] ) .';
            }
          }';
        }
        if( isset($atts['alignment_phone']) && 'default' !== $atts['alignment_phone'] ) {
            self::$element_css[] = '@media only screen and (max-width: 690px) {
              body .nectar-cta.alignment_phone_'. esc_attr( $atts['alignment_phone'] ) .' {
              text-align: '. esc_attr( $atts['alignment_phone'] ) .';
            }
          }';
        }



      } // End CTA Element.

      // Nectar Button Element.
      else if ( false !== strpos($shortcode[0],'[nectar_btn ') || false !== strpos($shortcode[0],'[button ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['button_style']) && 'see-through-3d' === $atts['button_style'] ||
            isset($atts['color']) && 'see-through-3d' === $atts['color'] ) {

          self::$element_css[] = '.nectar-3d-transparent-button {
            font-weight:700;
            font-size:12px;
            line-height:20px;
            visibility:hidden
          }
          .nectar-3d-transparent-button{
            display:inline-block
          }
          .nectar-3d-transparent-button a{
            display:block
          }
          .nectar-3d-transparent-button .hidden-text{
            height:1em;
            line-height:1.5;
            overflow:hidden
          }
          .nectar-3d-transparent-button .hidden-text{
            display:block;
            height:0;
            position:absolute
          }
          body .nectar-3d-transparent-button{
            position:relative;
            margin-bottom:0
          }
          .nectar-3d-transparent-button .inner-wrap{
            -webkit-perspective:2000px;
            perspective:2000px;
            position:absolute;
            top:0;
            right:0;
            bottom:0;
            left:0;
            width:100%;
            height:100%;
            display:block
          }
          .nectar-3d-transparent-button .front-3d{
            position:absolute;
            top:0;
            right:0;
            bottom:0;
            left:0;
            width:100%;
            height:100%;
            display:block
          }
          .nectar-3d-transparent-button .back-3d{
            position:relative;
            top:0;
            right:0;
            bottom:0;
            left:0;
            width:100%;
            height:100%;
            display:block
          }
          .nectar-3d-transparent-button .back-3d{
            -webkit-transform-origin:50% 50% -2.3em;
            transform-origin:50% 50% -2.3em
          }
          .nectar-3d-transparent-button .front-3d{
            -webkit-transform-origin:50% 50% -2.3em;
            transform-origin:50% 50% -2.3em;
            -webkit-transform:rotateX(-90deg);
            transform:rotateX(-90deg)
          }
          .nectar-3d-transparent-button:hover .front-3d{
            -webkit-transform:rotateX(0deg);
            transform:rotateX(0deg)
          }
          .nectar-3d-transparent-button:hover .back-3d{
            -webkit-transform:rotateX(90deg);
            transform:rotateX(90deg)
          }
          .nectar-3d-transparent-button .back-3d,
          .nectar-3d-transparent-button .front-3d{
            transition:-webkit-transform .25s cubic-bezier(.2,.65,.4,1);
            transition:transform .25s cubic-bezier(.2,.65,.4,1);
            transition:transform .25s cubic-bezier(.2,.65,.4,1),-webkit-transform .25s cubic-bezier(.2,.65,.4,1)
          }
          .nectar-3d-transparent-button .back-3d,
          .nectar-3d-transparent-button .front-3d{
            -webkit-backface-visibility:hidden;
            backface-visibility:hidden
          }
          .nectar-3d-transparent-button .back-3d svg,
          .nectar-3d-transparent-button .front-3d svg{
            display:block
          }';

        }

        // Button Sizes.
        if( isset($atts['size']) && 'small' === $atts['size'] ) {
          self::$element_css[] = '.nectar-button.small{
            border-radius:2px 2px 2px 2px;
            font-size:12px;
            padding:8px 14px;
            color:#FFF;
            box-shadow:0 -1px rgba(0,0,0,0.1) inset;
          }
          .nectar-button.small.see-through,
          .nectar-button.small.see-through-2,
          .nectar-button.small.see-through-3{
            padding-top:6px;
            padding-bottom:6px
          }
          .nectar-button.small i{
            font-size:16px;
            line-height:16px;
            right:26px
          }
          .nectar-button.small i.icon-button-arrow{
            font-size:16px
          }
          .nectar-button.has-icon.small,
          .nectar-button.tilt.has-icon.small{
            padding-left:33px;
            padding-right:33px
          }
          .nectar-button.has-icon.small:hover span,
          .nectar-button.tilt.small.has-icon span,
          body.material .nectar-button.has-icon.small span {
            -webkit-transform:translateX(-14px);
            transform:translateX(-14px)
          }
          .nectar-button.small.has-icon:hover i,
          .nectar-button.small.tilt.has-icon i,
          body.material .nectar-button.small.has-icon i {
            -webkit-transform:translateX(10px);
            transform:translateX(10px);
          }

          body.material .nectar-button.small i {
            font-size: 14px;
          }
          body.material[data-button-style^="rounded"] .nectar-button.small i {
            font-size: 12px;
          }
          ';
        }

        if( isset($atts['size']) && 'medium' === $atts['size'] ) {
          self::$element_css[] = '
          .nectar-button.medium{
            border-radius:3px 3px 3px 3px;
            padding:10px 15px;
            font-size:12px;
            color:#FFF;
            box-shadow:0 -2px rgba(0,0,0,0.1) inset;
          }
          .nectar-button.medium.see-through,
          .nectar-button.medium.see-through-2,
          .nectar-button.medium.see-through-3{
            padding-top:9px;
            padding-bottom:9px
          }
          .nectar-button.medium i.icon-button-arrow {
            font-size:16px
          }
          body[data-button-style^="rounded"] .nectar-button.medium:not(.see-through):not(.see-through-2):not(.see-through-3).has-icon,
          body[data-button-style^="rounded"] .nectar-button.medium:not(.see-through):not(.see-through-2):not(.see-through-3).tilt.has-icon{
            padding-left:42px;
            padding-right:42px
          }
          body[data-button-style^="rounded"] .nectar-button.medium:not(.see-through):not(.see-through-2):not(.see-through-3) {
            padding: 12px 18px;
          }

          .nectar-button.medium.has-icon, .nectar-button.medium.tilt.has-icon{
            padding-left:42px;
            padding-right:42px
          }

          ';
        }

        if( isset($atts['size']) && 'extra_jumbo' === $atts['size'] ) {

          self::$element_css[] = '
          .nectar-button.extra_jumbo{
            font-size:60px;
            line-height:60px;
            padding:60px 90px;
            box-shadow:0 -3px rgba(0,0,0,0.1) inset;
          }
          body .nectar-button.extra_jumbo.see-through,
          body .nectar-button.extra_jumbo.see-through-2,
          body .nectar-button.extra_jumbo.see-through-3{
            border-width:10px
          }
          .nectar-button.extra_jumbo.has-icon,
          .nectar-button.tilt.extra_jumbo.has-icon{
            padding-left:80px;
            padding-right:80px
          }
          .nectar-button.extra_jumbo i,
          .nectar-button.tilt.extra_jumbo i,
          .nectar-button.extra_jumbo i[class*="fa-"],
          .nectar-button.tilt.extra_jumbo i[class*="fa-"] {
            right:75px
          }
          .nectar-button.has-icon.extra_jumbo:hover i,
          .nectar-button.tilt.extra_jumbo.has-icon i{
            -webkit-transform:translateX(13px);
            transform:translateX(13px);
          }
          .nectar-button.has-icon.extra_jumbo:hover span,
          .nectar-button.tilt.extra_jumbo.has-icon span {
            -webkit-transform:translateX(-30px);
            transform:translateX(-30px)
          }
          body .nectar-button.extra_jumbo i{
            font-size:40px;
            margin-top:-20px;
            line-height:40px
          }
          .nectar-button.extra_jumbo .im-icon-wrap svg {
            width: 40px;
            height: 40px;
          }


          @media only screen and (max-width: 999px) and (min-width: 691px) {

            body.material .nectar-button.extra_jumbo.has-icon {
              font-size: 30px;
              line-height: 60px;
              padding: 30px 100px 30px 60px;
            }

            body.material .nectar-button.has-icon.extra_jumbo i {
              height: 74px;
              width: 74px;
              line-height: 74px;
            }

          }

          @media only screen and (min-width : 690px) and (max-width : 999px) {
            .nectar-button.extra_jumbo {
              font-size: 32px;
              line-height: 60px;
              padding: 30px 50px;
            }

            .nectar-button.see-through-extra-color-gradient-1.extra_jumbo,
            .nectar-button.see-through-extra-color-gradient-2.extra_jumbo,
            .nectar-button.extra-color-gradient-1.extra_jumbo,
            .nectar-button.extra-color-gradient-2.extra_jumbo {
              border-width: 8px;
            }
          }

          @media only screen and (max-width : 690px) {
            .nectar-button.extra_jumbo {
              font-size: 24px;
              line-height: 24px;
              padding: 20px 30px;
            }

            .nectar-button.extra_jumbo.has-icon.extra-color-gradient-1,
            .nectar-button.extra_jumbo.has-icon.extra-color-gradient-2,
          	.nectar-button.extra_jumbo.has-icon.see-through-extra-color-gradient-1,
            .nectar-button.extra_jumbo.has-icon.see-through-extra-color-gradient-2 {
              font-size: 24px;
              line-height: 24px;
              padding: 20px 50px;
            }

            .nectar-button.extra-color-gradient-1.has-icon.extra_jumbo span,
            .nectar-button.extra-color-gradient-2.has-icon.extra_jumbo span,
            .nectar-button.see-through-extra-color-gradient-1.has-icon.extra_jumbo span,
            .nectar-button.see-through-extra-color-gradient-2.has-icon.extra_jumbo span {
              left: -28px;
            }

            .nectar-button.extra_jumbo i,
            .nectar-button.extra_jumbo.has-icon i {
              font-size: 26px;
            }

            body.material #ajax-content-wrap .nectar-button.extra_jumbo.has-icon {
              font-size: 22px;
              line-height: 22px;
              padding: 24px 65px 24px 55px;
            }

            body.material #ajax-content-wrap .nectar-button.has-icon.extra_jumbo i {
              height: 50px;
              width: 50px;
              line-height: 50px;
            }

            body.material .nectar-button.extra_jumbo .im-icon-wrap svg {
              width: 24px;
              height: 24px;
            }

            .nectar-button.see-through-extra-color-gradient-1.extra_jumbo,
            .nectar-button.see-through-extra-color-gradient-2.extra_jumbo,
            .nectar-button.extra-color-gradient-1.extra_jumbo,
            .nectar-button.extra-color-gradient-2.extra_jumbo {
              border-width: 6px;
            }

          }';
        }


      } // End Button Element.

      // Category Grid Element.
      else if( false !== strpos($shortcode[0],'[nectar_category_grid ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Style.
        if( isset($atts['grid_style']) && 'mouse_follow_image' === $atts['grid_style']) {

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content .cat-heading {
              max-width: 100%;
            }
          }

          .nectar-category-grid[data-style="mouse_follow_image"] {
            justify-content: center;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .inner {
           position: static;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-link {
            z-index: 100;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .inner {
            overflow: visible;
            width: auto;
            background-color: transparent;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .bg-overlay {
            display: none;
          }

          #ajax-content-wrap .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content {
            position: relative;
            width: auto;
            top: auto;
            left: auto;
            padding: .3em .5em;
            line-height: 1;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
            position: fixed;
            left: -150px;
            top: -150px;
            opacity: 0;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
            width: 300px;
            height: 300px;
            transition: opacity 0.15s ease;
          }
          .nectar-category-grid[data-style="mouse_follow_image"]:not(.active) .nectar-category-grid-item-bg[data-nectar-img-src] {
            display: none;
          }
          .nectar-category-grid[data-style="mouse_follow_image"].mouse-over .nectar-category-grid-item .nectar-category-grid-item-bg {
            transition: opacity 0.15s ease 0.15s;
          }
          .nectar-category-grid[data-style="mouse_follow_image"].mouse-over .nectar-category-grid-item:hover .nectar-category-grid-item-bg {
            opacity: 1;
            transition: opacity 0.15s ease;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .content .cat-heading {
            line-height: 1em;
            transition: color 0.45s cubic-bezier(.15,.75,.5,1), background-size 0.45s cubic-bezier(.15,.75,.5,1);
            display: inline;
          }
          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content .subtext {
            transition: color 0.45s cubic-bezier(.15,.75,.5,1), opacity 0.45s cubic-bezier(.15,.75,.5,1);
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
            will-change: transform, opacity;
          }

          .using-mobile-browser .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
            will-change: auto;
          }
          @media only screen and (max-width: 690px) {
            #ajax-content-wrap .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content {
              padding: .33em .66em;
            }
          }

          ';

          if( isset($atts['image_aspect_ratio']) && '1-1' !== $atts['image_aspect_ratio'] ) {

            if( '4-5' === $atts['image_aspect_ratio'] ) {
              self::$element_css[] = '.nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
                  left: -150px;
                  top: -187.5px;
                  width: 300px;
                  height: 375px;
                }';
            }
            else if ( '16-9' === $atts['image_aspect_ratio'] ) {

              self::$element_css[] = '.nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
                  left: -175px;
                  top: -98px;
                  width: 350px;
                  height: 196px;
                }';
            }

            else if ( '4-3' === $atts['image_aspect_ratio'] ) {

              self::$element_css[] = '.nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
                  left: -175px;
                  top: -131px;
                  width: 350px;
                  height: 262px;
                }';
            }


          }

          if( isset($atts['subtext']) && 'none' !== $atts['subtext'] ) {
            self::$element_css[] = '

            .nectar-category-grid[data-text-hover-color="dark"] .nectar-category-grid-item .content .subtext {
              color: #000;
            }
            .nectar-category-grid[data-text-hover-color="light"] .nectar-category-grid-item .content .subtext {
              color: #fff;
            }
            #ajax-content-wrap .nectar-category-grid .nectar-category-grid-item .subtext:after {
              display: none;
            }
            .nectar-category-grid[data-style="mouse_follow_image"] .content {
              text-align: center;
            }
            .nectar-category-grid[data-style="mouse_follow_image"] .subtext {
              display: block;
              opacity: 0.6;
              margin-top: 12px;
              line-height: 1;
            }';
          }

        } // End mouse follow image style.

      } // End Category Grid Element.

      // Post Grid Element
      else if ( false !== strpos($shortcode[0],'[nectar_post_grid ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Font size.
        if( isset($atts['custom_font_size']) && !empty($atts['custom_font_size']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) { .nectar-post-grid.font_size_'.esc_attr($font_size).' .post-heading {
            font-size: '.esc_attr($font_size).';
          } }';

        }

        // BG Color Hover.
        if( isset($atts['card_bg_color_hover']) && !empty($atts['card_bg_color_hover']) ) {

          $card_hover_color = ltrim($atts['card_bg_color_hover'],'#');

          self::$element_css[] = '.nectar-post-grid[data-card="yes"].card_hover_color_'.esc_attr($card_hover_color).' .nectar-post-grid-item:hover {
            background-color: #'.esc_attr($card_hover_color) . esc_attr( $override ) .';
          }';

        }
        
        // Text Opacity.
        if( isset($atts['text_opacity']) && !empty($atts['text_opacity']) && '1' !== $atts['text_opacity'] ) {
          $text_opacity_selector = str_replace('.', '-', $atts['text_opacity']);
          self::$element_css[] = '.nectar-post-grid.text-opacity-'.esc_attr($text_opacity_selector).' .nectar-post-grid-item .content {
           opacity: '.esc_attr($atts['text_opacity']) .';
          }';
        }
        if( isset($atts['text_hover_opacity']) && !empty($atts['text_hover_opacity']) ) {
          $text_opacity_selector = str_replace('.', '-', $atts['text_hover_opacity']);
          self::$element_css[] = '.nectar-post-grid.text-opacity-hover-'.esc_attr($text_opacity_selector).' .nectar-post-grid-item:hover .content {
           opacity: '.esc_attr($atts['text_hover_opacity']) .';
          }';
        }

      } // End Post Grid Element.

      // Nectar Highlighted Text
      else if ( false !== strpos($shortcode[0],'[nectar_highlighted_text ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $style = ( isset($atts['style']) ) ? esc_attr($atts['style']) : 'full_text';

        if( isset($atts['custom_font_size']) && !empty($atts['custom_font_size']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h1,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h2,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h3,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h4 {
            font-size: '.esc_attr($font_size).';
            line-height: 1.1em;
          }}
          .nectar-highlighted-text[data-style="regular_underline"].font_size_'.esc_attr($font_size).' em:before,
          .nectar-highlighted-text[data-style="half_text"].font_size_'.esc_attr($font_size).' em:before {
             bottom: 0.07em;
          }';

        }

        // Coloring.
        if( isset($atts['highlight_color']) && !empty($atts['highlight_color']) ) {

          $color      = esc_attr($atts['highlight_color']);
          $grad_color = ( isset($atts['secondary_color']) && !empty($atts['secondary_color']) ) ? esc_attr($atts['secondary_color']) : false;

          if( 'text_outline' !== $style ) {

              // Regular
              if( false === $grad_color ) {
                self::$element_css[] = '.nectar-highlighted-text[data-color="'.esc_attr($color).'"]:not([data-style="text_outline"]) em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($color).' 100%);
                }
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"]:not([data-style="text_outline"]) em.has-link,
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"]:not([data-style="text_outline"]) a em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($color).' 100%),
                                    linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($color).' 100%);
                }';
              }
              else {

                // Grad
                self::$element_css[] = '.nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-color-gradient="'.esc_attr($grad_color).'"]:not([data-style="text_outline"]) em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($grad_color).' 100%);
                }
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-color-gradient="'.esc_attr($grad_color).'"]:not([data-style="text_outline"]) em.has-link,
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-color-gradient="'.esc_attr($grad_color).'"]:not([data-style="text_outline"]) a em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($grad_color).' 100%),
                                    linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($grad_color).' 100%);
                }';
              }


          } // end non text outline.

        } // end color.

        // Sizing.
        $underline_thickness = ( isset($atts['underline_thickness']) ) ? esc_attr($atts['underline_thickness']) : 'default';
        $underline_px        = ( 'default' !== $underline_thickness ) ? $underline_thickness : '3px';
        $expansion           = ( isset($atts['highlight_expansion']) ) ? esc_attr($atts['highlight_expansion']) : 'default';

        $expansion_amt = '90%';
        $bg_hover_height = '80%';

        switch( $expansion ) {
          case 'closer':
            $expansion_amt = '84%';
            $bg_hover_height = '70%';
            break;
          case 'closest':
            $expansion_amt = '78%';
            $bg_hover_height = '60%';
            break;
        }

        if( 'regular_underline' === $style ) {

          self::$element_css[] = '.nectar-highlighted-text[data-style="regular_underline"][data-exp="'.esc_attr($expansion).'"] em {
            background-position: left '.esc_attr($expansion_amt).';
          }
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"] em {
          	background-size: 0% '.esc_attr($underline_px).';
          }
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] a em,
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] em.has-link {
          	background-size: 0% '.esc_attr($underline_px).', 0% '.esc_attr($bg_hover_height).';
            background-position: left '.esc_attr($expansion_amt).', left 50%;
          }';

          self::$element_css[] = '.nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"] em.animated {
          	background-size: 100% '.esc_attr($underline_px).';
          }
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] a em.animated,
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] em.animated.has-link {
          	background-size: 100% '.esc_attr($underline_px).', 0% '.esc_attr($bg_hover_height).';
          }

          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] a em.animated:hover,
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] em.animated.has-link:hover  {
          	background-size: 100% '.esc_attr($underline_px).', 100% '.esc_attr($bg_hover_height).';
          }
          ';

        }

      } // End Nectar Highlighted Text Element.


      // Nectar Rotating Words Title
      else if ( false !== strpos($shortcode[0],'[nectar_rotating_words_title ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['font_size']) && !empty($atts['font_size']) ) {

          $font_size = self::font_sizing_format($atts['font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            body .nectar-rotating-words-title.font_size_'.esc_attr($font_size).' .heading {
            font-size: '.esc_attr($font_size).';
          } }';

        }

        if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
          $color = ltrim($atts['text_color'],'#');

          self::$element_css[] = '.nectar-rotating-words-title.color_'.esc_attr($color).' .heading {
            color: #'.esc_attr($color).';
          }';
        }

      }

      // Nectar Scrolling Text
      else if ( false !== strpos($shortcode[0],'[nectar_scrolling_text ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['custom_font_size']) && !empty($atts['custom_font_size']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-scrolling-text.font_size_'.esc_attr($font_size).' .nectar-scrolling-text-inner * {
            font-size: '.esc_attr($font_size).';
            line-height: 1.2em;
          } }';

        }

        if( isset($atts['custom_font_size_mobile']) && !empty($atts['custom_font_size_mobile']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size_mobile']);

          self::$element_css[] = '@media only screen and (max-width: 1000px) {
            .nectar-scrolling-text.font_size_mobile_'.esc_attr($font_size).' .nectar-scrolling-text-inner * {
            font-size: '.esc_attr($font_size).';
            line-height: 1.2em;
          } }';

        }

      } // End Nectar Scrolling Text.

      // Single Image Element.
      else if( false !== strpos($shortcode[0],'[image_with_animation ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        foreach( $devices as $device => $media_query ) {

          // Margin.

          //// Top.
          if( isset($atts['margin_top_'.$device]) && strlen($atts['margin_top_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_top_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_top_'.$device]) ) .' {
              margin-top: '.esc_attr( self::percent_unit_type($atts['margin_top_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          //// Right.
          if( isset($atts['margin_right_'.$device]) && strlen($atts['margin_right_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_right_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_right_'.$device]) ) .' {
              margin-right: '.esc_attr( self::percent_unit_type($atts['margin_right_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          //// Bottom.
          if( isset($atts['margin_bottom_'.$device]) && strlen($atts['margin_bottom_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_bottom_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_bottom_'.$device]) ) .' {
              margin-bottom: '.esc_attr( self::percent_unit_type($atts['margin_bottom_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          //// Left.
          if( isset($atts['margin_left_'.$device]) && strlen($atts['margin_left_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_left_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_left_'.$device]) ) .' {
              margin-left: '.esc_attr( self::percent_unit_type($atts['margin_left_'.$device]) ). esc_attr( $override ).';
            } }';
          }


        } // End foreach device loop.

        // Border Radius.
        if( isset($atts['border_radius']) && !empty($atts['border_radius']) ) {
          self::$element_css[] = '
          .img-with-aniamtion-wrap[data-border-radius="'.esc_attr($atts['border_radius']).'"] .img-with-animation,
          .img-with-aniamtion-wrap[data-border-radius="'.esc_attr($atts['border_radius']).'"] .hover-wrap {
            border-radius: '.esc_attr($atts['border_radius']).';
          }';
        }

        // Custom Max Width.
        if( isset($atts['max_width']) && 'custom' === $atts['max_width'] ) {
          if( isset($atts['max_width_custom']) && !empty( $atts['max_width_custom'] ) ) {

            self::$element_css[] = '.img-with-aniamtion-wrap.custom-width-'. esc_attr( self::percent_unit_type_class($atts['max_width_custom']) ) .' .inner {
              max-width: '. self::percent_unit_type($atts['max_width_custom']) .';
            }';
          }
        }

        // Default Max Width.
        $image_alignment = ( isset($atts['alignment']) ) ? $atts['alignment'] : '';

        if( isset($atts['max_width']) && in_array($atts['max_width'], array('110%','125%','150%','165%','175%','200%','225%','250%') ) ) {

          $image_max_width = $atts['max_width'];
          $image_center_margin_rel = array(
              '110%' => '-5%',
              '125%' => '-12.5%',
              '150%' => '-25%',
              '165%' => '-32.5%',
              '175%' => '-37.5%',
              '200%' => '-50%',
              '225%' => '-62.5%',
              '250%' => '-75%',
          );
          $image_right_margin_rel = array(
              '110%' => '-10%',
              '125%' => '-25%',
              '150%' => '-50%',
              '165%' => '-65%',
              '175%' => '-75%',
              '200%' => '-100%',
              '225%' => '-125%',
              '250%' => '-150%',
          );

          // Width Def.
          self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] .inner {
            width: '.esc_attr($atts['max_width']).';
            display: block;
          }
          .img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] img {
            max-width: 100%;
            width: auto;
          }
          .img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"][data-shadow*="depth"] img {
            max-width: none;
          	width: 100%;
          }';

          // Right specific image alignment.
          if( 'right' === $image_alignment ) {
            self::$element_css[] = '.right.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] img {
              display: block;
            }';
            self::$element_css[] = '.img-with-aniamtion-wrap.right[data-max-width="'.esc_attr($atts['max_width']).'"] .inner{
              margin-left: '.esc_attr($image_right_margin_rel[$image_max_width]).';
            }';
          }

          // Center specific image alignment.
          if( 'center' === $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"].center .inner{
              margin-left: '.esc_attr($image_center_margin_rel[$image_max_width]).';
            }';
          }

          // Left and Center image alignment.
          if( 'right' !== $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"]:not(.right) img {
              backface-visibility: hidden;
            }';
          }

          // Responsive.
          self::$element_css[] = '@media only screen and (max-width : 999px) {
            .img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] .inner {
              max-width: 100%;
            }
            .img-with-animation[data-max-width="'.esc_attr($atts['max_width']).'"] {
              max-width: 100%;
              margin-left: 0;
            }
          }';

        }

        if( isset($atts['max_width']) && in_array($atts['max_width'], array('50%','75%','custom')) ) {

          if( 'center' === $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"].center .inner {
              display: inline-block;
            }';
          }
          if( 'right' === $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"].right .inner {
              display: inline-block;
            }';
          }

        }

        // Mobile Max Width.
        if( isset($atts['max_width_mobile']) && in_array($atts['max_width_mobile'], array('110%','125%','150%','165%','175%','200%') ) ) {
          self::$element_css[] = '@media only screen and (max-width : 999px) {
              .img-with-aniamtion-wrap[data-max-width-mobile="'.esc_attr($atts['max_width_mobile']).'"] .inner {
                width: '.esc_attr($atts['max_width_mobile']).';
              }
          }';
        }

      } // End Single Image.



      // Divider Element.
      else if( false !== strpos($shortcode[0],'[divider ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $style = ( isset($atts['line_type']) && !empty($atts['line_type']) ) ? $atts['line_type'] : 'No Line';

        foreach( $devices as $device => $media_query ) {

          // Height.
          if( isset($atts['custom_height_'.$device]) && strlen($atts['custom_height_'.$device]) > 0 ) {

            if( 'No Line' === $style ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .divider-wrap.height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['custom_height_'.$device]) ) .' > .divider {
                height: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
              } }';
            } else {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .divider-wrap.height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['custom_height_'.$device]) ) .' > div {
                margin-top: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
                margin-bottom: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
              } }';
            }


          }

        } // End foreach device loop.

      } // End Divider.


      // Fancy Box Element.
      else if( false !== strpos($shortcode[0],'[fancy_box ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Min Height.
        foreach( $devices as $device => $media_query ) {

          // Height.
          if( isset($atts['min_height_'.$device]) && strlen($atts['min_height_'.$device]) > 0 ) {


            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              .nectar-fancy-box:not([data-style="parallax_hover"]):not([data-style="hover_desc"]).min_height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_height_'.$device]) ) .' .inner,
              .nectar-fancy-box[data-style="parallax_hover"].min_height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_height_'.$device]) ) .' .meta-wrap,
              .nectar-fancy-box[data-style="hover_desc"].min_height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_height_'.$device]) ) .' {
                 min-height: '.esc_attr( self::percent_unit_type($atts['min_height_'.$device]) ). esc_attr( $override ).';
              }
          }';


          }

        } // End foreach device loop.


        // Parallax hover styles.
        if( isset($atts['parallax_hover_box_overlay']) && !empty($atts['parallax_hover_box_overlay']) ) {

          $color = ltrim($atts['parallax_hover_box_overlay'],'#');

          self::$element_css[] = '.nectar-fancy-box[data-style="parallax_hover"].overlay_'.esc_attr($color).' .bg-img:after {
            background-color: #'.esc_attr($color).';
          }';

        }

        // Description on hover styles.
        if( isset($atts['hover_color_custom']) && !empty($atts['hover_color_custom']) ) {

          $color = ltrim($atts['hover_color_custom'],'#');

          self::$element_css[] = '.nectar-fancy-box[data-style="hover_desc"][data-color].hover_color_'.esc_attr($color).':before {
            background: linear-gradient(to bottom, rgba(0,0,0,0), #'.esc_attr($color).' 100%);
          }';

        }

        // Default and Color box hover.
        if( isset($atts['color_custom']) && !empty($atts['color_custom']) ) {

          $color = ltrim($atts['color_custom'],'#');

          self::$element_css[] = '.nectar-fancy-box[data-style="default"].box_color_'.esc_attr($color).':after {
            background-color: #'.esc_attr($color).esc_attr( $override ).';
          }';

          self::$element_css[] = '.nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).':hover:before {
             box-shadow: 0 30px 90px #'.esc_attr($color).';
          }
          .nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).':not(:hover) i.icon-default-style {
             color: #'.esc_attr($color).esc_attr( $override ).';
          }
          .nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).' .box-bg:after {
             background-color: #'.esc_attr($color).esc_attr( $override ).';
          }';

        }

        // Image Above Text Underline.
        if( isset($atts['box_style']) && 'image_above_text_underline' === $atts['box_style'] ) {

          // Image Ratio.
          $ratio = ( isset($atts['image_aspect_ratio']) ) ? esc_attr($atts['image_aspect_ratio']) : '1-1';

          self::$element_css[] = '.nectar-fancy-box[data-style="image_above_text_underline"].aspect-'.$ratio.' .box-bg {
            padding-bottom: '.esc_attr(self::image_aspect_ratio($ratio)).';
          }';

          // Content Color.
           if( isset($atts['content_color']) && !empty($atts['content_color']) ) {

             $color = ltrim($atts['content_color'],'#');

             self::$element_css[] = '.nectar-fancy-box[data-style="image_above_text_underline"].content-color-'.esc_attr($color).' * {
               color: #'.esc_attr($color).';
             }
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h2,
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h3,
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h4,
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h5 {
               background-image: linear-gradient(to right, #'.esc_attr($color).' 0%,  #'.esc_attr($color).' 100%);
             }';

           }

        }



      } // End Fancy Box


      // Cascading Images.
      else if( false !== strpos($shortcode[0],'[nectar_cascading_images') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Focred Aspect Sizing.
        $ratio = ( isset($atts['element_sizing_aspect']) ) ? esc_attr($atts['element_sizing_aspect']) : '1-1';

        if( isset( $atts['element_sizing'] ) && 'forced' === $atts['element_sizing'] ) {
          self::$element_css[] = '.nectar_cascading_images.forced-aspect.aspect-'.$ratio.' .cascading-image:first-child .bg-layer{
            padding-bottom: '.esc_attr(self::image_aspect_ratio($ratio)).';
          }';
        }

      } // End Cascading Images.


      // Icon List.
      else if( false !== strpos($shortcode[0],'[nectar_icon_list ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Coloring.
        if( isset($atts['color']) && !empty($atts['color']) ) {

          $color = self::locate_color(strtolower($atts['color']));

          // Gradient.
          if( $color && isset($color['gradient']) ) {

            self::$element_css[] = '.nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"] .list-icon-holder[data-icon_type="numerical"] span {
        			 color: '.esc_attr($color['gradient']['to']).';
        			 background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
        			 -webkit-background-clip: text;
        			 -webkit-text-fill-color: transparent;
        			 background-clip: text;
        			 text-fill-color: transparent;
        			 display: inline-block;
        		}';

          }
          // Regular.
          else if( $color && isset($color['color']) ) {

            self::$element_css[] = '.nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"][data-icon-style="border"] .content h4,
            .nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"][data-icon-style="border"] .list-icon-holder[data-icon_type="numerical"] span,
            .nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"] .nectar-icon-list-item .list-icon-holder[data-icon_type="numerical"] {
              color: '.esc_attr($color['color']).';
            }';

          }

        }

      }

      // Gradient Text.
      else if( false !== strpos($shortcode[0],'[nectar_gradient_text ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Coloring.
        if( isset($atts['color']) && !empty($atts['color']) ) {

          $color = self::locate_color(strtolower($atts['color']));

          // Gradient.
          if( $color && isset($color['gradient']) ) {

            // Diagonal.
            if( isset( $atts['gradient_direction'] ) && 'diagonal' === $atts['gradient_direction'] ) {

              self::$element_css[] = '.nectar-gradient-text[data-color="'.esc_attr($color['name']).'"][data-direction="diagonal"] * {
                color: '.esc_attr($color['gradient']['from']).';
                background-image: linear-gradient(to right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-fill-color: transparent;
                display: inline-block;
              }';

            }
            // Regular.
            else {

              self::$element_css[] = '.nectar-gradient-text[data-color="'.esc_attr($color['name']).'"][data-direction="horizontal"] * {
                color: '.esc_attr($color['gradient']['from']).';
                background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-fill-color: transparent;
                display: inline-block;
              }';

            } // end direction.

          } // gradient exists.

        }

      }


      // Tabbed.
      else if( false !== strpos($shortcode[0],'[tabbed_section ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);
        
        // v13 update assist - for cached stylesheets, ensure first tab still displays
        self::$element_css[] = 'body .row .tabbed >div:first-of-type {
            display: block;
            opacity: 1;
            visibility: visible;
            position: relative;
            left: 0;
        }';
        
        // Coloring.
        $tab_color = (isset($atts['tab_color'])) ? $atts['tab_color'] : 'accent-color';

        if( $tab_color ) {

          $color = self::locate_color(strtolower($tab_color));

          // Gradient.
          if( $color && isset($color['gradient']) ) {

            // Default.
            if( isset($atts['style']) && 'default' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style*="default"][data-color-scheme="'.esc_attr($color['name']).'"] ul li a:before {
                background: '.esc_attr($color['gradient']['to']).';
         		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';

            }

            // Material.
            if( isset($atts['style']) && 'material' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li a:before {
                background: '.esc_attr($color['gradient']['from']).';
         		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul:after {
          			background-color: '.esc_attr($color['gradient']['from']).';
          		}

          		.tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li .active-tab:after {
          			box-shadow: 0px 18px 50px '.esc_attr($color['gradient']['from']).';
          		}';

            }
            // Minimal Alt.
            else if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal_alt"] .magic-line {
                background: '.esc_attr($color['gradient']['from']).';
          		  background: linear-gradient(to right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';
            }

            // Minimal Flexible.
            else if( isset($atts['style']) && 'minimal_flexible' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style="minimal_flexible"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav > li a:before {
                box-shadow: 0px 8px 22px '. esc_attr($color['gradient']['from']) .';
              }';
            }

            // Vertical Sticky.
            else if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] ) {

              self::$element_css[] = '.nectar-scrolling-tabs[data-color-scheme="'.esc_attr($color['name']).'"] .scrolling-tab-nav .line {
                background: '.esc_attr($color['gradient']['from']).';
                background: linear-gradient(to bottom, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';

            }

            // All Vertical.
            if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] ||
                     isset($atts['style']) && 'vertical_modern' === $atts['style'] ||
                     isset($atts['style']) && 'vertical' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style*="vertical"][data-color-scheme="'.esc_attr($color['name']).'"] ul li a:before {
                  background: '.esc_attr($color['gradient']['from']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
            }

            // All Minimal.
            if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ||
                isset($atts['style']) && 'minimal_flexible' === $atts['style'] ||
                isset($atts['style']) && 'minimal' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style*="minimal"][data-color-scheme="'.esc_attr($color['name']).'"] > ul li a:after {
                  background: '.esc_attr($color['gradient']['from']).';
                  background: linear-gradient(to right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
            }

          }

          // Regular.
          else if( $color && isset($color['color']) ) {

            // Default.
            if( isset($atts['style']) && 'default' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="default"] li:not(.cta-button) .active-tab {
                background-color: '.esc_attr($color['color']).';
                border-color: '.esc_attr($color['color']).';
              }';
            }

            // Minimal.
            else if( isset($atts['style']) && 'minimal' === $atts['style'] ) {

              self::$element_css[] = 'body.material .tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal"]:not(.using-icons) >ul li:not(.cta-button) a:hover,
              body.material .tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal"]:not(.using-icons) >ul li:not(.cta-button) .active-tab {
                color: '.esc_attr($color['color']).';
              }';
            }

            // Minimal Alt.
            else if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal_alt"] .magic-line {
                background-color: '.esc_attr($color['color']).';
              }';
            }

            // Minimal Flexible.
            else if( isset($atts['style']) && 'minimal_flexible' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style="minimal_flexible"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav > li a:before {
                box-shadow: 0px 8px 22px '. esc_attr($color['color']) .';
              }';
            }

            // Vertical Modern.
            else if( isset($atts['style']) && 'vertical_modern' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style="vertical_modern"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav li .active-tab {
                  background-color: '.esc_attr($color['color']).';
                }';
            }

            // Vertical Sticky.
            else if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] ) {

              self::$element_css[] = '.nectar-scrolling-tabs[data-color-scheme="'.esc_attr($color['name']).'"] .scrolling-tab-nav .line,
              #ajax-content-wrap [data-stored-style="vs"] .tabbed[data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav li a:before {
                background-color: '.esc_attr($color['color']).';
              }';

            }

            // Material.
            if( isset($atts['style']) && 'material' === $atts['style'] ) {

              self::$element_css[] = 'body .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav li a:not(.active-tab):hover {
                color: '.esc_attr($color['color']).';
              }
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul:after,
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li .active-tab {
                background-color: '.esc_attr($color['color']).';
              }
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li .active-tab:after {
              	box-shadow: 0px 18px 50px  '.esc_attr($color['color']).';
              }';

            }


            // All Minimal.
            if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ||
                isset($atts['style']) && 'minimal_flexible' === $atts['style'] ||
                isset($atts['style']) && 'minimal' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style*="minimal"][data-color-scheme="'.esc_attr($color['name']).'"] > ul li a:after {
                  background-color: '.esc_attr($color['color']).';
                }';
            }


          }

        } // Tab color isset.

      }

      // Image With Hotspots.
      else if( false !== strpos($shortcode[0],'[nectar_image_with_hotspots ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['color_1']) && !empty($atts['color_1']) ) {

          $color = self::locate_color(strtolower($atts['color_1']));
          if( $color ) {

            self::$element_css[] = '.nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nectar_hotspot_wrap .nttip .tipclose {
              border-color: '.esc_attr($color['color']).';
            }
            .nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nectar_hotspot,
            .nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nttip .tipclose span:before,
            .nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nttip .tipclose span:after {
              background-color: '.esc_attr($color['color']).';
            }';

          }// color in use.

        }

      }

      // Carousel.
      else if ( false !== strpos($shortcode[0],'[carousel ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Flickity.
        if( isset($atts['script']) && 'flickity' === $atts['script'] ) {

          // Top/bottom margin.
          if( isset($atts['flickity_element_spacing']) && 'default' !== $atts['flickity_element_spacing'] ) {
            self::$element_css[] = '.nectar-flickity.nectar-carousel.nectar-carousel:not(.masonry).tb-spacing-'.esc_attr($atts['flickity_element_spacing']).' .flickity-viewport {
              margin-top: '.esc_attr($atts['flickity_element_spacing']).';
              margin-bottom: '.esc_attr($atts['flickity_element_spacing']).';
            }';
            if( in_array( $atts['flickity_element_spacing'], array('0','10px','20px','30px','40px','50px') ) ) {
              self::$element_css[] = '@media only screen and (max-width: 1000px) {
                .nectar-flickity.nectar-carousel:not(.masonry).tb-spacing-'.esc_attr($atts['flickity_element_spacing']).' .flickity-page-dots {
                  bottom: -50px;
                }
              }';
            }
          }

          // Spacing.
          if( isset($atts['flickity_spacing']) && in_array($atts['flickity_spacing'], array('5px','10px','15px','20px','30px')) ) {

            $item_spacing        = $atts['flickity_spacing'];

            $desktop_cols        = ( isset($atts['desktop_cols_flickity']) ) ? $atts['desktop_cols_flickity'] : '3';
            $desktop_cols_mod    = intval($desktop_cols) - 1;

            $sm_desktop_cols     = ( isset($atts['desktop_small_cols_flickity']) ) ? $atts['desktop_small_cols_flickity'] : '3';
            $sm_desktop_cols_mod = intval($sm_desktop_cols) - 1;

            $mobile_cols         = ( isset($atts['tablet_cols_flickity']) ) ? $atts['tablet_cols_flickity'] : '2';
            $mobile_cols_mod     = intval($mobile_cols) - 1;

            // Basic calc for space between = ((colNum - 1) x spacing) x 2

            // Desktop.
            if( in_array($desktop_cols, array('2','3','4','5','6')) ) {
              self::$element_css[] = '
              @media only screen and (min-width:1300px) {
                .nectar-flickity.nectar-carousel[data-desktop-columns="'.esc_attr($desktop_cols).'"][data-spacing="'.esc_attr($item_spacing).'"][data-format="default"] .cell {
                  width: calc((100% - '. ( intval($desktop_cols_mod) * intval($item_spacing) * 2 ) .'px) / '.esc_attr($desktop_cols).');
                }
              }';
            }

            // SM Desktop.
            if( in_array($sm_desktop_cols, array('2','3','4','5','6')) ) {
              self::$element_css[] = '
              @media only screen and (min-width:1000px) and (max-width:1299px) {
                .nectar-flickity.nectar-carousel[data-small-desktop-columns="'.esc_attr($sm_desktop_cols).'"][data-spacing="'.esc_attr($item_spacing).'"][data-format="default"] .cell {
                  width: calc((100% - '. ( intval($sm_desktop_cols_mod) * intval($item_spacing) * 2 ) .'px) / '.esc_attr($sm_desktop_cols).');
                }
              }';
            }

            // Mobile.
            if( in_array($mobile_cols, array('2','3')) ) {
              self::$element_css[] = '
              @media only screen and (max-width:999px) and (min-width:690px) {
                .nectar-flickity.nectar-carousel[data-tablet-columns="'.esc_attr($mobile_cols).'"][data-spacing="'.esc_attr($item_spacing).'"][data-format="default"] .cell {
                  width: calc((100% - '. ( intval($mobile_cols_mod) * intval($item_spacing) * 2 ) .'px) / '.esc_attr($mobile_cols).');
                }
              }';
            }

          }

        }

        // Simple Slider.
        if( isset($atts['script']) && 'simple_slider' === $atts['script']  ) {

          // Arrows
          if( isset($atts['simple_slider_arrow_positioning']) && 'overlapping' === $atts['simple_slider_arrow_positioning'] ) {
            self::$element_css[] = '
            .nectar-simple-slider.arrow-position-overlapping .flickity-prev-next-button.previous {
              left: -21px;
              width: 42px;
              height: 42px;
            }
            .nectar-simple-slider.arrow-position-overlapping .flickity-prev-next-button.next {
              right: -21px;
              width: 42px;
              height: 42px;
            }';

            if( isset($atts['simple_slider_arrow_button_border_color']) && !empty($atts['simple_slider_arrow_button_border_color']) ) {
              $color = ltrim($atts['simple_slider_arrow_button_border_color'],'#');
              self::$element_css[] = '.nectar-simple-slider.arrow-position-overlapping.arrow-btn-border-'.esc_attr($color).' .flickity-prev-next-button:before {
                box-shadow: 0px 0px 0px 6px #'.esc_attr($color).';
              }';
            }

          }

          //// Arrow Button Coloring.
          if( isset($atts['simple_slider_arrow_button_color']) && !empty($atts['simple_slider_arrow_button_color']) ) {
            $color = ltrim($atts['simple_slider_arrow_button_color'],'#');
            self::$element_css[] = '.nectar-simple-slider.arrow-btn-'.esc_attr($color).' .flickity-prev-next-button:before {
              background-color: #'.esc_attr($color).';
            }';
          }
          if( isset($atts['simple_slider_arrow_color']) && !empty($atts['simple_slider_arrow_color']) ) {
            $color = ltrim($atts['simple_slider_arrow_color'],'#');
            self::$element_css[] = '.nectar-simple-slider.arrow-'.esc_attr($color).' .flickity-prev-next-button .arrow {
              fill: #'.esc_attr($color).';
            }
            .nectar-simple-slider.arrow-'.esc_attr($color).' .flickity-prev-next-button:after {
              background-color: #'.esc_attr($color).';
            }';
          }

          // Pagination Coloring.
          if( isset($atts['simple_slider_pagination_coloring']) && 'default' !== $atts['simple_slider_pagination_coloring'] ) {

            if( 'light' === $atts['simple_slider_pagination_coloring'] ) {
              self::$element_css[] = '.nectar-simple-slider.pagination-color-light .flickity-page-dots .dot {
                mix-blend-mode: initial;
              }';
            }
            else {
              self::$element_css[] = '.nectar-simple-slider.pagination-color-dark .flickity-page-dots .dot {
                mix-blend-mode: initial;
              }
              .nectar-simple-slider.pagination-color-dark .flickity-page-dots .dot:before {
                background-color: #000;
              }
              .nectar-simple-slider.pagination-color-dark .flickity-page-dots svg circle.time {
                stroke: #000;
              }';
            }


     			}



          // Sizing.
          $sizing_type = 'aspect_ratio';

          if( isset($atts['simple_slider_sizing']) && 'percentage' === $atts['simple_slider_sizing'] ) {
            $sizing_type = 'percentage';
          }

          // Aspect Ratios.
          if( 'aspect_ratio' === $sizing_type ) {

            $ratio = ( isset($atts['simple_slider_aspect_ratio']) ) ? esc_attr($atts['simple_slider_aspect_ratio']) : '1-1';

            self::$element_css[] = '.nectar-simple-slider.sizing-aspect-ratio.aspect-'.$ratio.' {
              padding-bottom: '.self::image_aspect_ratio($ratio).';
            }';

          }

          // Percentage.
          else {

            $height_percent = ( isset($atts['simple_slider_height']) ) ? esc_attr($atts['simple_slider_height']) : '50vh';

            self::$element_css[] = '.nectar-simple-slider.sizing-percentage.height-'.$height_percent.' {
              height: '.$height_percent.';
            }';
          }

          // Min Height.
          if( isset($atts['simple_slider_min_height']) && !empty($atts['simple_slider_min_height']) ) {
            self::$element_css[] = '.nectar-simple-slider.min-height-'.esc_attr( self::percent_unit_type_class($atts['simple_slider_min_height']) ).' {
              min-height: '.esc_attr( self::percent_unit_type($atts['simple_slider_min_height']) ).';
            }';
          }


        }

      }

      // Caorusel Item - Simple Slider.
      else if ( false !== strpos($shortcode[0],'[item ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Font Color.
        if( isset($atts['simple_slider_font_color']) && !empty($atts['simple_slider_font_color']) ) {
          $color = ltrim($atts['simple_slider_font_color'],'#');
          self::$element_css[] = '.nectar-simple-slider .cell.text-color-'.esc_attr($color).' .inner {
             color: #'.esc_attr($color).';
          }';
        }

        // BG Pos.
        if( isset($atts['simple_slider_bg_image_position']) &&
            !empty($atts['simple_slider_bg_image_position']) &&
            'default' !== $atts['simple_slider_bg_image_position'] ) {

          self::$element_css[] = '.nectar-simple-slider .cell.bg-pos-'.esc_attr($atts['simple_slider_bg_image_position']).' > .bg-layer-wrap .bg-layer {
             background-position: '.esc_attr(str_replace('-',' ', $atts['simple_slider_bg_image_position'])).';
          }';
        }

        // BG Coloring.
        $simple_gradient = ( isset($atts['simple_slider_enable_gradient']) && 'true' === $atts['simple_slider_enable_gradient']) ? true : false;

        $simple_color_overlay_1       = ( isset($atts['simple_slider_color_overlay']) && !empty($atts['simple_slider_color_overlay']) ) ? $atts['simple_slider_color_overlay'] : 'transparent';
        $simple_color_overlay_1_class = str_replace('#','', $simple_color_overlay_1);
        $simple_color_overlay_1_class = str_replace('(', '\(', $simple_color_overlay_1_class);
        $simple_color_overlay_1_class = str_replace(')', '\)', $simple_color_overlay_1_class);
        $simple_color_overlay_1_class = str_replace('.', '\.', $simple_color_overlay_1_class);
        $simple_color_overlay_1_class = str_replace(',', '\,', $simple_color_overlay_1_class);

        $simple_color_overlay_2       = ( isset($atts['simple_slider_color_overlay_2']) && !empty($atts['simple_slider_color_overlay_2']) ) ? $atts['simple_slider_color_overlay_2'] : 'transparent';
        $simple_color_overlay_2_class = str_replace('#','',$simple_color_overlay_2);
        $simple_color_overlay_2_class = str_replace('(', '\(', $simple_color_overlay_2_class);
        $simple_color_overlay_2_class = str_replace(')', '\)', $simple_color_overlay_2_class);
        $simple_color_overlay_2_class = str_replace('.', '\.', $simple_color_overlay_2_class);
        $simple_color_overlay_2_class = str_replace(',', '\,', $simple_color_overlay_2_class);

        // Gradient overlays.
        if( true === $simple_gradient ) {

          $color_overlay_selectors = '';
          if( 'transparent' !== $simple_color_overlay_1_class ) {
            $color_overlay_selectors .= '.color-overlay-1-' . $simple_color_overlay_1_class;
          }
          if( 'transparent' !== $simple_color_overlay_2_class ) {
            $color_overlay_selectors .= '.color-overlay-2-' . $simple_color_overlay_2_class;
          }

          self::$element_css[] = '.nectar-simple-slider .cell.color-overlay-gradient'.esc_attr($color_overlay_selectors).' > .bg-layer-wrap > .color-overlay {
             background: linear-gradient(90deg, '.esc_attr($simple_color_overlay_1).', '.esc_attr($simple_color_overlay_2).');
          }';

        }
        // Regular color overlay.
        else {
          self::$element_css[] = '.nectar-simple-slider .cell.color-overlay-1-'.esc_attr($simple_color_overlay_1_class).' > .bg-layer-wrap > .color-overlay {
             background-color: '.esc_attr($simple_color_overlay_1).';
          }';
        }


      }

      // Interactive Map.
      else if( false !== strpos($shortcode[0],'[nectar_gmap ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // GMAP.
        if( isset($atts['map_type']) && 'google' === $atts['map_type'] || !isset($atts['map_type']) ) {

          // Coloring.
          if( isset($atts['nectar_marker_color']) && !empty($atts['nectar_marker_color']) ) {

            $color = self::locate_color(strtolower($atts['nectar_marker_color']));
            if( $color ) {

              // Animated Signal.
              if( isset($atts['marker_style']) && 'nectar' === $atts['marker_style'] ) {
                self::$element_css[] = '.nectar-google-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot .middle-dot,
                .nectar-google-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot div[class*="signal"] {
                  background-color: '.esc_attr($color['color']).';
                }';
              }

            } // color in use.

          }

        }
        // Leaflet.
        else if( isset($atts['map_type']) && 'leaflet' === $atts['map_type'] ) {

          // Coloring.
          if( isset($atts['color']) && !empty($atts['color']) ) {

            $color = self::locate_color(strtolower($atts['color']));
            if( $color ) {

              // Leaflet Pin.
              if( isset($atts['marker_style']) && 'default' === $atts['marker_style'] ) {
                self::$element_css[] = '.nectar-leaflet-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .nectar-leaflet-pin {
                  border: 10px solid '.esc_attr($color['color']).';
                }';
              }
              // Animated Signal.
              else if( isset($atts['marker_style']) && 'nectar' === $atts['marker_style'] ) {
                self::$element_css[] = '.nectar-leaflet-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot .middle-dot,
                .nectar-leaflet-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot div[class*="signal"] {
                  background-color: '.esc_attr($color['color']).';
                }';

              }

            } // color in use.

          }

        }

      }


      // Self Hosted Video Player.
      else if( false !== strpos($shortcode[0],'[nectar_video_player_self_hosted ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Border Radius.
        if( isset($atts['border_radius']) && !empty($atts['border_radius']) ) {
          self::$element_css[] = '
          .nectar_video_player_self_hosted[data-border-radius="'.esc_attr($atts['border_radius']).'"] .wpb_video_wrapper {
            border-radius: '.esc_attr($atts['border_radius']).';
          }';
        }

      }


      // Pricing Column.
      else if( false !== strpos($shortcode[0],'[pricing_column ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);
        $pricing_el_color = (isset($atts['color'])) ? $atts['color'] : 'accent-color';

        // Coloring.
        if( !empty($pricing_el_color) ) {

          $color = self::locate_color(strtolower($pricing_el_color));
          if( $color ) {

            self::$element_css[] = '.pricing-table[data-style="flat-alternative"] .pricing-column.highlight.'.esc_attr($color['name']).' h3,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).' h4,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).' .interval {
              color: '.esc_attr($color['color']).';
            }
            .pricing-table[data-style="default"] .pricing-column.highlight.'.esc_attr($color['name']).' h3,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).'.highlight h3 .highlight-reason,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).':before {
              background-color: '.esc_attr($color['color']).';
            }';

          } // color in use.

        }

      }

      // Legacy Shortcode Icon Element.
      else if ( false !== strpos($shortcode[0],'[icon ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $icon_el_color = (isset($atts['color'])) ? $atts['color'] : 'accent-color';

        if( !empty($icon_el_color) ) {

          $color = self::locate_color(strtolower($icon_el_color));

          if( $color && isset($color['color']) ) {

            if( isset($atts['size']) && !empty($atts['size']) ) {

              // 3x Both.
              if( 'large' === $atts['size'] ||
                  'large-2' === $atts['size'] ) {

                  self::$element_css[] = '.icon-3x[class^="icon-"].'.esc_attr($color['name']).',
                  .icon-3x[class*=" icon-"].'.esc_attr($color['name']).' {
                     color: '.esc_attr($color['color']).';
                  }
                  #sidebar .widget:hover [class^="icon-"].icon-3x.'.esc_attr($color['name']).' {
                    background-color: '.esc_attr($color['color']).';
                  }';

              }

              // 3x Alt style.
              if( 'large-2' === $atts['size'] ) {
                self::$element_css[] = '.col:not(.post-area):not(.span_12):not(#sidebar):hover [class^="icon-"].icon-3x.'.esc_attr($color['name']).'.alt-style.hovered,
                body .col:not(.post-area):not(.span_12):not(#sidebar):hover a [class*=" icon-"].icon-3x.'.esc_attr($color['name']).'.alt-style.hovered {
                  color: '.esc_attr($color['color']).'!important;
                }
                body [class^="icon-"].icon-3x.alt-style.'.esc_attr($color['name']).',
                body [class*=" icon-"].icon-3x.alt-style.'.esc_attr($color['name']).' {
                  background-color: '.esc_attr($color['color']).';
                }';
              }
              // 3x.
              else if( 'large' === $atts['size'] ) {

                self::$element_css[] = '
                .icon-3x[class^="icon-"].'.esc_attr($color['name']).':not(.alt-style),
            		.icon-3x[class*=" icon-"].'.esc_attr($color['name']).':not(.alt-style) {
                  color: '.esc_attr($color['color']).';
                }
                .col:hover > [class^="icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered,
                .col:hover > [class*=" icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered,
                .col:not(.post-area):not(.span_12):not(#sidebar):hover [class^="icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered,
                .col:not(.post-area):not(.span_12):not(#sidebar):hover a [class*=" icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered {
                  background-color: '.esc_attr($color['color']).';
                }
                .col:not(.post-area):not(.span_12):not(#sidebar):hover .'.esc_attr($color['name']).'.hovered .circle-border,
                body .col:not(.post-area):not(.span_12):not(#sidebar):hover [class*=" icon-"].icon-3x.hovered.'.esc_attr($color['name']).' .circle-border,
                body #sidebar .widget:hover [class*=" icon-"].icon-3x.'.esc_attr($color['name']).' .circle-border {
                  	border-color: '.esc_attr($color['color']).';
                }';

              }
              else if( 'tiny' === $atts['size']  ) {
                self::$element_css[] = '.icon-tiny[class^="icon-"].'.esc_attr($color['name']) . '{
                  color: '.esc_attr($color['color']).';
                }';
              }


            }

          } // color in use.

        }

      }



      // Iconsmind Elements.
      if( false !== strpos($shortcode[0],'[nectar_flip_box ') ||
         false !== strpos($shortcode[0],'[nectar_btn ' ) ||
         false !== strpos($shortcode[0],'[fancy_box ') ||
         false !== strpos($shortcode[0],'[nectar_icon ') ||
         false !== strpos($shortcode[0],'[nectar_horizontal_list_item ') ||
         false !== strpos($shortcode[0],'[nectar_icon_list_item ') ||
         false !== strpos($shortcode[0],'[tab ') ) {

          $atts = shortcode_parse_atts($shortcode_inner);

          // Iconsmind CSS
          if( isset($atts['icon_family']) &&
             'iconsmind' === $atts['icon_family'] &&
             isset($atts['icon_iconsmind']) &&
             !empty($atts['icon_iconsmind']) &&
             function_exists('nectar_iconsmind_icon_list') ) {

            $iconsmind_icons = nectar_iconsmind_icon_list();
            $selected_iconsmind = $atts['icon_iconsmind'];

            if( isset($iconsmind_icons[$selected_iconsmind]) ) {
              self::$element_css[] = '.'.esc_attr($atts['icon_iconsmind']).':before{content:"'.esc_attr($iconsmind_icons[$selected_iconsmind]).'"}';
            }

          }

      }


  }



  /**
  * Prepares font sizing
  */
  public static function font_sizing_format($str) {

    if( strpos($str,'vw') !== false ||
      strpos($str,'vh') !== false ||
      strpos($str,'em') !== false ||
      strpos($str,'rem') !== false ) {
      return $str;
    } else {
      return intval($str) . 'px';
    }

  }


  /**
  * Verifies custom coloring is in use.
  */
  public static function custom_color_bool($param, $atts) {

    if(isset($atts[$param.'_type']) &&
			!empty($atts[$param.'_type']) &&
			'custom' === $atts[$param.'_type'] &&
			isset($atts[$param.'_custom']) &&
			!empty($atts[$param.'_custom']) ) {
			return true;
		}
		return false;

  }


  /**
  * Determines the unit type px or percent
  */
  public static function percent_unit_type($str) {

    if( false !== strpos($str,'%') ) {
      return intval($str) . '%';
    } else if( false !== strpos($str,'vh') ) {
      return intval($str) . 'vh';
    } else if( false !== strpos($str,'vw') ) {
      return intval($str) . 'vw';
    } else if( 'auto' === $str ) {
			return 'auto';
		}

    return intval($str) . 'px';

  }

  /**
  * Determines the unit type classname px or percent
  */
  public static function percent_unit_type_class($str) {

    if( false !== strpos($str,'%') ) {
      return str_replace('%','pct', $str);
    } else if( false !== strpos($str,'vh') ) {
      return intval($str) . 'vh';
    } else if( false !== strpos($str,'vw') ) {
      return intval($str) . 'vw';
    } else if( 'auto' === $str ) {
			return 'auto';
		}

    return intval($str) . 'px';
  }

  /**
  * Generates the padding for a specific aspect ratio.
  */
  public static function image_aspect_ratio($aspect) {

    if( '4-3' === $aspect )  {
      return 'calc((3 / 4) * 100%)';
    }
    else if( '3-2' === $aspect )  {
      return 'calc((2 / 3) * 100%)';
    }
    else if( '16-9' === $aspect )  {
      return 'calc((9 / 16) * 100%)';
    }
    else if( '2-1' === $aspect )  {
      return 'calc((1 / 2) * 100%)';
    }
    else if( '4-5' === $aspect )  {
      return 'calc((5 / 4) * 100%)';
    }
    else { //1-1 default
      return 'calc((1 / 1) * 100%)';
    }
  }



}

$nectar_el_dynamic_styles = new NectarElDynamicStyles();







/**
* Determines the unit type classname px or percent
*
* @since 11.1
*/
if( !function_exists('nectar_el_custom_color_bool') ) {

	function nectar_el_custom_color_bool($param, $atts) {

		if(isset($atts[$param.'_type']) &&
			!empty($atts[$param.'_type']) &&
			'custom' === $atts[$param.'_type'] &&
			isset($atts[$param.'_custom']) &&
			!empty($atts[$param.'_custom']) ) {
			return true;
		}
		return false;

	}

}


/**
* Determines the unit type classname px or percent
*
* @since 11.1
*/
if( !function_exists('nectar_el_padding_unit_type_class') ) {

	function nectar_el_percent_unit_type_class($str) {

		if( false !== strpos($str,'%') ) {
			return str_replace('%','pct', $str);
		} else if( false !== strpos($str,'vw') ) {
			return intval($str) . 'vw';
		} else if( false !== strpos($str,'vh') ) {
			return intval($str) . 'vh';
		} else if( 'auto' === $str ) {
			return 'auto';
		}

		return intval($str) . 'px';
	}

 }

/**
* Nectar Dynamic Class Names.
*
* Generates dynamic classnames for dynamic page builder element styles.
*
* @see NectarElDynamicStyles
* @since 11.1
*/
 if( !function_exists('nectar_el_dynamic_classnames') ) {

	 function nectar_el_dynamic_classnames( $el, $atts ) {

		 $classnames = '';

		 if( 'row' === $el || 'inner_row' === $el ) {

			 $row_params = array('top_padding','bottom_padding','translate_x','translate_y','right_padding','left_padding');

			 // inner row specifc.
			 if( 'inner_row' === $el ) {
				 if( isset($atts['min_width_desktop']) && strlen($atts['min_width_desktop']) > 0 ) {
				   $classnames .= 'min_width_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['min_width_desktop'])) . ' ';
			   }
				 $row_params[] = 'min_width';
		   }

       // row specific.
       if( 'row' === $el ) {
         if( isset($atts['shape_divider_height_tablet']) && strlen($atts['shape_divider_height_tablet']) > 0 ) {
				   $classnames .= 'shape_divider_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts['shape_divider_height_tablet'])) . ' ';
			   }
         if( isset($atts['shape_divider_height_phone']) && strlen($atts['shape_divider_height_phone']) > 0 ) {
				   $classnames .= 'shape_divider_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts['shape_divider_height_phone'])) . ' ';
			   }
       }

			 // desktop specific.
			if( isset($atts['right_padding_desktop']) && strlen($atts['right_padding_desktop']) > 0 ) {
				$classnames .= 'right_padding_'. nectar_el_percent_unit_type_class(esc_attr($atts['right_padding_desktop'])) . ' ';
			}
			if( isset($atts['left_padding_desktop']) && strlen($atts['left_padding_desktop']) > 0 ) {
				$classnames .= 'left_padding_'. nectar_el_percent_unit_type_class(esc_attr($atts['left_padding_desktop'])) . ' ';
			}

			// column dir.
			if( isset($atts['column_direction']) && 'reverse' === $atts['column_direction'] ) {
				$classnames .= 'reverse_columns_desktop ';
			}
			if( isset($atts['column_direction_tablet']) && 'row_reverse' === $atts['column_direction_tablet'] ) {
				$classnames .= 'reverse_columns_row_tablet ';
			} else if( isset($atts['column_direction_tablet']) && 'column_reverse' === $atts['column_direction_tablet'] ) {
				$classnames .= 'reverse_columns_column_tablet ';
			}

			if( isset($atts['column_direction_phone']) && 'row_reverse' === $atts['column_direction_phone'] ) {
				$classnames .= 'reverse_columns_row_phone ';
			} else if( isset($atts['column_direction_phone']) && 'column_reverse' === $atts['column_direction_phone'] ) {
				$classnames .= 'reverse_columns_column_phone ';
			}

			 // loop.
			 foreach( $row_params as $param ) {

				 if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
					 $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
				 }
				 if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
					 $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
				 }

			 }


		 } else if ( 'column' === $el || 'inner_column' === $el ) {

			 $column_params = array('top_margin','bottom_margin','right_margin','left_margin','column_padding');

			 // parent specifc.
			 if( 'column' === $el ) {
				 if( isset($atts['max_width_desktop']) && strlen($atts['max_width_desktop']) > 0 ) {
				   $classnames .= 'max_width_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['max_width_desktop'])) . ' ';
			   }
				 $column_params[] = 'max_width';
		   }

			 // desktop specific.
			 if( isset($atts['right_margin']) && strlen($atts['right_margin']) > 0 ) {
				 $classnames .= 'right_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['right_margin'])) . ' ';
			 }
			 if( isset($atts['left_margin']) && strlen($atts['left_margin']) > 0 ) {
				 $classnames .= 'left_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['left_margin'])) . ' ';
			 }
       if( isset($atts['column_element_spacing']) && 'default' !== $atts['column_element_spacing'] ) {
         $classnames .= 'el_spacing_'. esc_attr($atts['column_element_spacing']) . ' ';
       }

			 // loop.
			 foreach( $column_params as $param ) {

				 if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {

					 if('column_padding' === $param) {
						 $classnames .= esc_attr($atts[$param.'_tablet']) . '_tablet ';
					 } else {
						 $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
					 }

				 }
				 if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {

					 if('column_padding' === $param) {
						$classnames .= esc_attr($atts[$param.'_phone']) . '_phone ';
					 } else {
						 $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
					 }

				 }

			 } // end column general param loop.

       $column_border_params = array('border_left','border_top','border_right','border_bottom');
       if( isset($atts['border_type']) && 'advanced' === $atts['border_type'] ) {

         // border width.
         foreach( $column_border_params as $param ) {

           if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {
             $classnames .= $param.'_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_desktop'])) . ' ';
           }
           if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
             $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
           }
           if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
             $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
           }
         } // end column border param loop.

         // border color.
         if( isset($atts['column_border_color']) && !empty($atts['column_border_color']) ) {
  				 $border_color = ltrim($atts['column_border_color'],'#');
  				 $classnames .= 'border_color_'. esc_attr($border_color) . ' ';
  			 }

         // border style.
         if( isset($atts['column_border_radius']) && 'none' !== $atts['column_border_radius'] ) {
           $classnames .= 'border_style_solid';
         }
         if( isset($atts['column_border_style']) && !empty($atts['column_border_style']) ) {
  				 $classnames .= 'border_style_'. esc_attr($atts['column_border_style']) . ' ';
  			 }

       } // end using advanced border style.

		 }

		 else if( 'nectar_icon' === $el ) {

			 // Custom color.
			 if( isset($atts['icon_color_custom']) && true === nectar_el_custom_color_bool('icon_color', $atts) ) {
				 $color = ltrim($atts['icon_color_custom'],'#');
				 $classnames .= 'icon_color_custom_'. esc_attr($color) . ' ';
			 }

		 }

		 else if( 'nectar_cta' === $el ) {

			 // Custom color.
			 if( isset($atts['button_color_hover']) && !empty($atts['button_color_hover']) ) {
				 $color = ltrim($atts['button_color_hover'],'#');
				 $classnames .= 'hover_color_'. esc_attr($color) . ' ';
			 }

       // Custom Alignment.
			 if( isset($atts['alignment_tablet']) && strlen($atts['alignment_tablet']) > 0 ) {
				 $classnames .= 'alignment_tablet_'. esc_attr($atts['alignment_tablet']) . ' ';
			 }
       if( isset($atts['alignment_phone']) && strlen($atts['alignment_phone']) > 0 ) {
				 $classnames .= 'alignment_phone_'. esc_attr($atts['alignment_phone']) . ' ';
			 }

		 }

		 else if( 'nectar_highlighted_text' === $el || 'nectar_scrolling_text' === $el ) {

			 // Custom size.
			 if( isset($atts['custom_font_size']) ) {
				 $classnames .= 'font_size_'. esc_attr($atts['custom_font_size']) . ' ';
			 }
			 if( isset($atts['custom_font_size_mobile']) ) {
				 $classnames .= 'font_size_mobile_'. esc_attr($atts['custom_font_size_mobile']) . ' ';
			 }
		 }

     else if( 'nectar_rotating_words_title' === $el ) {

      // Custom font size.
      if( isset($atts['font_size']) ) {
        $classnames .= 'font_size_'. esc_attr($atts['font_size']) . ' ';
      }
      // Custom color.
      if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
        $color = ltrim($atts['text_color'],'#');
        $classnames .= 'color_'. esc_attr($color) . ' ';
      }
      // Animation.
      if( isset($atts['element_animation']) && 'none' !== $atts['element_animation'] ) {
        $classnames .= 'element_'. esc_attr($atts['element_animation']);
      }

    }

		 else if( 'divider' === $el ) {

			 // Custom Height.
			 if( isset($atts['custom_height_tablet']) && strlen($atts['custom_height_tablet']) > 0 ) {
				 $classnames .= 'height_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts['custom_height_tablet'])) . ' ';
			 }
			 if( isset($atts['custom_height_phone']) && strlen($atts['custom_height_phone']) > 0 ) {
				 $classnames .= 'height_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts['custom_height_phone'])) . ' ';
			 }

		 }

		 else if( 'simple_slider' === $el ) {

       // Pagination Coloring.
       if( isset($atts['simple_slider_pagination_coloring']) && 'default' !== $atts['simple_slider_pagination_coloring'] ) {
				$classnames .= 'pagination-color-'.esc_attr($atts['simple_slider_pagination_coloring']).' ';
			 }

			 // Arrows.
			 if( isset($atts['simple_slider_arrow_positioning']) && 'overlapping' === $atts['simple_slider_arrow_positioning'] ) {
				$classnames .= 'arrow-position-overlapping ';
			 }
			 if( isset($atts['simple_slider_arrow_button_color']) && !empty($atts['simple_slider_arrow_button_color']) ) {
				 $color = ltrim($atts['simple_slider_arrow_button_color'],'#');
				 $classnames .= 'arrow-btn-'. esc_attr($color) . ' ';
			 }
			 if( isset($atts['simple_slider_arrow_button_border_color']) && !empty($atts['simple_slider_arrow_button_border_color']) ) {
				 $color = ltrim($atts['simple_slider_arrow_button_border_color'],'#');
				 $classnames .= 'arrow-btn-border-'. esc_attr($color) . ' ';
			 }
			 if( isset($atts['simple_slider_arrow_color']) && !empty($atts['simple_slider_arrow_color']) ) {
				 $color = ltrim($atts['simple_slider_arrow_color'],'#');
				 $classnames .= 'arrow-'. esc_attr($color) . ' ';
			 }

			 // Slider Sizing.
			 $sizing_type = 'aspect_ratio';

			 if( isset($atts['simple_slider_sizing']) && 'percentage' === $atts['simple_slider_sizing'] ) {
				$sizing_type = 'percentage';
			 }

			 if( 'aspect_ratio' === $sizing_type ) {
				 	$aspect = ( isset($atts['simple_slider_aspect_ratio']) ) ? esc_attr($atts['simple_slider_aspect_ratio']) : '1-1';

					$classnames .= 'sizing-aspect-ratio ';
					$classnames .= 'aspect-'. $aspect . ' ';
			 } else {

				 $height_percent = ( isset($atts['simple_slider_height']) ) ? esc_attr($atts['simple_slider_height']) : '50vh';

				 $classnames .= 'sizing-percentage ';
				 $classnames .= 'height-'. $height_percent . ' ';
			 }

       // Minimum Height.
       if( isset($atts['simple_slider_min_height']) && !empty($atts['simple_slider_min_height']) ) {
         $classnames .= 'min-height-'. nectar_el_percent_unit_type_class(esc_attr($atts['simple_slider_min_height'])) . ' ';
       }

		 }

     else if( 'carousel' === $el ) {

       // Flickity.
       if( isset($atts['script']) && 'flickity' === $atts['script'] ) {

         // Top/bottom margin.
         if( isset($atts['flickity_element_spacing']) && 'default' !== $atts['flickity_element_spacing'] ) {
           $classnames .= 'tb-spacing-'. esc_attr($atts['flickity_element_spacing']) . ' ';
         }


			 }

     }

		 else if( 'simple_slider_slide' === $el ) {

			 // Color Overlay.
			 if( isset($atts['simple_slider_enable_gradient']) && 'true' === $atts['simple_slider_enable_gradient'] ) {
				 $classnames .= 'color-overlay-gradient ';
			 }
			 if( isset($atts['simple_slider_color_overlay']) && !empty($atts['simple_slider_color_overlay']) ) {
				 $color = ltrim($atts['simple_slider_color_overlay'],'#');
				 $classnames .= 'color-overlay-1-'. esc_attr($color) . ' ';
			 }
			 if( isset($atts['simple_slider_color_overlay_2']) && !empty($atts['simple_slider_color_overlay_2']) ) {
				 $color = ltrim($atts['simple_slider_color_overlay_2'],'#');
				 $classnames .= 'color-overlay-2-'. esc_attr($color) . ' ';
			 }

			 // Text Color.
			 if( isset($atts['simple_slider_font_color']) && !empty($atts['simple_slider_font_color']) ) {
				 $color = ltrim($atts['simple_slider_font_color'],'#');
				 $classnames .= 'text-color-'. esc_attr($color) . ' ';
			 }

			 // BG Image Pos.
			 if( isset($atts['simple_slider_bg_image_position']) && !empty($atts['simple_slider_bg_image_position']) ) {
				 $classnames .= 'bg-pos-'. esc_attr($atts['simple_slider_bg_image_position']) . ' ';
			 }

		 }


		 else if( 'nectar_post_grid' === $el ) {
			 // Custom font size.
			 if( isset($atts['custom_font_size']) ) {
				 $classnames .= 'font_size_'. esc_attr($atts['custom_font_size']) . ' ';
			 }
			 // Hover color.
			 if( isset($atts['card_bg_color_hover']) ) {
				 $color = ltrim($atts['card_bg_color_hover'],'#');
				 $classnames .= 'card_hover_color_'. esc_attr($color) . ' ';
			 }
       // Text Opacity.
       if( isset($atts['text_opacity']) && !empty($atts['text_opacity']) ) {
         $text_opacity_selector = str_replace('.', '-', $atts['text_opacity']);
         $classnames .= 'text-opacity-'. esc_attr($text_opacity_selector) . ' ';
       }
       if( isset($atts['text_hover_opacity']) && !empty($atts['text_hover_opacity']) ) {
         $text_opacity_selector = str_replace('.', '-', $atts['text_hover_opacity']);
         $classnames .= 'text-opacity-hover-'. esc_attr($text_opacity_selector) . ' ';
       }

		 }

		 else if( 'nectar_fancy_box' === $el ) {

			 // Min height.
			 if( isset($atts['min_height_tablet']) && strlen($atts['min_height_tablet']) > 0 ) {
				 $classnames .= 'min_height_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts['min_height_tablet'])) . ' ';
			 }
			 if( isset($atts['min_height_phone']) && strlen($atts['min_height_phone']) > 0 ) {
				 $classnames .= 'min_height_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts['min_height_phone'])) . ' ';
			 }

       // Image Above Text.
       if( isset($atts['box_style']) && 'image_above_text_underline' === $atts['box_style'] ) {

         // Text Color.
        if( isset($atts['content_color']) && !empty($atts['content_color']) ) {
          $color = ltrim($atts['content_color'],'#');
          $classnames .= 'content-color-'. esc_attr($color) . ' ';
        }

        // Aspect Ratio.
        $aspect = ( isset($atts['image_aspect_ratio']) ) ? esc_attr($atts['image_aspect_ratio']) : '1-1';
        $classnames .= 'aspect-'. $aspect . ' ';

       }

			 // Parallax hover.
			 if( isset($atts['parallax_hover_box_overlay']) && !empty($atts['parallax_hover_box_overlay']) ) {

				 $color = ltrim($atts['parallax_hover_box_overlay'],'#');

				 $classnames .= 'overlay_'. esc_attr($color) . ' ';
			 }

			 // Hover description.
			 if( isset($atts['hover_color_custom']) && !empty($atts['hover_color_custom']) ) {

				 $color = ltrim($atts['hover_color_custom'],'#');

				 $classnames .= 'hover_color_'. esc_attr($color) . ' ';
			 }

			 // Hover description.
			 if( isset($atts['color_custom']) && !empty($atts['color_custom']) ) {

				 $color = ltrim($atts['color_custom'],'#');

				 $classnames .= 'box_color_'. esc_attr($color) . ' ';
			 }

		 }

		 else if ( 'image_with_animation' === $el ) {

			 $image_params = array('margin_top','margin_right','margin_bottom','margin_left');

			 foreach( $image_params as $param ) {

				 if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
					 $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
				 }
				 if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
					 $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
				 }

			 }

       // custom max width.
       if( isset($atts['max_width']) && 'custom' === $atts['max_width'] &&
           isset($atts['max_width_custom']) && !empty( $atts['max_width_custom'] ) ) {

         $classnames .= 'custom-width-'. esc_attr( nectar_el_percent_unit_type_class($atts['max_width_custom']) );
       }


		 }

     else if ( 'nectar_cascading_images' === $el ) {

       // Sizing.
       $aspect = ( isset($atts['element_sizing_aspect']) ) ? esc_attr($atts['element_sizing_aspect']) : '1-1';

       if( isset($atts['element_sizing']) && 'forced' === $atts['element_sizing'] ) {
         $classnames .= 'forced-aspect aspect-'. $aspect . ' ';
       }

       // Overflow.
       if( isset($atts['overflow']) && 'hidden' === $atts['overflow'] ) {
         $classnames .= 'overflow-hidden ';
       }

		 }

		 if( !empty($classnames) ) {
			 return ' ' . $classnames;
		 }
		 return $classnames;

	 }

 }
