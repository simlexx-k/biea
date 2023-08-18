<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract(shortcode_atts(array(
	"title" => 'Title',
	'color' => 'Accent-Color'), $atts));

echo '<div class="toggle '.esc_attr(strtolower($color)).'" data-inner-wrap="true"><h3><a href="#"><i class="fa fa-plus-circle"></i>'. wp_kses_post($title) .'</a></h3><div><div class="inner-toggle-wrap">' . do_shortcode($content) . '</div></div></div>';

?>
