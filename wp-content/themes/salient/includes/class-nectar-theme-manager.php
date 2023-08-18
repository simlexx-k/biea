<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}


/**
* Nectar Theme Manager.
*/

if( !class_exists('NectarThemeManager') ) {

  class NectarThemeManager {

    private static $instance;
    
    public static $options                = '';
    public static $skin                   = '';
    public static $ocm_style              = '';
    public static $woo_product_filters    = false;
    public static $global_seciton_options = array(
      'global-section-after-header-navigation',
      'global-section-above-footer' 
    );
    
    private function __construct() {

      self::setup();

    }

    /**
     * Initiator.
     */
    public static function get_instance() {
      if ( !self::$instance ) {
        self::$instance = new self;
      }
      return self::$instance;
    }

    /**
     * Determines all theme settings
     * which are conditionally forced.
     */
    public static function setup() {
      
      self::$options = get_nectar_theme_options();

      // Theme Skin.
      $theme_skin        = ( isset(self::$options['theme-skin']) && !empty(self::$options['theme-skin']) ) ? self::$options['theme-skin'] : 'material';
      $header_format     = ( isset(self::$options['header_format']) ) ? self::$options['header_format'] : 'default';
      $search_enabled    = ( isset(self::$options['header-disable-search']) && '1' === self::$options['header-disable-search'] ) ? false : true;
      $ajax_search       = ( isset(self::$options['header-disable-ajax-search']) && '1' === self::$options['header-disable-ajax-search'] ) ? false : true;
      $ajax_search_style = ( isset(self::$options['header-ajax-search-style']) ) ? self::$options['header-ajax-search-style'] : 'default';
      
    	if( 'centered-menu-bottom-bar' === $header_format ) {
    		$theme_skin = 'material';
    	}
      if( true === $ajax_search && 'extended' === $ajax_search_style && true === $search_enabled ) {
    		$theme_skin = 'material';
    	}

      self::$skin = esc_html($theme_skin);
      
      
      // OCM style.
      $theme_ocm_style    = ( isset( self::$options['header-slide-out-widget-area-style'] ) && !empty( self::$options['header-slide-out-widget-area-style'] ) ) ? self::$options['header-slide-out-widget-area-style'] : 'slide-out-from-right';
      $legacy_double_menu = ( function_exists('nectar_legacy_mobile_double_menu') ) ? nectar_legacy_mobile_double_menu() : false;
      
      if( true === $legacy_double_menu && in_array($theme_ocm_style, array('slide-out-from-right-hover', 'simple')) ) {
         $theme_ocm_style = 'slide-out-from-right';
      }
      
      self::$ocm_style = esc_html($theme_ocm_style);
      
      
      // Woo filter area.
      $product_filter_trigger = ( isset( self::$options['product_filter_area']) && '1' === self::$options['product_filter_area'] ) ? true : false;
			$main_shop_layout       = ( isset( self::$options['main_shop_layout'] ) ) ? self::$options['main_shop_layout'] : 'no-sidebar';
			
			if( $main_shop_layout != 'right-sidebar' && $main_shop_layout != 'left-sidebar' ) {
				$product_filter_trigger = false;
			}
      
      self::$woo_product_filters = $product_filter_trigger;
			

    }


  }
  

  /**
	 * Initialize the NectarThemeManager class
	 */
	NectarThemeManager::get_instance();
}
