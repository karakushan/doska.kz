<?php

function directorypress_pull_current_listing_admin() {
	global $directorypress_object;
	
	return $directorypress_object->current_listing;
}
function directorypress_is_admin_directory_page() {
	global $pagenow;

	if (
		is_admin() &&
		(($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = directorypress_get_input_value($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(DIRECTORYPRESS_LOCATIONS_TAX, DIRECTORYPRESS_CATEGORIES_TAX, DIRECTORYPRESS_TAGS_TAX)))
		) ||
		(($page = directorypress_get_input_value($_GET, 'page')) &&
				(in_array($page,
						array(
								'directorypress-admin-panel',
								'directorypress_admin_settings',
								'directorypress_directorytypes',
								'directorypress_packages',
								//'directorypress_manage_upgrades',
								'directorypress_locations_depths',
								'directorypress_fields',
								//'directorypress_csv_import',
								'directorypress_renew',
								'directorypress_upgrade',
								'directorypress_changedate',
								'directorypress_raise_up',
								'directorypress_upgrade',
								'directorypress_upgrade_bulk',
								'directorypress_process_claim',
								'directorypress_choose_package'
						)
				))
		)
	) {
		return true;
	}
}
function directorypress_is_admin_terms_page() {
	global $pagenow;

	if (
		is_admin() &&
		(($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = directorypress_get_input_value($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(DIRECTORYPRESS_CATEGORIES_TAX)))
		)
	) {
		return true;
	}
}
function directorypress_is_listing_admin_edit_page() {
	global $pagenow;

	if (
		($pagenow == 'post-new.php' && ($post_type = directorypress_get_input_value($_GET, 'post_type')) &&
				(in_array($post_type, array(DIRECTORYPRESS_POST_TYPE)))
		) ||
		($pagenow == 'post.php' && ($post_id = directorypress_get_input_value($_GET, 'post')) && ($post = get_post($post_id)) &&
				(in_array($post->post_type, array(DIRECTORYPRESS_POST_TYPE)))
		)
	) {
		return true;
	}
}
add_action('directorypress_reduxt_custom_header_before', 'directorypress_redux_template_header_before');
function directorypress_redux_template_header_before(){
	if(isset($_GET['page'])){
		if($_GET['page'] == 'directorypress_settings'){
			echo '<div class="wrap about-wrap directorypress-admin-wrap">';
				DirectoryPress_Admin_Panel::listing_dashboard_header();
				echo '<div class="directorypress-plugins directorypress-theme-browser-wrap';
					echo '<div class="theme-browser rendered">';
						echo '<div class="directorypress-box">';
							echo '<div class="directorypress-box-head">';
								echo '<h1>'. esc_html__('DirectoryPress Settings', 'DIRECTORYPRESS').'</h1>';
								echo '<p>'. esc_html__('All DirectoryPress Settings can be handle here', 'DIRECTORYPRESS').'</p>';
								echo '<a href="https://www.youtube.com/@DesigninventoSupport/videos" target="_blank" style="width:200px;margin:40px auto 0;display:block;"><img src="'. esc_url(DIRECTORYPRESS_RESOURCES_URL .'images/vt.jpg') .'" alt="'. esc_attr__('tutorials', 'DIRECTORYPRESS') .'" /></a>';
							echo '</div>';
							echo '<div class="directorypress-box-content wp-clearfix">';
		}
	}
}
add_action('directorypress_reduxt_custom_header_after', 'directorypress_redux_template_header_after');
function directorypress_redux_template_header_after(){
	echo '</div></div></div></div></div></div>';
}

add_action('directorypress_tutorial_section', 'directorypress_tutorial_section');
function directorypress_tutorial_section($page = 'fields'){
	//if(isset($_GET['page'])){
		$data_file = file_get_contents('https://assets.designinvento.net/tutorials/directorypress/theme.json');
		$data = json_decode($data_file, true); // decode the JSON into an associative array
		//echo '<pre>' . print_r($data, true) . '</pre>';
		?>
		
		<div class="row sticky-top">
			<?php if($data[$page]): ?>
				<div class="col-12">
					<div class="directorypress-admin-side-card">
						<ul class="list-group list-group-flush">
							<img src="<?php echo esc_url(DIRECTORYPRESS_RESOURCES_URL .'images/vt.jpg'); ?>" alt="<?php esc_attr_e('tutorials', 'DIRECTORYPRESS'); ?>" style="width:150px;margin:0 0 15px;" />
							<?php foreach($data[$page] AS $key=>$value): ?>
								<li class="list-group-item list-group-item-col3"><a href="<?php echo esc_url($value); ?>" target="_blank"><img src="<?php echo esc_url(DIRECTORYPRESS_RESOURCES_URL .'images/youtube.png'); ?>" alt="<?php echo esc_attr__('tutorials', 'DIRECTORYPRESS'); ?>"><?php echo esc_html($key); ?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php foreach($data['support'] AS $key=>$value): ?>
							<a class="btn btn-outline-primary btn-sm" href="<?php echo esc_url($value); ?>"><?php echo esc_html($key); ?></a>
						<?php endforeach; ?>
						<?php foreach($data['forum'] AS $key=>$value): ?>
							<a class="btn btn-outline-success btn-sm" href="<?php echo esc_url($value); ?>"><?php echo esc_html($key); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if($data['banners']): ?>
				<?php foreach($data['banners'] as $src=>$link): ?>
					<div class="col-12 mb-4">
						<a href="<?php echo esc_url($link); ?>" target="_blank"><img src="<?php echo esc_url($src); ?>" /></a>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
<?php
	//}
}