<?php
/**
 *  Hero Post type
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
*/

function hero_post_type() {

  $args = [
    "labels" => [
      'name' => 'Hero',
      'singular_name' => 'Hero Item',
    ],
    "show_in_menu" => false,
    "menu_position" => 1,
    "menu_icon" => 'dashicons-slides',
    "public" => true,
    "hierarchical" => true, // true=pages, false=posts
    "exclude_from_search" => true,
    "supports" => ['title', 'editor', 'thumbnail'],
    "rewrite" => false,
  ];

  register_post_type('hero', $args);

}
add_action('init', 'hero_post_type');