<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$output = $title = $id = '';
extract(shortcode_atts($this->predefined_atts, $atts));


extract(shortcode_atts(array(
  'icon_family' => '',
  'icon_fontawesome' => '',
  'icon_linecons' => '',
	'icon_linea' => '',
  'icon_iconsmind' => '',
  'icon_steadysets' => '',
	'el_class' => '',
), $atts));


//icon
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
		break;
	case 'iconsmind':
		$icon = $icon_iconsmind;
		break;
	default:
		$icon = '';
		break;
}

$nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

// Check if iconsmind SVGs exist.
$svg_iconsmind = ( defined('NECTAR_THEME_DIRECTORY') && file_exists( NECTAR_THEME_DIRECTORY . '/css/fonts/svg-iconsmind/Aa.svg.php' ) ) ? true : false;

$icon_attr = ( $icon_family !== 'iconsmind' || $nectar_using_VC_front_end_editor || !$svg_iconsmind ) ? 'data-tab-icon="'.$icon.'"' : '';

$icon_markup = '';

if( $icon_family === 'iconsmind' && $svg_iconsmind && ! $nectar_using_VC_front_end_editor ) {
  
  // SVG iconsmind.
  $icon_id        = 'nectar-iconsmind-icon-'.uniqid();
  $icon_markup    = '<span class="im-icon-wrap tab-icon"><span>';
  $converted_icon = str_replace('iconsmind-', '', $icon);
  ob_start();

  get_template_part( 'css/fonts/svg-iconsmind/'. $converted_icon .'.svg' );
  
  $icon_markup .=  ob_get_contents();
  
  ob_end_clean();
  
  $icon_markup .= '</span></span>';
} else if( $icon_family === 'iconsmind' ) {
  wp_enqueue_style( 'iconsmind-core' );
} else if( $icon_family === 'linea' ) {
	wp_enqueue_style('linea'); 
}

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_tab ui-tabs-panel wpb_ui-tabs-hide clearfix', $this->settings['base'], $atts);
if( !empty($el_class) ) {
	$css_class .= ' '. $el_class;
}
$output .= "\n\t\t\t" . '<div id="tab-'. (empty($id) ? sanitize_title( $title ) : $id) .'" '.$icon_attr .' class="'.esc_attr($css_class).'">' . $icon_markup;
$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "js_composer") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.wpb_tab');

echo $output;