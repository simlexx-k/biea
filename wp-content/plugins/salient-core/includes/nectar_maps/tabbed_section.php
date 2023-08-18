<?php 

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab_id_1 = time().'-1-'.rand(0, 100);
$tab_id_2 = time().'-2-'.rand(0, 100);
$vc_is_wp_version_3_6_more = version_compare(preg_replace('/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo('version')), '3.6') >= 0;

return array(
	"name"  => esc_html__("Tabs", "salient-core"),
	"base" => "tabbed_section",
	"show_settings_on_create" => false,
	"is_container" => true,
	"icon" => "icon-wpb-ui-tab-content",
	"category" => esc_html__('Nectar Elements', 'salient-core'),
	"description" => esc_html__('Tabbed content', 'salient-core'),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Style", "salient-core"),
			"param_name" => "style",
			"admin_label" => true,
			"value" => array(
				esc_html__("Default", "salient-core") => "default",
				esc_html__("Material", "salient-core") => "material",
				esc_html__("Minimal", "salient-core") => "minimal",
				esc_html__("Minimal Alt", "salient-core") => "minimal_alt",
				esc_html__("Minimal Flexible Width", "salient-core") => "minimal_flexible",
				esc_html__("Vertical", "salient-core") => "vertical",
				esc_html__("Vertical Material", "salient-core") => "vertical_modern",
				esc_html__("Vertical Sticky Scrolling", "salient-core") => "vertical_scrolling",
			),
			'save_always' => true,
			"description" => esc_html__("Please select the style you desire for your tabbed element.", "salient-core")
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Alignment", "salient-core"),
			"param_name" => "alignment",
			"admin_label" => false,
			"value" => array(
				"Left" => "left",
				"Center" => "center",
				"Right" => "right"
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('minimal','default', 'minimal_alt', 'material')),
			"description" => esc_html__("Please select your tabbed alignment", "salient-core")
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Spacing", "salient-core"),
			"param_name" => "spacing",
			"admin_label" => false,
			"value" => array(
				"Default" => "default",
				"15px" => "side-15px",
				"20px" => "side-20px",
				"25px" => "side-25px",
				"30px" => "side-30px",
				"35px" => "side-35px",
				"40px" => "side-40px",
				"45px" => "side-45px"
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('minimal','default', 'minimal_alt',  'material')),
			"description" => esc_html__("Please select your desired spacing", "salient-core")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			'save_always' => true,
			"heading" => "Tab Color",
			"param_name" => "tab_color",
			"value" => array(
				esc_html__( "Accent Color", "salient-core") => "Accent-Color",
				esc_html__( "Extra Color 1", "salient-core") => "Extra-Color-1",
				esc_html__( "Extra Color 2", "salient-core") => "Extra-Color-2",	
				esc_html__( "Extra Color 3", "salient-core") => "Extra-Color-3",
				esc_html__( "Color Gradient 1", "salient-core") => "extra-color-gradient-1",
				esc_html__( "Color Gradient 2", "salient-core") => "extra-color-gradient-2"
			)
		),
		
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Navigation Width", "salient-core"),
			"param_name" => "vs_navigation_width",
			"admin_label" => false,
			"value" => array(
				"Regular" => "regular",
				"Wide" => "wide",
				"Narrow" => "narrow"
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('vertical_scrolling'))
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Navigation Item Spacing", "salient-core"),
			"param_name" => "vs_navigation_spacing",
			"admin_label" => false,
			"value" => array(
				"15px" => "15px",
				"20px" => "20px",
				"25px" => "25px",
				"30px" => "30px",
				"35px" => "35px",
				"40px" => "40px",
				"45px" => "45px",
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('vertical_scrolling'))
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Navigation Item Mobile Display", "salient-core"),
			"param_name" => "vs_navigation_mobile_display",
			"admin_label" => false,
			"value" => array(
				"Visible Above Each Section" => "visible",
				"Hidden" => "hidden",
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('vertical_scrolling'))
		),
		
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Tab Spacing", "salient-core"),
			"param_name" => "vs_tab_spacing",
			"admin_label" => false,
			"value" => array(
				"10%" => "10%",
				"20%" => "20%",
				"30%" => "30%",
				"40%" => "40%",
				"50%" => "50%",
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('vertical_scrolling'))
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Tab Link Element", "salient-core"),
			"param_name" => "vs_tab_tag",
			"admin_label" => false,
			"value" => array(
				"Inherit from Body" => "p",
				"Heading 6" => "h6",
			  "Heading 5" => "h5",
				"Heading 4" => "h4",
				"Heading 3" => "h3",
				"Heading 2" => "h2",
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('vertical_scrolling'))
		),
		
		array(
			"type" => "textfield",
			"heading" => esc_html__("Optional CTA button", "salient-core"),
			"param_name" => "cta_button_text",
			"description" => esc_html__("If you wish to include an optional CTA button on your tabbed nav, enter the text here", "salient-core"),
			"admin_label" => false,
			"dependency" => Array('element' => "style", 'value' => array('minimal','minimal_alt'))
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("CTA button link", "salient-core"),
			"param_name" => "cta_button_link",
			"description" => esc_html__("Enter a URL for your button link here", "salient-core"),
			"admin_label" => false,
			"dependency" => Array('element' => "style", 'value' => array('minimal','minimal_alt'))
		),
		array(
			"type" => "dropdown",
			"heading" => esc_html__("CTA Button Color", "salient-core"),
			"param_name" => "cta_button_style",
			"admin_label" => false,
			"value" => array(
				"Accent Color" => "accent-color",
				"Extra Color 1" => "extra-color-1",
				"Extra Color 2" => "extra-color-2",
				"Extra Color 3" => "extra-color-3"
			),
			'save_always' => true,
			'description' => __( 'Choose a color from your','salient-core') . ' <a target="_blank" href="'. esc_url(NectarThemeInfo::global_colors_tab_url()) .'"> ' . esc_html__('globally defined color scheme','salient-core') . '</a>',
			"dependency" => Array('element' => "style", 'value' => array('minimal','minimal_alt'))
		),
		
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Full width divider line", "salient-core"),
			"param_name" => "full_width_line",
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
			"description" => esc_html__("This will cause the line that separates the tab links their content to display the full width of the screen.", "salient-core"),
			"value" => Array(esc_html__("Yes, please", "salient-core") => 'true'),
			"dependency" => Array('element' => "style", 'value' => array('material'))
		),
		
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Icon Font Size", "salient-core"),
			"param_name" => "icon_size",
			"admin_label" => false,
			"value" => array(
				"24px" => "24",
				"26px" => "26",
				"28px" => "28",
				"30px" => "30",
				"32px" => "32",
				"34px" => "34",
				"36px" => "36",
			),
			'save_always' => true,
			"dependency" => Array('element' => "style", 'value' => array('minimal','minimal_alt','material','minimal_flexible')),
			'description' => esc_html__( 'Select the size you would like the optional tab icons to display in - Thin border sets like "Iconsmind" and "Linea" are better suited to display at higher values.', 'salient-core' ),
		),
		
		array(
			"type" => "textfield",
			"heading" => esc_html__("Extra class name", "salient-core"),
			"param_name" => "el_class",
			"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "salient-core")
		)
	),
	"custom_markup" => '
	<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	<ul class="tabs_controls">
	</ul>
	%content%
	</div>'
	,
	'default_content' => '
	[tab title="'.esc_html__('Tab','salient-core').'" id="'.$tab_id_1.'"] I am text block. Click edit button to change this text. [/tab]
	[tab title="'.esc_html__('Tab','salient-core').'" id="'.$tab_id_2.'"] I am text block. Click edit button to change this text. [/tab]
	',
	"js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
);
?>