<?php

$data = [];

include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

$args = array(
	'posts_per_page' => -1,
	'post_type'      => 'location',
);

$the_query = new WP_Query( $args );

$address = [];
foreach ($the_query->posts as $key => $post) {
    $meta[$post->ID] = get_post_meta($post->ID);

//    print_r($meta);

    $address[$post->ID]['id'] = $post->ID;
    $address[$post->ID]['name'] = get_the_title();

    $address[$post->ID]['state'] = get_post_meta(get_the_id(), 'location_-_state')[0];
    $address[$post->ID]['region'] = get_post_meta(get_the_id(), 'location_-_region')[0];

    $address[$post->ID]['services'] = get_post_meta(get_the_id(), 'services')[0];
    $address[$post->ID]['markets'] = get_post_meta(get_the_id(), 'market_solutions')[0];
    $address[$post->ID]['companies'] = get_post_meta(get_the_id(), 'companies')[0];
    $address[$post->ID]['careers'] = get_post_meta(get_the_id(), 'careers')[0];


    $address[$post->ID]['lat'] = get_post_meta(get_the_id(), 'longitude')[0];
    $address[$post->ID]['lng'] = get_post_meta(get_the_id(), 'latitude')[0];

    $address[$post->ID]['street'] = get_post_meta(get_the_id(), 'location_-_street_address')[0];
    $address[$post->ID]['city'] = get_post_meta(get_the_id(), 'location_-_city')[0];
    $address[$post->ID]['phone'] = get_post_meta(get_the_id(), 'location_-_phone')[0];
    $address[$post->ID]['fax'] = get_post_meta(get_the_id(), 'location_-_fax_number')[0];

    $address[$post->ID]['popup_text'] =
                                        '<strong>' . $address[$post->ID]['name'] . '</strong><br>' .
                                        $address[$post->ID]['street'] . ', ' .
                                        $address[$post->ID]['city'] . ', ' .
                                        $address[$post->ID]['state'] . '<br>' .
                                        'Phone: ' . $address[$post->ID]['phone'] . ' <br>' .
                                        'Fax: ' . $address[$post->ID]['fax'];


    $address[$post->ID]['data'] = json_encode($data);

    if (isset($_POST['location_-_state']) &&
        $_POST['location_-_state'] == '0' &&
        isset($_POST['location_-_region']) &&
        $_POST['location_-_region'] == '0') {

    }
        else {

            if (isset($_POST['location_-_state']) &&
                $_POST['location_-_state'] <> '0' &&
                $_POST['location_-_state'] <> $address[$post->ID]['state']) {
                unset($address[$post->ID]);
            }

            if (isset($_POST['location_-_region']) &&
                $_POST['location_-_region'] <> '0' &&
                $_POST['location_-_region'] <> $address[$post->ID]['region']) {
                unset($address[$post->ID]);
            }
        }

            if (isset($_POST['services']) &&
                $_POST['services'] != '0' ||
                isset($_POST['markets']) &&
                $_POST['markets'] != '0' ||
                isset($_POST['companies']) &&
                $_POST['companies'] != '0' ||
                isset($_POST['careers']) &&
                $_POST['careers'] != '0' ) {

                if (isset($_POST['services']) &&
                    $_POST['services'] != 0 &&
                    $_POST['services'] <> $address[$post->ID]['services']) {
                    unset($address[$post->ID]);
                }
                if (isset($_POST['markets']) &&
                    $_POST['markets'] != 0 &&
                    $_POST['markets'] <> $address[$post->ID]['markets']) {
                    unset($address[$post->ID]);
                }
                if (isset($_POST['companies']) &&
                    $_POST['companies'] != 0 &&
                    $_POST['companies'] <> $address[$post->ID]['companies']) {
                    unset($address[$post->ID]);
                }
                if (isset($_POST['careers']) &&
                    $_POST['careers'] != 0 &&
                    $_POST['careers'] <> $address[$post->ID]['careers']) {
                    unset($address[$post->ID]);
                }
            }
    }


print json_encode($address);

