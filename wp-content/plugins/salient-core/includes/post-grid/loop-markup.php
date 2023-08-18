<?php

/**
 * Post grid category total count.
 *
 * @since 1.3
 */
function nectar_post_grid_get_category_total($category_id, $post_type, $term_tax_query = '') {

  // All.
  if( '-1' === $category_id) {
    $category_id = null;
  }

  if( 'post' === $post_type && empty($term_tax_query) ) {

    $nectar_post_grid_cat_query = new WP_Query( array(
      'nopaging' => false,
      'posts_per_page' => 1,
      'post_type' => 'post',
      'category_name' => sanitize_text_field($category_id)
    ));

  } else if( 'portfolio' === $post_type && empty($term_tax_query) ) {

    $nectar_post_grid_cat_query = new WP_Query( array(
      'nopaging' => false,
      'posts_per_page' => 1,
      'post_type' => 'portfolio',
      'project-type' => sanitize_text_field($category_id)
    ));

  } else {
    
    $custom_query_args = array(
      'nopaging' => false,
      'posts_per_page' => 1,
      'post_type' => sanitize_text_field($post_type),
    );

    if( $term_tax_query ) {
      $custom_query_args['tax_query'] = array($term_tax_query);
    }

    $nectar_post_grid_cat_query = new WP_Query($custom_query_args);

  }


  return $nectar_post_grid_cat_query->found_posts;

}


/**
 * Post grid item display.
 *
 * @since 1.3
 */
if(!function_exists('nectar_post_grid_item_markup')) {

  function nectar_post_grid_item_markup($atts) {

      $markup = '';

      global $post;

      if( $post ) {

          $bg_style_markup = '';
          $category_markup = null;
          $excerpt_markup = '';
          $image_size = 'large';

          if( isset($atts['image_size']) && !empty($atts['image_size']) ) {
            $image_size = sanitize_text_field($atts['image_size']);
          }

          // Defaults
          if( !isset($atts['color_overlay_opacity']) ) {
            $atts['color_overlay_opacity'] = '0';
          }
          if( !isset($atts['color_overlay_hover_opacity']) ) {
            $atts['color_overlay_hover_opacity'] = '0';
          }
          if( !isset($atts['grid_style'])) {
            $atts['grid_style'] = 'content_overlaid';
          }
          if( !isset($atts['heading_tag'])) {
            $atts['heading_tag'] = 'default';
          }
          if( !isset($atts['enable_gallery_lightbox'])) {
            $atts['enable_gallery_lightbox'] = '0';
          }

          // Handle Heading Tag.
          $heading_tag = 'h3';
          switch( $atts['heading_tag'] ) {
            case 'h2':
              $heading_tag = 'h2';
              break;
            case 'default':
              $heading_tag = 'h3';
              break;
            case 'h4':
              $heading_tag = 'h4';
              break;
            default:
              $heading_tag = 'h3';
          }

          // Aspect Ratio Image size.
          $regular_image = false;
          $regular_image_markup = '';
          if( isset($atts['aspect_ratio_image_size']) &&
              !empty($atts['aspect_ratio_image_size']) &&
              'yes' === $atts['aspect_ratio_image_size'] &&
              'content_under_image' === $atts['grid_style'] ) {
            $regular_image = true;
          }

          // Card design
          $card_color_style = '';
          if( 'content_under_image' === $atts['grid_style'] &&
              isset($atts['card_bg_color']) &&
              !empty($atts['card_bg_color']) ) {
              $card_color_style = ' style="background-color: '.esc_attr($atts['card_bg_color']).';"';
          }

          // Custom Class
          $custom_class_name = '';

          // Gird item link attributes.
          $link_classes_escaped = '';
          $link_attrs_escaped = '';

          /****************** Post. ******************/
          if( $atts['post_type'] === 'post' || $atts['post_type'] === 'custom' ) {

            // Featured Image.
            if( has_post_thumbnail() ) {

              // Lazy Load.
              if( 'lazy-load' === $atts['image_loading'] && NectarLazyImages::activate_lazy() ||
              ( property_exists('NectarLazyImages', 'global_option_active') && true === NectarLazyImages::$global_option_active ) ) {

                if( true === $regular_image ) {

                  $new_img = false;
                  $stored_img = get_the_post_thumbnail($post->ID, $image_size, array( 'class' => 'nectar-lazy' ));

                  // Srcset.
                  preg_match( '/< *img[^>]*srcset *= *["\']?([^"\']*)/i', $stored_img, $srcset_match);

                  if( $srcset_match && isset($srcset_match[1]) ) {
                    $new_img = preg_replace( '#<img([^>]+?)srcset=[\'"](.*)[\'"]?([^>]*)>#', '<img${1} data-nectar-img-srcset="'.esc_attr($srcset_match[1]).'"${3}>', $stored_img );
                  }

                  // Src.
                  preg_match( '/< *img[^>]*src *= *["\']?([^"\']*)/i', $stored_img, $src_match);
                  if( $src_match && isset($src_match[1]) ) {
                    if(false === $new_img) {
                      $new_img = $stored_img;
                    }
                    $new_img = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', '<img${1} data-nectar-img-src="'.esc_attr($src_match[1]).'"${3}>', $new_img );
                  }

                  // Set lazy loading img
                  if( false !== $new_img ) {
                    $regular_image_markup = '<div class="img-wrap nectar-lazy-wrap">'.$new_img.'</div>';
                  } else {
                    // Default to regular.
                    $regular_image_markup = $stored_img;
                  }

                } else {
                  $bg_style_markup = 'data-nectar-img-src="'. get_the_post_thumbnail_url( $post->ID, $image_size, array( 'title' => '' ) ) .'"';
                }

              }

              // No Lazy Load.
              else {

                if( true === $regular_image ) {
                  $regular_image_markup = '<div class="img-wrap">'.get_the_post_thumbnail($post->ID, $image_size).'</div>';
                }
                else {
                  $bg_style_markup = 'style="background-image:url('. get_the_post_thumbnail_url( $post->ID, $image_size, array( 'title' => '' ) ) .');"';
                }

              }

            } // endif has featured img.

            // Categories.
            if( isset($atts['display_categories']) && 'yes' === $atts['display_categories'] ) {

              $category_markup .= '<span class="meta-category">';

              $categories = get_the_category();

              if ( ! empty( $categories ) ) {
                $output = null;
                foreach ( $categories as $category ) {
                  $output .= '<a class="' . esc_attr( $category->slug ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                }
                $category_markup .=  trim( $output );
              }

              $category_markup .= '</span>';

            }

            // Excerpt.
            if( isset($atts['display_excerpt']) && 'yes' === $atts['display_excerpt'] && function_exists('get_nectar_theme_options') ) {
              $nectar_options = get_nectar_theme_options();
              $excerpt_length = ( ! empty( $nectar_options['blog_excerpt_length'] ) ) ? intval( $nectar_options['blog_excerpt_length'] ) : 15;
              $excerpt_markup = '<div class="item-meta-extra"><span class="meta-excerpt">' . nectar_excerpt( $excerpt_length ) . '</span></div>';
            }

            // Permalink.
            $post_perma = get_the_permalink();
            
            // Lightbox item.
            if( 'yes' === $atts['enable_gallery_lightbox'] && has_post_thumbnail() ) {

              $post_featured_image_full_src = get_the_post_thumbnail_url($post->ID,'full');

              if( $post_featured_image_full_src && !empty($post_featured_image_full_src) ) {
                
                $post_perma = $post_featured_image_full_src;
                
                $post_image_caption  = get_post(get_post_thumbnail_id())->post_content;
                $post_image_caption  = strip_tags($post_image_caption);
                if( $post_image_caption && !empty($post_image_caption) ) {
                  $link_attrs_escaped .= ' title="'. wp_kses_post( $post_image_caption ) .'"';
                }
                
                $link_classes_escaped .= ' pretty_photo';
                
              }

            } // End lightbox item.
            
            // Link Format.
            if( $atts['post_type'] === 'post' && get_post_format() === 'link' ) {
              $post_link_url  = get_post_meta( $post->ID, '_nectar_link', true );
              $post_link_text = get_the_content();

              if ( empty( $post_link_text ) && !empty($post_link_url) ) {
                $post_perma = esc_url($post_link_url);
                $link_attrs_escaped .= ' target="_blank"';
              }

            }

          }

          /****************** Portfolio post type. ******************/
          else if( $atts['post_type'] === 'portfolio') {

            $custom_project_class = get_post_meta($post->ID, '_nectar_project_css_class', true);
            $custom_thumbnail     = get_post_meta($post->ID, '_nectar_portfolio_custom_thumbnail', true);

            // Class name
            if( !empty($custom_project_class) ) {
              $custom_class_name = ' ' . $custom_project_class;
            }

            // Custom thumb.
            if( !empty($custom_thumbnail) ) {

              // Lazy load.
              if( 'lazy-load' === $atts['image_loading'] && NectarLazyImages::activate_lazy() ||
                 ( property_exists('NectarLazyImages', 'global_option_active') && true === NectarLazyImages::$global_option_active ) ) {

                if( true === $regular_image ) {
                  $regular_image_markup = '<div class="img-wrap nectar-lazy-wrap"><img class="nectar-lazy" data-nectar-img-src="'.nectar_ssl_check( esc_url( $custom_thumbnail ) ).'" alt="'. get_the_title() .'" /></div>';
                } else {
                  $bg_style_markup = 'data-nectar-img-src="'. nectar_ssl_check( esc_url( $custom_thumbnail ) ) .'"';
                }

              }

              // Regular load.
              else {

                if( true === $regular_image ) {
                  $regular_image_markup = '<div class="img-wrap"><img class="skip-lazy" src="'.nectar_ssl_check( esc_url( $custom_thumbnail ) ).'" alt="'. get_the_title() .'" /></div>';
                } else {
                  $bg_style_markup = 'style="background-image:url('. nectar_ssl_check( esc_url( $custom_thumbnail ) ) .');"';
                }

              }

            }

            // Featured Img.
            else {
              $thumbnail_id = get_post_thumbnail_id( $post->ID );
              $image_bg = wp_get_attachment_image_src( $thumbnail_id, $image_size);

              // Lazy load.
              if( 'lazy-load' === $atts['image_loading'] && NectarLazyImages::activate_lazy() ||
                  ( property_exists('NectarLazyImages', 'global_option_active') && true === NectarLazyImages::$global_option_active ) ) {

                if( true === $regular_image ) {

                  $new_img = false;
                  $stored_img = wp_get_attachment_image($thumbnail_id, $image_size, '', array( 'class' => 'nectar-lazy' ));

                  // Srcset.
                  preg_match( '/< *img[^>]*srcset *= *["\']?([^"\']*)/i', $stored_img, $srcset_match);

                  if( $srcset_match && isset($srcset_match[1]) ) {
                    $new_img = preg_replace( '#<img([^>]+?)srcset=[\'"](.*)[\'"]?([^>]*)>#', '<img${1} data-nectar-img-srcset="'.esc_attr($srcset_match[1]).'"${3}>', $stored_img );
                  }

                  // Src.
                  preg_match( '/< *img[^>]*src *= *["\']?([^"\']*)/i', $stored_img, $src_match);
                  if( $src_match && isset($src_match[1]) ) {
                    if(false === $new_img) {
                      $new_img = $stored_img;
                    }
                    $new_img = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', '<img${1} data-nectar-img-src="'.esc_attr($src_match[1]).'"${3}>', $new_img );
                  }

                  // Set lazy loading img
                  if( false !== $new_img ) {
                    $regular_image_markup = '<div class="img-wrap nectar-lazy-wrap">'.$new_img.'</div>';
                  } else {
                    // Default to regular.
                    $regular_image_markup = $stored_img;
                  }

                }

                else {
                  $bg_style_markup = (!empty($image_bg)) ? 'data-nectar-img-src="'. esc_url( $image_bg[0] ) .'"' : '';
                }

              }

              // Regular Load.
              else {

                  if( true === $regular_image ) {
                    $regular_image_markup = '<div class="img-wrap">'.wp_get_attachment_image($thumbnail_id, $image_size).'</div>';
                  } else {
                    $bg_style_markup = (!empty($image_bg)) ? 'style="background-image:url('. esc_url( $image_bg[0] ) .');"' : '';
                  }
              }

            } // End Featured Img.

            // Categories.
            $category_markup = null;

            if( isset($atts['display_categories']) && 'yes' === $atts['display_categories'] ) {

              $category_markup .= '<span class="meta-category">';

              $project_categories = get_the_terms($post->id,"project-type");

              if ( !empty($project_categories) ){
                $output = null;
                foreach ( $project_categories as $term ) {

                  if( isset($term->slug) ) {
                    $output .= '<a class="' . esc_attr( $term->slug ) . '" href="' . esc_url( get_category_link( $term->term_id ) ) . '">' . esc_html( $term->name ) . '</a>';
                  }

                }
                $category_markup .=  trim( $output );
              }

              $category_markup .= '</span>';

            }

            // Excerpt.
            if( isset($atts['display_excerpt']) && 'yes' === $atts['display_excerpt'] ) {
              $project_excerpt = get_post_meta($post->ID, '_nectar_project_excerpt', true);
              $excerpt_markup = (!empty($project_excerpt)) ? '<div class="item-meta-extra"><span class="meta-excerpt">' . $project_excerpt . '</span></div>' : '';
            }

            // Permalink.
            $custom_project_link = get_post_meta($post->ID, '_nectar_external_project_url', true);
            $lightbox_only_item  = get_post_meta($post->ID, '_nectar_portfolio_lightbox_only_grid_item', true);

            $post_perma = ( !empty($custom_project_link) ) ? $custom_project_link : get_the_permalink();

            // Lightbox item.
            if( !empty( $lightbox_only_item ) && 'on' === $lightbox_only_item || 'yes' === $atts['enable_gallery_lightbox'] ) {

              $video_embed = get_post_meta($post->ID, '_nectar_video_embed', true);
              $video_mp4 = get_post_meta($post->ID, '_nectar_video_m4v', true);

              //video
              if( !empty($video_embed) && function_exists('nectar_extract_video_lightbox_link') ||
                  !empty($video_mp4) && function_exists('nectar_extract_video_lightbox_link') ) {

                $project_video_link = nectar_extract_video_lightbox_link($post, $video_embed, $video_mp4);
                $post_perma = $project_video_link;
                $link_classes_escaped .= ' pretty_photo';

              } else if( empty($custom_project_link) ) {

                $featured_image_full_src = wp_get_attachment_image_src( $thumbnail_id, 'full');

                if( $featured_image_full_src && !empty($featured_image_full_src) ) {
                  $post_perma = $featured_image_full_src[0];
                  $project_image_caption  = get_post(get_post_thumbnail_id())->post_content;
      						$project_image_caption  = strip_tags($project_image_caption);
                  if( $project_image_caption && !empty($project_image_caption) ) {
                    $link_attrs_escaped .= ' title="'. wp_kses_post( $project_image_caption ) .'"';
                  }
                  $link_classes_escaped .= ' pretty_photo';
                }
                
              }

            } // End lightbox item.

          }




          $bg_overlay_markup = (isset($atts['color_overlay']) && !empty($atts['color_overlay'])) ? 'style=" background-color: '. esc_attr($atts['color_overlay']) .';"' : '';




          /****************** Output Markup ******************/
          $markup .= '<div class="nectar-post-grid-item'.esc_attr($custom_class_name).'"'.$card_color_style.'> <div class="inner">';

          // Conditional based on style
          if( 'content_overlaid' !== $atts['grid_style'] ) {
            $markup .= '<div class="nectar-post-grid-item-bg-wrap"><div class="nectar-post-grid-item-bg-wrap-inner"><a aria-label="'.get_the_title().'" href="'. esc_attr($post_perma) .'"></a>';
          }

          $markup .= $regular_image_markup . '<div class="nectar-post-grid-item-bg" '.$bg_style_markup.'></div>';

          if( 'content_overlaid' !== $atts['grid_style'] ) {
            $markup .= '</div></div>';
          }

          if( 'content_overlaid' === $atts['grid_style'] ) {
            $markup .= '<div class="bg-overlay" '.$bg_overlay_markup.' data-opacity="'. esc_attr($atts['color_overlay_opacity']) .'" data-hover-opacity="'. esc_attr($atts['color_overlay_hover_opacity']) .'"></div>';
          }



          $markup .= '<div class="content">';

          $markup .= '<a class="nectar-post-grid-link'.$link_classes_escaped.'" href="'. esc_attr($post_perma) .'" aria-label="'.get_the_title().'"'.$link_attrs_escaped.'></a>';

          $markup .= $category_markup;

          $post_title_overlay = ( isset($atts['post_title_overlay']) && 'yes' === $atts['post_title_overlay'] ) ? ' data-title-text="'.get_the_title().'"' : '';

          $markup .= '<div class="item-main"><'.esc_html($heading_tag).' class="post-heading"><a href="'. esc_attr($post_perma) .'"'.$post_title_overlay.'><span>'. get_the_title() .'</span></a></'.esc_html($heading_tag).'>';

          if( isset($atts['display_date']) && 'yes' === $atts['display_date'] ) {

            $date = get_the_date();

            if( function_exists('get_nectar_theme_options') && $atts['post_type'] === 'post' ) {

              $date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';

              if( 'last_editied_date' === $date_functionality ) {
                $date = get_the_modified_date();
              }

            }

            $markup .= '<span class="meta-date">' . $date . '</span>';
          }

          if( has_filter('nectar_post_grid_excerpt') ) {
            $post_type_in_use = ($atts['post_type'] === 'custom' ) ? $atts['cpt_name'] : $atts['post_type'];
            $excerpt_markup = apply_filters('nectar_post_grid_excerpt', $excerpt_markup, $post_type_in_use);
          }

          $markup .= $excerpt_markup;

          $markup .= '</div>';

          $markup .= '</div>';
          $markup .= '</div></div>';
      }

      return $markup;

  }
}
