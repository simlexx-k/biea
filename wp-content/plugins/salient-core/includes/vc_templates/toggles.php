<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'nectar-element-toggle-panels' );

extract(shortcode_atts(array(
	"accordion" => 'false', 
	'accordion_starting_functionality' => 'default',
	'style' => 'default'), $atts));  
	
($accordion === 'true') ? $accordion_class = 'accordion': $accordion_class = null ;
echo '<div class="toggles '.$accordion_class.'" data-starting="'.esc_attr($accordion_starting_functionality).'" data-style="'.esc_attr($style).'">' . do_shortcode($content) . '</div>'; 

?>




















