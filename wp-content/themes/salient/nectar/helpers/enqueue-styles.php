<?php
/**
 * Enqueue styles
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 12.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register/Enqueue frontend CSS.
 *
 * @since 1.0
 */
function nectar_main_styles() {

		 global $nectar_get_template_directory_uri;
     global $nectar_options;

		 $nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
		 $nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

		 $nectar_theme_version = nectar_get_theme_version();

		 // Core.
		 if( isset($nectar_options['rm-legacy-icon-css']) &&
		     !empty($nectar_options['rm-legacy-icon-css']) &&
				 '1' === $nectar_options['rm-legacy-icon-css'] ) {
			 wp_register_style( 'font-awesome', $nectar_get_template_directory_uri . '/css/font-awesome.min.css', '', '4.7.1' );
		 } else {
			 wp_register_style( 'font-awesome', $nectar_get_template_directory_uri . '/css/font-awesome-legacy.min.css', '', '4.7.1' );
		 }
		 wp_register_style( 'main-styles', $nectar_get_template_directory_uri . '/css/style.css', '', $nectar_theme_version );
		 wp_register_style( 'salient-grid-system-legacy', $nectar_get_template_directory_uri . '/css/grid-system-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'salient-grid-system', $nectar_get_template_directory_uri . '/css/grid-system.css', '', $nectar_theme_version );

		 // WooCommerce
		 wp_register_style( 'nectar-product-style-minimal', $nectar_get_template_directory_uri . '/css/third-party/woocommerce/product-style-minimal.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-product-style-classic', $nectar_get_template_directory_uri . '/css/third-party/woocommerce/product-style-classic.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-product-style-text-on-hover', $nectar_get_template_directory_uri . '/css/third-party/woocommerce/product-style-text-on-hover.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-product-style-material', $nectar_get_template_directory_uri . '/css/third-party/woocommerce/product-style-material.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-woocommerce-non-critical', $nectar_get_template_directory_uri . '/css/third-party/woocommerce/woocommerce-non-critical.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-woocommerce-single', $nectar_get_template_directory_uri . '/css/third-party/woocommerce/product-single.css', '', $nectar_theme_version );
		 wp_register_style( 'woocommerce', $nectar_get_template_directory_uri . '/css/woocommerce.css', '', $nectar_theme_version );

		 wp_register_style( 'iconsmind', $nectar_get_template_directory_uri . '/css/iconsmind.css', '', '12.5' );
		 wp_register_style( 'iconsmind-core', $nectar_get_template_directory_uri . '/css/iconsmind-core.css', '', $nectar_theme_version );
		 wp_register_style( 'linea', $nectar_get_template_directory_uri . '/css/fonts/svg/font/arrows_styles.css' );
		 wp_register_style( 'fullpage', $nectar_get_template_directory_uri . '/css/plugins/fullpage.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-steadysets', $nectar_get_template_directory_uri . '/css/steadysets.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-linecon', $nectar_get_template_directory_uri . '/css/linecon.css', '', $nectar_theme_version );

		 // Header Formats.
		 wp_register_style( 'nectar-header-layout-left', $nectar_get_template_directory_uri . '/css/header/header-layout-left.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-bottom-bar', $nectar_get_template_directory_uri . '/css/header/header-layout-centered-bottom-bar.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-menu', $nectar_get_template_directory_uri . '/css/header/header-layout-centered-menu.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-menu-under-logo', $nectar_get_template_directory_uri . '/css/header/header-layout-centered-menu-under-logo.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-logo-between-menu', $nectar_get_template_directory_uri . '/css/header/header-layout-centered-logo-between-menu.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-logo-between-menu-alt', $nectar_get_template_directory_uri . '/css/header/header-layout-centered-logo-between-menu-alt.css', '', $nectar_theme_version );

		 wp_register_style( 'nectar-header-secondary-nav', $nectar_get_template_directory_uri . '/css/header/header-secondary-nav.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-perma-transparent', $nectar_get_template_directory_uri . '/css/header/header-perma-transparent.css', '', $nectar_theme_version );

		 wp_register_style( 'nectar-boxed', $nectar_get_template_directory_uri . '/css/plugins/boxed.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-single-styles', $nectar_get_template_directory_uri . '/css/single.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-widget-posts', $nectar_get_template_directory_uri . '/css/elements/widget-nectar-posts.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-search-results', $nectar_get_template_directory_uri . '/css/search.css', '', $nectar_theme_version );

		 // Elements.
		 wp_register_style( 'nectar-image-with-hotspots', $nectar_get_template_directory_uri . '/css/elements/element-image-with-hotspots.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-recent-posts', $nectar_get_template_directory_uri . '/css/elements/element-recent-posts.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-testimonial', $nectar_get_template_directory_uri . '/css/elements/element-testimonial.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-flip-box', $nectar_get_template_directory_uri . '/css/elements/element-flip-box.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-fancy-box', $nectar_get_template_directory_uri . '/css/elements/element-fancy-box.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-post-grid', $nectar_get_template_directory_uri . '/css/elements/element-post-grid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-category-grid', $nectar_get_template_directory_uri . '/css/elements/element-category-grid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-icon-list', $nectar_get_template_directory_uri . '/css/elements/element-icon-list.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-tabbed-section', $nectar_get_template_directory_uri . '/css/elements/element-tabbed-section.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-team-member', $nectar_get_template_directory_uri . '/css/elements/element-team-member.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-pricing-table', $nectar_get_template_directory_uri . '/css/elements/element-pricing-table.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-wpb-column-border-legacy', $nectar_get_template_directory_uri . '/css/elements/element-wpb-column-border-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-wpb-column-border', $nectar_get_template_directory_uri . '/css/elements/element-wpb-column-border.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-horizontal-list-item', $nectar_get_template_directory_uri . '/css/elements/element-horizontal-list-item.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-video-lightbox', $nectar_get_template_directory_uri . '/css/elements/element-video-lightbox.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-product-carousel', $nectar_get_template_directory_uri . '/css/elements/element-product-carousel.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-animated-title', $nectar_get_template_directory_uri . '/css/elements/element-animated-title.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-cascading-images', $nectar_get_template_directory_uri . '/css/elements/element-cascading-images.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-highlighted-text', $nectar_get_template_directory_uri . '/css/elements/element-highlighted-text.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-toggle-panels', $nectar_get_template_directory_uri . '/css/elements/element-toggles.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-scrolling-text', $nectar_get_template_directory_uri . '/css/elements/element-scrolling-text.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-interactive-map', $nectar_get_template_directory_uri . '/css/elements/element-interactive-map.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-clients', $nectar_get_template_directory_uri . '/css/elements/element-clients.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-fancy-unordered-list', $nectar_get_template_directory_uri . '/css/elements/element-fancy-unordered-list.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-milestone', $nectar_get_template_directory_uri . '/css/elements/element-milestone.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-morphing-outline', $nectar_get_template_directory_uri . '/css/elements/element-morphing-outline.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-page-submenu', $nectar_get_template_directory_uri . '/css/elements/element-page-submenu.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-parallax-image-grid', $nectar_get_template_directory_uri . '/css/elements/element-parallax-gallery-grid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-food-item', $nectar_get_template_directory_uri . '/css/elements/element-food-item.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-rotating-words-title', $nectar_get_template_directory_uri . '/css/elements/element-rotating-words-title.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-button-legacy', $nectar_get_template_directory_uri . '/css/elements/element-button-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-icon-with-text', $nectar_get_template_directory_uri . '/css/elements/element-icon-with-text.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-vc-icon', $nectar_get_template_directory_uri . '/css/elements/element-vc-icon-element.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-vc-separator', $nectar_get_template_directory_uri . '/css/elements/element-vc-separator.css', '', $nectar_theme_version );

		 wp_register_style( 'nectar-element-asset-reveal-animation', $nectar_get_template_directory_uri . '/css/elements/asset-reveal-animation.css', '', $nectar_theme_version );

		 // Blog.
		 wp_register_style( 'nectar-blog-masonry-classic-enhanced', $nectar_get_template_directory_uri . '/css/blog/masonry-classic-enhanced.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced', $nectar_get_template_directory_uri . '/css/blog/auto-masonry-meta-overlaid-spaced.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-blog-masonry-meta-overlaid', $nectar_get_template_directory_uri . '/css/blog/masonry-meta-overlaid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-blog-standard-minimal', $nectar_get_template_directory_uri . '/css/blog/standard-minimal.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-blog-standard-featured-left', $nectar_get_template_directory_uri . '/css/blog/standard-featured-left.css', '', $nectar_theme_version );

		 // Off canvas menu styles.
		 wp_register_style( 'nectar-ocm-core', $nectar_get_template_directory_uri . '/css/off-canvas/core.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-ocm-slide-out-right-hover', $nectar_get_template_directory_uri . '/css/off-canvas/slide-out-right-hover.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-ocm-fullscreen-legacy', $nectar_get_template_directory_uri . '/css/off-canvas/fullscreen-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-ocm-fullscreen-split', $nectar_get_template_directory_uri . '/css/off-canvas/fullscreen-split.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-ocm-simple', $nectar_get_template_directory_uri . '/css/off-canvas/simple-dropdown.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-ocm-slide-out-right-material', $nectar_get_template_directory_uri . '/css/off-canvas/slide-out-right-material.css', '', $nectar_theme_version );

		 // Third Party.
		 wp_register_style( 'twentytwenty', $nectar_get_template_directory_uri . '/css/plugins/twentytwenty.css' );
		 wp_register_style( 'magnific', $nectar_get_template_directory_uri . '/css/plugins/magnific.css', '', '8.6.0' );
		 wp_register_style( 'fancyBox', $nectar_get_template_directory_uri . '/css/plugins/jquery.fancybox.css', '', '3.3.1' );
		 wp_register_style( 'box-roll', $nectar_get_template_directory_uri . '/css/plugins/box-roll.css', '', $nectar_theme_version);
		 wp_register_style( 'leaflet', $nectar_get_template_directory_uri . '/css/plugins/leaflet.css', '1.3.1' );
		 wp_register_style( 'nectar-flickity', $nectar_get_template_directory_uri . '/css/plugins/flickity.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-caroufredsel', $nectar_get_template_directory_uri . '/css/plugins/caroufredsel.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-owl-carousel', $nectar_get_template_directory_uri . '/css/plugins/owl-carousel.css', '', $nectar_theme_version );
		 wp_register_style( 'responsive', $nectar_get_template_directory_uri . '/css/responsive.css', '', $nectar_theme_version );
		 wp_register_style( 'select2', $nectar_get_template_directory_uri . '/css/plugins/select2.css', '', '6.2' );
		 wp_register_style( 'non-responsive', $nectar_get_template_directory_uri . '/css/non-responsive.css' );
		 wp_register_style( 'skin-original', $nectar_get_template_directory_uri . '/css/skin-original.css', '', $nectar_theme_version );
		 wp_register_style( 'skin-ascend', $nectar_get_template_directory_uri . '/css/ascend.css', '', $nectar_theme_version );
		 wp_register_style( 'skin-material', $nectar_get_template_directory_uri . '/css/skin-material.css', '', $nectar_theme_version );

		 global $post;

		 if ( ! is_object( $post ) ) {
 			$post = (object) array(
 				'post_content' => ' ',
 				'ID'           => ' ',
 			);
 		}


		// Boxed style.
	 	$nectar_using_boxed = ( ! empty( $nectar_options['boxed_layout'] ) ) ? $nectar_options['boxed_layout'] : 'off';
	 	if( $nectar_using_boxed === '1') {
	 		wp_enqueue_style( 'nectar-boxed' );
	 	}

	 wp_enqueue_style( 'font-awesome' );

	 // Grid system.
	 if( function_exists('nectar_use_flexbox_grid') && true === nectar_use_flexbox_grid() ) {
		 /* Salient provides a modern flexbox grid system as of v10.6 as long
		 as the Salient core and Salient page builder plugins are up to date. */
		 $nectar_modern_grid_compat = true;
	 } else {
		 $nectar_modern_grid_compat = false;
	 }

	 if( true === $nectar_modern_grid_compat ) {
		 wp_enqueue_style( 'salient-grid-system' );
	 } else {
		 wp_enqueue_style( 'salient-grid-system-legacy' );
	 }

	 // Main Salient styles.
	 wp_enqueue_style( 'main-styles' );

	 // Header layouts.
	 $header_format = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';

	 if( $header_format === 'left-header' ) {
		 wp_enqueue_style( 'nectar-header-layout-left' );
	 }
	 else if( $header_format === 'centered-menu-bottom-bar' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-bottom-bar' );
	 }
	 else if ( $header_format === 'centered-menu-under-logo' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-menu-under-logo' );
	 }
	 else if ( $header_format === 'centered-menu' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-menu' );
	 }
	 else if( $header_format === 'centered-logo-between-menu' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-logo-between-menu' );
	 }
	 else if( $header_format === 'centered-logo-between-menu-alt' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-logo-between-menu-alt' );
	 }

	// Secondary navigation bar.
	$header_secondary_format = ( ! empty( $nectar_options['header_layout'] ) ) ? $nectar_options['header_layout'] : 'standard';
  if( $header_secondary_format === 'header_with_secondary') {
	  wp_enqueue_style( 'nectar-header-secondary-nav' );
  }

	 // Permanent transparent navigation option.
	 $header_trans = ( ! empty( $nectar_options['transparent-header'] ) ) ? $nectar_options['transparent-header'] : '0';
	 $header_perma_trans = ( ! empty( $nectar_options['header-permanent-transparent'] ) ) ? $nectar_options['header-permanent-transparent'] : '0';

	 if( $header_trans === '1' && $header_perma_trans === '1' ) {
		 wp_enqueue_style( 'nectar-header-perma-transparent' );
	 }


	 // Single posts.
	 if( is_single() && !is_singular( 'product' ) ) {
		 wp_enqueue_style( 'nectar-single-styles' );
	 }

	 // Testimonials.
	 if ( NectarElAssets::locate(array('[testimonial_slider','[nectar_single_testimonial')) ) {
		 wp_enqueue_style( 'nectar-element-testimonial' );
	 }

	 // Image with hotspots.
	 if ( NectarElAssets::locate(array('[nectar_image_with_hotspots')) ) {
		 wp_enqueue_style( 'nectar-image-with-hotspots' );
	 }

	 // Fancy box.
	 if ( NectarElAssets::locate(array('[fancy_box')) ) {
		 wp_enqueue_style( 'nectar-element-fancy-box' );
	 }

	 // Flip box.
	 if ( NectarElAssets::locate(array('[nectar_flip_box')) ) {
		 wp_enqueue_style( 'nectar-element-flip-box' );
	 }

	 // Scrolling Text.
	 if ( NectarElAssets::locate(array('[nectar_scrolling_text')) ) {
		 wp_enqueue_style( 'nectar-element-scrolling-text' );
	 }

	 // Animated Title.
	 if ( NectarElAssets::locate(array('[nectar_animated_title')) ) {
		 wp_enqueue_style( 'nectar-element-animated-title' );
	 }

	 // Highlighted Text.
	 if ( NectarElAssets::locate(array('[nectar_highlighted_text')) ) {
		 wp_enqueue_style( 'nectar-element-highlighted-text' );
	 }

	 // Post grid.
	 if ( NectarElAssets::locate(array('[nectar_post_grid')) ) {
		 wp_enqueue_style( 'nectar-element-post-grid' );
	 }

	 // Category grid.
	 if ( NectarElAssets::locate(array('[nectar_category_grid')) ) {
		 wp_enqueue_style( 'nectar-element-category-grid' );
	 }

	 // Icon list.
	 if ( NectarElAssets::locate(array('[nectar_icon_list')) ) {
		 wp_enqueue_style( 'nectar-element-icon-list' );
	 }

	 // Tabbed section.
	 if ( NectarElAssets::locate(array('[tabbed_section')) ) {
		 wp_enqueue_style( 'nectar-element-tabbed-section' );
	 }

	 // Team member.
	 if ( NectarElAssets::locate(array('[team_member')) ) {
		 wp_enqueue_style( 'nectar-element-team-member' );
	 }

	 // Pricing table.
	 if ( NectarElAssets::locate(array('[pricing_table')) ) {
		 wp_enqueue_style( 'nectar-element-pricing-table' );
	 }

	 // Horizontal List Item.
	 if ( NectarElAssets::locate(array('[nectar_horizontal_list_item')) ) {
		 wp_enqueue_style( 'nectar-element-horizontal-list-item' );
	 }

	 // Cascading Images.
	 if( NectarElAssets::locate(array('[nectar_cascading_images')) ) {
		 wp_enqueue_style( 'nectar-element-cascading-images' );
	 }

	 // Toggle Panels.
	 if( NectarElAssets::locate(array('[toggle')) ) {
		 wp_enqueue_style( 'nectar-element-toggle-panels' );
	 }

	 // Video Lightbox.
	 if ( NectarElAssets::locate(array('[nectar_video_lightbox')) ) {
		 wp_enqueue_style( 'nectar-element-video-lightbox' );
	 }

	 // Interactive Map.
	 if( NectarElAssets::locate(array('[nectar_gmap')) || is_page_template( 'template-contact.php' ) ) {
		 wp_enqueue_style( 'nectar-element-interactive-map' );
	 }

	 // Clients.
	 if( NectarElAssets::locate(array('[clients')) ) {
		 wp_enqueue_style( 'nectar-element-clients' );
	 }

	 // Fancy UL.
	 if( NectarElAssets::locate(array('[fancy-ul')) ) {
		 wp_enqueue_style( 'nectar-element-fancy-unordered-list' );
	 }

	 // Food Item.
	 if( NectarElAssets::locate(array('[nectar_food_menu')) ) {
		 wp_enqueue_style( 'nectar-element-food-item' );
	 }

	 // Milestone.
	 if( NectarElAssets::locate(array('[milestone')) ) {
		 wp_enqueue_style( 'nectar-element-milestone' );
	 }

	 // Morphing Outline.
	 if( NectarElAssets::locate(array('[morphing_outline')) ) {
		 wp_enqueue_style( 'nectar-element-morphing-outline' );
	 }

	 // Page Submenu.
	 if( NectarElAssets::locate(array('[nectar_rotating_words_title')) ) {
		wp_enqueue_style( 'nectar-element-rotating-words-title' );
	}

	 // Page Submenu.
   if( NectarElAssets::locate(array('[page_submenu')) ) {
		 wp_enqueue_style( 'nectar-element-page-submenu' );
	 }

	 // Owl Carousel.
	 if( NectarElAssets::locate(array('owl_carousel')) ) {
		 wp_enqueue_style( 'nectar-owl-carousel' );
	 }

	 // VC Separator.
	 if( NectarElAssets::locate(array('[vc_text_separator', '[vc_zigzag')) ) {
		 wp_enqueue_style( 'nectar-element-vc-separator' );
	 }
	 // VC Icon.
	 if( NectarElAssets::locate(array('[vc_text_separator','[vc_text_icon','[vc_btn','[vc_icon_element')) ) {
		 wp_enqueue_style( 'nectar-element-vc-icon' );
	 }

	 // Text with icon.
	 if( NectarElAssets::locate(array('[text-with-icon')) ) {
		 wp_enqueue_style( 'nectar-element-icon-with-text' );
	 }

	 // Caroufredsel.
	 if( NectarElAssets::locate(array('[recent_projects', '[carousel easing', '[carousel auto', 'carouFredSel', 'carousel="true"')) || is_page_template( 'template-home-1.php' )  ) {
		 wp_enqueue_style( 'nectar-caroufredsel' );
	 }

	 // Image grid - parallax style.
	 if( NectarElAssets::locate(array('[vc_gallery')) && NectarElAssets::locate(array('parallax_image_grid')) ) {
		 wp_enqueue_style( 'nectar-element-parallax-image-grid' );
	 }



	 // Product Carousel.
	 if ( NectarElAssets::locate(array('[nectar_woo_products')) ) {
		 if( NectarElAssets::locate(array('carousel="1"')) || NectarElAssets::locate(array("carousel='1'")) ) {
			 wp_enqueue_style( 'nectar-element-product-carousel' );
		 }
	 }

	 // Reveal Animation.
	 if( NectarElAssets::locate(array('ro-reveal-from')) ) {
		  wp_enqueue_style( 'nectar-element-asset-reveal-animation' );
	 }

	 // Legacy Button.
	 $theme_skin = NectarThemeManager::$skin;

	 if( 'material' === $theme_skin && NectarElAssets::locate(array('[button')) ) {
		 wp_enqueue_style( 'nectar-element-button-legacy' );
	 }


	 // Column border.
	 if ( NectarElAssets::locate(array('column_border_color="#','column_border_color="rgb')) ) {

		 if( true === $nectar_modern_grid_compat ) {
			 wp_enqueue_style( 'nectar-element-wpb-column-border' );
		 } else {
			 wp_enqueue_style( 'nectar-element-wpb-column-border-legacy' );
		 }

	 }

	 // Recent posts.
	 if ( NectarElAssets::locate(array('[recent_posts')) ||
		 is_page_template( 'template-home-2.php' ) ||
		 is_page_template( 'template-home-3.php' ) ) {
		 wp_enqueue_style( 'nectar-element-recent-posts' );
	 }


	 // Single post using related posts.
	 $nectar_using_related_posts = ( ! empty( $nectar_options['blog_related_posts'] ) ) ? $nectar_options['blog_related_posts'] : 'off';
	 if( is_single() && $nectar_using_related_posts === '1') {
		 wp_enqueue_style( 'nectar-element-recent-posts' );
	 }


	if( class_exists( 'bbPress' ) ) {
		wp_enqueue_style( 'nectar-basic-bbpress', $nectar_get_template_directory_uri . '/css/third-party/bbpress.css', '', $nectar_theme_version );
	}
	if( class_exists( 'BuddyPress' ) ) {
		wp_enqueue_style( 'nectar-basic-buddypress', $nectar_get_template_directory_uri . '/css/third-party/buddypress.css', '', $nectar_theme_version );
	}
	if( class_exists( 'Tribe__Main' ) ) {
		wp_enqueue_style( 'nectar-basic-events-calendar', $nectar_get_template_directory_uri . '/css/third-party/events-calendar.css', '', $nectar_theme_version );
	}

	// Icons
	if ( NectarElAssets::locate(array('steadysets-')) ) {
		wp_enqueue_style('nectar-steadysets');
	}
	if( NectarElAssets::locate(array('linecon')) ) {
		wp_enqueue_style('nectar-linecon');
	}



	// Default Salient font (Open Sans).
	$nectar_default_font = ( ! empty( $nectar_options['default-theme-font'] ) ) ? $nectar_options['default-theme-font'] : 'from_google';

	if( 'from_google' === $nectar_default_font ) {
		// Load from Google.

		$display_swap_str = '';

		if( isset($nectar_options['typography_font_swap']) && !empty($nectar_options['typography_font_swap']) && '1' === $nectar_options['typography_font_swap'] ) {
			$display_swap_str = '&display=swap';
		}

		wp_enqueue_style( 'nectar_default_font_open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans%3A300%2C400%2C600%2C700&subset=latin%2Clatin-ext'.$display_swap_str, false, null, 'all' );
	} else if( 'from_theme' === $nectar_default_font ) {
		// Load locally.
		$nectar_default_font_css = "
		@font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-Light.woff') format('woff');
		     font-weight:300;
		     font-style:normal
		}
		 @font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-Regular.woff') format('woff');
		     font-weight:400;
		     font-style:normal
		}
		 @font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-SemiBold.woff') format('woff');
		     font-weight:600;
		     font-style:normal
		}
		 @font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-Bold.woff') format('woff');
		     font-weight:700;
		     font-style:normal
		}";

		wp_add_inline_style( 'main-styles', $nectar_default_font_css );
	}


	// Front end editor needs all.
	if ( $nectar_using_VC_front_end_editor ) {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_style( 'iconsmind' );
		wp_enqueue_style( 'fullpage' );
		wp_enqueue_style( 'nectar-slider' );
		wp_enqueue_style( 'nectar-portfolio' );
		wp_enqueue_style( 'nectar-flickity' );
		wp_enqueue_style( 'nectar-owl-carousel' );
		wp_enqueue_style( 'nectar-caroufredsel' );
		wp_enqueue_style( 'twentytwenty' );
		wp_enqueue_style( 'linea' );
		wp_enqueue_style( 'leaflet' );
		wp_enqueue_style( 'nectar-element-recent-posts' );
		wp_enqueue_style( 'nectar-header-layout-left' );
	  wp_enqueue_style( 'nectar-single-styles' );
    wp_enqueue_style( 'nectar-element-testimonial' );
		wp_enqueue_style( 'nectar-image-with-hotspots' );
		wp_enqueue_style( 'nectar-element-fancy-box' );
		wp_enqueue_style( 'nectar-element-flip-box' );
		wp_enqueue_style( 'nectar-element-category-grid' );
		wp_enqueue_style( 'nectar-element-post-grid' );
		wp_enqueue_style( 'nectar-element-icon-list' );
		wp_enqueue_style( 'nectar-element-tabbed-section' );
		wp_enqueue_style( 'nectar-element-team-member' );
		wp_enqueue_style( 'nectar-element-pricing-table' );
    wp_enqueue_style( 'nectar-element-wpb-column-border' );
		wp_enqueue_style( 'nectar-element-horizontal-list-item' );
		wp_enqueue_style( 'nectar-element-video-lightbox' );
		wp_enqueue_style( 'nectar-element-product-carousel' );
		wp_enqueue_style( 'nectar-element-animated-title' );
		wp_enqueue_style( 'nectar-element-cascading-images' );
		wp_enqueue_style( 'nectar-element-highlighted-text' );
		wp_enqueue_style( 'nectar-element-toggle-panels' );
		wp_enqueue_style( 'nectar-element-scrolling-text' );
		wp_enqueue_style( 'nectar-element-interactive-map' );
		wp_enqueue_style( 'nectar-element-clients' );
		wp_enqueue_style( 'nectar-element-fancy-unordered-list' );
		wp_enqueue_style( 'nectar-element-milestone' );
		wp_enqueue_style( 'nectar-element-morphing-outline' );
		wp_enqueue_style( 'nectar-element-page-submenu' );
		wp_enqueue_style( 'nectar-element-parallax-image-grid' );
		wp_enqueue_style( 'nectar-element-food-item' );
		wp_enqueue_style( 'nectar-element-rotating-words-title' );
		wp_enqueue_style( 'nectar-element-vc-separator' );
		wp_enqueue_style( 'nectar-element-vc-icon' );
		wp_enqueue_style( 'nectar-element-asset-reveal-animation' );
		wp_enqueue_style( 'nectar-element-icon-with-text' );
		wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
		wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
		wp_enqueue_style( 'nectar-blog-standard-minimal' );
		wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
		wp_enqueue_style( 'nectar-blog-standard-featured-left' );


	}

	// Remove WP CSS.
	if( isset($nectar_options['rm-block-editor-css']) &&
			!empty($nectar_options['rm-block-editor-css']) &&
			'1' === $nectar_options['rm-block-editor-css'] ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wc-block-style' );
	}


}

add_action( 'wp_enqueue_scripts', 'nectar_main_styles' );



/**
 * Page specific frontend CSS.
 *
 * @since 1.0
 */
function nectar_page_sepcific_styles() {

	global $post;
	global $nectar_options;

	if ( ! is_object( $post ) ) {
		$post = (object) array(
			'post_content' => ' ',
			'ID'           => ' ',
		);
	}

	// Home templates.
	if ( is_page_template( 'template-home-1.php' ) ||
	is_page_template( 'template-home-2.php' ) ||
	is_page_template( 'template-home-3.php' ) ||
	is_page_template( 'template-home-4.php' ) ) {
		wp_enqueue_style( 'orbit' );
	}

	// Full page option.
	$page_full_screen_rows = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows', true ) : '';
	if ( $page_full_screen_rows === 'on' ) {
		wp_enqueue_style( 'fullpage' );
	}

	// Nectar slider.
	if ( NectarElAssets::locate(array('[nectar_slider','type="nectarslider_style"')) ) {
		wp_enqueue_style( 'nectar-slider' );
	}

	// Portfolio.
	if ( NectarElAssets::locate(array('nectar_portfolio','recent_projects', 'type="image_grid"')) ||
	   is_page_template( 'template-portfolio.php' ) ||
		 is_post_type_archive( 'portfolio' ) ||
		 is_singular( 'portfolio' ) ||
		 is_tax( 'project-attributes' ) ||
		 is_tax( 'project-type' ) ) {
			 wp_enqueue_style( 'nectar-portfolio' );
	}

	// Blog std style containing image gallery grid - non archive.
	if ( NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('layout="std-blog-')) && NectarElAssets::locate(array('blog_standard_style="classic')) ||
		NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('layout="std-blog-')) && NectarElAssets::locate(array('blog_standard_style="minimal')) ) {
		wp_enqueue_style( 'nectar-portfolio' );
	}

	// Blog styles - page builder element.
	$nectar_using_related_posts = ( ! empty( $nectar_options['blog_related_posts'] ) ) ? $nectar_options['blog_related_posts'] : 'off';
	$nectar_related_posts_style = ( isset( $nectar_options['blog_related_posts_style'] ) && ! empty( $nectar_options['blog_related_posts_style'] ) ) ? $nectar_options['blog_related_posts_style'] : 'material';

	$posttype                     = get_post_type( $post );
	$nectar_on_blog_archive_check = ( is_archive() || is_author() || is_category() || is_home() || is_tag() );
	$nectar_blog_type             = ( ! empty( $nectar_options['blog_type'] ) ) ? $nectar_options['blog_type'] : 'masonry-blog-fullwidth';
	$nectar_blog_std_style        = ( ! empty( $nectar_options['blog_standard_type'] ) ) ? $nectar_options['blog_standard_type'] : 'featured_img_left';
	$nectar_blog_masonry_style    = ( ! empty( $nectar_options['blog_masonry_type'] ) ) ? $nectar_options['blog_masonry_type'] : 'auto_meta_overlaid_spaced';


	// Page builder elements.
	if( NectarElAssets::locate(array('[nectar_blog')) ||
      NectarElAssets::locate(array('[recent_posts')) ) {

				//// Classic enhanced.
				if( NectarElAssets::locate(array('classic_enhanced')) ) {
					wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
				}

				//// Meta Overlaid.
				if( NectarElAssets::locate(array('meta_overlaid')) ) {
					wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
				}

				//// Auto Masonry Meta Overlaid.
				if( NectarElAssets::locate(array('auto_meta_overlaid_spaced')) ) {
					wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
				}

				if( NectarElAssets::locate(array('standard_style="minimal"')) ) {
					wp_enqueue_style( 'nectar-blog-standard-minimal' );
				}

				if( NectarElAssets::locate(array('featured_img_left')) ) {
					wp_enqueue_style( 'nectar-blog-standard-featured-left' );
				}

				// Inherit from theme options.
				if( NectarElAssets::locate(array('blog_standard_style="inherit"')) || NectarElAssets::locate(array('blog_masonry_style="inherit"')) ) {

					if( 'classic_enhanced' === $nectar_blog_masonry_style ) {
						wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
					}
					if( 'meta_overlaid' === $nectar_blog_masonry_style ) {
						wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
					}
					if( 'auto_meta_overlaid_spaced' === $nectar_blog_masonry_style ) {
						wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
					}

					if( 'minimal' === $nectar_blog_std_style ) {
						wp_enqueue_style( 'nectar-blog-standard-minimal' );
					}
					if( 'featured_img_left' === $nectar_blog_std_style ) {
						wp_enqueue_style( 'nectar-blog-standard-featured-left' );
					}

				}

	} // End page builder elements.

	// Archives.
	if( $nectar_on_blog_archive_check ) {

		//// Masonry Styles.
		if( $nectar_blog_type === 'masonry-blog-sidebar' ||
		    $nectar_blog_type === 'masonry-blog-fullwidth' ||
	      $nectar_blog_type === 'masonry-blog-full-screen-width' ) {

				//// Classic enhanced.
				if( 'classic_enhanced' === $nectar_blog_masonry_style ) {
					wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
				}

				//// Meta Overlaid.
				if( 'meta_overlaid' === $nectar_blog_masonry_style ) {
					wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
				}

				//// Auto Masonry Meta Overlaid.
				if( 'auto_meta_overlaid_spaced' === $nectar_blog_masonry_style ) {
					wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
				}

		}
		//// Standard Styles.
		else {

			if( 'minimal' === $nectar_blog_std_style ) {
				wp_enqueue_style( 'nectar-blog-standard-minimal' );
			}
			if( 'featured_img_left' === $nectar_blog_std_style ) {
				wp_enqueue_style( 'nectar-blog-standard-featured-left' );
			}

		}

	} // End archive check.

	//// Related Posts
	if( '1' === $nectar_using_related_posts && is_single() ) {

		if( 'classic_enhanced' === $nectar_related_posts_style ) {
			wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
		}

	}

	// Blog std style containing image gallery grid - archive.
	if ( $nectar_on_blog_archive_check ) {

		if ( $nectar_blog_type === 'std-blog-sidebar' || $nectar_blog_type === 'std-blog-fullwidth' ) {
			//// Standard styles that could contain gallery sliders.
			if ( $nectar_blog_std_style === 'classic' || $nectar_blog_std_style === 'minimal' ) {
				 wp_enqueue_style( 'nectar-flickity' );
				 wp_enqueue_style( 'nectar-portfolio' );
			}
		}
	}


	// Responsive.
	if ( ! empty( $nectar_options['responsive'] ) && $nectar_options['responsive'] == 1 ) {
		wp_enqueue_style( 'responsive' );
	} else {
		wp_enqueue_style( 'non-responsive' );

		add_filter( 'body_class', 'salient_non_responsive' );
		function salient_non_responsive( $classes ) {

				$classes[] = 'salient_non_responsive';

				return $classes;
		}
	}


	// WooCommerce.
	if ( function_exists( 'is_woocommerce' ) ) {

		// Product styles
		if( isset($nectar_options['product_style']) && 'classic' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-classic' );
		}
		else if( isset($nectar_options['product_style']) && 'text_on_hover' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-text-on-hover' );
		}
		else if( isset($nectar_options['product_style']) && 'material' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-material' );
		}
		else if( isset($nectar_options['product_style']) && 'minimal' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-minimal' );
		}
		else {
			wp_enqueue_style( 'nectar-product-style-classic' );
		}

		wp_enqueue_style( 'woocommerce' );
		if( is_product() ) {
			wp_enqueue_style( 'nectar-woocommerce-single' );
			if( isset( $nectar_options['single_product_related_upsell_carousel'] ) && '1' === $nectar_options['single_product_related_upsell_carousel'] ) {
				wp_enqueue_style( 'nectar-element-product-carousel' );
				wp_enqueue_style( 'nectar-flickity' );
			}
		}

	}

	// Gradient linea icons.
	if ( NectarElAssets::locate(array('.svg')) && NectarElAssets::locate(array('Extra-Color-Gradient')) ) {
		wp_enqueue_style( 'linea' );
	}


	// Flickity.
	$nectar_flickity_els = array(
		'[vc_gallery type="flickity"',
		'[vc_gallery type="flickity_style"',
		'style="multiple_visible"',
		'style="slider_multiple_visible"',
		'script="flickity"',
		'script="simple_slider"',
		'style="multiple_visible_minimal"',
		'style="slider"',
	);

	if ( NectarElAssets::locate($nectar_flickity_els) ) {
		wp_enqueue_style( 'nectar-flickity' );
	}


	$fancy_rcs = ( ! empty( $nectar_options['form-fancy-select'] ) ) ? $nectar_options['form-fancy-select'] : 'default';
	if ( $fancy_rcs === '1' ) {
		wp_enqueue_style( 'select2' );
	}


	// Portfolio template inline styles.
	if( is_page_template( 'template-portfolio.php' ) ) {

		$nectar_portfolio_archive_layout = ( !empty($nectar_options['main_portfolio_layout']) ) ? $nectar_options['main_portfolio_layout'] : '3';
		$nectar_inline_filters   				 = ( ! empty( $nectar_options['portfolio_inline_filters'] ) && $nectar_options['portfolio_inline_filters'] === '1' ) ? '1' : '0';
		$nectar_portfolio_archive_bg     = get_post_meta( $post->ID, '_nectar_header_bg', true );

		$nectar_portfolio_css = '.page-template-template-portfolio-php .row .col.section-title h1{
		  margin-bottom:0
		}';

		if( $nectar_portfolio_archive_layout === 'fullwidth' ) {
			$nectar_portfolio_css .= '.container-wrap { padding-bottom: 0px!important; } #call-to-action .triangle { display: none; }';
		}

		if( $nectar_portfolio_archive_layout === 'fullwidth' && !empty($nectar_portfolio_archive_bg) ) {
			$nectar_portfolio_css .= '.container-wrap { padding-top: 0px!important; }';
		}

		if( $nectar_inline_filters === '1' && empty($nectar_portfolio_archive_bg) ) {
			$nectar_portfolio_css .= '.page-header-no-bg { display: none; }
			.container-wrap { padding-top: 0px!important; }
			body #portfolio-filters-inline { margin-top: -50px!important; padding-top: 5.8em!important; }';
		}

		if( $nectar_inline_filters === '1' && empty($nectar_portfolio_archive_bg) && $nectar_portfolio_archive_layout != 'fullwidth') {
			$nectar_portfolio_css .= '#portfolio-filters-inline.non-fw { margin-top: -37px!important; padding-top: 6.5em!important;}';
		}

		if( $nectar_inline_filters === '1' && !empty($nectar_portfolio_archive_bg) && $nectar_portfolio_archive_layout != 'fullwidth') {
			$nectar_portfolio_css .= '.container-wrap { padding-top: 3px!important; }';
		}

		wp_add_inline_style( 'main-styles', $nectar_portfolio_css );

	}

	// Search template inline styles.
	if( is_search() ) {

		wp_enqueue_style( 'nectar-search-results' );

		$search_results_header_bg_color   = ( ! empty( $nectar_options['search-results-header-bg-color'] ) ) ? $nectar_options['search-results-header-bg-color'] : '#f4f4f4';
		$search_results_header_font_color = ( ! empty( $nectar_options['search-results-header-font-color'] ) ) ? $nectar_options['search-results-header-font-color'] : '#000000';

		$nectar_search_css = '
		body:not(.archive) #page-header-bg {
			background-color: '.$search_results_header_bg_color.';
		}
		body:not(.archive) #page-header-bg h1, #page-header-bg .result-num {
			color: '.$search_results_header_font_color.';
		}
		';

		wp_add_inline_style( 'main-styles', $nectar_search_css );
	}

	// 404 template inline styles.
	if( is_404() ) {

		$page_404_bg_color               = ( ! empty( $nectar_options['page-404-bg-color'] ) ) ? $nectar_options['page-404-bg-color'] : '';
		$page_404_font_color             = ( ! empty( $nectar_options['page-404-font-color'] ) ) ? $nectar_options['page-404-font-color'] : '';
		$page_404_bg_image_overlay_color = ( ! empty( $nectar_options['page-404-bg-image-overlay-color'] ) ) ? $nectar_options['page-404-bg-image-overlay-color'] : '';

		$nectar_404_css = '
		#error-404{
		  text-align:center;
		  padding: 10% 0;
		  position: relative;
		  z-index: 10;
		}
		body.error {
		  padding: 0;
		}
		body #error-404[data-cc="true"] h1,
		body #error-404[data-cc="true"] h2,
		body #error-404[data-cc="true"] p {
		  color: inherit;
		}
		body.error404 .error-404-bg-img,
		body.error404 .error-404-bg-img-overlay {
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  background-size: cover;
		  background-position: 50%;
		  z-index: 1;
		}
		body.error404 .error-404-bg-img-overlay {
		  opacity: 0.8;
		}
		body #error-404 h1,
		body #error-404 h2 {
		  font-family: "Open Sans";
		  font-weight:700
		}
		body #ajax-content-wrap #error-404 h1 {
		  font-size:250px;
		  line-height:250px;
		}
		body #ajax-content-wrap #error-404 h2 {
		  font-size:54px;
		}
		body #error-404 .nectar-button {
		  margin-top: 50px;
		}

		@media only screen and (max-width : 690px) {

			body .row #error-404 h1,
			body #ajax-content-wrap #error-404 h1 {
				font-size: 150px;
				line-height: 150px;
			}

			body #ajax-content-wrap #error-404 h2 {
				font-size: 32px;
			}

			body .row #error-404 {
				margin-bottom: 0;
			}
		}
		';

		if ( ! empty( $page_404_bg_color ) ) {
			$nectar_404_css .= 'html .error404 .container-wrap {
				background-color: '.$page_404_bg_color.';
			}';
		}

		if ( ! empty( $page_404_bg_image_overlay_color ) ) {
			$nectar_404_css .= '.error404 .error-404-bg-img-overlay {
				background-color: '. $page_404_bg_image_overlay_color .';
			}';
		}
		if ( ! empty( $page_404_font_color ) ) {
			$nectar_404_css .= '.error404 #error-404,
			.error404 #error-404 h1,
			.error404 #error-404 h2 {
				color: '. $page_404_font_color .';
			}';
		}

		wp_add_inline_style( 'main-styles', $nectar_404_css );
	}


	// Lgeacy Dual Mobile Menu.
	$legacy_double_menu = nectar_legacy_mobile_double_menu();
	if( true === $legacy_double_menu ) {

		$nectar_dual_mobile_menu = '@media only screen and (max-width: 999px) and (min-width: 1px) {
			#header-outer[data-has-menu="true"] #top .span_3 .nectar-ocm-trigger-open {
				display: inline-block;
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
			}
			#header-outer[data-has-menu="true"] #top .span_3 {
				text-align: center!important;
			}
		}';

		wp_add_inline_style( 'main-styles', $nectar_dual_mobile_menu );
	}


}

add_action( 'wp_enqueue_scripts', 'nectar_page_sepcific_styles' );


if( !function_exists('nectar_preload_key_requests') ) {
	function nectar_preload_key_requests() {

		global $nectar_options;
		global $nectar_get_template_directory_uri;

		if( isset($nectar_options['typography_font_swap']) && '1' === $nectar_options['typography_font_swap'] ) {

			// Icomoon.
			echo '<link rel="preload" href="'.esc_attr($nectar_get_template_directory_uri) . '/css/fonts/icomoon.woff?v=1.1" as="font" type="font/woff" crossorigin="anonymous">';
		}
	}

}
add_action( 'wp_head', 'nectar_preload_key_requests', 5 );

/**
 * Non rendering blocking CSS.
 *
 * @since 1.0
 */
 if( !function_exists('nectar_deferred_styles') ) {
	function nectar_deferred_styles() {

		 global $nectar_options;

		 // WooCommerce.
		 if ( function_exists( 'is_woocommerce' ) ) {
			 wp_enqueue_style( 'nectar-woocommerce-non-critical');
		 }

		 // Lightbox.
		 $lightbox_script = ( ! empty( $nectar_options['lightbox_script'] ) ) ? $nectar_options['lightbox_script'] : 'magnific';
		 if ( $lightbox_script === 'pretty_photo' ) {
			 $lightbox_script = 'magnific';
		 }
		 if ( $lightbox_script === 'magnific' ) {
			 wp_enqueue_style( 'magnific' );
		 } elseif ( $lightbox_script === 'fancybox' ) {
			 wp_enqueue_style( 'fancyBox' );
		 }

		 // Off canvas menu.
		 wp_enqueue_style( 'nectar-ocm-core' );

	 	 $header_off_canvas_style = NectarThemeManager::$ocm_style;
		 $legacy_double_menu = nectar_legacy_mobile_double_menu();

	 	 if( $header_off_canvas_style === 'slide-out-from-right-hover' ) {
	 		 	wp_enqueue_style( 'nectar-ocm-slide-out-right-hover' );
	 	 }
	 	 else if( $header_off_canvas_style === 'fullscreen' ||
	 	          $header_off_canvas_style === 'fullscreen-alt' ) {
	 	 		wp_enqueue_style( 'nectar-ocm-fullscreen-legacy' );
	 	 }
	 	 else if( $header_off_canvas_style === 'fullscreen-split' ) {
	 		 wp_enqueue_style( 'nectar-ocm-fullscreen-split' );
	 	 }
		 else if( $header_off_canvas_style === 'slide-out-from-right' ) {
			 $theme_skin = NectarThemeManager::$skin;

			 if( 'material' === $theme_skin ) {
				 wp_enqueue_style('nectar-ocm-slide-out-right-material');
			 }

		 }

		 if( $header_off_canvas_style === 'simple' || true === $legacy_double_menu ) {
	 		 wp_enqueue_style( 'nectar-ocm-simple' );
	 	 }


	}
}
add_action( 'wp_footer', 'nectar_deferred_styles' );

/**
 * Assist in upgrading users with outdated child theme structure
 */
 if( !function_exists('salient_child_theme_enqueue_stylesheet') ) {

	function salient_child_theme_enqueue_stylesheet() {

		// Child stylesheet
		if( is_child_theme() ) {
			$nectar_theme_version = nectar_get_theme_version();
			wp_register_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
			wp_enqueue_style( 'salient-child-style' );
		}

	}
	add_action( 'wp_enqueue_scripts', 'salient_child_theme_enqueue_stylesheet', 90 );
}



/**
 * Allow users to disable default WP emojis
 *
 * @since 13.0
 */
 if ( ! function_exists( 'nectar_disable_emojis_dns_prefetch' ) ) {

 	function nectar_disable_emojis_dns_prefetch( $urls, $relation_type ) {

 		if ( 'dns-prefetch' === $relation_type ) {
 			foreach ( $urls as $key => $url ) {
 				if (  false !== strpos( $url, 'https://s.w.org/images/core/emoji' ) ) {
 					unset( $urls[$key] );
 				}
 			}
 		}

 		return $urls;
 	}

 }

if ( ! function_exists( 'nectar_disable_emojis' ) ) {
 function nectar_disable_emojis() {

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		add_filter( 'wp_resource_hints', 'nectar_disable_emojis_dns_prefetch', 10, 2 );

	}
}

global $nectar_options;
if( isset($nectar_options['rm-wp-emojis']) &&
		!empty($nectar_options['rm-wp-emojis']) &&
		'1' === $nectar_options['rm-wp-emojis'] ) {

		add_action( 'init', 'nectar_disable_emojis' );
}
