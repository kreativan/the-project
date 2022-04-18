<?php
/**
 *  Project Settings
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://kraetivan.dev
*/

/**
 * Add Settings Page
 *
 */

function project_add_settings_page() {
  add_options_page(
    the_project("name") . ' Settings', // page_title
    the_project("name"), // menu_title
    'manage_options', // permision
    'project-settings', // slug
    'project_render_settings_page'
  );
}
add_action('admin_menu', 'project_add_settings_page');


/**
 * Render Settings Page
 *
 */
function project_render_settings_page() {
  include("settings-form.php");
}


/**
 *  Define Settings
 *  @method register_setting()
 *  @method add_settings_section()
 *  @method add_settings_field()
 */
function project_register_settings() {

  register_setting(
    'project_settings', // options group
    'project_settings', // options_name
  );

  //
  //  Development
  //

  add_settings_section(
    'dev_options', // id
    'Development', // title
    '', // callback function
    'project-settings' // slug-name of the settings page
  );

  add_settings_field(
    'dev_mode', // id
    'Dev Mode', // title 
    'dev_mode_setting_render', // callback func
    'project-settings', // slug-name of the settings page
    'dev_options' // section
  );

  add_settings_field(
    'assets_suffix', // id
    'Assets Suffix', // title 
    'assets_suffix_setting_render', // callback func
    'project-settings', // slug-name of the settings page
    'dev_options' // section
  );


  //
  //  General
  //

  add_settings_section(
    'general_options', // id
    'General', // title
    '', // callback function
    'project-settings' // slug-name of the settings page
  );

  add_settings_field(
    'test_email', // id
    'Test Email', // title 
    'test_email_setting_render', // callback func
    'project-settings', // slug-name of the settings page
    'general_options' // section
  );


  //
  //  Katalog
  //

  add_settings_section(
    'katalog_options', // id
    'Katalog', // title
    '', // callback function
    'project-settings' // slug-name of the settings page
  );

  add_settings_field(
    'katalog_per_page', // id
    'Items Per Page', // title 
    'katalog_per_page_setting_render', // callback func
    'project-settings', // slug-name of the settings page
    'katalog_options' // section
  );

}
add_action('admin_init', 'project_register_settings');

/**
 *  development
 */


function dev_mode_setting_render() {
  $options = get_option('project_settings');
  $checked_1 = $options['dev_mode'] == "1" ? "checked" : "";
  $checked_2 = $options['dev_mode'] == "2" ? "checked" : "";
  $input = "
    <fieldset>
      <label>
        <input type='radio' name='project_settings[dev_mode]' value='1' $checked_1 />
        Enabled
      </label>
      <label>
        <input type='radio' name='project_settings[dev_mode]' value='2' $checked_2 />
        Disabled
      </label>
    </fieldset>
  ";
  echo $input;
}

function assets_suffix_setting_render() {
  $options = get_option('project_settings');
  printf(
    '<input type="text" name="%s" value="%s" />',
    esc_attr( 'project_settings[assets_suffix]' ),
    esc_attr( $options['assets_suffix'] )
  );
}

/**
 *  General
 */

function test_email_setting_render() {
  $options = get_option('project_settings');
  printf(
    '<input type="email" name="%s" value="%s" style="min-width:200px;" />',
    esc_attr( 'project_settings[test_email]' ),
    esc_attr( $options['test_email'] )
  );
}


/**
 *  katalog
 */

function katalog_per_page_setting_render() {
  $options = get_option('project_settings');
  printf(
    '<input type="number" name="%s" value="%s" />',
    esc_attr( 'project_settings[katalog_per_page]' ),
    esc_attr( $options['katalog_per_page'] )
  );
}
