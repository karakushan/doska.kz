<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="directorypress-box">
				<div class="directorypress-box-head">
					<h1><?php esc_html_e('Delete This Field Group', 'DIRECTORYPRESS'); ?></h1>				
				</div>
				<div class="directorypress-box-content wp-clearfix">
					<div class="directorypress-configuration-page-wrap">
						<p class="alert alert-warning"><?php esc_html_e('Are you sure, This option can not undo', 'DIRECTORYPRESS'); ?></p>
						<form action="" method="POST">
							<?php submit_button(esc_html__('Delete', 'DIRECTORYPRESS')); ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>