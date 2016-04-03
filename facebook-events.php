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
  add_shortcode('fb_events', 'fb_events_list');

  function fb_events_settings() {
    $options = array('fb_page_id', 'fb_client_id', 'fb_client_secret', 'access_token');
    $option_count = count($options);
    for($i = 0; $i < $option_count; $i++){
      register_setting('fb-events-settings-group', $options[$i]);
    }
  }

  function fb_events_menu() {
	  add_menu_page('Facebook Events Settings', 'FB Events', 'administrator', 'my-plugin-settings', 'fb_events_settings_page', 'dashicons-admin-generic');
  }

  function fb_events_settings_page() {
    $options = array('fb_page_id', 'fb_client_id', 'fb_client_secret', 'access_token');
    ?>
    <div class="wrap">
      <h2>Facebook Settings</h2>
      <form method="post" action="options.php">
          <?php settings_fields( 'fb-events-settings-group' ); ?>
          <?php do_settings_sections( 'fb-events-settings-group' ); ?>
            <table class="form-table">
              <?php
                $option_count = count($options);
                for($i = 0; $i < $option_count; $i++){
                  $option = $options[$i];
                  $val = esc_attr( get_option($option) );
                  echo "<tr>";
                  echo "<th scope='row'><label for='$option'>$option</label></th>";
                  echo "<td><input name='$option' type='text' value='$val' class='regular-text'></td>";
                  echo "</tr>";
                }
              ?>
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
    $event_fields = array("id", "picture", "name", "description", "start_time", "place");
    $fields = array_merge($event_fields, $relation_fields);

    # get events
    $field_params = implode(",", $fields);
    $json_link = "https://graph.facebook.com/{$fb_page_id}/events/attending/?fields={$field_params}&access_token={$access_token}";
    $json = file_get_contents($json_link);
    $obj = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
    $events = $obj['data'];

    // draw events
    echo '<div class="term-wonen portfolio-item eight columns">';
    $event_count = count($events);
    for($x = 0; $x < $event_count; $x++){
      $event = $events[$x];
      $event_name = $event['name'];
      $event_id = $event['id'];
      $event_description = $event['description'];
      $event_picture =  $event['picture']['data']['url'];
      $event_url = "https://www.facebook.com/events/$event_id/";

      // draw image
      echo "<div class='portfolio-image'>";
      echo "<a href='' title='$event_name'>";
      echo "<div class='portfolio-image-img'><img src='$event_picture' alt='$event_name'></div>";
      echo "<div class='portfolio-overlay overlay-icon'></div>";
      echo "<i class='icon-minti-plus'></i></a></div>";
      // draw title
      echo "<h4><a href='$event_url' title='$event_name'>$event_name</a></h4>";
      // draw decription
      echo "<span class='portfolio-subtitle'>$event_description</span>";

      // echo "<ul>";
      // $relations_count = count($relation_fields);
      // for($a = 0; $a < $relations_count; $a++){
      //   $relation = $relation_fields[$a];
      //   $relation_item_count = count($event[$relation]['data']);
      //   if($relation_item_count) {
      //     echo "<h4>$relation</h4>";
      //     for($y = 0; $y < $relation_item_count; $y++){
      //       $name = $event[$relation]['data'][$y]['name'];
      //       $rsvp = $event[$relation]['data'][$y]['rsvp_status'];
      //
      //       echo "<li><h5>$name</h5><p>$rsvp</p></li>";
      //     }
      //   }
      // }
      // echo '</ul>';
    }
    echo "</div>";
  }
