<?php


$posts = get_posts(array(
	'posts_per_page'	=> -1,
	'post_type'			=> 'post'
));


$selectDrop = [];
$filter = [];

foreach( $posts as $post ) {
	$meta = get_post_meta($post->ID);
	$filter['selectpart'][]  = $meta['part'][0];
	$filter['selectcity'][]  = $meta['city'][0];
	// $filter['selectstate'][] = $meta['state'][0];
	$filter['selecttype'][]  = $meta['type'][0];
}

$typeselection = array_unique($filter);

foreach ($filter as $selection => $name) {
	$name = array_unique($name);
	sort($name);
	if ($selection == 'selecttype') {
	    $form .= ' OR ';
    }
	$form .= '<select id="' . $selection . '">';
	$selectionName = preg_replace('/select/i', '', $selection);
	$form .= '<option value="0"> ' . ucfirst($selectionName) . ' </option>';
	    foreach ($name as $key => $type) {
		    $form .= '<option value="' . $type . '">' . $type . '</option>';
        }
    $form .= '</select>';

}
?>

<form id="searchform_query">
    <?php print $form; ?>
</form>

