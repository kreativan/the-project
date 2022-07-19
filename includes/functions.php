<?php
function dump($var) {
  echo '<pre>',print_r($var,1),'</pre>';
}

/* =========================================================== 
  language
=========================================================== */

function lng($str = "") {

  $key = lng_key($str);

  $lang = get_option('WPLANG');
  $lang = explode("_", $lang);
  $lang = $lang[0];
  $lang = !empty($lang) ? $lang : 'en';

  $default_file = get_template_directory() . "/assets/language/default.json";
  $default_json = file_get_contents($default_file);
  $default = json_decode($default_json, true);

  if(!isset($default[$key])) lng_update($str);

  $translation_file = get_template_directory() . "/assets/language/$lang.json";
  if(file_exists($translation_file)) {
    $translation_json = file_get_contents($translation_file);
    $translation = json_decode($translation_json, true);
    if(isset($translation[$key])) return $translation[$key];
  }

  return isset($default[$key]) ? $default[$key] : $str;
}

function lng_update($str) {
  $key = lng_key($str);
  $file = get_template_directory() . "/assets/language/default.json";
  $json = file_get_contents($file);
  $array = json_decode($json, true);
  $array[$key] = $str;
  file_put_contents($file, json_encode($array));
}

function lng_key($str) {
  $key = strtolower($str);
  $key = str_replace(" ", "_", $key);
  $key = str_replace(".", "", $key);
  $key = str_replace("'", "", $key);
  $key = str_replace('"', "", $key);
  $key = str_replace("%s", "__s__", $key);
  return $key;
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
 *  Render Picture
 *  @param object $image
 *  @example $source = ["max-width: 600px" => $image['sizes']['600'];
 */
function picture($image, $args = []) {

  if(empty($image)) return;

  $size = !empty($args['size']) ? $args['size'] : false;
  $alt = !empty($args['alt']) ? $args['alt'] : $image['alt'];
  $lazy = !empty($args["lazy"]) && $args["lazy"] == "false" ? false : true;
  $webp = !empty($args["webp"]) && $args["webp"] == "true" ? true : false;
  $class = !empty($args["class"]) ? $args["class"] : false;
  $img_class = !empty($args["img_class"]) ? $args["img_class"] : false;
  $img_attr = !empty($args["img_attr"]) ? $args["img_attr"] : false;
  $source = !empty($args["source"]) ? $args["source"] : [];

  if($size) {
    $size_width = "{$size}-width";
    $size_height = "{$size}-height";
    $width = !empty($args['width']) ? $args['width'] : $image['sizes'][$size_width];
    $height = !empty($args['height']) ? $args['height'] : $image['sizes'][$size_height];
  } else {
    $width = !empty($args['width']) ? $args['width'] : $image['width'];
    $height = !empty($args['height']) ? $args['height'] : $image['height'];
  }

  $img = $size ? $image['sizes']["$size"] : $image['url'];
  
  $attr = "";
  $cls = $class ? "class='$class'" : "";
  $img_cls = $img_class ? "class='$img_class'" : "";

  // lazy load or not
  $attr .= $lazy ? "loading='lazy'" : "";

  // img_attr
  $attr .= $img_attr ? " $img_attr" : "";

  // Start <picture> html
  $html = "<picture $cls>";

  // add additional sources if exists
  if(count($source)) {
    foreach($source as $media => $srcset) {
      $html .= "<source media='($media)' srcset='$srcset' />";
    }
  }

  if($webp) $html .= "<source srcset='($webp}' type='image/webp'>";

  $html .= "<img src='{$img}' alt='$alt' width='$width' height='$height' $img_cls $attr />";

  //end picture tag
  $html .= "</picture>";

  echo $html;
}

/**
 *  Render SVG
 *  @param string $svg_file - svg file path relative to the theme folder
 *  @param array $options
 *  @return markup
 */
function svg($svg_file, $options = []) {
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
 *  @param $url - regular youtube url
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
  require_once(__DIR__."/../valitron/src/Valitron/Validator.php");
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

/**
 *  Convert Text to Markdown
 */
function markdown($text) {
  if(!class_exists('Parsedown')) require_once(__DIR__."/../parsedown/Parsedown.php");
  $Parsedown = new Parsedown();
  echo $Parsedown->text($text);
}