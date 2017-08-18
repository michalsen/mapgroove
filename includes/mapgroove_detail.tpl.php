<?php
echo 'Job Detail';

$xml_load = getXML();
$cached_XML = get_transient('mapgroove_xml');

if ($cached_XML == false) {
  $cached_XML = getContent($xml_load[0]->xml_url);
  saveXML($cached_XML);
}

  $row = [];
  foreach ($cached_XML as $key => $value) {
    foreach ($value as $vkey => $vvalue) {
      if ($vkey == $_REQUEST['row']) {
        foreach ($vvalue as $contentkey => $contentvalue) {
          $row[] = $contentvalue;
        }
      }
    }
  }

?>

<div class="job">
<strong><?php print $row[14]; ?></strong><br>

<div class="job_detail">
  <?php print $row[3]; ?>
</div>


<div class="job_table">
<strong>Job Specifics</strong>
  <table>
    <tr>
      <td>
         Job Title:
      </td>
      <td>
        <?php print $row[14]; ?>
      </td>
    </tr>

    <tr>
      <td>
         City:
      </td>
      <td>
        <?php print $row[2]; ?>
      </td>
    </tr>

    <tr>
      <td>
         State:
      </td>
      <td>
        <?php print $row[12]; ?>
      </td>
    </tr>

    <tr>
      <td>
         Site/Terminal:
      </td>
      <td>
        <?php print $row[13]; ?>
      </td>
    </tr>

    <tr>
      <td>
         Start Date:
      </td>
      <td>
        <?php print $row[11]; ?>
      </td>
    </tr>

    <tr>
      <td>
         Pay Rate:
      </td>
      <td>
        <?php print $row[9]; ?>
      </td>
    </tr>

    <tr>
      <td>
         CDL Class:
      </td>
      <td>
        <?php print $row[1]; ?>
      </td>
    </tr>

    <tr>
      <td>
         Type:
      </td>
      <td>
        <?php print $row[15]; ?>
      </td>
    </tr>

    <tr>
      <td>
         Driver Domicile:
      </td>
      <td>
        <?php print $row[4]; ?>
      </td>
    </tr>

  </table>

  <a href="<?php print $row[6]; ?>"><button>Apply Online Today</button></a>
</div>


</div>
