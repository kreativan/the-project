<?php
/**
 *  Add your custom post types here
 *  This file is included in main pugin file
 *  Use The_Project_Post_Type class to create post types
*/

//  Documentation
// ===========================================================

new The_Project_Post_Type([
  "name" => "docs",
  "slug" => 'docs',
  "title" => __('Documentation'),
  "item_title" => __('Documentation Page'),
  "show_in_menu" => "false",
  "menu_icon" => 'dashicons-text',
  "exclude_from_search" => "false",
  "supports" => ['title', 'editor'],
  "has_archive" => "true",
  "taxonomy" => "false",
]);

new The_Project_Sub_Menu([
  "title" => __('Documentation'),
  "slug" => "edit.php?post_type=docs&orderby=title&order=desc"
]);


//  Katalog
// ===========================================================

$katalog_per_page = get_field("katalog_per_page", "options");

$katalog = [
  "name" => "katalog",
  "title" => "Katalog",
  "item_title" => "Katalog Item",
  "slug" => "katalog",
  "menu_position" => 2,
  "menu_icon" => "dashicons-archive",
  "has_archive" => "true",  // post type should have archive page?
  "posts_per_page" => $katalog_per_page,
  "taxonomy" => "true",
  "taxonomy_title" => "Category",
  "taxonomy_name" => "katalog_category",
  "taxonomy_slug" => "katalog-category", // disable this to use /katalog/my-category/ rewrite
  "admin_columns" => [
    'ganre' => 'Ganre',
  ]
];

new The_Project_Post_Type($katalog);