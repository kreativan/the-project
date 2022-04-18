<?php
/**
 *  Katalog Post Type
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
*/

function katalog_post_type() {

  $args = [
    "labels" => [
      'name' => 'Katalog',
      'singular_name' => 'Katalog Item',
    ],
    "show_in_menu" => true,
    "menu_position" => 2,
    "hierarchical" => true, // true=pages, false=posts
    "menu_icon" => 'dashicons-archive',
    "public" => true,
    "supports" => ['title', 'editor', 'thumbnail'],
    "rewrite" => ["slug" => "katalog/%katalog_category%", 'with_front' => false], // POST_TYPE_SLUG/%TAXONOMY_NAME%
    "has_archive" => "katalog", // POST_TYPE_SLUG
  ];

  register_post_type('katalog', $args);

}
add_action('init', 'katalog_post_type');


/**
 *  Taxonomy
 *  related to the custom post type
 *  Rewrite Slug: POST_TYPE_SLUG
 */

function katalog_taxonomy() {

  $args = [
    "labels" => [
      'name' => 'Categories',
      'singular_name' => 'Category',
    ],
    "public" => true,
    "hierarchical" => true, // true=category, false=tag
    "rewrite" => ["slug" => "katalog", 'with_front' => false], // POST_TYPE_SLUG
  ];

  register_taxonomy('katalog_category', ['katalog'], $args);

}
add_action('init', 'katalog_taxonomy');


/**
 *  Post per page
 *  Set a post per page for specific post type
 */

function katalog_posts_per_page($query) {
  if ( !is_admin() && $query->is_main_query() && is_post_type_archive('katalog') ) {
    $items_per_page = the_project('katalog_per_page');
    $query->set('posts_per_page', $items_per_page);
  }
}
add_action('pre_get_posts', 'katalog_posts_per_page');


/**
 *  Rewrite katalog urls
 *  @example /post-type-slub/item-slug/
 */

function katalog_rewrite($post_link, $post) {
  if ( is_object( $post ) && $post->post_type == 'katalog' ) {
    $terms = wp_get_object_terms( $post->ID, 'katalog_category');
    if($terms) {
      return str_replace( '%katalog_category%' , $terms[0]->slug , $post_link );
    }
  }
  return $post_link;
}
add_filter('post_type_link', 'katalog_rewrite', 1, 2);