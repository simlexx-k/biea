<?php 

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


return array(
	"name" => esc_html__("Self Hosted Video Player", "salient-core"),
	"base" => "nectar_video_player_self_hosted",
	"icon" => "icon-wpb-video-lightbox",
	"allowed_container_element" => 'vc_row',
	"category" => esc_html__('Nectar Elements', 'salient-core'),
	"description" => esc_html__('Self Hosted Video', 'salient-core'),
	"params" => array(
    
    array(
      "type" => "nectar_attach_video",
      "class" => "",
      "heading" => esc_html__("Video WebM File URL", "salient-core"),
      "value" => "",
      "param_name" => "video_webm",
      "description" => esc_html__("You must include this format & the mp4 format to render your video with cross browser compatibility.
    Video must be in a 16:9 aspect ratio.", "salient-core")
    ),

    array(
      "type" => "nectar_attach_video",
      "class" => "",
      "heading" => esc_html__("Video MP4 File URL", "salient-core"),
      "value" => "",
      "param_name" => "video_mp4",
      "description" => esc_html__("Enter the URL for your mp4 video file here", "salient-core")
    ),
    array(
      "type" => "fws_image",
      "class" => "",
      "heading" => esc_html__("Video Preview Image", "salient-core"),
      "value" => "",
      "param_name" => "video_image",
      "description" => "",
    ),
    
    array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Video Width', 'js_composer' ),
			'param_name' => 'el_width',
			'value' => array(
				'100%' => '100',
				'90%' => '90',
				'80%' => '80',
				'70%' => '70',
				'60%' => '60',
				'50%' => '50',
				'40%' => '40',
				'30%' => '30',
				'20%' => '20',
				'10%' => '10',
			),
			'description' => esc_html__( 'Select video width (percentage).', 'js_composer' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Video Aspect Ratio', 'js_composer' ),
			'param_name' => 'el_aspect',
			'value' => array(
				'16:9' => '169',
				'4:3' => '43',
				'2.35:1' => '235',
			),
      "admin_label" => true,
			'description' => esc_html__( 'Select video aspect ratio.', 'js_composer' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Alignment', 'js_composer' ),
			'param_name' => 'align',
			'description' => esc_html__( 'Select video alignment.', 'js_composer' ),
			'value' => array(
				esc_html__( 'Left', 'js_composer' ) => 'left',
				esc_html__( 'Right', 'js_composer' ) => 'right',
				esc_html__( 'Center', 'js_composer' ) => 'center',
			),
		),
    array(
			"type" => 'checkbox',
			"heading" => esc_html__("Hide Controls", "salient-core"),
			"param_name" => "hide_controls",
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
			"value" => Array(esc_html__("Yes, please", "salient-core") => 'yes'),
		),
    array(
			"type" => 'checkbox',
			"heading" => esc_html__("Loop Video", "salient-core"),
			"param_name" => "loop",
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
			"value" => Array(esc_html__("Yes, please", "salient-core") => 'yes'),
		),
    array(
			"type" => 'checkbox',
			"heading" => esc_html__("Autoplay Video", "salient-core"),
			"param_name" => "autoplay",
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
			"value" => Array(esc_html__("Yes, please", "salient-core") => 'yes'),
      'description' => esc_html__( 'This will automatically mute the video as well.', 'js_composer' ),
		),
		array(
			'type' => 'el_id',
			'heading' => esc_html__( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
		),
    array(
		"type" => "dropdown",
		"heading" => esc_html__("Border Radius", "salient-core"),
		'save_always' => true,
		"param_name" => "border_radius",
		"value" => array(
			esc_html__("0px", "salient-core") => "none",
			esc_html__("3px", "salient-core") => "3px",
			esc_html__("5px", "salient-core") => "5px", 
			esc_html__("10px", "salient-core") => "10px", 
			esc_html__("15px", "salient-core") => "15px", 
			esc_html__("20px", "salient-core") => "20px"),
		),	
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Box Shadow", "salient-core"),
			'save_always' => true,
			"param_name" => "box_shadow",
			"value" => array(esc_html__("None", "salient-core") => "none", esc_html__("Small Depth", "salient-core") => "small_depth", esc_html__("Medium Depth", "salient-core") => "medium_depth", esc_html__("Large Depth", "salient-core") => "large_depth", esc_html__("Very Large Depth", "salient-core") => "x_large_depth"),
			"description" => esc_html__("Select your desired image box shadow", "salient-core"),
			"dependency" => Array('element' => "animation", 'value' => array('None','Fade In','Fade In From Left','Fade In From Right','Fade In From Bottom','Grow In', 'Flip In', 'flip-in-vertical')),
		),
    
	)
);

?>