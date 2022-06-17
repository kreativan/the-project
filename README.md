# The Project
WordPress plugin for building custom websites.

### Features
* Custom Settings
* Custom Admin Pages 
* Less Compiler
* Valitron Library
* Ajax routing: `/ajax/test/ => my_theme/ajax/tets.php`
* SMTP options
* JavaScript helpers
* Easy to add new admin menu items
* Easy to create custom post types
* The Project Theme - works great with my `the-project-theme` wordpress starter theme. You can get it from
<a href="https://github.com/kreativan/the-project-theme">here</a>.

### Project Data
Set project data here, then it can be used as `the_project('field_name')`. All project settings are also included here...
```
function the_project($field = "") {

  $project = [

    "name" => "The Project",
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
```

### Init Project
```
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
   *  WooCommerce
   *  Let plugin handle basic woocommerce stuff
   *  @var string woocommerce: true/false
   *  Enable / Disable default styles
   *  @var string woocommerce_styles: true/false
   */
  "woocommerce" => the_project("woo") == "1" ? "true" : 'false',
  "woocommerce_styles" => "false",

]);
```

### Init Project Settings (Developer Settings)
```
new The_Project_Settings;
```

### Add Developer Settings
Use `The_Project_Settings_Field` to add custom settings.
```
new The_Project_Settings_Field([
  "name" => "my_field",
  "title" => "My Field"
  "type" => "radio", // text, number, radio, select, email, password
  "options" => ["one" => "1", "two" => "2"]
])

// Example

$my_custom_dev_setting = [
  "name" => "custom_dev_setting",
  "title" => "Custom dev setting",
  "type" => "text",
];

new The_Project_Settings_Field($my_custom_dev_setting);
```

### Admin Menus
Use The_Project_Menu` class to add admin submenus.
```
new The_Project_Sub_Menu([
  "title" => "Submenu",
  "slug" => "project-submenu",
  "view" => "submenu",
]);
```

### Create Post Types
Use `The_Project_Post_Type` to create new post types
```
// With archive
$katalog = [
  "name" => "katalog",
  "title" => "Katalog",
  "item_title" => "Katalog Item",
  "slug" => "katalog",
  "menu_position" => 2,
  "menu_icon" => "dashicons-archive",
  "has_archive" => "true", // post type should have archive page?
  "posts_per_page" => 12,
  "taxonomy" => "true",
  "taxonomy_title" => "Category",
  "taxonomy_name" => "katalog_category",
  "taxonomy_slug" => "katalog-category", // disable this to use /katalog/my-category/ rewrite
  "admin_columns" => [
    'ganre' => 'Ganre',
    'year' => 'Year',
  ]
];

new The_Project_Post_Type($katalog);

// Pages only
new The_Project_Post_Type([
  "name" => "docs",
  "slug" => 'docs',
  "title" => __('Documentation'),
  "item_title" => __('Documentation Page'),
  "show_in_menu" => "false", // do not show in root admin menu
  "exclude_from_search" => "false",
  "supports" => ['title', 'editor'],
  "has_archive" => "true",
  "taxonomy" => "false",
]);

// Create submenu
new The_Project_Sub_Menu([
  "title" => __('Documentation'),
  "slug" => "edit.php?post_type=docs&orderby=title&order=desc" // sort by latest
]);
```

## Less Compiler
Compile less files using built-in less compiler
```
<?php
// $output_dir is optional, default "assets"
$lessCompiler = new Less_Compiler($output_dir);
?>

<link rel="stylesheet" type="text/css" href="<?= $lessCompiler->less($less_files, $less_vars, "main", $dev_mode); ?>">
```

### Valitron
Use built-in valitron library to validate your forms.
```
$v = the_project_valitron($_POST);
$v = $v->rule('email', 'email');
if ( !$v->validate() ) {
  print_r( $v->errors() )
};
```

### Menu items
Get menu items as array.
```
// pass the menu name as argument
$navbar = the_project_menu('navbar');

foreach($navbar as $item) {
  print_r($item);
}
```

### Project.js
Project JavaScript helper functions...
```
// Submit form as ajax req on form action url eg: action="/ajax/test-form/"
// this will automatically collect form data and send it to the action url...
<button type="button" onclick="project.formSubmit('my_form_id')">
  Form Submit
</button>

// Send ajax request to the url
<button type="button" onclick="project.ajaxReq('/ajax/test/?key=value')">
  Ajax Req
</button>
```

### Ajax
```
To do...
```

### HTMX
```
To do...
```