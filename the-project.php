<?php
/**
 *  Plugin Name: The Project
 *  Description: Custom project plugin
 *  Version: 1000.0.1
 *  Author: kreativan.dev
 *  Author URI: http://kreativan.dev/
 */

// Classes
include_once("classes/less-compiler.php"); 
include_once("classes/project.php");
include_once("classes/post-type.php");
include_once("classes/submenu.php");
include_once("classes/settings.php");

// includes
include_once("includes/functions.php");
include_once("includes/init.php");

/**
 *  The Project Data
 *  @param string $field
 *  @return array|string
 */

function the_project($field = "") {

  $project = [

    "name" => "The Project",
    "title" => "Custom Project",
    "developer" => "Ivan Milincic",
    "website" => "https://kreativan.dev"

  ];

  $settings = get_option('project_settings');
  $arr = array_merge($project, $settings);
  
  if($field != "") {
    return isset($arr[$field]) ? $arr[$field] : "";
  } else {
    return $arr;
  }

}

//-------------------------------------------------------- 
//  Init Project
//-------------------------------------------------------- 

new The_Project([

  // Project Title
  "title" => the_project('name'),
  
  // gutenberg
  "gutenberg" => 'false',

  // admin menu
  "menu" => "true",

  // Admin menu icon
  "icon" => 'dashicons-superhero',

  /**
   *  ACF Options Page - Website Settings
   *  Menu Title or false (string)
   *  Need to create ACF Options field group and asign it to the Options Page
   */
  'acf_options' => 'Site Settings',

  /**
   *  Enable ajax route on front end?
   *  http request on /ajax/my-file/
   *  will call for /my-theme/ajax/my-file.php
   */
  "ajax" => the_project("ajax"),

  /**
   *  Load htmx lib
   *  Use /ajax/ route to fetch content
   */
  "htmx" => the_project("htmx"),
  "htmx_version" => "1.7.0",

  // js, css files suffix
  "assets_suffix" => the_project("dev_mode") == "1" ? time() : the_project("assets_suffix"),

  /**
   *  Load JS files on front end
   *  @example ["my_file" => "my_file_url.js"]
   */
  "js" => [
    "project_js" => plugin_dir_url(__FILE__) . "assets/the_project.js",
  ],

  /**
   *  Load CSS files on front end
   *  @example ["my_file" => "my_file_url.css"]
   */
  "css" => [],

  /**
   *  WooCommerce
   *  Let plugin handle basic woocommerce stuff
   *  @var string woocommerce: true/false
   *  Enable / Disable default styles
   *  @var string woocommerce_styles: true/false
   */
  "woocommerce" => the_project("woo") == "1" ? "true" : 'false',
  "woocommerce_styles" => "false",

]);


//  Init Project Settings (Developer Settings)
new The_Project_Settings;


//-------------------------------------------------------- 
//  SMTP
//  Enable and set up SMPT from developer settings
//-------------------------------------------------------- 

if(the_project('smtp_enable') == '1') {

  add_action( 'phpmailer_init', 'the_project_SMTP' );

  function the_project_SMTP($phpmailer) {

    $from_email = the_project('smtp_from_email');
    $from_name = the_project('smtp_from_name');

    $phpmailer->IsSMTP();
    $phpmailer->SetFrom($from_email, $from_name);
    $phpmailer->Host = the_project('smtp_host');
    $phpmailer->Port = the_project('smtp_port');
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = the_project('smtp_secure');
    $phpmailer->Username = the_project('smtp_username');
    $phpmailer->Password = the_project('smtp_password');

  }

}

//-------------------------------------------------------- 
//  Custom Submenus
//  You can add custom project submenus here
//  ["view" => "my_file"] - /views/my_file.php 
//-------------------------------------------------------- 

new The_Project_Sub_Menu([
  "title" => "Submenu",
  "slug" => "project-submenu",
  "view" => "submenu"
]);

//-------------------------------------------------------- 
//  Custom Post Types
//  Include custom post types here
//  so they appear on top of the project submenu
//-------------------------------------------------------- 

include_once("post-types.php");

//-------------------------------------------------------- 
//  Default Post Types and Menus
//-------------------------------------------------------- 

new The_Project_Post_Type([
  "name" => "project-forms",
  "title" => 'Forms',
  "item_title" => 'Form',
  "show_in_menu" => "false",
  "menu_position" => 1,
  "menu_icon" => 'dashicons-feedback',
  "hierarchical" => "true", // true=pages, false=posts
  "exclude_from_search" => "true",
  "supports" => ['title'],
  "has_archive" => "false",
  "taxonomy" => "false",
]);

new The_Project_Sub_Menu([
  "title" => __('Forms'),
  "slug" => "edit.php?post_type=project-forms"
]);


new The_Project_Sub_Menu([
  "title" => "Translate",
  "slug" => "project-translate",
  "view" => "translate",
]);
