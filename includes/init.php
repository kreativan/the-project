<?php
//
// Options
//

add_theme_support('title-tag');
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');

if(the_project("woo") == "1") {
  add_theme_support('woocommerce');
}

// Init Translations
// load_theme_textdomain('default', get_template_directory());

//  Pagination 404 Fix
// ===========================================================
function custom_pre_get_posts( $query ) {  
  if( $query->is_main_query() && !$query->is_feed() && !is_admin() && is_category()) {  
    $query->set( 'paged', str_replace( '/', '', get_query_var( 'page' ) ) );  
  }  
} 

add_action('pre_get_posts','custom_pre_get_posts'); 

function custom_request($query_string ) { 
  if( isset( $query_string['page'] ) ) { 
    if( ''!=$query_string['page'] ) { 
      if( isset( $query_string['name'] ) ) { 
        unset( $query_string['name'] ); 
      } 
    }
  } 
  return $query_string; 
} 

add_filter('request', 'custom_request');

//  Load css in admin
// ===========================================================
function the_project_admin_assets() {
  if (is_admin()) {
    $suffix = the_project('dev_mode') == '1' ? time() : '1.0';
    $css_file = plugin_dir_url(__FILE__) . "../assets/the_project.css";
    wp_register_style("the_project_css", $css_file, null, $suffix);
    wp_enqueue_style( 'the_project_css' );
    // wp_enqueue_script("rm_script", $file_dir."/scripts/custom.js", false, $suffix);
  }
}
add_action('init', 'the_project_admin_assets');