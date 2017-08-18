<?php

   // TO CHANGE!
   require_once( "../../../../../wp-config.php" );

/**
 *  Posts
 *
 */
if (isset($_REQUEST)) {

   if ($_REQUEST['keys']) {
      foreach ($_REQUEST['keys'] as $key => $value) {
        $item = explode(':', $value);
        $data[] = array($item[0] => $item[1]);
      }
    }

  foreach ($data as $key => $value) {
    foreach ($value as $type => $ids) {
      switch ($type) {
        case 'post':
          $postIds = $type;
          $Val = split(',', $ids);
          break;
        case 'page':
          $pageIds = $type;
          $Val = split(',', $ids);
          break;
        case 'categories':
          $catIds = $type;
          $catVal = split(',', $ids);
          break;
        case 'tags':
          $tagIds = $type;
          $tagVal = split(',', $ids);
          break;
      }
    }
  }

  $args = array(
    'post_type' => $type,
    'post__in'  => $Val,
  );

  $the_query = new WP_Query( $args );

  // The Loop
  if ( $the_query->have_posts() ) {
    $result = '<ul>';
    while ( $the_query->have_posts() ) {
      $the_query->the_post();
      $result .= '<li>' . get_the_title() . '</li>';
    }
    $result .= '</ul>';

  } else {
    $result = 'No Pages Found.';
  }
   echo $result;

}



