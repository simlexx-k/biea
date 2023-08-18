<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract(shortcode_atts(array(
	"id" => ""
), $atts));

if( !empty($id) ) {

  $section_id = intval($id);
  $section_id = apply_filters( 'wpml_object_id', $section_id, 'post', true );

	$section_status = get_post_status($section_id);
	if( 'publish' === $section_status ) {

	  $section_content = get_post_field( 'post_content', $section_id );

		if( $section_content ) {

			$unneeded_tags = array(
	        '<p>['    => '[',
	        ']</p>'   => ']',
	        ']<br />' => ']',
	        ']<br>'   => ']',
	    );

	    $section_content = strtr($section_content, $unneeded_tags);

		  echo do_shortcode($section_content);

		}

	  /* Output dynamic CSS */
	  if ( class_exists('Vc_Base') ) {
	  	$vc = new Vc_Base();
			$vc->addPageCustomCss($section_id);
	  	$vc->addShortcodesCustomCss($section_id);
	  }

	}

}
