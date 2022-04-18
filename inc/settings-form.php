<h1><?= the_project("name") ?> Settings</h1>
<form id="project-settings-form" action="options.php" method="post">
  <?php 
    settings_fields('project_settings');
    do_settings_sections('project-settings');
  ?>
  <input
    type="submit"
    name="submit"
    class="button button-primary"
    style="margin-top:20px;"
    value="<?php esc_attr_e( 'Save' ); ?>"
  />
</form>

<style>
.form-table th, 
.form-table td {
  padding: 5px 10px;
}
.form-table td fieldset label {
  margin-right: 10px !important;
}
</style>