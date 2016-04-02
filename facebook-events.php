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
    register_setting('fb-events-settings-group', 'fb_client_id');
    register_setting('fb-events-settings-group', 'fb_client_secret');
    register_setting('fb-events-settings-group', 'access_token');
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
                <th scope="row"><label for="fb_page_id">FB Page id</label></th>
                <td><input name="fb_page_id" type="text" value="<?php echo esc_attr( get_option('fb_page_id') ); ?>" class="regular-text"></td>
              </tr>
              <tr>
                <th scope="row"><label for="fb_client_id">FB App Client id</label></th>
                <td><input name="fb_client_id" type="text" value="<?php echo esc_attr( get_option('fb_client_id') ); ?>" class="regular-text"></td>
              </tr>
              <tr>
                <th scope="row"><label for="fb_client_secret">FB App Client secret</label></th>
                <td><input name="fb_client_secret" type="text" value="<?php echo esc_attr( get_option('fb_client_secret') ); ?>" class="regular-text"></td>
              </tr>
              <tr>
                <th scope="row"><label for="access_token">FB App Access Token</label></th>
                <td><input name="access_token" type="text" value="<?php echo esc_attr( get_option('access_token') ); ?>" class="regular-text"></td>
              </tr>
            </table>
            <?php submit_button(); ?>
        </form>
      </div>
    <?php
  }

  function fb_events_list($atts) {
    # get options
    $client_id = get_option('fb_client_id');
    $client_secret = get_option('fb_client_secret');
    $fb_page_id = get_option('fb_page_id');
    $access_token = get_option('access_token');

    # prepare fields
    $relation_fields = array("interested", "attending", "declined", "noreply");
    $event_fields = array("name", "description");
    $fields = array_merge($event_fields, $relation_fields);

    # get events
    $field_params = implode(",", $fields);
    $json_link = "https://graph.facebook.com/{$fb_page_id}/events/attending/?fields={$field_params}&access_token={$access_token}";
    $json = file_get_contents($json_link);
    $obj = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
    $events = $obj['data'];

    // draw events
    echo '<ul>';

    $event_count = count($events);
    for($x = 0; $x < $event_count; $x++){
      $event = $events[$x];
      echo "<li>";
      $event_fields_count = count($event_fields);
      for($i = 0; $i < $event_fields_count; $i++){
        $event_value = $event[$event_fields[$i]];
        echo "<p>$event_value</p>";
      }
      echo "<ul>";
      $relations_count = count($relation_fields);
      for($a = 0; $a < $relations_count; $a++){
        $relation = $relation_fields[$a];
        $relation_item_count = count($event[$relation]['data']);
        if($relation_item_count) {
          echo "<h4>$relation</h4>";
          for($y = 0; $y < $relation_item_count; $y++){
            $name = $event[$relation]['data'][$y]['name'];
            $rsvp = $event[$relation]['data'][$y]['rsvp_status'];

            echo "<li><h5>$name</h5><p>$rsvp</p></li>";
          }
        }
      }
      echo '</ul>';
      echo "</li>";
    }
    echo "</ul>";
  }
