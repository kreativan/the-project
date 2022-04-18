# The Project
WordPress plugin for building custom websites.

### Features
* Custom Settings
* Custom Admin Pages 
* Less Compiler
* Valitron Library
* Ajax routing: `/ajax/test/ => my_theme/ajax/tets.php`
* HTMX integration and routing: `/htmx/test/ => my_theme/htmx/test.php`
* SMTP options
* JavaScript helpers
* Easy to add new admin menu items
* Easy to create custom post types
* The Project Theme - works great with my `the-project-theme` wordpress starter theme...

### Project Data
Set project data here, then it can be used as `the_project('field_name')`. All project settings are also included here...
```
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
```

### Init Project
```
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
```
### Init Project Settings
```
new The_Project_Settings;
```

### Admin Menus
Use The_Project_Menu` class to add admin submenus
```
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
```

### Create Post Types
Use `The_Project_Post_Type` to create new post types
```
// With Archive
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
];

new The_Project_Post_Type($katalog);

// Pages only
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
];

new The_Project_Post_Type($hero);
```

### Add Settings
Use `The_Project_Settings_Field` to add custom settings.
```
new The_Project_Settings_Field([
  "name" => "my_field",
  "title" => "My Field"
  "type" => "radio", // text, number, radio
  "options" => ["one" => "1", "two" => "2"]
])

// Example

$katalog_per_page = [
  "name" => "katalog_per_page",
  "title" => "Katalog items per page",
  "type" => "number",
];

new The_Project_Settings_Field($katalog_per_page);
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

## Project Functions
Use project functions and methods.
```
$project = new The_Project_Func;

// Hello
echo $project->hello;
```

### Valitron
Use built-in valitron library to validate your forms.
```
$project = new The_Project_Func;
$v = $project->valitron($_POST);
$v = $v->rule('email', 'email');
if ( !$v->validate() ) {
  print_r( $v->errors() )
};
```

### Menu items
Get menu items as array.
```
$project = new The_Project_Func;

// pass the menu name as argument
$navbar = $project->menu_items('navbar');

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