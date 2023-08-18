<?php
/**
* Post featured image
*
* Used when "Auto Masonry: Meta Overlaid Spaced" masonry style is selected.
*
* @version 11.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

// Featured image.
$image_attrs = array(
  'title' => '',
  'sizes' => '(min-width: 1600px) 20vw, (min-width: 1300px) 25vw, (min-width: 1000px) 33.3vw, (min-width: 690px) 50vw, 100vw',
);
if( has_post_thumbnail() ) { 
  
  // Lazy load.
  if( !empty($nectar_options['blog_lazy_load']) && '1' === $nectar_options['blog_lazy_load'] && NectarLazyImages::activate_lazy() ) {
    echo '<span class="post-featured-img" data-nectar-img-src="' . get_the_post_thumbnail_url( $post->ID, 'medium_featured', array( 'title' => '' ) ) . '"></span>';
  } else {
    echo '<span class="post-featured-img" style="background-image: url(' . get_the_post_thumbnail_url( $post->ID, 'medium_featured', array( 'title' => '' ) ) . ');"></span>';
  }
  
} else {
  echo '<span class="post-featured-img no-img"></span>';
}