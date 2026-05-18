<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="directorypress-box">
				<div class="directorypress-box-head">
					<h1><?php _e('DirectoryPress Address Settings', 'directorypress-extended-locations'); ?></h1>
					<p><?php _e('You can create unlimited Levels of Address', 'directorypress-extended-locations'); ?></p>
					<?php echo '<a class="dp-admin-btn dp-success directorypress-location-action-link" data-action="create_location" data-title="'. esc_attr__('Create Location Level:', 'DIRECTORYPRESS') .'" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" href="#">' . __('Create New Location Level', 'directorypress-extended-locations') . '</a>'; ?>
				</div>
				<div id="locations_list"  class="directorypress-box-content wp-clearfix">
					<div class="row">
						<div class="col-9">
							<?php echo $items_list; ?>
							<div id="locations-list-ajax-response"></div>
						</div>
						<div class="col-3">
							<?php do_action('directorypress_tutorial_section','locations'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="directorypress-backend-offcanvas offcanvas <?php if(is_rtl()){ echo 'offcanvas-start'; }else{ echo 'offcanvas-end'; } ?>" tabindex="-1" id="directorypress-backend-offcanvas" aria-labelledby="directorypress-backend-offcanvas">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="directorypress-offcanvas-title"></h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body"></div>
	<div class="offcanvas-footer">
		<button type="button" class="btn btn-success location_callback_link" data-id="" data-callback=""><?php echo esc_html__('Update', 'DIRECTORYPRESS'); ?></button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas"><?php echo esc_html__('Close', 'DIRECTORYPRESS'); ?></button>
	</div>
</div>