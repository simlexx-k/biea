<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'nectar-element-highlighted-text' );

$text = $color = '';

extract(shortcode_atts(array(
	'highlight_color' => '',
	'secondary_color' => '',
  'style' => 'full_text',
	'delay' => 'false',
	'outline_thickness' => 'thin',
	'underline_thickness' => 'default',
	'highlight_expansion' => 'default',
	'text_color' => ''
), $atts));

$using_custom_color = (!empty($highlight_color)) ? 'true' : 'false';

// Dynamic style classes.
if( function_exists('nectar_el_dynamic_classnames') ) {
	$dynamic_el_styles = nectar_el_dynamic_classnames('nectar_highlighted_text', $atts);
} else {
	$dynamic_el_styles = '';
}

$style_specific_attrs_escaped = '';

if( 'text_outline' === $style ) {
	$style_specific_attrs_escaped .= 'data-outline-thickness="'.esc_attr($outline_thickness).'" ';
} else {
	$style_specific_attrs_escaped .= 'data-exp="'.esc_attr($highlight_expansion).'" ';
}

if( 'regular_underline' === $style ) {
	$style_specific_attrs_escaped .= 'data-underline-thickness="'.esc_attr($underline_thickness).'" ';
}

if( !empty($text_color) ) {
	$style_specific_attrs_escaped .= 'data-user-color="true" style="color: '.esc_attr($text_color).';" ';
}

echo '<div class="nectar-highlighted-text'.esc_attr($dynamic_el_styles).'" data-style="'.esc_attr($style).'" '.$style_specific_attrs_escaped.'data-using-custom-color="'.esc_attr($using_custom_color).'" data-animation-delay="'.esc_attr($delay).'" data-color="'.esc_attr($highlight_color).'" data-color-gradient="'.esc_attr($secondary_color).'" style="">'.$content.'</div>';