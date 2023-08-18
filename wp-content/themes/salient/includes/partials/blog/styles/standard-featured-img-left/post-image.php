<?php
/**
* Post featured image
*
* Used when "Featured Image Left" standard style is selected.
*
* @version 11.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

$nectar_post_format = get_post_format();

if( has_post_thumbnail() ) { 
  
  if( !empty($nectar_options['blog_lazy_load']) && '1' === $nectar_options['blog_lazy_load'] && NectarLazyImages::activate_lazy() ) {
    echo '<a href="' . esc_url( get_permalink() ) . '" aria-label="'.get_the_title().'"><span class="post-featured-img" data-nectar-img-src="' . get_the_post_thumbnail_url( $post->ID, 'wide_photography', array( 'title' => '' ) ) . '">';
    if( 'video' === $nectar_post_format || 'audio' === $nectar_post_format ) {
      get_template_part( 'includes/partials/blog/media/play-button' );
    }
    echo '</span></a>';
  } else {
    echo '<a href="' . esc_url( get_permalink() ) . '" aria-label="'.get_the_title().'"><span class="post-featured-img" style="background-image: url(' . get_the_post_thumbnail_url( $post->ID, 'wide_photography', array( 'title' => '' ) ) . ');">';
    if( 'video' === $nectar_post_format || 'audio' === $nectar_post_format ) {
      get_template_part( 'includes/partials/blog/media/play-button' );
    }
    echo '</span></a>';
  }
}