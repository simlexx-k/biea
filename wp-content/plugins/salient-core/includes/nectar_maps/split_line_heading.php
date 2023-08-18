<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	"name" => esc_html__("Split Line Heading", "salient-core"),
	"base" => "split_line_heading",
	"icon" => "icon-wpb-split-line-heading",
	"allowed_container_element" => 'vc_row',
	"category" => esc_html__('Nectar Elements', 'salient-core'),
	"description" => esc_html__('Animated multi line heading', 'salient-core'),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Animation Type", "salient-core"),
			"param_name" => "animation_type",
			"value" => array(
				esc_html__("Line reveal by each new line (default)", "salient-core") => "default",
				esc_html__("Line reveal by available space", "salient-core") => "line-reveal-by-space",
				esc_html__("Twist in entire text", "salient-core") => "twist-in"
			),
			'save_always' => true,
			"description" => ''
		),
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"heading" => esc_html__("Text Content", "salient-core"),
			"param_name" => "content",
			"value" => '',
			"description" => '',
			"admin_label" => false,
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('default'),
			),
		),
		array(
			"type" => "textarea",
			"heading" => esc_html__("Text", "salient-core"),
			"param_name" => "text_content",
			"admin_label" => true,
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space','letter-fade-reveal','twist-in'),
			),
			"description" => ''
		),
		array(
			"type" => "dropdown",
			"class" => "",
			'save_always' => true,
			"heading" => "Text Font Style",
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space','letter-fade-reveal','twist-in'),
			),
			"description" => esc_html__("Choose what element your text will inherit styling from", "salient-core"),
			"param_name" => "font_style",
			"value" => array(
				"H1" => "h1",
				"H2" => "h2",
				"H3" => "h3",
				"H4" => "h4",
				"H5" => "h5",
				"H6" => "h6",
				"Paragraph" => "p",
			)
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "Text Color",
			"param_name" => "text_color",
			"value" => "",
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space','letter-fade-reveal','twist-in'),
			),
			"description" => esc_html__("Defaults to light or dark based on the current row text color.", "salient-core")
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Custom Font Size", "salient-core"),
			"param_name" => "font_size",
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space','letter-fade-reveal','twist-in'),
			),
		),
		array(
			"type" => "checkbox",
			"class" => "",
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
			"heading" => esc_html__("Stagger Animation", "salient-core"),
			"param_name" => "stagger_animation",
			"value" => array(esc_html__("Yes", "salient-core") => 'true'),
			"description" => esc_html__("Causes each word to animate in at a slightly different rate for a more dramatic effect.", "salient-core"),
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space'),
			),
		),
		array(
			"type" => "checkbox",
			"class" => "",
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
			"heading" => esc_html__("Disable Animation on Mobile", "salient-core"),
			"param_name" => "mobile_disable_animation",
			"value" => array(esc_html__("Yes", "salient-core") => 'true'),
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space'),
			),
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Animation Delay", "salient-core"),
			"param_name" => "animation_delay",
			"description" => esc_html__("Enter delay (in milliseconds) if needed e.g. 150.", "salient-core")
		),
		array(
      "type" => "textfield",
      "heading" => esc_html__("Max Width", "salient-core"),
      "param_name" => "max_width",
      "admin_label" => false,
      "description" => esc_html__("Optionally enter your desired max width in pixels without the \"px\", e.g. 200", "salient-core")
    ),
		array(
			"type" => "dropdown",
			"class" => "",
			'save_always' => true,
			"heading" => "Content Alignment",
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space','letter-fade-reveal','twist-in'),
			),
			"description" => esc_html__("When using a max width smaller than the container, you can use this to optionally define how to align the text content.", "salient-core"),
			"param_name" => "content_alignment",
			"value" => array(
				esc_html__("Default",'salient-core') => "default",
				esc_html__("Left",'salient-core') => "left",
				esc_html__("Center",'salient-core') => "center",
				esc_html__("Right",'salient-core') => 'right'
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			'save_always' => true,
			"heading" => "Mobile Content Alignment",
			'dependency' => array(
				'element' => 'animation_type',
				'value' => array('line-reveal-by-space','letter-fade-reveal','twist-in'),
			),
			"description" => esc_html__("When using a max width smaller than the container, you can use this to optionally define how to align the text content.", "salient-core"),
			"param_name" => "mobile_content_alignment",
			"value" => array(
				esc_html__("Inherit",'salient-core') => "inherit",
				esc_html__("Left",'salient-core') => "left",
				esc_html__("Center",'salient-core') => "center",
				esc_html__("Right",'salient-core') => 'right'
			)
		),

	)
);

?>
