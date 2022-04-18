<?php
/**
 *  Create Post Type Class
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
*/

class The_Project_Post_Type {


  public function __construct($data = []) {

    $has_archive = !empty($data["has_archive"]) && $data["has_archive"] == "false" ? false : true;
    $rewrite_func = !empty($data["rewrite_func"]) && $data["rewrite_func"] == 'true' ? true : false;

    // posts
    $this->name = $data['name']; // my_type
    $this->title = $data['title']; // My Type
    $this->item_title = $data['item_title'];
    $this->slug = !empty($data['slug']) ? $data['slug'] : false; // my-slug
    $this->public = !empty($data["public"]) && $data["public"] == "false" ? false : true;
    $this->exclude_from_search = !empty($data['exclude_from_search']) ? $data['exclude_from_search'] : false;
    $this->show_in_menu = !empty($data["show_in_menu"]) && $data["show_in_menu"] == "false" ? false : true;
    $this->menu_position = !empty($data['menu_position']) ? $data['menu_position'] : 2;
    $this->hierarchical = !empty($data['hierarchical']) && $data['hierarchical'] == false ? false : true;
    $this->menu_icon = !empty($data['menu_icon']) ? $data['menu_icon'] : "dashicons-archive";
    $this->supports = !empty($data['supports']) ? $data['supports'] : ['title', 'editor', 'thumbnail'];
    $this->has_archive = $has_archive;
    $this->posts_per_page = !empty($data['posts_per_page']) ? $data['posts_per_page'] : 12;

    // Rewrite URL
    // POST_TYPE_SLUG/%TAXONOMY_NAME%
    // "my-type/%my_category%";
    $this->rewrite = !empty($data["rewrite"]) ? $data['rewrite'] : false;
    
    // category
    $this->category_name = !empty($data["category_name"]) ? $data["category_name"] : "project_category"; // my_category
    $this->category_title = !empty($data["category_title"]) ? $data["category_title"] : "Categories"; // Categories
    $this->category_items =  !empty($data["category_items"]) ? $data["category_items"] : "Category"; // Category

    // actions
    add_action('init', [$this, 'post_type']);

    // if there is categories
    if($has_archive) {
      add_action('init', [$this, 'taxonomy']);
      add_action('pre_get_posts', [$this, 'posts_per_page']);
      if($rewrite_func) add_filter('post_type_link', [$this, 'rewrite_func'], 1, 2);
    }

  }


  //
  //  Post Type
  //
  public function post_type() {

    $args = [
      "labels" => [
        'name' => $this->title,
        'singular_name' => $this->item_title,
      ],
      "show_in_menu" => $this->show_in_menu,
      "menu_position" => $this->menu_position,
      "hierarchical" => $this->hierarchical, // true=pages, false=posts
      "menu_icon" => $this->menu_icon,
      "public" => $this->public,
      "supports" => $this->supports,
      "has_archive" => $this->has_archive ?  $this->slug : false, // POST_TYPE_SLUG
      "exclude_from_search" => $this->exclude_from_search,
    ];

    // POST_TYPE_SLUG/%TAXONOMY_NAME%
    if($this->rewrite) {
      $args['rewrite'] = ["slug" => "{$this->rewrite}", 'with_front' => false];
    }

    register_post_type("{$this->name}", $args);

  }


  //
  //  taxonomy
  //
  public function taxonomy() {

    $args = [
      "labels" => [
        'name' => $this->category_title,
        'singular_name' => $this->category_items,
      ],
      "public" => true,
      "hierarchical" => true, // true=category, false=tag
      "rewrite" => ["slug" => "{$this->slug}", 'with_front' => false], // POST_TYPE_SLUG
    ];

    register_taxonomy("$this->category_name", ["{$this->name}"], $args);

  }

  // set posts per page
  public function posts_per_page($query) {
    if ( !is_admin() && $query->is_main_query() && is_post_type_archive("{$this->name}") ) {
      $items_per_page = $this->posts_per_page;
      $query->set('posts_per_page', $items_per_page);
    }
  }


  /**
   *  Rewrite katalog urls
   *  @example /post-type-slub/item-slug/
   */
  public function rewrite_func($post_link, $post) {
    if ( is_object( $post ) && $post->post_type == $this->name) {
      $terms = wp_get_object_terms( $post->ID, $this->category_name);
      if($terms && !empty($this->category_name)) {
        return str_replace( "%{$this->category_name}%" , $terms[0]->slug , $post_link );
      } else {
        return str_replace( "%{$this->category_name}%" , 'all' , $post_link );
      }
    }
    return $post_link;
  }


}