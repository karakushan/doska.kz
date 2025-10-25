<?php

//database update
if(!get_option('pacz_directorypress_3_4_0')){
	pacz_directorypress_3_4_0_fix();
}

function pacz_directorypress_3_4_0_fix(){
	global $wpdb;
	$prefix = $wpdb->prefix;

	$old_values = array('"location_style":"1"','"location_style":"2"','"location_style":"3"','"location_style":"5"','"location_style":"6"','"location_style":"7"','"location_style":"10"');
	$new = '"location_style":"custom"';
	foreach($old_values AS $old_value){
		$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$old_value."', '".$new."')");
	}
	$old_value = '"location_style":"4"';
	$new_value = '"location_style":"2"';
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$old_value."', '".$new_value."')");
	
	$old_value = '"location_style":"8"';
	$new_value = '"location_style":"3"';
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$old_value."', '".$new_value."')");
	
	
	$old_value = '"location_style":"0"';
	$new_value = '"location_style":"default"';
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$old_value."', '".$new_value."')");
	
	
	$old_value = '"location_style":"9"';
	$new_value = '"location_style":"default"';
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$old_value."', '".$new_value."')");
	
	// shortcode update
	
	$old_value_1 = 'location_style="1"';
	$old_value_2 = 'location_style="2"';
	$old_value_3 = 'location_style="3"';
	$old_value_4 = 'location_style="4"';
	$old_value_5 = 'location_style="5"';
	$old_value_6 = 'location_style="6"';
	$old_value_7 = 'location_style="7"';
	$old_value_8 = 'location_style="8"';
	$old_value_9 = 'location_style="9"';
	$old_value_10 = 'location_style="10"';
	
	$new_value = 'location_style="custom"';
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_1."', '".$new_value."')");
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_2."', '".$new_value."')");
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_3."', '".$new_value."')");
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_5."', '".$new_value."')");
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_6."', '".$new_value."')");
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_7."', '".$new_value."')");
	
	$new_value = 'location_style="2"';
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_4."', '".$new_value."')");
	$new_value = 'location_style="3"';
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_8."', '".$new_value."')");
	$new_value = 'location_style="default"';
	$wpdb->query("UPDATE `".$prefix."posts` SET `post_content` = replace(post_content, '".$old_value_9."', '".$new_value."')");
	
	add_option('pacz_directorypress_3_4_0', 1);
	
	
}
if(!get_option('pacz_directorypress_3_4_2')){
	pacz_directorypress_3_4_2_fix();
}

function pacz_directorypress_3_4_2_fix(){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$quote = '"';
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."2".$quote."', '".$quote."columns_set2".$quote.":".$quote."1".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"1\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."3".$quote."', '".$quote."columns_set2".$quote.":".$quote."1".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"1\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."4".$quote."', '".$quote."columns_set2".$quote.":".$quote."1".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"1\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."5".$quote."', '".$quote."columns_set2".$quote.":".$quote."1".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"1\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."6".$quote."', '".$quote."columns_set2".$quote.":".$quote."1".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"1\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."inline".$quote."', '".$quote."columns_set2".$quote.":".$quote."1".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"1\"%')");
	
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."1".$quote."', '".$quote."columns_set2".$quote.":".$quote."2".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"2\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."3".$quote."', '".$quote."columns_set2".$quote.":".$quote."2".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"2\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."4".$quote."', '".$quote."columns_set2".$quote.":".$quote."2".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"2\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."5".$quote."', '".$quote."columns_set2".$quote.":".$quote."2".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"2\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."6".$quote."', '".$quote."columns_set2".$quote.":".$quote."2".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"2\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."inline".$quote."', '".$quote."columns_set2".$quote.":".$quote."2".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"2\"%')");
	
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."1".$quote."', '".$quote."columns_set2".$quote.":".$quote."3".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"3\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."2".$quote."', '".$quote."columns_set2".$quote.":".$quote."3".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"3\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."4".$quote."', '".$quote."columns_set2".$quote.":".$quote."3".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"3\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."5".$quote."', '".$quote."columns_set2".$quote.":".$quote."3".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"3\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."6".$quote."', '".$quote."columns_set2".$quote.":".$quote."3".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"3\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."inline".$quote."', '".$quote."columns_set2".$quote.":".$quote."3".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"3\"%')");
	
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."1".$quote."', '".$quote."columns_set2".$quote.":".$quote."4".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"4\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."2".$quote."', '".$quote."columns_set2".$quote.":".$quote."4".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"4\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."3".$quote."', '".$quote."columns_set2".$quote.":".$quote."4".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"4\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."5".$quote."', '".$quote."columns_set2".$quote.":".$quote."4".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"4\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."6".$quote."', '".$quote."columns_set2".$quote.":".$quote."4".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"4\"%')");
	$wpdb->query("UPDATE `".$prefix."postmeta` SET `meta_value` = replace(meta_value, '".$quote."columns_set2".$quote.":".$quote."inline".$quote."', '".$quote."columns_set2".$quote.":".$quote."4".$quote."') WHERE ((CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"default\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"3\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"6\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"7\"%' OR CONVERT(`meta_value` USING utf8) LIKE '%\"cat_style\":\"10\"%') AND CONVERT(`meta_value` USING utf8) LIKE '%\"columns_set1\":\"4\"%')");
	
	add_option('pacz_directorypress_3_4_2', 1);
	
}
if(!get_option('pacz_classiadspro_db_5_10_5')){
	pacz_classiadspro_db_5_10_5();
}
function pacz_classiadspro_db_5_10_5(){
	if(class_exists('Redux')){
		Redux::set_option('pacz_settings', 'logo_dimensions','');
		add_option('pacz_classiadspro_db_5_10_5', 1);
	}
}
if(!get_option('pacz_classiadspro_db_6_0_0')){
	pacz_classiadspro_db_6_0_0();
}
function pacz_classiadspro_db_6_0_0(){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$terms = $wpdb->get_results('SELECT term_id FROM '.$wpdb->prefix.'termmeta');
	foreach($terms AS $term){
		delete_term_meta($term->term_id, 'category-svg-icon-id');
		delete_term_meta($term->term_id, 'category-svg-image-id');
	}
	add_option('pacz_classiadspro_db_6_0_0', 1);
}
