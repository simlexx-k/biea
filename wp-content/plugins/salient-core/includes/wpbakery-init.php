<?php
/**
 * Salient WPBakery page builder addons initialization
 *
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !defined( 'NECTAR_THEME_NAME' ) && !class_exists('NectarLazyImages') ) {
	require_once ( SALIENT_CORE_ROOT_DIR_PATH . 'includes/class-nectar-lazy.php' );
}

$nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

// Add Nectar Functionality to WPBakery page builder
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) && defined( 'SALIENT_VC_ACTIVE' ) ) {
	
	/**
	 * Add Nectar elements to WPBakery.
	 *
	 * @since 1.0
	 */
	if( !function_exists('add_nectar_to_vc') ) {
		function add_nectar_to_vc() {
			if ( version_compare( WPB_VC_VERSION, '4.9', '>=' ) ) {
				require_once ( SALIENT_CORE_ROOT_DIR_PATH . 'includes/nectar-addons.php' );
			} 
		}
	}

	add_action( 'init', 'add_nectar_to_vc', 5 );
	add_action( 'admin_enqueue_scripts', 'nectar_vc_styles' );
	
	add_action( 'wp_enqueue_scripts', 'nectar_core_wpbakery_styles' );
	
	if( $nectar_using_VC_front_end_editor ) {
		add_action( 'wp_enqueue_scripts', 'nectar_frontend_vc_styles' );
	}
	
	if( !class_exists('Salient_Portfolio') ) {
		add_action( 'wp_enqueue_scripts', 'salient_portfolio_fallback_assets' );
	}
	
	/**
	 * General frontend css.
	 *
	 * @since 1.8
	 */
	if( !function_exists('nectar_core_wpbakery_styles') ) {
		
		function nectar_core_wpbakery_styles() {
			
			global $Salient_Core;
			
			$enable_raw_wpbakery_post_grid = false;
			if( has_filter('salient_enable_core_wpbakery_post_grid') ) {
				$enable_raw_wpbakery_post_grid = apply_filters('salient_enable_core_wpbakery_post_grid', $enable_raw_wpbakery_post_grid);
			}
			
			if( true === $enable_raw_wpbakery_post_grid ) {
				wp_enqueue_style('nectar-wpbakery-core-grid', SALIENT_CORE_PLUGIN_PATH . '/css/wpbakery-core-grid-el.css','', $Salient_Core->plugin_version );
			}
			
		}
		
	}
	
	/**
	 * When the Salient Portfolio is not in use. Elements that rely on
	 * portfolio styles such as the image gallery will still need some assets.
	 *
	 * @since 1.0
	 */
	function salient_portfolio_fallback_assets() {
		
		global $Salient_Core;
		global $post;
		
		// When Salient is not in use.
		if( ! defined( 'NECTAR_THEME_NAME' ) ) {
			wp_register_script('isotope', SALIENT_CORE_PLUGIN_PATH . '/js/fallback/isotope.js','', $Salient_Core->plugin_version );
			wp_register_script('salient-portfolio-waypoints', SALIENT_CORE_PLUGIN_PATH . '/js/fallback/waypoints.js','', $Salient_Core->plugin_version );
		}
		
		// Salient Portfolio styles.
		wp_register_style('nectar-portfolio', SALIENT_CORE_PLUGIN_PATH . '/css/fallback/portfolio.css','', $Salient_Core->plugin_version );

		if ( ! is_object( $post ) ) {
			$post = (object) array(
				'post_content' => ' ',
				'ID'           => ' ',
			);
		}
		
		$post_content = ( isset( $post->post_content ) ) ? $post->post_content : '';
		
		if( strpos( $post_content, 'type="image_grid"' ) !== false ) {
			wp_enqueue_style('nectar-portfolio');
		}
		
		// Salient Portfolio scripts.
		wp_register_script('salient-portfolio-js', SALIENT_CORE_PLUGIN_PATH . '/js/fallback/salient-portfolio.js','', $Salient_Core->plugin_version );
		wp_localize_script('salient-portfolio-js','nectar_theme_info', array(
			'using_salient' => ( defined( 'NECTAR_THEME_NAME' ) ) ? 'true' : 'false'
		));
		
	}
	
	/**
	 * WPBakery Salient styles.
	 *
	 * Also enqueues Salient WPBalery js dependencies.
	 *
	 * @since 1.0
	 */
	if( !function_exists('nectar_vc_styles') ) { 
		
		function nectar_vc_styles() {
			global $Salient_Core;
			
			wp_enqueue_script( 'chosen', SALIENT_CORE_PLUGIN_PATH . '/includes/admin/assets/js/chosen/chosen.jquery.min.js', array(), $Salient_Core->plugin_version );
			
			wp_enqueue_style( 'chosen', SALIENT_CORE_PLUGIN_PATH . '/includes/admin/assets/css/chosen/chosen.css', array(), $Salient_Core->plugin_version, 'all'); 
			wp_enqueue_style( 'nectar-vc', SALIENT_CORE_PLUGIN_PATH . '/includes/nectar-addons.css', array(), $Salient_Core->plugin_version, 'all' );
			
			wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome-legacy.min.css'); 
			wp_enqueue_style('steadysets', get_template_directory_uri() . '/css/steadysets.css');
			wp_enqueue_style('linecon', get_template_directory_uri() . '/css/linecon.css');
			wp_enqueue_style('linea', get_template_directory_uri() . '/css/fonts/svg/font/style.css');
			wp_enqueue_style('iconsmind', get_template_directory_uri() . '/css/iconsmind.css');
			
			// Page Builder Deps.
			wp_enqueue_script( 'nectar-page-builder-edit', SALIENT_CORE_PLUGIN_PATH.'/includes/admin/assets/js/nectar-element-edit.js', array(), $Salient_Core->plugin_version);
			$translation_array = array(
				'alphabetical' => __('Alphabetical', 'salient-core'),
				'date'         => __('Date', 'salient-core'),
				'sortby'       => __('Sort By', 'salient-core')
			);
			wp_localize_script( 'nectar-page-builder-edit', 'nectar_translations', $translation_array);
			 
		}
	}
	
	/**
	 * WPBakery Salient frontend editor styles.
	 *
	 * @since 1.0
	 */
	if( !function_exists('nectar_frontend_vc_styles') ) {  
		function nectar_frontend_vc_styles() {
			global $Salient_Core;
			wp_enqueue_style( 'nectar-vc-frontend', SALIENT_CORE_PLUGIN_PATH . '/includes/nectar-addons-frontend.css', array(), $Salient_Core->plugin_version, 'all' );
		}
	}
	
	/**
	 * Salient Studio category list.
	 *
	 * @since 1.0
	 */
	 if( !function_exists('nectar_vc_library_cat_list') ) { 
		 
		function nectar_vc_library_cat_list() {
			return array(
				esc_html__( 'All', 'salient-core' )            => 'all',
				esc_html__( 'About', 'salient-core' )          => 'about',
				esc_html__( 'Blog', 'salient-core' )           => 'blog',
				esc_html__( 'Call To Action', 'salient-core' ) => 'cta',
				esc_html__( 'Counters', 'salient-core' )       => 'counters',
				esc_html__( 'General', 'salient-core' )        => 'general',
				esc_html__( 'Icons', 'salient-core' )          => 'icons',
				esc_html__( 'Hero Section', 'salient-core' )   => 'hero_section',
				esc_html__( 'Map', 'salient-core' )            => 'map',
				esc_html__( 'Project', 'salient-core' )        => 'portfolio',
				esc_html__( 'Pricing', 'salient-core' )        => 'pricing',
				esc_html__( 'Services', 'salient-core' )       => 'services',
				esc_html__( 'Team', 'salient-core' )           => 'team',
				esc_html__( 'Testimonials', 'salient-core' )   => 'testimonials',
				esc_html__( 'Shop', 'salient-core' )           => 'shop'
			);
		}
		
	}

	if ( ! function_exists( 'add_salient_studio_to_vc' ) ) {
		function add_salient_studio_to_vc() {
				require_once ( SALIENT_CORE_ROOT_DIR_PATH. 'includes/salient-studio-templates.php' );
		}
	}

	add_salient_studio_to_vc();

} 

// Not using Salient WPBakery Notice.
else if( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
	require_once ( SALIENT_CORE_ROOT_DIR_PATH . 'includes/salient-notice/notice.php' );
}