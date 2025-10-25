<?php directorypress_renderMessages(); ?>
<script>
	(function($) {
	"use strict";

		
		$(function() {
			$('[data-popup-open]').on('click', function(event)  {
				//alert(targeted_popup_class);
				var targeted_popup_class = $(this).attr('data-popup-open');
				$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
				
				event.preventDefault();
			});
			$('[data-popup-close]').on('click', function(event)  {
				var targeted_popup_class = $(this).attr('data-popup-close');
				$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
				event.preventDefault();
			});
		});
		$(function() {
			$('[data-popup-open]').on('click', function(event)  {
				//alert(targeted_popup_class);
				var targeted_package_id = $(this).attr('data-package-id');
				$('form.upgrade').prepend('<input type="hidden" name="package_id" value="'+targeted_package_id+'">');
				
				
				event.preventDefault();
			});
		});
	})(jQuery);
</script>
<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="directorypress-box">
				<div class="directorypress-box-head">
					<h1><?php _e('DirectoryPress Packages', 'directorypress-payment-manager'); ?></h1>
					<p><?php _e('You can create unlimited packages based on available features', 'directorypress-payment-manager'); ?></p>
					<?php echo '<a class="dp-admin-btn dp-success directorypress-package-action-link" data-action="create_package" data-title="'. esc_attr__('Create New Package:', 'DIRECTORYPRESS') .'" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" href="#">' . __('Create New', 'directorypress-payment-manager') . '</a>'; ?>
				</div>
				<div id="packages_list" class="directorypress-box-content wp-clearfix">
					<div class="row">
						<div class="col-9">
							<?php _e('You may order listings packages by drag & drop rows in the table.', 'directorypress-payment-manager'); ?>
							<form method="POST" action="<?php echo admin_url('admin.php?page=directorypress_packages'); ?>">
								<div class="order-response"></div>
								<input type="hidden" id="packages_order" name="packages_order" value="" />
								<div class="packages_list_wrapper">
								<?php echo $items_list; ?>
								</div>
								<?php //submit_button(__('Save order', 'directorypress-payment-manager')); ?>
							</form>
						</div>
						<div class="col-3">
							<?php do_action('directorypress_tutorial_section','packages'); ?>
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
		<button type="button" class="btn btn-success package_callback_link" data-id="" data-callback=""><?php echo esc_html__('Update', 'DIRECTORYPRESS'); ?></button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas"><?php echo esc_html__('Close', 'DIRECTORYPRESS'); ?></button>
	</div>
</div>