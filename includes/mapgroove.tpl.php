<?php
echo 'Showing Jobs for: All Locations';

$xml_load = getXML();
$cached_XML = get_transient('mapgroove_xml');

if ($cached_XML == false) {
  $cached_XML = getContent($xml_load[0]->xml_url);
  saveXML($cached_XML);
}


  $fieldMapping = $xml_load[0]->field_to_from;
  $fieldsmapped = json_decode($fieldMapping);


  /**
   *  This is a bit rough.
   *  Sorry.
   */
  $i++;
  $row = [];
  foreach ($cached_XML as $key => $value) {
    foreach ($value as $vkey => $vvalue) {
      $head = [];
      foreach ($vvalue as $contentkey => $contentvalue) {
        foreach ($fieldsmapped as $mappedkey => $mappedvalue) {
          if ($contentkey == $mappedvalue->xml) {
            $row[$i][$mappedvalue->human] = $contentvalue;
          }
        }
      }
      $i++;
    }
  }


  // TOFIX: sortOrder hardcoded
  // TOADD: add sort order to fields in admin screen
  $sortOrder = ['Job Title',
                'City',
                'State',
                'Start Date',
                'Pay Rate'];


  foreach ($row as $key => $value) {
    uksort($value, function($key1, $key2) use ($sortOrder ) {
      return (array_search($key1, $sortOrder ) > array_search($key2, $sortOrder ));
    });
    unset($buildRow[$key]);
    $buildRow[$key] = $value;
  }


  /**
   *  Let's build this bad boy
   *  Hey look, another foreach()
   */
  $row = '<tr id="row_1">';
  $row .= '<form id="searchForm">';
  foreach ($sortOrder as $name) {
      $row .= '<td>';
        $row .= '<input type=text id="' . $name . '" class="filter_search" name="' . $name . '">';
      $row .= '</td>';
    }
  $row .= '</tr>';
  $row .= '</form>';

  $i=2;
  $markers = [];
  foreach ($buildRow as $key => $value) {
    $geo = getGeo($value['City'],  $value['State']);
    array_push($markers, $geo);


    $head = '<thead><tr id="row_0">';
    $row .= '<tr id="row_' . $i . '" class="clickrow" data-points="' . $geo . '">';
      foreach ($value as $title => $detail) {
        $head .= '<th>' . $title . '</th>';
        $row .= '<td>' . $detail . '</td>';

      }
    $head .= '</thead>';
    $row .= '</tr>';
    $i++;
  }

  print '<div class="hidden_markers">';
  print json_encode($markers, JSON_PRETTY_PRINT);
  print '</div>';

  print '<div id="mapid" style="width: 1200px; height: 400px;"></div>';
  print '<table id="dataTable" class="tablesorter">';
  print $head;
  print $row;
  print '</table>';

