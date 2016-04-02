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

  session_start();
  include('src/Facebook/autoload.php');

  add_action('admin_menu', 'fb_events_menu');
  add_action('admin_init', 'fb_events_settings');
  add_shortcode('fb_events', 'fb_events_list');


  function fb_events_settings() {
    register_setting('fb-events-settings-group', 'fb_page_id');
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
              <tr>
                <th scope="row"><label for="fb_page_id">FB Page id</label></th>
                <td><input name="fb_page_id" type="text" value="<?php echo esc_attr( get_option('fb_page_id') ); ?>" class="regular-text"></td>
              </tr>
            </table>
            <?php submit_button(); ?>
        </form>
      </div>
    <?php
  }

  function fb_events_list($atts) {
    $url = get_option('fb_page_id') . "/events";
    $events = [['name' => 'Dunya'], ['name' => 'Ilana']];
    $fb = new Facebook\Facebook([
       'app_id' => '{app-id}',
      'app_secret' => '{app-secret}',
      'default_graph_version' => 'v2.5',
      'default_access_token' => get_option('fb_page_token')
    ]);
    try {
      // Get the Facebook\GraphNodes\GraphUser object for the current user.
      // If you provided a 'default_access_token', the '{access-token}' is optional.
      // $response = $fb->get('/me', '{access-token}');
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    ?>

    <ul>
      <?php
        for ($x = 0; $x < count($events); $x++) {
          $event_name = $events[$x]['name'];
          echo "<li>$event_name</li>";
        }
      ?>
      </ul>
    <?php
  }
