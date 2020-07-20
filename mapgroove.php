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

// Assign the map API Key to a js object for Leaflet
$xml = getXML();
$variable_array = array(
	'mg_token' => __($xml[0]->api_key),
);

function mapgroove_enqueue_style() {
	wp_enqueue_style( 'leafletCSS', "https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" );
	wp_enqueue_style( 'leafletCSS' );
	wp_enqueue_style( 'mapgrooveCSS', plugins_url() . "/mapgroove/assets/css/mapgroove.css" );
	wp_enqueue_style( 'mapgrooveCSS' );
}

function mapgroove_enqueue_script() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'leaflet.js', 'https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', false );
	wp_enqueue_script( 'leaflet.js' );
	wp_enqueue_script( 'mapgrooveJS', plugins_url() . "/mapgroove/assets/js/mapgroove.js" );
	wp_enqueue_script( 'mapgrooveJS' );
}

add_action( 'wp_enqueue_scripts', 'mapgroove_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'mapgroove_enqueue_script' );


/*
 * *
 * * MAP FILTER SHORTCODE
 * * ./templates/filter.tpl.php
 */

add_shortcode('map_search', 'map_search_form');

function map_search_form() {
	return filter_get_template( 'filter.tpl.php' );

}

function filter_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = filter_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	include $template_file;
}
function filter_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) :
		$template_path = 'templates/';
	endif;
	if ( ! $default_path ) :
		$default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the template folder
	endif;
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'filter_locate_template', $template, $template_name, $template_path, $default_path );
}
/*
 * *
 * *
 * *
 */


// Plugin install hooks
register_activation_hook( __FILE__, 'mapgroove_install' );
register_deactivation_hook( __FILE__, 'mapgroove_remove' );

function filterMap($listings) {
	// $data = file_get_contents('/wp-content/plugins/mapgroove/listings.php');
	exit;
}
add_action('wp_ajax_folder_contents', 'filterMap');
add_action('wp_ajax_nopriv_folder_contents', 'filterMap');


function getMarkers() {
	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'post',
	);
	$the_query = new WP_Query( $args );

	$address = [];
	foreach ($the_query->posts as $key => $post) {
		$meta = get_post_meta($post->ID);

		$address[$post->ID]['id']     = $post->ID;
		$address[$post->ID]['name']   = get_the_title();
		$address[$post->ID]['street'] = $meta['street'][0];
		$address[$post->ID]['city']   = $meta['city'][0];
		$address[$post->ID]['state']  = $meta['state'][0];
		$address[$post->ID]['zip']    = $meta['zip'][0];
		$address[$post->ID]['lat']    = $meta['longitude'][0];
		$address[$post->ID]['lng']    = $meta['latitude'][0];
	}
	return $address;

}


add_action( 'save_post', 'geolocate', 10,3 );

function geolocate( $post_id, $post, $update ) {

	$latlon = address_geocode($_POST["acf"]["field_5efcafadfc207"],
		                       $_POST["acf"]["field_5efcb008fc208"],
		                       $_POST["acf"]["field_5efcb00efc209"],
		                       $_POST["acf"]["field_5efcb013fc20a"]);


	update_field('field_5f033fa4bbe6d', $latlon["latitude"], $post_id);
	update_field('field_5f033fe45047f', $latlon["longitude"], $post_id);

}

function address_geocode($street_address, $city, $state){

	$api = getXML();
    $apiKey = $api[0]->api_key;

	$street_address = str_replace(" ", "+", $street_address); //google doesn't like spaces in urls, but who does?
	$city = str_replace(" ", "+", $city);
	$state = str_replace(" ", "+", $state);

	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$street_address,$city,$state&sensor=false&key=$apiKey";

	$google_api_response = wp_remote_get( $url );

	$results = json_decode( $google_api_response['body'] ); //grab our results from Google
	$results = (array) $results; //cast them to an array
	$status = $results["status"]; //easily use our status
	$location_all_fields = (array) $results["results"][0];
	$location_geometry = (array) $location_all_fields["geometry"];
	$location_lat_long = (array) $location_geometry["location"];

	if( $status == 'OK'){
		$latitude = $location_lat_long["lat"];
		$longitude = $location_lat_long["lng"];
	}else{
		$latitude = '';
		$longitude = '';
	}

	$return = array(
		'latitude'  => $latitude,
		'longitude' => $longitude
	);

	return $return;
}


function post2address($postID) {
	$args = array(
		'p'                => $postID,
		'post_type'        => 'post',
	);
	$the_query = new WP_Query( $args );

	$address = [];
	foreach ($the_query->posts as $key => $post) {
		$meta = get_post_meta($post->ID);
		$address[$post->ID]['id']     = $post->ID;
		$address[$post->ID]['name']   = get_the_title();
		$address[$post->ID]['street'] = get_post_meta(get_the_id(), 'street')[0];
		$address[$post->ID]['city']   = get_post_meta(get_the_id(), 'city')[0];
		$address[$post->ID]['state']  = get_post_meta(get_the_id(), 'state')[0];
		$address[$post->ID]['zip']    = get_post_meta(get_the_id(), 'zip')[0];
	}
	return $address;
}




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
//    if ( ! is_plugin_active( 'sn_helper/sn_helper.php' ) and current_user_can( 'activate_plugins' ) ) {
//        wp_die('The <a href="https://github.com/michalsen/sn_helper" target=_blank>SN Helper Plugin</a> to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
//    }


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
