MapGroove
v.02

mapgroove.php
```function mapgroove_enqueue_style() {
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

function geolocate() -> function address_geocode(() (google API geolocate)

function post2address
Saves geo data in post_type

function mapgroove_install()
Table for API default_path

filter.tpl.php
Drop down filter


listings.php
AJAX map data
