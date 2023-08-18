<?php 

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	"name" => esc_html__("Section", "salient-core"),
	"base" => "toggle",
	"allowed_container_element" => 'vc_row',
	"is_container" => true,
	"content_element" => false,
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => esc_html__("Title", "salient-core"),
			"param_name" => "title",
			"description" => esc_html__("Accordion section title.", "salient-core")
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Color", "salient-core"),
			"param_name" => "color",
			"admin_label" => true,
			"value" => array(
				esc_html__( "Default", "salient-core") => "Default",
				esc_html__( "Accent Color", "salient-core") => "Accent-Color",
				esc_html__( "Extra Color 1", "salient-core") => "Extra-Color-1",
				esc_html__( "Extra Color 2", "salient-core") => "Extra-Color-2",	
				esc_html__( "Extra Color 3", "salient-core") => "Extra-Color-3"
			),
			'save_always' => true,
			'description' => esc_html__( 'Choose a color from your','salient-core') . ' <a target="_blank" href="'. esc_url(NectarThemeInfo::global_colors_tab_url()) .'"> ' . esc_html__('globally defined color scheme','salient-core') . '</a>',
		)
	),
	'js_view' => 'VcAccordionTabView'
);

?>