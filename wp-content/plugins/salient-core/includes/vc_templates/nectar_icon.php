<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract(shortcode_atts(array(
	'icon_family' => 'fontawesome',
	'icon_fontawesome' => '',
	'icon_linecons' => '',
	'icon_linea' => '',
	'icon_iconsmind' => '',
	'icon_steadysets' => '',
	'icon_color' => 'accent-color',
	'icon_color_type' => 'color_scheme',
	'icon_color_custom' => '',
	'icon_size' => '50',
	'icon_style' => '',
	'icon_border_thickness' => '2px',
	'enable_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'url' => '',
	'open_new_tab' => '',
	'icon_padding' => '20px',
	'margin_top' => '',
	'margin_right' => '',
	'margin_bottom' => '',
	'margin_left' => '',
), $atts));

// Icon.
switch($icon_family) {
	case 'fontawesome':
		$icon = $icon_fontawesome;
		break;
	case 'steadysets':
		$icon = $icon_steadysets;
		break;
	case 'linea':
		$icon = $icon_linea;
		break;
	case 'linecons':
		$icon = $icon_linecons;
		wp_enqueue_style( 'vc_linecons' );
		break;
	case 'iconsmind':
		$icon = $icon_iconsmind;
		break;
	default:
		$icon = '';
		break;
}

$icon_size_val = (!empty($icon_style) && $icon_style === 'border-basic' || !empty($icon_style) && $icon_style === 'border-animation' || !empty($icon_style) && $icon_style === 'soft-bg' || !empty($icon_style) && $icon_style === 'shadow-bg') ? intval($icon_size)*1.5 : intval($icon_size);

// Regular icon only grad extra space.
if(!empty($icon_style) && $icon_style === 'default') {
	if(strtolower($icon_color) == 'extra-color-gradient-1' || strtolower($icon_color) == 'extra-color-gradient-2') {
		$icon_size_val = intval($icon_size)*1.2;
	}
}

// Needed because display: initial will cause imperfect circles.
$grad_dimensions = '';

if( strtolower($icon_color) === 'extra-color-gradient-1' || strtolower($icon_color) === 'extra-color-gradient-2' ) {
	$circle_size     = ($icon_size_val + (intval($icon_padding)*2) + intval($icon_border_thickness));
	$grad_dimensions = 'style="height: '. esc_attr($circle_size) .'px; width: '.esc_attr($circle_size).'px;"';
}

// Check if iconsmind SVGs exist.
$svg_iconsmind = ( defined('NECTAR_THEME_DIRECTORY') && file_exists( NECTAR_THEME_DIRECTORY . '/css/fonts/svg-iconsmind/Aa.svg.php' ) ) ? true : false;

// SVG linea.
if( $icon_family === 'linea' && $enable_animation === 'true' && $icon !== '' && strlen($grad_dimensions) < 2 ) {
	
	wp_enqueue_script('vivus'); 
	
	$converted_icon = str_replace('-', '_', $icon);
	$converted_icon = str_replace('icon_', '', $converted_icon);
	$icon_markup    = '<span class="svg-icon-holder" data-size="'. esc_attr($icon_size) . '" data-animation-speed="'.esc_attr($animation_speed).'" data-animation="'.esc_attr($enable_animation).'" data-animation-delay="'.esc_attr($animation_delay).'" data-color="'.esc_attr(strtolower($icon_color)) .'"><span>';
	
	ob_start();
	get_template_part( 'css/fonts/svg/'. $converted_icon .'.svg' );
	$icon_markup .=  ob_get_contents();
	ob_end_clean();
	
	$icon_markup .= '</span></span>';
} 

else if( $icon_family === 'iconsmind' && $svg_iconsmind ) {
	
	// SVG iconsmind.
	$icon_id        = 'nectar-iconsmind-icon-'.uniqid();
	$icon_markup    = '<span class="im-icon-wrap" data-color="'.strtolower($icon_color) .'"><span>';
	$converted_icon = str_replace('iconsmind-', '', $icon);
	
	ob_start();

	get_template_part( 'css/fonts/svg-iconsmind/'. $converted_icon .'.svg' );
	$icon_markup .=  ob_get_contents();
	
	// Custom size.
	$icon_markup = preg_replace(
   array('/width="\d+"/i', '/height="\d+"/i'),
   array('width="'.$icon_size.'"', 'height="'.$icon_size.'"'),
   $icon_markup);
	
	// Handle gradients.
	if( strtolower($icon_color) === 'extra-color-gradient-1' || strtolower($icon_color) === 'extra-color-gradient-2') {
			
			$nectar_options = get_nectar_theme_options();
			
			if( strtolower($icon_color) === 'extra-color-gradient-1' && isset($nectar_options["extra-color-gradient"]['from']) ) {
				
				$accent_gradient_from = $nectar_options["extra-color-gradient"]['from'];
				$accent_gradient_to   = $nectar_options["extra-color-gradient"]['to'];
				
			} else if( strtolower($icon_color) === 'extra-color-gradient-2' && isset($nectar_options["extra-color-gradient-2"]['from']) ) {
				
				$accent_gradient_from = $nectar_options["extra-color-gradient-2"]['from'];
				$accent_gradient_to   = $nectar_options["extra-color-gradient-2"]['to'];
				
			}
			
		  $icon_markup =  preg_replace('/(<svg\b[^><]*)>/i', '$1 fill="url(#'.$icon_id.')">', $icon_markup);
			
		  $icon_markup .= '<svg style="height:0;width:0;position:absolute;" aria-hidden="true" focusable="false">
			  <linearGradient id="'.$icon_id.'" x2="1" y2="1">
			    <stop offset="0%" stop-color="'.$accent_gradient_from.'" />
			    <stop offset="100%" stop-color="'.$accent_gradient_to.'" />
			  </linearGradient>
			</svg>';
	} 
	 
	ob_end_clean();
	
	$icon_markup .= '</span></span>';
}
// Regular.
else {

	// Regular (gradient) linea.
	if( !empty($icon_family) && $icon_family === 'linea' ) {
		wp_enqueue_style('linea'); 
	}
	if( !empty($icon_family) && $icon_family === 'iconsmind' ) {
		wp_enqueue_style( 'iconsmind-core' );
	}

	if( !empty($icon_family) && $icon_family !== 'none' ) {
		$icon_markup = '<i style="font-size: '.intval($icon_size).'px; line-height: '. esc_attr($icon_size_val) .'px; height: '. esc_attr($icon_size_val) .'px; width: '. esc_attr($icon_size_val) .'px;" class="' . esc_attr($icon) .'"></i>'; 
	} 
	else {
		$icon_markup = null; 
	}
}

// Margins.
$margins = '';
if( !empty($margin_top) ) {
	$margins .= 'margin-top: '.intval($margin_top).'px; ';
}
if( !empty($margin_right) ) {
	$margins .= 'margin-right: '.intval($margin_right).'px; ';
}
if( !empty($margin_bottom) ) {
	$margins .= 'margin-bottom: '.intval($margin_bottom).'px; ';
}
if( !empty($margin_left) ) {
	$margins .= 'margin-left: '.intval($margin_left).'px;';
}

// Link.
if( !empty($url) ) {
	$target    = ($open_new_tab === 'true') ? 'target="_blank"' : null;
	$icon_link = '<a href="'.esc_attr($url).'" '.$target.'></a>';
} else {
	$icon_link = null;
}

// Dynamic style classes.
if( function_exists('nectar_el_dynamic_classnames') ) {
	$dynamic_el_styles = nectar_el_dynamic_classnames('nectar_icon', $atts);
} else {
	$dynamic_el_styles = '';
}

$icon_attributes_escaped = '';

if( 'border-basic' === $icon_style || 'border-animation' === $icon_style ) {
	$icon_attributes_escaped .= ' data-border-thickness="'.esc_attr($icon_border_thickness).'"';
}
if( 'linea' === $icon_family && 'true' === $enable_animation ) {
	$icon_attributes_escaped .= ' data-draw="'.esc_attr($enable_animation).'"';
}

echo '<div class="nectar_icon_wrap"'.$icon_attributes_escaped.' data-style="'.esc_attr($icon_style).'" data-padding="'.esc_attr($icon_padding).'" data-color="'.esc_attr(strtolower($icon_color)).'" style="'.$margins.'" >
		<div class="nectar_icon'.$dynamic_el_styles.'" '.$grad_dimensions.'>'. $icon_link. $icon_markup.'</div>
	</div>';


?>