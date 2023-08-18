<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'nectar-element-testimonial' );

extract(shortcode_atts(array(
  "autorotate"=> '', 
  "disable_height_animation" => '',
  'style' => 'default', 
  'color' => '', 
  'star_rating_color' => 'accent-color', 
  'add_border' => ''), $atts));

if( ! defined( 'NECTAR_THEME_NAME' ) ) {
  $style = 'default';
}

$height_animation_class = null;

if( $disable_height_animation === 'true' ) { 
  $height_animation_class = 'disable-height-animation'; 
}

$GLOBALS['nectar-testimonial-slider-style'] = $style;

$flickity_markup_opening = ($style == 'multiple_visible' || $style == 'multiple_visible_minimal') ? '<div class="flickity-viewport"> <div class="flickity-slider">' : '';
$flickity_markup_closing = ($style == 'multiple_visible' || $style == 'multiple_visible_minimal') ? '</div></div>' : '';

echo '<div class="col span_12 testimonial_slider '.$height_animation_class.'" data-color="'.esc_attr($color).'"  data-rating-color="'.esc_attr($star_rating_color).'" data-add-border="'.esc_attr($add_border).'" data-autorotate="'.esc_attr($autorotate).'" data-style="'.esc_attr($style).'" ><div class="slides">'.$flickity_markup_opening.do_shortcode($content).$flickity_markup_closing.'</div></div>';

?>