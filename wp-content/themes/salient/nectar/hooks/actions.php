<?php
/**
 * Salient Theme Actions.
 *
 * @package Salient WordPress Theme
 * @subpackage hooks
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

// After body open.
add_action('nectar_hook_after_body_open', 'nectar_skip_to_content_link');

// Before content.
add_action('nectar_hook_before_content', 'nectar_yoast_breadcrumbs');
add_action('nectar_hook_before_content', 'nectar_buddypress_title');
add_action('nectar_hook_before_content', 'nectar_fullpage_markup_open');

// After content.
add_action('nectar_hook_after_content', 'nectar_fullpage_markup_close');


// Before Header Navigation.
add_action('nectar_hook_before_header_nav', 'nectar_material_skin_ocm_wrap_open');
add_action('nectar_hook_before_header_nav', 'nectar_page_trans_markup');


// OCM fullscreen split.
$side_widget_class = ( ! empty( $nectar_options['header-slide-out-widget-area-style'] ) ) ? $nectar_options['header-slide-out-widget-area-style'] : 'slide-out-from-right';
if( 'fullscreen-split' === $side_widget_class ) {
	add_action('nectar_hook_ocm_before_menu', 'nectar_fullscreen_split_ocm_nav_open');
	add_action('nectar_hook_ocm_before_secondary_items', 'nectar_fullscreen_split_ocm_secondary_open');

	add_action('nectar_hook_ocm_after_menu', 'nectar_fullscreen_split_ocm_nav_close');
	add_action('nectar_hook_ocm_after_secondary_items', 'nectar_fullscreen_split_ocm_container_close');
}

// After WP Footer.
add_action('nectar_hook_after_wp_footer', 'nectar_material_skin_ocm_wrap_close');


// After theme outer wrap open.
add_action('nectar_hook_after_outer_wrap_open', 'nectar_fullscreen_blur_wrap_open');


// Before theme outer wrap close.
add_action('nectar_hook_before_outer_wrap_close', 'nectar_fullscreen_blur_wrap_close');


// Header text widget.
add_action('nectar_hook_ocm_before_menu', 'nectar_header_text_widget_content_mobile');

if( isset($nectar_options['header_format']) && 'centered-menu-bottom-bar' === $nectar_options['header_format'] ) {

	if( !isset($nectar_options['centered-menu-bottom-bar-alignment']) ||
	    isset($nectar_options['centered-menu-bottom-bar-alignment']) &&
			'center' === $nectar_options['centered-menu-bottom-bar-alignment'] ) {
		add_action('nectar_hook_pull_left_menu_items', 'nectar_header_text_widget_content');
	} else {
		add_action('nectar_hook_pull_right_menu_items', 'nectar_header_text_widget_content');
	}

} else {
	add_action('nectar_hook_before_button_menu_items', 'nectar_header_text_widget_content');
}


/**
* Adds a skip to content link for accessibility.
*
* @since 13.0
*/
function nectar_skip_to_content_link() {
	echo '<a href="#ajax-content-wrap" class="nectar-skip-to-content">'. esc_html__('Skip to main content','salient') . '</a>';
}


/**
 * Adds header text widget to OCM
 *
 * @since 13.0
 */
function nectar_header_text_widget_content_mobile() {

	$nectar_header_options = nectar_get_header_variables();

	if( !empty($nectar_header_options['header_text_widget']) ) {
		echo '<div class="nectar-header-text-content mobile-only"><div>'.do_shortcode( wp_kses_post($nectar_header_options['header_text_widget']) ).'</div></div>';
	}

}

/**
 * Adds header text widget to main navigation
 *
 * @since 12.5
 */
function nectar_header_text_widget_content() {

	global $nectar_options;
	$nectar_header_options = nectar_get_header_variables();

	if( !empty($nectar_header_options['header_text_widget']) ) {

		if( isset($nectar_options['header_format']) && 'centered-menu-bottom-bar' === $nectar_options['header_format'] ) {
			echo '<div class="nectar-header-text-content"><div>'.do_shortcode( wp_kses_post($nectar_header_options['header_text_widget']) ).'</div></div>';
		}
		else {
			echo '<li class="nectar-header-text-content-wrap"><div class="nectar-header-text-content"><div>'.do_shortcode( wp_kses_post($nectar_header_options['header_text_widget']) ).'</div></div></li>';
		}

	}

}

/**
 * Add off canvas menu fullscreen blur wrap opening markup.
 *
 * @since 10.1
 */
function nectar_fullscreen_blur_wrap_open() {

	$nectar_header_options = nectar_get_header_variables();

	if ( $nectar_header_options['side_widget_area'] === '1' && $nectar_header_options['side_widget_class'] === 'fullscreen' ) {
		echo '<div class="blurred-wrap">';
	}

}

/**
 * Add off canvas menu fullscreen blur wrap closing markup.
 *
 * @since 10.1
 */
function nectar_fullscreen_blur_wrap_close() {

	$nectar_header_options = nectar_get_header_variables();

	if ( $nectar_header_options['side_widget_area'] === '1' && $nectar_header_options['side_widget_class'] === 'fullscreen' ) {
		echo '</div><!--blurred-wrap-->';
	}

}


/**
 * Add off canvas menu fullscreen split wrapping menu div.
 *
 * @since 11.5
 */
function nectar_fullscreen_split_ocm_nav_open() {
	echo '<div class="container normal-container"><div class="left-side">';
}

/**
 * Add off canvas menu fullscreen split wrapping menu div.
 *
 * @since 11.5
 */
function nectar_fullscreen_split_ocm_secondary_open() {
	echo '<div class="right-side"><div class="right-side-inner">';
}

/**
 * Add off canvas menu fullscreen split closing div.
 *
 * @since 11.5
 */
function nectar_fullscreen_split_ocm_nav_close() {
	echo '</div>';
}
/**
 * Add off canvas menu fullscreen split container closing div.
 *
 * @since 11.5
 */
function nectar_fullscreen_split_ocm_container_close() {
	echo '</div></div></div>';
}


/**
 * Add page fullscreen rows wrapping markup.
 *
 * @since 10.1
 */
 function nectar_fullpage_markup_open() {

   if ( is_page() ) {

     if ( is_page_template( 'template-no-footer.php' ) ||
     is_page_template( 'template-no-header.php' ) ||
     is_page_template( 'template-no-header-footer.php' ) ||
     ! is_page_template() ) {

       $nectar_fp_options = nectar_get_full_page_options();

       if ( $nectar_fp_options['page_full_screen_rows'] === 'on' ) {
         echo '<div id="nectar_fullscreen_rows" data-animation="' . esc_attr( $nectar_fp_options['page_full_screen_rows_animation'] ) . '" data-row-bg-animation="' . esc_attr( $nectar_fp_options['page_full_screen_rows_bg_img_animation'] ) . '" data-animation-speed="' . esc_attr( $nectar_fp_options['page_full_screen_rows_animation_speed'] ) . '" data-content-overflow="' . esc_attr( $nectar_fp_options['page_full_screen_rows_content_overflow'] ) . '" data-mobile-disable="' . esc_attr( $nectar_fp_options['page_full_screen_rows_mobile_disable'] ) . '" data-dot-navigation="' . esc_attr( $nectar_fp_options['page_full_screen_rows_dot_navigation'] ) . '" data-footer="' . esc_attr( $nectar_fp_options['page_full_screen_rows_footer'] ) . '" data-anchors="' . esc_attr( $nectar_fp_options['page_full_screen_rows_anchors'] ) . '">';
       }

     }

   }

 }


/**
 * Add page fullscreen rows closing markup.
 *
 * @since 10.1
 */
function nectar_fullpage_markup_close() {

  if ( is_page() ) {

    if ( is_page_template( 'template-no-footer.php' ) ||
    is_page_template( 'template-no-header.php' ) ||
    is_page_template( 'template-no-header-footer.php' ) ||
    ! is_page_template() ) {

      $nectar_fp_options = nectar_get_full_page_options();

      if ( $nectar_fp_options['page_full_screen_rows'] === 'on' ) {
        echo '</div>';
      }

    }

  }

}



/**
 * Add Yoast breadcrumbs before content.
 *
 * @since 10.1
 */
function nectar_yoast_breadcrumbs() {

  if ( function_exists( 'yoast_breadcrumb' ) && ! is_home() && ! is_front_page() ) {
    yoast_breadcrumb( '<p id="breadcrumbs" class="yoast">', '</p>' ); }
}



/**
 * Add buddypress title before content.
 *
 * @since 10.1
 */
function nectar_buddypress_title() {
  global $bp;
  if ( $bp && ! bp_is_blog_page() && ! is_singular( 'post' ) ) {
    echo '<h1>' . get_the_title() . '</h1>';
  }
}



/**
 * Opening markup for material theme skin.
 *
 * @since 10.1
 */
function nectar_material_skin_ocm_wrap_open() {

	$theme_skin = NectarThemeManager::$skin;

	if ( 'material' === $theme_skin ) {
		echo '<div class="ocm-effect-wrap"><div class="ocm-effect-wrap-inner">';
	}

}



/**
 * Closing markup for material theme skin.
 *
 * @since 10.1
 */
function nectar_material_skin_ocm_wrap_close() {
	
	$theme_skin = NectarThemeManager::$skin;

	if ( 'material' === $theme_skin ) {
		echo '</div></div><!--/ocm-effect-wrap-->';
	}

}

?>
