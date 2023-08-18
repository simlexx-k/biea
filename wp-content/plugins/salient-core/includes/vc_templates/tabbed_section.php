<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'nectar-element-tabbed-section' );

$output = $title = $interval = $el_class = '';
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
    'style' => 'default',
    'alignment' => 'left',
    'spacing' => '',
    'tab_color' => 'accent-color',
    'cta_button_text' => '',
    'cta_button_link' => '',
    'cta_button_style' => 'accent-color',
    'full_width_line' => '',
    'icon_size' => '24',
		'vs_navigation_width' => 'regular',
		'vs_navigation_spacing' => '15px',
		'vs_tab_spacing' => '10%',
		'vs_navigation_mobile_display' => 'visible',
		'vs_tab_tag' => 'p'
), $atts));


$nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;
$true_style = '';

// Can't use vs on front-end editor.
if( $nectar_using_VC_front_end_editor && 'vertical_scrolling' === $style ) {
	$style = 'vertical_modern';
	$true_style = 'data-stored-style="vs"';
}

$el_class = $this->getExtraClass($el_class);

$element = 'wpb_tabs';

if ( 'vc_tour' === $this->shortcode) {
  $element = 'wpb_tour';
}

if( $style === 'default' || $style === 'vertical' ) {
  $icon_size = '';
}



// Regular Tabbed
if( 'vertical_scrolling' !== $style ) {

	// Extract tab titles
	preg_match_all( '/tab[^]]+title="([^\"]+)"(\sid\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );

	$tab_titles = array();

	if ( isset($matches[0]) ) { $tab_titles = $matches[0]; }

	$tabs_nav = '';
	$tabs_nav .= '<ul class="wpb_tabs_nav ui-tabs-nav clearfix">';
	$tab_index = 0;
	foreach ( $tab_titles as $tab ) {
	    preg_match('/title="([^\"]+)"(\sid\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
	    if(isset($tab_matches[1][0])) {
				  $active_class = ( $tab_index === 0 ) ? 'class="active-tab"' : '';
	        $tabs_nav .= '<li><a href="#tab-'. (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .'" '.$active_class.'><span>' . $tab_matches[1][0] . '</span></a></li>';

	    }
			$tab_index++;
	}

	//cta button
	if(strlen($cta_button_text) >= 1) {
	     $tabs_nav .= '<li class="cta-button"><a class="nectar-button medium regular-button '.esc_attr($cta_button_style).'" data-color-override="false" href="'.esc_url($cta_button_link).'">' . wp_kses_post($cta_button_text) . '</a></li>';
	}

	$tabs_nav .= '</ul>'."\n";

	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim('wpb_content_element '.$el_class), $this->settings['base'], $atts);

	$output .= "\n\t".'<div class="'.$css_class.'"'.$true_style.' data-interval="'.$interval.'">';
	$output .= "\n\t\t".'<div class="wpb_wrapper tabbed clearfix" data-style="'.esc_attr($style).'" data-spacing="'.esc_attr($spacing).'" data-icon-size="'.esc_attr($icon_size).'" data-full-width-line="'.esc_attr($full_width_line).'" data-color-scheme="'.esc_attr(strtolower($tab_color)).'" data-alignment="'.esc_attr($alignment).'">';
	$output .= wpb_widget_title(array('title' => $title, 'extraclass' => $element.'_heading'));
	$output .= "\n\t\t\t".$tabs_nav;
	$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
	if ( 'vc_tour' == $this->shortcode) {
	    $output .= "\n\t\t\t" . '<div class="wpb_tour_next_prev_nav clearfix"> <span class="wpb_prev_slide"><a href="#prev" title="'.esc_html__('Previous slide', 'js_composer').'">'.esc_html__('Previous slide', 'salient-core').'</a></span> <span class="wpb_next_slide"><a href="#next" title="'.esc_html__('Next slide', 'salient-core').'">'.esc_html__('Next slide', 'salient-core').'</a></span></div>';
	}
	$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
	$output .= "\n\t".'</div> '.$this->endBlockComment($element);

}

// Vertical scrolling.
else {

	preg_match_all( '/\[tab[^]]+title="([^\"]+)"[^]]+/i', $content, $matches, PREG_OFFSET_CAPTURE );
	$tab_titles = array();

	if ( isset($matches[0]) ) { $tab_titles = $matches[0]; }

	$opening_tag = null;
	$closing_tag = null;

	$acceptable_tags = array('h6','h5','h4','h3','h2');

	if( !empty($vs_tab_tag) && in_array($vs_tab_tag, $acceptable_tags) ) {
		$opening_tag = '<' . $vs_tab_tag . '>';
		$closing_tag = '</' . $vs_tab_tag . '>';
	}

	$vs_navigation_escaped = '<div class="scrolling-tab-nav"><div class="line"></div><ul class="wpb_tabs_nav ui-tabs-nav" data-spacing="'.esc_attr($vs_navigation_spacing).'">';

	foreach ( $tab_titles as $tab ) {

	    preg_match('/title="([^\"]+)"(\sid\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
			preg_match('/sub_desc="([^\"]+)"/i', $tab[0], $tab_sub_desc_matches, PREG_OFFSET_CAPTURE );

	    if(isset($tab_matches[1][0])) {

					$tab_qid = uniqid("tab_");
					$sub_desc = ( isset($tab_sub_desc_matches[1]) ) ? $tab_sub_desc_matches[1] : false;

	        $vs_navigation_escaped .= '<li class="menu-item"><div class="menu-content">';
					$vs_navigation_escaped .= $opening_tag.'<a class="skip-hash" href="#'. esc_attr( $tab_qid ) .'"><span>' . $tab_matches[1][0] . '</span></a>'.$closing_tag;

					if( $sub_desc ) {
						$vs_navigation_escaped .= '<a class="sub-desc skip-hash" href="#'. esc_attr( $tab_qid ) .'">' . $sub_desc[0] . '</a>';
					}

					$vs_navigation_escaped .= '</div></li>';
	    }
	}

	$vs_navigation_escaped .= '</ul></div>';

	$extra_class = (!empty($el_class)) ? ' ' . $el_class : '';

	$output .= '<div class="nectar-scrolling-tabs'.esc_attr($extra_class).'" data-m-display="'.esc_attr($vs_navigation_mobile_display).'" data-nav-tag="'.esc_attr($vs_tab_tag).'" data-tab-spacing="'.esc_attr($vs_tab_spacing).'" data-navigation-width="'.esc_attr($vs_navigation_width).'" data-color-scheme="'.esc_attr(strtolower($tab_color)).'">';
	$output .= $vs_navigation_escaped;
	$output .= '<div class="scrolling-tab-content">' . wpb_js_remove_wpautop($content) . '</div>';
	$output .= '</div>';
}

echo $output;
