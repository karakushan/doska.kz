<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="row">
				<div class="col-12">
					<div class="directorypress-box">
						<div class="directorypress-box-head">
							<h1><?php esc_html_e('DirectoryPress Fields', 'DIRECTORYPRESS'); ?></h1>
							<p><?php esc_html_e('You can create unlimited fields as per your requirements', 'DIRECTORYPRESS'); ?></p>
							<?php echo '<a class="dp-admin-btn dp-success directorypress-field-action-link" data-action="create_field" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" data-title="'. esc_attr__('Create  New Field:', 'DIRECTORYPRESS') .'" href="#">' . esc_html__('Create New Field', 'directorypress-extended-locations') . '</a>'; ?>
							<?php echo '<a class="dp-admin-btn dp-success directorypress-field-action-link" data-action="create_group" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" data-title="'. esc_attr__('Create  New Group:', 'DIRECTORYPRESS') .'" href="#">' . esc_html__('Create New Group', 'directorypress-extended-locations') . '</a>'; ?>
							
						</div>
						<div class="directorypress-box-content wp-clearfix">
							<div class="row">
								<div class="col-9">
									<div class="directorypress-manager-page-wrap">
										<?php $itab_id = uniqid(); ?>
										<ul class="nav nav-tabs" id="tabContent">
											<li class="nav-item"><a class="nav-link active" href="#fields_list" data-bs-toggle="tab"><?php esc_html_e('Fields', 'DIRECTORYPRESS'); ?></a></li>
											<li class="nav-item"><a class="nav-link" href="#fields_group" data-bs-toggle="tab"><?php esc_html_e('Groups', 'DIRECTORYPRESS'); ?></a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane fade active show" id="fields_list">
												</br>
												<p class="alert alert-info"><?php esc_html_e('Fields order can be changed by drag & drop.', 'DIRECTORYPRESS'); ?></p>
												<form method="POST" action="">
													<div id="fields-ajax-response"></div>
													<input type="hidden" id="fields_order" name="fields_order" value="" />
													<div class="fields_list_wrapper"><?php echo $items_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already ?></div>
													
												</form>
											</div>
											<div class="tab-pane fade" id="fields_group">
												<form method="POST" action="">
													<div id="groups-ajax-response"></div>
													<div class="fields_group_list_wrapper"><?php echo $group_items_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already ?></div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="col-3">
									<?php do_action('directorypress_tutorial_section','fields'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="directorypress-backend-offcanvas offcanvas <?php if(is_rtl()){ echo 'offcanvas-start'; }else{ echo 'offcanvas-end'; } ?>" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="directorypress-offcanvas-title"></h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body"></div>
	<div class="offcanvas-footer">
		<button type="button" class="btn btn-success field_callback_link" data-id="" data-callback=""><?php echo esc_html__('Update', 'DIRECTORYPRESS'); ?></button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas"><?php echo esc_html__('Close', 'DIRECTORYPRESS'); ?></button>
	</div>
</div>
<div class="toast-container position-fixed bottom-0 mb-4 <?php if(is_rtl()){ echo 'ml-4 start-0'; }else{ echo 'mr-4 end-0'; } ?>">
	<div id="directorypress-backend-toast-success" class="toast align-items-center text-bg-success mt-4border-0" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="d-flex">
			<div class="toast-body"></div>
			<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
	</div>
</div>