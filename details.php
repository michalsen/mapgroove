<?php

$data = [];

include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

$return = [];
foreach ($_POST as $k => $v) {

    // print_r(get_taxonomies());

    $tmp = get_term_by('id', $v['services'], 'location_services');
    $return[$v['id']]['service'] = $tmp->name;

    $tmp = get_term_by('id', $v['markets'], 'location_market_solutions');
    $return[$v['id']]['markets'] = $tmp->name;

    $tmp = get_term_by('id', $v['companies'], 'location_companies');
    $return[$v['id']]['companies'] = $tmp->name;

    $tmp = get_term_by('id', $v['careers'], 'location_careers');
    $return[$v['id']]['careers'] = $tmp->name;
}

print json_encode($return);