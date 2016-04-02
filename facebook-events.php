<?php
 /**
  * Plugin Name: Facebook Events
  * Plugin URI: http://ahtung.co
  * Description: This plugin adds events through Facebook Open Graph.
  * Version: 1.0.0
  * Author: Dunya Kirkali
  * Author URI: http://ahtung.co
  * License: GPL2
  */


  add_action('admin_menu', 'fb_events_menu');
  add_action('admin_init', 'fb_events_settings');

  function fb_events_settings() {
	  register_setting('fb-events-settings-group', 'fb_page_token');
  }

  function fb_events_menu() {
	  add_menu_page('Facebook Events Settings', 'FB Events', 'administrator', 'my-plugin-settings', 'fb_events_settings_page', 'dashicons-admin-generic');
  }

  function fb_events_settings_page() {
    ?>
    <div class="wrap">
      <h2>Facebook Settings</h2>
      <form method="post" action="options.php">
          <?php settings_fields( 'fb-events-settings-group' ); ?>
          <?php do_settings_sections( 'fb-events-settings-group' ); ?>
            <table class="form-table">
              <tr>
                <th scope="row"><label for="fb_page_token">FB Page token</label></th>
                <td><input name="fb_page_token" type="text" value="<?php echo esc_attr( get_option('fb_page_token') ); ?>" class="regular-text"></td>
              </tr>
            </table>
            <?php submit_button(); ?>
        </form>
      </div>
    <?php
  }
