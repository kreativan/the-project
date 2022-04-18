<?php
/**
 *  Plugin Name: The Project
 *  Description: Custom project plugin
 *  Version: 1000.0.1
 *  Author: kreativan.dev
 *  Author URI: http://kreativan.dev/
 */

// includes
include_once("inc/the-project-class.php");
include_once("inc/project-menu-class.php");
include_once("inc/settings-class.php"); 
include_once("inc/settings-field-class.php"); 
include_once("inc/post-type-class.php");
include_once("inc/less-compiler.php");
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

  // Admin menu icon
  "icon" => 'dashicons-superhero',

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

//-------------------------------------------------------- 
//  Settings
//-------------------------------------------------------- 

// Init Project Settings
new The_Project_Settings;

// Add settings Fields

$katalog_per_page = [
  "name" => "katalog_per_page",
  "title" => "Katalog items per page",
  "type" => "number",
  "class" => "small-text",
];

new The_Project_Settings_Field($katalog_per_page);

//-------------------------------------------------------- 
//  Menus
//-------------------------------------------------------- 

new The_Project_Menu([
  "title" => "Hero",
  "slug" => "edit.php?post_type=hero"
]);

new The_Project_Menu([
  "title" => "Submenu",
  "slug" => "project-submenu",
  "view" => "submenu",
]);

new The_Project_Menu([
  "title" => "Settings",
  "slug" => "options-general.php?page=project-settings",
]);

//-------------------------------------------------------- 
//  Post Types
//-------------------------------------------------------- 

$katalog = [
  "name" => "katalog",
  "title" => "Katalog",
  "item_title" => "Katalog Item",
  "slug" => "katalog",
  "menu_position" => 2,
  "menu_icon" => "dashicons-archive",
  "has_archive" => "true",
  "posts_per_page" => the_project('katalog_per_page'),
  "category_name" => "katalog_category",
  "rewrite" => "katalog/%katalog_category%",
  "rewrite_func" => "true",
  "gutenberg" => "false"
];

new The_Project_Post_Type($katalog);


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