<?php


include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

$return = '';
if (count($_REQUEST) > 0) {
  foreach ($_REQUEST as $type => $item) {

    if ($type == 'save_map') {
      $mappingData = [];
      $tempArray = [];

      $i=0;
      foreach ($item as $key => $value) {
        if ($key % 2 == 0) {
          $tempArray[$i]['xml'] = $value;
        }
         else {
           $tempArray[$i-1]['human'] = $value;
         }

      $i++;

      }

      array_pop($tempArray);
      array_unshift($tempArray, array(1=>1));
      array_shift($tempArray);

      global $wpdb;
      $table_name = $wpdb->prefix . "mapgroove";
      $sql = $wpdb->prepare("UPDATE $table_name SET
            field_to_from = '%s'
            WHERE id = %d", json_encode($tempArray), 1);
      $wpdb->query($sql);

      $return = 'Fields saved.';

    }
  }
}
 else {
  $return = 'error.';
}

echo $return;
