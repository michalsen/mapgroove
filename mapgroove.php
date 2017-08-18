<?php
/*
Plugin Name: Map Groove
Plugin URI: http://localhost
Description: Presenting Wordpress content since 1975.
Version:     1
Author: Eric L. Michalsen
Author URI:
Text Domain: wporg
Domain Path: /languages
License:     GPL2

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}


// If admin include admin functions
if(admin){
  include_once( 'includes/admin/class-mapgroove-admin.php' );
}

// Include the rest of the functions
include_once( 'includes/mapgroove-search.php' );


// Plugin install hook
register_activation_hook( __FILE__, 'jal_install' );


// Assign shortcode to appropriate TPL file
function mapgroove_search(){
  // If table row is called
  if (isset($_REQUEST['row'])) {
    include( 'includes/mapgroove_detail.tpl.php' );
  }
   else {
    // Else show map and table
    include( 'includes/mapgroove.tpl.php' );
    return $form;
  }
}

// Define shortcode
add_shortcode('mapgroove', 'mapgroove_search');



/**
 *  MapGroove DB Table
 */
function jal_install () {
  global $wpdb;
  global $jal_db_version;
  $table_name = $wpdb->prefix . 'mapgroove';
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            xml_url varchar(255),
            field_to_from text NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
  add_option( 'jal_db_version', $jal_db_version );
}
