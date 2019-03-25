<?php
/*
Plugin Name: Beaufairy Tabs
Plugin URI: http://leetdigital.com
Description: Create tabs using shortcodes.
Version: 1.0
Author: Jedi
License: none
*/

if ( ! function_exists( 'beaufairy_tabs_wp_init' ) ) {

    /**
     * Add Bootstrap tab functionality
     * http://virtusdesigns.net/wp-bootstrap-tabs/
     *
     * @return void
     */
    function beaufairy_tabs_wp_init() {
        global $beaufairy_tabs_count, $beaufairy_tabs_tab_count, $beaufairy_tabs_content;
                
        $beaufairy_tabs_count = 0;
        $beaufairy_tabs_tab_count = 0;
        $beaufairy_tabs_content = array();
    }

    add_action( 'wp', 'beaufairy_tabs_wp_init' );
}

if ( ! function_exists( 'beaufairy_tabs_tab_shortcode' ) ) {

    /**
     * Shortcode callback for Beaufairy tabs
     *
     * @param  array  $atts
     * @param  string  $content
     * @return void
     */
    function beaufairy_tabs_tab_shortcode( $atts, $content ) {

        extract( shortcode_atts( array(
            'name' => 'Tab Name',
            'link' => '',
            'active' => '',
        ), $atts ) );
      
        global $beaufairy_tabs_content, $beaufairy_tabs_tab_count, $beaufairy_tabs_count;

        $beaufairy_tabs_content[$beaufairy_tabs_tab_count]['name'] = $name;
        $beaufairy_tabs_content[$beaufairy_tabs_tab_count]['link'] = $link;
        $beaufairy_tabs_content[$beaufairy_tabs_tab_count]['active'] = $active;  
        $beaufairy_tabs_content[$beaufairy_tabs_tab_count]['content'] = do_shortcode($content);
        $beaufairy_tabs_tab_count = $beaufairy_tabs_tab_count + 1;
    }

    add_shortcode('beaufairy_tab', 'beaufairy_tabs_tab_shortcode');
}

if ( ! function_exists( 'beaufairy_tabs_end_shortcode' ) ) {

    /**
     * End shortcode for beaufairy tabs
     *
     * @param  array  $atts
     * @return void
     */
    function beaufairy_tabs_end_shortcode( $atts ) {

        global $beaufairy_tabs_content, $beaufairy_tabs_tab_count, $beaufairy_tabs_count;
       
        if( $beaufairy_tabs_tab_count != 0 and isset( $beaufairy_tabs_tab_count ) ) {

            $tab_content = '<div class="tabs"><ul class="tabs__nav tabs__nav--home nav nav-tabs" data-tabs="tabs">'; 

            for ( $i=0; $i < $beaufairy_tabs_tab_count; $i++ ) {
      
                $tab_content = $tab_content.'<li class="tabs '.$beaufairy_tabs_content[$i]['active'].'"><a data-toggle="tab" href="#'.$beaufairy_tabs_content[$i]['link'].'">'.$beaufairy_tabs_content[$i]['name'].'</a></li>';
            }

            $tab_content = $tab_content.'</ul></div><div id="my-tab-content" class="tab-content">';
                  
            $tab_html = '';

            for ( $i = 0; $i < $beaufairy_tabs_tab_count; $i++ ) {
                $link_html = $beaufairy_tabs_content[$i]['link'];
                $tab_html .= '<div id="'.$beaufairy_tabs_content[$i]['link'].'" class="tab-pane '.$beaufairy_tabs_content[$i]['active'].'"><p>'.$beaufairy_tabs_content[$i]['content'].'</p></div>';
            }

            $tab_content = $tab_content . $tab_html;
        }

        $tab_content = $tab_content;
              
        return $tab_content;
    }

    add_shortcode('end_beaufairy_tab', 'beaufairy_tabs_end_shortcode');
}