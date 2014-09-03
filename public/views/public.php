<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 */


// This file is used to markup the public facing aspect of the plugin.
// Here we will grab a random quote and output to the public view
global $wpdb;
$table_name = $wpdb->prefix."rs_bruce_table";
$result = $wpdb->get_results("SELECT bruce_quote from $table_name ORDER BY rand() LIMIT 1",ARRAY_A);
if($result[0]['bruce_quote'] != ""){
	echo '<div class="widget-wrapper bruce-quote-wrapper"><blockquote>"'.$result[0]['bruce_quote'].'"<div class="quote">Bruce Lee</div></blockquote></div>';
}
?>
