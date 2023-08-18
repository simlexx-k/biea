<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract(shortcode_atts(array(
	"video_webm" => "", 
  'video_mp4' => '', 
	"video_image" => "", 
	"el_width" => '100', 
	"el_aspect" => "169", 
	"align" => "left", 
	"hide_controls" => "", 
	'loop' => '', 
	'autoplay' => '', 
	'border_radius' => 'none',
	'box_shadow' => '',
  'el_id' => ''), $atts));
  

$video_image_src = false;

if( strpos($video_image, "http") !== false ){
  $video_image_src = $video_image;
} else {
  $video_image_src = wp_get_attachment_image_src($video_image, 'full');
	if( isset($video_image_src[0]) ) {
	  $video_image_src = $video_image_src[0];
	}
}
    
$el_classes = array(
  'nectar_video_player_self_hosted',
	'wpb_video_widget',
	'wpb_content_element',
	'vc_clearfix',
	'vc_video-aspect-ratio-' . esc_attr( $el_aspect ),
	'vc_video-el-width-' . esc_attr( $el_width ),
	'vc_video-align-' . esc_attr( $align ),
);

$css_class = implode( ' ', $el_classes );

echo '<div class="' . esc_attr( $css_class ) . '" data-border-radius="'.esc_attr($border_radius).'" data-shadow="'.esc_attr($box_shadow).'">
<div class="wpb_wrapper"><div class="wpb_video_wrapper">';

$video_attrs_arr = array();

if( 'yes' === $loop ) {
  $video_attrs_arr[] = 'loop';
}
if( 'yes' !== $hide_controls ) {
  $video_attrs_arr[] = 'controls controlsList="nodownload"';
}
if( 'yes' === $autoplay ) {
  $video_attrs_arr[] = 'autoplay muted playsinline';
}

$preload_attr = 'auto';

if( false !== $video_image_src ) {
	$preload_attr = 'metadata';
  $video_attrs_arr[] = 'poster="'.esc_attr($video_image_src).'"';
}

$video_attrs_escaped = implode( ' ', $video_attrs_arr );

echo '<video width="1280" height="720" preload="'.esc_attr( $preload_attr ).'" '.$video_attrs_escaped.'>';
  if(!empty($video_webm)) { echo '<source src="'. esc_url( $video_webm ) .'" type="video/webm">'; }
  if(!empty($video_mp4)) { echo '<source src="'. esc_url( $video_mp4 ) .'"  type="video/mp4">'; }
 echo '</video>';
echo '</div></div></div>';
  