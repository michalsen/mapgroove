<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}



// Search Assests (js/css)
function mapping_assets() {
    // Leaflet Mapping
    wp_enqueue_style('searchCSS', plugins_url() . "/mapgroove/assets/css/search.css" );
    wp_enqueue_style('searchCSS');

    wp_enqueue_style('leafletCSS', plugins_url() . "/mapgroove/assets/css/leaflet.css" );
    wp_enqueue_style('leafletCSS');

    wp_enqueue_script('jquery');
    wp_enqueue_script('leafletscript',  plugins_url() . "/mapgroove/assets/js/leaflet.js" );
    wp_enqueue_script('tablesorterscript',  plugins_url() . "/mapgroove/assets/js/jquery.tablesorter.min.js" );
    wp_enqueue_script('search',  plugins_url() . "/mapgroove/assets/js/search.js", '', '1.0', true);
}


add_action( 'wp_enqueue_scripts', 'mapping_assets' );


// Sets Transient API
function saveXML($xml) {
  set_transient( 'mapgroove_xml', $xml, 60*60 );
}


// Finds cities within state JSON files in geodata and returns lat/lon
function getGeo($city, $state) {
  $json = file_get_contents(plugins_url() . '/mapgroove/includes/geodata/' . $state . '.json');
  $ob = json_decode($json);
  if (count($ob->result) > 0) {
    foreach ($ob->result as $key => $value) {
      if (strtoupper(trim($city)) === $value->City && $state === $value->State) {
        return preg_replace('/\+/', '', $value->Latitude) . ',' . preg_replace('/\+/', '', $value->Longitude);
      }
    }
  }
}


// When a city can not be found, Google geocode is called and returns a lat/lon
// TOFIX: api key is current hardcoded
// TOADD: city lat/lon should be added to state JSON file in geodata
function getGoogleGeo($city, $state) {
  $city_state = preg_replace('/ /', '', $city . ',' . $state);
  $city_state = preg_replace('/\//', '_', $city_state);
  $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $city_state . '&&key=AIzaSyCERrd1-fjZEBYpXqCTkAM9SvmwnUCD4x4';
  $result = file_get_contents($url);
  $return = json_decode($result, true);
  return $return;
}
