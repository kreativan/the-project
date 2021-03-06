<?php
/**
 *  The_Project class
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
 */

class The_Project {

  public function __construct($data) {

    $this->gutenberg = !empty($data["gutenberg"]) && $data["gutenberg"] == "true" ? true : false; 
    $this->menu = !empty($data["menu"]) && $data["menu"] == "false" ? false : true; 
    $this->title = !empty($data["title"]) ? $data["title"] : ''; 
    $this->icon = !empty($data["icon"]) ? $data["icon"] : 'dashicons-superhero'; 
    $this->ajax = !empty($data["ajax"]) && $data["ajax"] == "false" ? false : true; 
    $this->htmx = !empty($data["htmx"]) && $data["htmx"] == "false" ? false : true; 
    $this->htmx_version = !empty($data["htmx_version"]) ? $data["htmx_version"] : '1.7.0'; 
    $this->js = (!empty($data["js"]) && count($data["js"]) > 0) ? $data["js"] : false;
    $this->css = (!empty($data["css"]) && count($data["css"]) > 0) ? $data["css"] : false;
    $this->assets_suffix = !empty($data["assets_suffix"]) ? $data["assets_suffix"] : false;
    $this->acf_options = !empty($data['acf_options']) && $data['acf_options'] != "false" ? $data['acf_options'] : false;
    $this->woocommerce = !empty($data["woocommerce"]) && $data["woocommerce"] == "true" ? true : false; 
    $this->woocommerce_styles = !empty($data["woocommerce_styles"]) && $data["woocommerce_styles"] == "true" ? true : false; 

    //
    //  Actions
    //

    // Admin menu
    if($this->menu) add_action('admin_menu', [$this, 'project_admin_menu']);

    // ACF Options
    if($this->acf_options) $this->acf_website_settings($this->acf_options);

    // Assets
    add_action('wp_enqueue_scripts', [$this, 'load_assets']);

    // Ajax 
    if($this->ajax) add_action('template_redirect', [$this, 'ajax_route']);

    // WooCommerce
    if($this->woocommerce && !$this->woocommerce_styles) {
      add_filter( 'woocommerce_enqueue_styles', '__return_false' );
    }

  }

  // Project Main Menu
  public function project_admin_menu() {

    // Main Page
    add_menu_page(
      $this->title , // title
      $this->title , // menu_title
      'manage_options', // permision
      'project', // slug
      [$this, 'project_render_view'], // callback function
      $this->icon, // icon
      2, // position/sort
    );

  }

  // render project view file
  public function project_render_view() {
    $view_file = __DIR__ . "/../views/main.php";
    if(file_exists($view_file)) include($view_file);
  }

  /**
   *  Website Settings
   */
  public function acf_website_settings($title = 'Website Settings') {
    if(function_exists('acf_add_options_page')) {
      acf_add_options_page([
        'page_title' => $title,
        'menu_title' => $title,
        'menu_slug' => 'website-settings', 
        'capability' => 'edit_posts',
        'parent_slug' => 'project',
      ]);
    }
  }

  /**
   * load assets on front end
   */
  public function load_assets() {

    // reset
    if(!$this->gutenberg) {
      wp_dequeue_style( 'wp-block-library' ); // Wordpress core
      wp_dequeue_style( 'wp-block-library-theme' ); // Wordpress core
      wp_dequeue_style( 'wc-block-style' ); // WooCommerce
      wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
    }

    // JS
    if($this->js) {
      foreach($this->js as $key => $value) {
        wp_register_script($key, $value, [], $this->assets_suffix, true);
        wp_enqueue_script($key);
      }
    }

    // CSS
    if($this->css) {
      foreach($this->css as $key => $value) {
        wp_register_style($key, $value, [], $this->assets_suffix, 'all');
        wp_enqueue_style($key);
      }
    }

    // HTMX
    if($this->htmx) {
      wp_register_script('htmx', plugin_dir_url(__FILE__) . "../assets/htmx.min.js", [], $this->htmx_version, true);
      wp_enqueue_script('htmx');
    }

    // woocommerce
    if($this->woocommerce && !$this->woocommerce_styles) {
      wp_dequeue_style( 'wc-blocks-vendors-style' ); 
      wp_dequeue_style( 'wc-blocks-style' );
    }

  }

  /**
   *  Ajax Route
   *  @example http request to: /ajax/test/ will execute /ajax/test.php
   */
  public function ajax_route() {
    $url = explode("/", $_SERVER['REQUEST_URI']);
    if ($url[1] == 'ajax') {
      global $wp_query;
      $wp_query->is_404 = false;
      status_header(200);
      $file =  get_template_directory() . "/ajax/{$url[2]}.php";
      if(!empty($url[3]) && (substr($url[3], 0, 1) != "?")) {
        $file =  get_template_directory() . "/ajax/{$url[2]}/$url[3].php";
      }
      if(file_exists($file)) {
        include($file);
      } else {
        header('Content-type: application/json');
        echo json_encode([
          "status" => "error",
          "message" => "ajax file not found",
        ]);
        exit();
      }
    }
  }

}