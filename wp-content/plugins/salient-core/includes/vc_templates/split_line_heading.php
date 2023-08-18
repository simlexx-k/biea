<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title = $el_class = $value = $label_value = $units = '';

extract(shortcode_atts(array(
  'animation_type' => 'default',
	'text_content' => '',
	'font_style' => 'h1',
	'animation_delay' => '0',
	'max_width' => '',
  'text_color' => '',
  'font_size' => '',
	'stagger_animation' => '',
	'mobile_disable_animation' => '',
	'content_alignment' => 'default',
	'mobile_content_alignment' => 'inherit'
), $atts));

$array = preg_split("/\r\n|\n|\r/", $content);
$heading_lines = array_filter($array);

$style_markup_escaped = null;
$font_style_markup_escaped = null;
$custom_font_size = 'false';

if(!empty($max_width) || !empty($text_color)) {

  $style_markup_escaped = 'style="';

  if( !empty($max_width) ) {
		
		if( strpos($max_width,'vh') !== false ) {
			$style_markup_escaped .= 'max-width: '. intval($max_width) .'vh;';
		} 
		else if( strpos($max_width,'vw') !== false ) {
			$style_markup_escaped .= 'max-width: '. intval($max_width) .'vw;';
		} 
		else if( strpos($max_width,'%') !== false ) {
			$style_markup_escaped .= 'max-width: '. intval($max_width) .'%;';
		} 
		else {
			$style_markup_escaped .= 'max-width: '. intval($max_width) .'px;';
		}

  }
  if( !empty($text_color) ) {
    $style_markup_escaped .= ' color: '. esc_attr($text_color) .';';
  }

  $style_markup_escaped .= '"';
}


if( !empty($font_size)) {

  if( strpos($font_size,'vw') !== false  ) {
    $font_style_markup_escaped .= 'style="font-size: '. esc_attr(intval($font_size)) .'vw; line-height: '. esc_attr(intval($font_size)*1.1) .'vw;"';
  } else if( strpos($font_size,'vh') !== false  ) {
    $font_style_markup_escaped .= 'style="font-size: '. esc_attr(intval($font_size)) .'vh; line-height: '. esc_attr(intval($font_size)*1.1) .'vh;"';
  } else {
		$multi = 1.08;
		if( 'p' === $font_style ) {
			$multi = 1.5;
		} 
    $font_style_markup_escaped .= 'style="font-size: '. esc_attr(intval($font_size)) .'px; line-height: '.esc_attr(intval($font_size)*$multi).'px;"';
  }
  $custom_font_size = 'true';

}


echo '<div class="nectar-split-heading" data-align="'.esc_attr($content_alignment).'" data-m-align="'.esc_attr($mobile_content_alignment).'" data-animation-type="'.esc_attr($animation_type).'" data-animation-delay="'.esc_attr($animation_delay).'" data-m-rm-animation="'.esc_attr($mobile_disable_animation).'" data-stagger="'.esc_attr($stagger_animation).'" data-custom-font-size="'.esc_attr($custom_font_size).'" '.$font_style_markup_escaped.'>';

if( 'default' === $animation_type ) {
	foreach($heading_lines as $k => $v) {
		echo '<div class="heading-line" '. $style_markup_escaped .'> <div>' . do_shortcode($v) . ' </div> </div>';
	}
} else if( 'line-reveal-by-space' === $animation_type || 'letter-fade-reveal' === $animation_type || 'twist-in' === $animation_type  ) {
	echo '<'.esc_html($font_style).' '. $style_markup_escaped .'>'.do_shortcode($text_content).'</'.esc_html($font_style).'>';
}

echo '</div>';
