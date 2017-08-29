<?php

$get_XML = get_transient( 'mapgroove_xml' );
//print_r($get_XML);

$xml = getXML();
//print_r($xml);


if (strlen($xml[0]->xml_url) > 1) {
  $value = $xml[0]->xml_url;
  $fieldMapping = $xml[0]->field_to_from;
  $mapAPIkey = $xml[0]->api_key;
  $fieldsmapped = json_decode($fieldMapping);
  $rows = getFields($value);

  for ($i=0; $i < 51; $i++) {
    $options .= '<option value=' . $i . '>' . $i . '</option>';
  }

  $fieldRow = '<div id="fieldSets">';
  if (count($rows) > 1) {
    foreach ($rows as $field) {
     if ($fieldsmapped) {
       foreach ($fieldsmapped as $fkey => $fvalue) {
         if ($field === $fvalue->xml) {
           $fieldValue = $fvalue->human;
           $set = 'mapped';
         }
       }
     }

      $fields[] = $fieldValue;

      $fieldRow .= '<div id="left">' . $field . ':</div>' .
                   '<div id="right"><input type="text" value="' . $fieldValue . '" id="' . $field . '" class="fieldAdd"></div>';
      unset($fieldValue);
      $set = ' ';
      }
    }
    $fieldRow .= '<button id="setbutton" value="Save">Save</button>';
  $fieldRow .= '</div>';
}
 else {
  $value = '//';
 }

if (isset($mapAPIkey)) {
  $apikey = $mapAPIkey;
}
 else {
  $apikey = '';
 }


?>

    <div class="panel panel-info">
      <div class="panel-heading">
          <img src="<?php print plugins_url(); ?>/mapgroove/assets/images/mapgroove.png" width="200px">
       </div>
       <div class="panel-body">

    <div id="xml_button">
      <input type="text" value="<?php print $value; ?>" id="url" size="60">
      <button id="url_button" value="xml">XML URL</button><br>

      <input type="text" value="<?php print $apikey; ?>" id="api" size="60">
      <button id="api_button" value="api">API Key</button><br>


    </div>


    <div id="right">
      <h3>Search XML Mapping</h3>
      <div class="content_result">
        <div id="pagewrap">
          <?php
            print $fieldRow;
          ?>
        </div>
        <div class="datamapping">
        </div>
      </div>
    </div>


    <div class="clear"></div>



    </div>
  </div>



