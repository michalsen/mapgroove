<?php

$data = [];
$meta_query = ['relation' => 'OR'];
foreach ($_POST as $field => $value) {
	$data[$field] = $value;
	if ($value <> '0') {
		array_push($meta_query, ['key' => $field, 'value' => $value]);
		if ($field == 'type') {
			$querytype = $value;
		}
	}
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

$IRS = ['key' => 'type',
	    'value' => $querytype];

if (array_search('type', array_column($meta_query, 'key'))) {
	unset($meta_query);
	$meta_query[] = $IRS;
}

$args = array(
	'posts_per_page' => -1,
	'post_type'      => 'post',
	'meta_query'     => $meta_query
);

$the_query = new WP_Query( $args );

$address = [];
foreach ($the_query->posts as $key => $post) {
	$meta = get_post_meta($post->ID);

			$address[ $post->ID ]['id']     = $post->ID;
			$address[ $post->ID ]['name']   = get_the_title();
			$address[ $post->ID ]['street'] = get_post_meta( get_the_id(), 'street' )[0];
			$address[ $post->ID ]['city']   = get_post_meta( get_the_id(), 'city' )[0];
			$address[ $post->ID ]['state']  = get_post_meta( get_the_id(), 'state' )[0];
			$address[ $post->ID ]['zip']    = get_post_meta( get_the_id(), 'zip' )[0];
			$address[ $post->ID ]['lat']    = get_post_meta( get_the_id(), 'latitude' )[0];
			$address[ $post->ID ]['lng']    = get_post_meta( get_the_id(), 'longitude' )[0];
			$address[ $post->ID ]['part']   = get_post_meta( get_the_id(), 'part' )[0];
			$address[ $post->ID ]['data'] = json_encode( $data );

}

print json_encode($address);

