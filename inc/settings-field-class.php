<?php
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