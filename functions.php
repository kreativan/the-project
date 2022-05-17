<?php
function dump($var) {
  echo '<pre>',print_r($var,1),'</pre>';
}

// Init Translations
// load_theme_textdomain('default', get_template_directory());

//
// Options
//

add_theme_support('title-tag');
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');

/* =========================================================== 
  language
=========================================================== */

function lng_key($str) {
  $key = strtolower($str);
  $key = str_replace(" ", "_", $key);
  $key = str_replace(".", "", $key);
  $key = str_replace("'", "", $key);
  $key = str_replace('"', "", $key);
  $key = str_replace("%s", "__s__", $key);
  return $key;
}

function lng($str = "") {

  $key = lng_key($str);

  $lang = get_settings('WPLANG');
  $lang = explode("_", $lang);
  $lang = $lang[0];
  $lang = !empty($lang) ? $lang : 'en';

  $default_file = get_template_directory() . "/language/default.json";
  $default_json = file_get_contents($default_file);
  $default = json_decode($default_json, true);

  if(!isset($default[$key])) lng_update($str);

  $translation_file = get_template_directory() . "/language/$lang.json";
  if(file_exists($translation_file)) {
    $translation_json = file_get_contents($translation_file);
    $translation = json_decode($translation_json, true);
    if(isset($translation[$key])) return $translation[$key];
  }

  return isset($default[$key]) ? $default[$key] : false;
}

function lng_update($str) {
  $key = lng_key($str);
  $file = get_template_directory() . "/language/default.json";
  $json = file_get_contents($file);
  $array = json_decode($json, true);
  $array[$key] = $str;
  file_put_contents($file, json_encode($array));
}

/* =========================================================== 
  Menu
=========================================================== */

/**
 *  Get Menu items 
 *  @param string $name
 *  @return array; 
 */
function the_project_menu($name) {
  $menu_items = wp_get_nav_menu_items($name);
  $array = [];
  foreach($menu_items as $item) {
    $item_arr = [
      'id' => $item->ID,
      'title' => $item->title,
      'href' => $item->url,  
      'object' => $item->object,
      'type' => $item->type,
    ];
    if($item->menu_item_parent) {
      $array[$item->menu_item_parent]["submenu"][] = $item_arr;
    } else {
      $array[$item->ID] = $item_arr;
    }
  }
  return $array;
}

/* =========================================================== 
    Media
=========================================================== */

/**
 *  Render SVG from /assets/svg/ folder
 *  @param string $svg_file_nam
 *  @param array $options
 *  @return markup
 */
function the_project_svg($svg_file, $options = []) {
  $svg_file = get_template_directory() . "{$svg_file}.svg";
  if(!file_exists($svg_file)) return false;

  // Options
  $type = !empty($options["type"]) ? $options["type"] : "stroke"; // stroke / fill
  $color = !empty($options["color"]) ? $options["color"] : ""; // hex
  $size = !empty($options["size"]) ? $options["size"] : "28px"; // px
  $class = "svg-$type";
  $class .= !empty($options["class"]) ? " " . $options["class"] : "";
  $sty = !empty($options["style"]) ? $options["style"] : ""; // style=""

  $style = "width:$size;height:$size;";
  if($color != "") {
    $style .= ($type == "stroke") ? "stroke: $color;" : "fill: $color;";
  }
  $style .= !empty($sty) ? " $sty" : "";

  $svg = file_get_contents($svg_file);
  echo "<span class='svg {$class}' style='{$style}'>{$svg}</span>";
}

/**
 *  Get Youtube embed url from regular url
 */
function the_project_youtube($url) {
  $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
  $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
  if (preg_match($longUrlRegex, $url, $matches)) {
    $youtube_id = $matches[count($matches) - 1];
  }
  if (preg_match($shortUrlRegex, $url, $matches)) {
    $youtube_id = $matches[count($matches) - 1];
  }
  return 'https://www.youtube.com/embed/' . $youtube_id ;
}

// Get media by slug
function the_project_media($slug) {

  $args = array(
    'post_type' => 'attachment',
    'name' => sanitize_title($slug),
    'posts_per_page' => 1,
    'post_status' => 'inherit',
  );
  $_header = get_posts( $args );
  $header = $_header ? array_pop($_header) : null;
  return $header ? wp_get_attachment_url($header->ID) : '';

}


/* =========================================================== 
  Utility
=========================================================== */

// render file and pass the data
function the_project_render($file_name, $vars = '') {
  foreach($vars as $key => $value) $$key = $value;
  include($file_name);
}


/**
 *  Validate Data/Form with Valitron
 *  @param array $array
 *  @example 
 *  $v = the_project_valitron($_POST);
 *  $v = $v->rule('email', 'email');
 *  if ( !$v->validate() ) print_r( $v->errors() );
 */
function the_project_valitron($array, $lang = "en") {
  require_once(__DIR__."/valitron/src/Valitron/Validator.php");
  Valitron\Validator::lang($lang);
  $v = new Valitron\Validator($array);
  return $v;
}

/**
 *  Replace {my_field} with $_POST['my_field'] or any array key
 *  Or {my_field} with any array data ['first_name' => 'Project']
 */
function the_project_str($string, $data = []) {
  preg_match_all("/\{(.*?)\}/", $string, $matches);
  foreach($matches[1] as $key) {
    $replace = sanitize_text_field($data[$key]);
    $string = str_replace("{".$key."}", $replace, $string);
  }
  return $string;
}