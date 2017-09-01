<?php
/*
Plugin Name: Map Groove
Plugin URI: http://localhost
Description: Presenting Wordpress content since 1975.
Version:     1
Author: Eric L. Michalsen
Author URI:
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}


// If admin include admin functions
if('admin'){
  include_once( 'includes/admin/class-mapgroove-admin.php' );
}


// Include the rest of the functions
include( 'includes/mapgroove-search.php' );


// Plugin install hooks
register_activation_hook( __FILE__, 'mapgroove_install' );
register_deactivation_hook( __FILE__, 'mapgroove_remove' );


// Assign the map API Key to a js object for Leaflet
$xml = getXML();
//wp_register_script( 'mapgroove_handle', plugins_url() . '/mapgroove/assets/js/mapgroove.js' );
  $variable_array = array(
    'mg_token' => __($xml[0]->api_key),
  );

wp_localize_script( 'mapgroove_handle', 'php_vars', $variable_array );
wp_enqueue_script('jquery');
wp_enqueue_script( 'mapgroove_handle' );



// Assign shortcode to appropriate TPL file
function mapgroove_search(){
  // If table row is clicked, show detail
  if (isset($_REQUEST['row'])) {
    include( 'includes/mapgroove_detail.tpl.php' );
  }
   else {
    // Else show map and table

       wp_enqueue_script('leafletscript',  plugins_url() . "/mapgroove/assets/js/leaflet.js" );
       wp_enqueue_script('tablesorterscript',  plugins_url() . "/mapgroove/assets/js/jquery.tablesorter.min.js" );
       wp_enqueue_script('search',  plugins_url() . "/mapgroove/assets/js/search.js", '', '1.0', true);

       include( 'includes/mapgroove.tpl.php' );
    return $form;
  }
}


// Define shortcode
add_shortcode('mapgroove', 'mapgroove_search');



/**
 *  MapGroove DB Table
 */
function mapgroove_install () {

    // Require SN Helper plugin
    if ( ! is_plugin_active( 'sn_helper/sn_helper.php' ) and current_user_can( 'activate_plugins' ) ) {
        wp_die('The <a href="https://github.com/michalsen/sn_helper" target=_blank>SN Helper Plugin</a> to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }


  global $wpdb;
  global $jal_db_version;
  $table_name = $wpdb->prefix . 'mapgroove';
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            xml_url varchar(255),
            api_key varchar(255),
            field_to_from text NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
  add_option( 'jal_db_version', $jal_db_version );

  $wpdb->insert(
  $table_name,
  array(
    'id' => 1,
    'time' => current_time( 'mysql' ),
    'xml_url' => 'External Job URL',
    'api_key' => 'Mapping API KEY required',
    )
  );
}

function mapgroove_remove () {
  global $wpdb;
  $table_name = $wpdb->prefix . 'mapgroove';
  $sql = "DROP TABLE IF EXISTS $table_name;";
  $wpdb->query($sql);
}
