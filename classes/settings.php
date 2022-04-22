<?php
/**
 *  Developer Settings
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
*/

class The_Project_Settings {

  public function __construct() {
    
    $options = get_option('project_settings');
    $this->is_smtp = $options['smtp_enable'] == "1" ? true : false;


    add_action('admin_menu', [$this, 'settings_page']);
    add_action('admin_init', [$this, 'project_settings']);

  }

  // Settings Page
  public function settings_page() {
    add_options_page(
      'Developer Settings', // page_title
      'Developer', // menu_title
      'manage_options', // permision
      'project-settings', // slug
      [$this, 'render_settings_page']
    );
  }

  public function render_settings_page() {
    include("settings-form.php");
  }


  /**
   *  Define Settings
   *  @method register_setting()
   *  @method add_settings_section()
   *  @method add_settings_field()
   */
  public function project_settings() {

    register_setting(
      'project_settings', // options group
      'project_settings', // options_name
    );

    //
    //  Fields
    //

    add_settings_section(
      'dev_options', // id
      '', // title
      [$this, 'render_settings_headline'], // callback function
      'project-settings' // slug-name of the settings page
    );

    add_settings_field(
      'dev_mode', // id
      'Dev Mode', // title 
      [$this, 'render_dev_mode'], // callback func
      'project-settings', // slug-name of the settings page
      'dev_options' // section
    );

    add_settings_field(
      'ajax', // id
      'Ajax', // title 
      [$this, 'render_ajax'], // callback func
      'project-settings', // slug-name of the settings page
      'dev_options' // section
    );

    add_settings_field(
      'htmx', // id
      'HTMX', // title 
      [$this, 'render_htmx'], // callback func
      'project-settings', // slug-name of the settings page
      'dev_options' // section
    );

    add_settings_field(
      'assets_suffix', // id
      'Assets Suffix', // title 
      [$this, 'render_assets_suffix'], // callback func
      'project-settings', // slug-name of the settings page
      'dev_options' // section
    );

    add_settings_field(
      'smtp_enable', // id
      'Enable SMTP', // title 
      [$this, 'render_smtp_enable'], // callback func
      'project-settings', // slug-name of the settings page
      'dev_options' // section
    );

    //
    //  SMTP
    //

    if($this->is_smtp) {

      add_settings_section(
        'smtp_options', // id
        'SMTP Options', // title
        '', // callback function
        'project-settings' // slug-name of the settings page
      );

      add_settings_field(
        'smtp_from_email', // id
        'From Email', // title 
        [$this, 'render_smtp_from_email'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

      add_settings_field(
        'smtp_from_name', // id
        'From Name', // title 
        [$this, 'render_smtp_from_name'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

      add_settings_field(
        'smtp_host', // id
        'Host', // title 
        [$this, 'render_smtp_host'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

      add_settings_field(
        'smtp_port', // id
        'Port', // title 
        [$this, 'render_smtp_port'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

      add_settings_field(
        'smtp_secure', // id
        'Secure', // title 
        [$this, 'render_smtp_secure'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

      add_settings_field(
        'smtp_username', // id
        'Username', // title 
        [$this, 'render_smtp_username'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

      add_settings_field(
        'smtp_password', // id
        'Password', // title 
        [$this, 'render_smtp_password'], // callback func
        'project-settings', // slug-name of the settings page
        'smtp_options' // section
      );

    }

  }


  //
  //  Render
  //

  public function render_settings_headline() {
    echo "<h1>Developer Settings</h1>";
  }

  public function render_dev_mode() {
    $options = get_option('project_settings');
    $name = "dev_mode";
    $value = isset($options[$name]) ? $options[$name] : "1";
    $checked_1 = ($value == "1") ? "checked" : "";
    $checked_2 = ($value == "2") ? "checked" : "";
    $input = "
      <fieldset>
        <label>
          <input type='radio' name='project_settings[$name]' value='1' $checked_1 />
          Enabled
        </label>
        <label>
          <input type='radio' name='project_settings[$name]' value='2' $checked_2 />
          Disabled
        </label>
      </fieldset>
    ";
    echo $input;
  }

  public function render_ajax() {
    $name = "ajax";
    $options_arr = ["Enabled" => "true", "Disabled" => "false"];
    $options = get_option('project_settings');
    $value = isset($options[$name]) ? $options[$name] : "true";
    $input = "<fieldset>";
    foreach($options_arr as $key => $val) {
      $checked = ($value == $val) ? "checked" : "";
      $input .= "
        <label>
          <input type='radio' name='project_settings[$name]' value='$val' $checked />
          $key
        </label>
      ";
    }
    $input .= "</fieldset>";
    echo $input;
  }

  public function render_htmx() {
    $name = "htmx";
    $options_arr = ["Enabled" => "true", "Disabled" => "false"];
    $options = get_option('project_settings');
    $value = isset($options[$name]) ? $options[$name] : "true";
    $input = "<fieldset>";
    foreach($options_arr as $key => $val) {
      $checked = ($value == $val) ? "checked" : "";
      $input .= "
        <label>
          <input type='radio' name='project_settings[$name]' value='$val' $checked />
          $key
        </label>
      ";
    }
    $input .= "</fieldset>";
    echo $input;
  }

  public function render_assets_suffix() {
    $options = get_option('project_settings');
    $name = "assets_suffix";
    $value = isset($options[$name]) ? $options[$name] : "";
    printf(
      '<input type="text" name="%s" value="%s" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

  //
  //  SMTP
  //

  public function render_smtp_enable() {
    $options = get_option('project_settings');
    $name = "smtp_enable";
    $value = isset($options[$name]) ? $options[$name] : "1";
    $checked_1 = ($value == "1") ? "checked" : "";
    $checked_2 = ($value == "2") ? "checked" : "";
    $input = "
      <fieldset>
        <label>
          <input type='radio' name='project_settings[$name]' value='1' $checked_1 />
          Enabled
        </label>
        <label>
          <input type='radio' name='project_settings[$name]' value='2' $checked_2 />
          Disabled
        </label>
      </fieldset>
    ";
    echo $input;;
  }

  public function render_smtp_from_email() {
    $options = get_option('project_settings');
    $name = "smtp_from_email";
    $value = isset($options[$name]) ? $options[$name] : "";
    printf(
      '<input type="email" name="%s" value="%s" class="regular-text" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

  public function render_smtp_from_name() {
    $options = get_option('project_settings');
    $name = "smtp_from_name";
    $value = isset($options[$name]) ? $options[$name] : "";
    printf(
      '<input type="text" name="%s" value="%s" class="regular-text" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

  public function render_smtp_host() {
    $options = get_option('project_settings');
    $name = "smtp_host";
    $value = isset($options[$name]) ? $options[$name] : "";
    printf(
      '<input type="text" name="%s" value="%s" class="regular-text" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

  public function render_smtp_port() {
    $options = get_option('project_settings');
    $name = "smtp_port";
    $value = isset($options[$name]) ? $options[$name] : 587;
    printf(
      '<input type="text" name="%s" value="%s" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

  public function render_smtp_secure() {
    $options = get_option('project_settings');
    $name = "smtp_secure";
    $value = isset($options[$name]) ? $options[$name] : "tls";
    $selected_1 = ($value == "tls") ? "selected" : "";
    $selected_2 = ($value == "ssl") ? "selected" : "";
    $input = "
      <select name='project_settings[$name]'>
        <option value='tls' $selected_1>TLS</option>
        <option value='ssl' $selected_2>SSL</option>
      </select>
    ";
    echo  $input;
  }

  public function render_smtp_username() {
    $options = get_option('project_settings');
    $name = "smtp_username";
    $value = isset($options[$name]) ? $options[$name] : "";
    printf(
      '<input type="text" name="%s" value="%s" class="regular-text" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

  public function render_smtp_password() {
    $options = get_option('project_settings');
    $name = "smtp_password";
    $value = isset($options[$name]) ? $options[$name] : "";
    printf(
      '<input type="password" name="%s" value="%s" class="regular-text" />',
      esc_attr("project_settings[$name]"),
      esc_attr($value)
    );
  }

}


/**
 *  ProjectSettingsField
 *  Used to add new setting field 
 *  @example 
    new The_Project_Settings_Field([
      "name" => "my_field",
      "title" => "My Field"
      "type" => "radio",
      "options" => ["one" => "1", "two" => "2"]
    ])
 */

class The_Project_Settings_Field {

  public function __construct($data) {

    $this->name = !empty($data["name"]) ? $data["name"] : false; // my_field_name
    $this->title = !empty($data["title"]) ? $data["title"] : $this->title; // My Field Title
    $this->type = !empty($data["type"]) ? $data["type"] : 'text'; // text, radio
    $this->options = !empty($data["options"]) ? $data["options"] : false; // my_field_name
    $this->class = !empty($data["class"]) ? $data["class"] : ''; // text, radio

    if($this->name) {
      add_action('admin_init', [$this, 'create_field']);
    }

  }

  // create field
  public function create_field() {
    add_settings_field(
      $this->name, // id
      $this->title, // title 
      [$this, "render_{$this->type}"], // callback func
      'project-settings', // slug-name of the settings page
      'dev_options' // section
    );
  }

  // text
  public function render_text() {
    $options = get_option('project_settings');
    printf(
      "<input type='text' name='%s' value='%s' class='{$this->class}' />",
      esc_attr( "project_settings[$this->name]" ),
      esc_attr( $options["$this->name"] )
    );
  }

  // text
  public function render_email() {
    $options = get_option('project_settings');
    printf(
      "<input type='email' name='%s' value='%s' class='{$this->class}' />",
      esc_attr( "project_settings[$this->name]" ),
      esc_attr( $options["$this->name"] )
    );
  }

  // text
  public function render_password() {
    $options = get_option('project_settings');
    printf(
      "<input type='password' name='%s' value='%s' class='{$this->class}' />",
      esc_attr( "project_settings[$this->name]" ),
      esc_attr( $options["$this->name"] )
    );
  }

  // number
  public function render_number() {
    $options = get_option('project_settings');
    printf(
      "<input type='number' name='%s' value='%s' class='{$this->class}' />",
      esc_attr( "project_settings[$this->name]" ),
      esc_attr( $options["$this->name"] )
    );
  }

  // radio
  public function render_radio() {
    $options = get_option('project_settings');
    $input = "<fieldset>";
    foreach($this->options as $key => $value) {
      $checked = $options[$this->name] == $value ? "checked" : "";
      $input .= "
        <label>
          <input type='radio' name='project_settings[$this->name]' value='$value' $checked />
          $key
        </label>
      ";
    }
    $input .= "</fieldset>";
    echo $input;
  }

  // Select
  public function render_select() {
    $options = get_option('project_settings');
    $input = "<select name='project_settings[$this->name]'>";
    $input .= "<option value=''>- Select -</option>";
    foreach($this->options as $key => $value) {
      $selected = $options[$this->name] == $value ? "selected" : "";
      $input .= "<option value='$value' $selected>$key</option>";
    }
    $input .= "</select>";
    echo $input;
  }

}