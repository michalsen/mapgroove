<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class MapGroove_Admin {

  public function __construct() {
    add_action('admin_init', array( $this, 'wp_mapgroove_admin_init'));
    add_action('admin_init', array( $this, 'admin_scripts_style'));
    add_action('admin_menu', array( $this, 'admin_menu'));
  }

  public function admin_scripts_style() {
    if (isset($_REQUEST['page'])) {
      if ($_REQUEST['page'] == "mapgroove") {

        wp_enqueue_style('mapgrooveCSS', plugins_url() . "/mapgroove/assets/css/mapgroove.css" );
        wp_enqueue_style('mapgrooveCSS');

        wp_enqueue_script('jquery');
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script('mapgrooveJS',  plugins_url() . "/mapgroove/assets/js/mapgroove.js" );
        wp_enqueue_script('mapgrooveJS');

        add_action( 'wp_enqueue_scripts', 'mapgrooveJS' );
      }
    }

  }


  public function admin_menu() {
    $page = add_management_page('Map Groove', 'Map Groove ', 'manage_options', 'mapgroove', array( $this,'wp_mapgroove_settings_page'));
  }


  function wp_mapgroove_admin_init() {
    if(is_admin()){
      //print plugins_url() . '<br>';
    }
  }

  public function wp_mapgroove_settings_page(){
    include('functions.php');
    include('mapgroove_admin.tpl.php');

  }

}


return new MapGroove_Admin();


function getXML() {
  global $wpdb;
  $sql = 'SELECT xml_url, field_to_from FROM ' . $wpdb->prefix . 'mapgroove ORDER BY TIME DESC LIMIT 1';
  $xml = $wpdb->get_results($sql);
  return $xml;
}



function getFields($item) {
  $rows = [];
  $xml = json_decode(file_get_contents($item));

  foreach ($xml as $key => $state) {
    foreach ($state as $obj => $detail) {
      if ($obj == 0) {
        foreach ($detail as $row => $job) {
          $rows[] = $row;
        }
      }
    }
  }
  return $rows;
}


function getContent($url) {
  $xml = json_decode(file_get_contents($url));
  return $xml;
}
