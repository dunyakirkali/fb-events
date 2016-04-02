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


  add_action('admin_menu', 'my_plugin_menu');
  add_action( 'admin_init', 'my_plugin_settings' );

  function my_plugin_settings() {
	  register_setting( 'fb-events-settings-group', 'fb_page_token' );
  }

  function my_plugin_menu() {
	  add_menu_page('My Plugin Settings', 'Plugin Settings', 'administrator', 'my-plugin-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
  }

  function my_plugin_settings_page() {
    ?>
    <div class="wrap">
      <h2>Staff Details</h2>
      <form method="post" action="options.php">
          <?php settings_fields( 'fb-events-settings-group' ); ?>
          <?php do_settings_sections( 'fb-events-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                  <th scope="row">FB Key</th>
                  <td><input type="text" name="fb_page_token" value="<?php echo esc_attr( get_option('fb_page_token') ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
      </div>
    <?php
  }