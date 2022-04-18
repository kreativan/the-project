<?php
/**
 *  The_Project_Func
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
*/

class The_Project_Func {

  public function __construct() {

  }

  public function hello() {
    return "Hello";
  }

  /**
   *  Get Menu items
   *  @param string $name
   *  @return array; 
   */
  function menu_items($name) {
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


  /**
   *  Validate Data/Form with Valitron
   *  @param array $array
   *  @example  $project_func = new The_Project_Func;
   *            $v = $project_func->valitron($_POST);
   *            $v = $v->rule('email', 'email');
   *            if ( !$v->validate() ) print_r( $v->errors() );
   */
  public function valitron($array, $lang = "en") {
    require_once(__DIR__."/valitron/src/Valitron/Validator.php");
    Valitron\Validator::lang($lang);
    $v = new Valitron\Validator($array);
    return $v;
  }


  /**
   *  Render SVG from /assets/svg/ folder
   *  @param string $svg_file_nam
   *  @param array $options
   *  @return markup
   */
  public function svg($svg_file, $options = []) {
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

}