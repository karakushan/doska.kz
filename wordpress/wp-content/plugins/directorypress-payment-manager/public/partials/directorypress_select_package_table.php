<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="directorypress-box">
				<div class="directorypress-box-head">
					<?php echo __('Select A Package', 'directorypress-payment-manager'); ?>
				</div>
				<div class="directorypress-box-content wp-clearfix">
					<?php if ($packages_count == 0): ?>
					<p class="alert alert-info"><?php echo sprintf(__('Before listings creation you must have at least one listings package, please create new package <a href="%s">here</a>', 'directorypress-payment-manager'), admin_url('admin.php?page=directorypress_packages&action=add')); ?></p>
					<?php endif; ?>
					<?php echo $packages_table; ?>
				</div>
			</div>
		</div>
	</div>
</div>