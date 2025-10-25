<?php
if(!get_option('directorypress_advanced_fields_db_update_1_0_0') || get_option('directorypress_advanced_fields_db_update_1_0_0') != 'updated'){
	directorypress_advanced_fields_db_update_1_0_0();
}
function directorypress_advanced_fields_db_update_1_0_0(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;

	if (!$wpdb->get_var("SELECT id, is_search_configuration_page FROM `".$prefix."directorypress_fields` WHERE slug = 'status'  AND type = 'status'")){
		$wpdb->query("INSERT INTO `".$prefix."directorypress_fields` (`is_core_field`, `order_num`, `name`, `slug`, `description`, `fieldwidth`, `fieldwidth_archive`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `checkbox_icon_type`, `search_options`, `group_id`) VALUES(1, 6, 'Status', 'status', '', '', '', 'status', '', 0, 1, 1, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', 'a:2:{s:15:\"selection_items\";a:3:{i:1;s:8:\"For Sale\";i:2;s:8:\"For Rent\";i:3;s:6:\"Wanted\";}s:11:\"color_codes\";a:3:{i:1;s:7:\"#81d742\";i:2;s:7:\"#1e73be\";i:3;s:7:\"#dd9933\";}}', '', '', 0);");
	}elseif ($wpdb->get_var("SELECT id FROM `".$prefix."directorypress_fields` WHERE slug = 'status' AND is_search_configuration_page = '0'  AND type = 'status'")){
		$wpdb->query("UPDATE `".$prefix."directorypress_fields` SET is_search_configuration_page='1' WHERE slug='status' AND type = 'status'");
	}
	update_option('directorypress_advanced_fields_db_update_1_0_0', 'updated');
}