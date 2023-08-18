<?php 

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	"name" => esc_html__("Highlighted Text", "salient-core"),
	"base" => "nectar_highlighted_text",
	"icon" => "icon-wpb-nectar-gradient-text",
	"allowed_container_element" => 'vc_row',
	"category" => esc_html__('Nectar Elements', 'salient-core'),
	"description" => esc_html__('Highlight text in an animated manner', 'salient-core'),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Color Type", "salient-core"),
			"param_name" => "color_type",
			"dependency" => Array('element' => "style", 'value' => array('regular_underline','half_text','full_text')),
			"value" => array(
				esc_html__("Regular", "salient-core") => "regular",
				esc_html__("Gradient", "salient-core") => "gradient",
			),
			'save_always' => true,
			"description" => ''
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => esc_html__("Highlight Color", "salient-core"),
			"param_name" => "highlight_color",
			"value" => "",
			"dependency" => Array('element' => "style", 'value' => array('regular_underline','half_text','full_text')),
			"description" => esc_html__("If left blank this will default to a desaturated variant of your defined theme accent color.", "salient-core")
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => esc_html__("Highlight Color #2", "salient-core"),
			"param_name" => "secondary_color",
			"value" => "",
			"dependency" => Array('element' => "color_type", 'value' => 'gradient'),
			"description" => esc_html__("Add a second color which will be used for the gradient", "salient-core")
		),
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"heading" => esc_html__("Text Content", "salient-core"),
			"param_name" => "content",
			"value" => '',
			"description" => esc_html__("Any text that is wrapped in italics will get an animated highlight. Use the \"I\" button on the editor above when highlighting text to toggle italics.", "salient-core"),
			"admin_label" => false
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => esc_html__("Text Color", "salient-core"),
			"param_name" => "text_color",
			"value" => "",
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Style", "salient-core"),
			"param_name" => "style",
			"value" => array(
				esc_html__("Full Text BG Cover", "salient-core") => "full_text",
				esc_html__("Fancy Underline", "salient-core") => "half_text",
				esc_html__("Regular Underline", "salient-core") => "regular_underline",
				esc_html__("Text Outline", "salient-core") => "text_outline"
			),
			'save_always' => true,
			"description" => esc_html__("Please select the style you would like for your highlights.", "salient-core")
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Highlight Color Expansion", "salient-core"),
			"param_name" => "highlight_expansion",
			"description" => esc_html__("Adjust this value as needed depending on how you would like the highlight color to align behind your text.", "salient-core"),
			"dependency" => Array('element' => "style", 'value' => array('full_text','regular_underline')),
			"value" => array(
				esc_html__("Default", "salient-core") => "default",
				esc_html__("Closer To Text", "salient-core") => "closer",
				esc_html__("Closest To Text", "salient-core") => "closest",
			),
			'save_always' => true,
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Outline Thickness", "salient-core"),
			"param_name" => "outline_thickness",
			"dependency" => Array('element' => "style", 'value' => array('text_outline')),
			"value" => array(
				esc_html__("Thin", "salient-core") => "thin",
				esc_html__("Regular", "salient-core") => "regular",
				esc_html__("Thick", "salient-core") => "thick",
				esc_html__("Extra Thick", "salient-core") => "extra_thick"
			),
			'save_always' => true,
			"description" => ''
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Underline Thickness", "salient-core"),
			"param_name" => "underline_thickness",
			"dependency" => Array('element' => "style", 'value' => array('regular_underline')),
			"value" => array(
				esc_html__("Default", "salient-core") => "default",
				esc_html__("1px", "salient-core") => "1px",
				esc_html__("2px", "salient-core") => "2px",
				esc_html__("3px", "salient-core") => "3px",
				esc_html__("4px", "salient-core") => "4px"
			),
			'save_always' => true,
			"description" => ''
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Animation Delay", "salient-core"),
			"param_name" => "delay",
			"dependency" => Array('element' => "style", 'value' => array('regular_underline','half_text','full_text')),
			"description" => esc_html__("Enter delay (in milliseconds) if needed e.g. 150. This parameter comes in handy when creating the animate in \"one by one\" effect.", "salient-core")
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Custom Font Size", "salient-core"),
			"param_name" => "custom_font_size",
			"admin_label" => true,
			"description" => esc_html__("This will apply to any heading tags in the above editor.", "salient-core")
		),
		
	)
);

?>