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
include_once("functions.php");

/**
 *  The Project Data
 *  @param string $field
 *  @return array|string
 */

function the_project($field = "") {

  $project = [

    "name" => "Kreativan",
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
   *  Enable htmx and htmx route
   *  http request on /htmx/my-file/
   *  will call for /my-theme/htmx/my-file.php
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
    "project_js" => plugin_dir_url(__FILE__) . "js/project.js",
  ],

  /**
   *  Load CSS files on front end
   *  @example ["my_file" => "my_file_url.css"]
   */
  "css" => [],

  /**
   *  SMTP
   *  Use smtp to send email from the website
   */
  "SMTP" => [
    "from_email" => the_project('smtp_from_email'),
    "from_name" => the_project('smtp_from_name'),
    "host" => the_project('smtp_host'),
    "port" => the_project('smtp_port'),
    "secure" => the_project('smtp_secure'),
    "username" => the_project('smtp_username'),
    "password" => the_project('smtp_password'),
  ],

]);

//  Init Project Settings (Developer Settings)
// ===========================================================

new The_Project_Settings;


//  Project Submenu
// ===========================================================

new The_Project_Sub_Menu([
  "title" => "Submenu",
  "slug" => "project-submenu",
  "view" => "submenu",
]);


//  Katalog Post Type Example
// ===========================================================

$katalog_per_page = get_field("katalog_per_page", "options");

$katalog = [
  "name" => "katalog",
  "title" => "Katalog",
  "item_title" => "Katalog Item",
  "slug" => "katalog",
  "menu_position" => 2,
  "menu_icon" => "dashicons-archive",
  "has_archive" => "true",
  "taxonomy" => "true",
  "posts_per_page" => $katalog_per_page,
  "category_name" => "katalog_category",
  "rewrite" => "katalog/%katalog_category%",
  "rewrite_func" => "true",
  "gutenberg" => "false"
];

new The_Project_Post_Type($katalog);


//  Single post type example 
// ===========================================================

$hero = [
  "name" => "hero",
  "title" => "Hero",
  "item_title" => "Hero item",
  "show_in_menu" => "false",
  "menu_position" => 1,
  "menu_icon" => 'dashicons-slides',
  "hierarchical" => "true", // true=pages, false=posts
  "exclude_from_search" => "true",
  "supports" => ['title', 'editor', 'thumbnail'],
  "has_archive" => "false",
  "rewrite" => "false",
  "rewrite_func" => "false",
  "gutenberg" => "false"
];

new The_Project_Post_Type($hero);

// Hero projecy submenu
new The_Project_Sub_Menu([
  "title" => "Hero",
  "slug" => "edit.php?post_type=hero"
]);