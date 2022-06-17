<div class="the-project-panel padding margin">
  <form class="the-project-form" action="options.php" method="post">
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
</div>