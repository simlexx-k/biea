<?php
/**
* Salient Post Grid element
*
* @version 1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Loop Markup.
require_once( SALIENT_CORE_ROOT_DIR_PATH.'includes/post-grid/loop-markup.php' );

// Post Grid Class.
class NectarPostGrid {
  
  /**
	 * Constructor.
	 */
  public function __construct() {
		
    add_action( 'wp_ajax_nectar_get_post_grid_segment', array($this, 'nectar_get_post_grid_segment') );
    add_action( 'wp_ajax_nopriv_nectar_get_post_grid_segment', array($this, 'nectar_get_post_grid_segment') );
    
  }
  
  public function nectar_get_post_grid_segment() {
    
    // Query args.
    $post_type        = sanitize_text_field( $_POST['post_type'] );
    $cpt_name         = sanitize_text_field( $_POST['cpt_name'] );
    $custom_query_tax = sanitize_text_field( $_POST['custom_query_tax'] );
    $posts_per_page   = intval($_POST['posts_per_page']);
    $current_page     = intval($_POST['current_page']);
    $post_offset      = intval($_POST['offset']);
    $order            = ( 'DESC' === $_POST['order'] ) ? 'DESC' : 'ASC';
    $orderby          = sanitize_text_field( $_POST['orderby'] );
    $category         = sanitize_text_field( $_POST['category'] );
    $action           = sanitize_text_field( $_POST['load_action'] );
    
    // Post Grid Instance Settings.
    $attributes = array();
    $attributes['image_loading']               = 'normal';
    $attributes['cpt_name']                    = $cpt_name; 
    $attributes['post_type']                   = sanitize_text_field($_POST['settings']['post_type']); 
    $attributes['image_size']                  = sanitize_text_field($_POST['settings']['image_size']); 
    $attributes['aspect_ratio_image_size']     = sanitize_text_field($_POST['settings']['aspect_ratio_image_size']); 
    $attributes['display_categories']          = sanitize_text_field($_POST['settings']['display_categories']); 
    $attributes['display_excerpt']             = sanitize_text_field($_POST['settings']['display_excerpt']); 
    $attributes['display_date']                = sanitize_text_field($_POST['settings']['display_date']);
    $attributes['color_overlay']               = sanitize_text_field($_POST['settings']['color_overlay']);  
    $attributes['color_overlay_opacity']       = sanitize_text_field($_POST['settings']['color_overlay_opacity']);  
    $attributes['color_overlay_hover_opacity'] = sanitize_text_field($_POST['settings']['color_overlay_hover_opacity']);  
    $attributes['card_bg_color']               = sanitize_text_field($_POST['settings']['card_bg_color']);  
    $attributes['grid_style']                  = sanitize_text_field($_POST['settings']['grid_style']);  
    $attributes['post_title_overlay']          = sanitize_text_field($_POST['settings']['post_title_overlay']);  
    $attributes['heading_tag']                 = sanitize_text_field($_POST['settings']['heading_tag']);  
    $attributes['enable_gallery_lightbox']     = sanitize_text_field($_POST['settings']['enable_gallery_lightbox']); 
    
    if( 'all' === $category || '-1' === $category ) {
      $category  = null;
    }
    
    // Load More
    if( 'load-more' === $action && $current_page > 0 ) {
      $post_offset = $post_offset + ($posts_per_page*$current_page);
      $sticky_post_IDs = get_option( 'sticky_posts' );
    } else {
      $sticky_post_IDs = array();
    }
    
    // Query
    $nectar_post_grid_query_args = array(
      'post_status'         => 'publish',
      'posts_per_page'      => $posts_per_page,
      'order'               => $order,
      'orderby'             => $orderby,
      'offset'              => $post_offset,
      'post__not_in'        => $sticky_post_IDs
    );

    if( 'portfolio' === $post_type ) {
      $nectar_post_grid_query_args['post_type']    = $post_type;
      $nectar_post_grid_query_args['project-type'] = $category;
    } 
    else if( 'post' === $post_type ) {
      $nectar_post_grid_query_args['post_type']     = $post_type;
      $nectar_post_grid_query_args['category_name'] = $category;
    } 
    else if( 'custom' === $post_type ) {
      $nectar_post_grid_query_args['post_type'] = $cpt_name;
      
      if( !empty($custom_query_tax) ) {
    		
    		$nectar_taxonomies_types = get_taxonomies( array( 'public' => true ) );
    		$terms = get_terms( array_keys( $nectar_taxonomies_types ), array(
    			'hide_empty' => false,
    			'include' => $custom_query_tax,
    		) );
    		
    		$tax_query   = array(); 
    		$tax_queries = array(); 
    		foreach ( $terms as $term ) {
    			if ( ! isset( $tax_queries[ $term->taxonomy ] ) ) {
    				$tax_queries[ $term->taxonomy ] = array(
    					'taxonomy' => $term->taxonomy,
    					'field' => 'id',
    					'terms' => array( $term->term_id ),
    					'relation' => 'IN',
    				);
    			} else {
    				$tax_queries[ $term->taxonomy ]['terms'][] = $term->term_id;
    			}
    		}
    		$tax_query = array_values( $tax_queries );
    		$tax_query['relation'] = 'OR';
    		
    		$nectar_post_grid_query_args['tax_query'] = $tax_query;
    
    	} // end not empty custom tax
      
    }

    $nectar_post_grid_query = new WP_Query( $nectar_post_grid_query_args );
          
    if( $nectar_post_grid_query->have_posts() ) : while( $nectar_post_grid_query->have_posts() ) : $nectar_post_grid_query->the_post();
          
      echo nectar_post_grid_item_markup($attributes);
    
    endwhile; endif; 
    
    wp_die(); 
    
  }
  
}

// Start it up.
$nectar_post_grid = new NectarPostGrid();