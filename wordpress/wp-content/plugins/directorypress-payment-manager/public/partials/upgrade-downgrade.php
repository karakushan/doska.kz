<div class="directorypress-modal-content wp-clearfix">
<script>
	(function($) {
	"use strict";

		$(function() {
			$("input[name*='package_disabled_']").each( function() {
				packageDisableChange($(this));
			});
	
			$("input[name*='package_disabled_']").change( function() {
				packageDisableChange($(this));
			});
	
			function packageDisableChange(checkbox) {
				if (checkbox.is(':checked'))
					checkbox.parent().parent().parent().find("input:not(.package_disabled)").attr('disabled', 'true');
				else
					checkbox.parent().parent().parent().find("input:not(.package_disabled)").removeAttr('disabled');
			}
		});
		
	})(jQuery);
</script>
<?php 
//$id = $_GET['package_id'];
$item1 = $items->packages_array[$id];
?>
<form class="upgrade" method="POST" action="">
<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_packages_nonce');?>
						<table class="widefat directorypress-admin-table">
							<thead>
								<tr>
									<th><?php _e('Package Name', 'directorypress-payment-manager'); ?></th>
									<th><?php _e('BumpUp', 'directorypress-payment-manager'); ?></th>
									<th><?php _e('Disable/Enable Upgrade', 'directorypress-payment-manager'); ?></th>
									<th><?php _e('Upgrade Price', 'directorypress-payment-manager'); ?></th>
								</tr>
							</thead>
							<?php
							$i = 0; 
							$i++; 
							foreach ($items->packages_array AS $item2): ?>
								<tr>
									<?php if ($item1->id != $item2->id): ?>
										<th><?php echo $item2->name; ?></th>
										<th>
											<label class="switch">
												<input type="checkbox" name="package_raiseup_<?php echo $item1->id; ?>_<?php echo $item2->id; ?>" value=1 <?php if (isset($item1->upgrade_meta[$item2->id])) checked($item1->upgrade_meta[$item2->id]['raiseup'], 1, true); ?> />
												<span class="slider"></span>
											</label>
										</th>
										<th>
											<label class="switch">
												<input type="checkbox" class="package_disabled" name="package_disabled_<?php echo $item1->id; ?>_<?php echo $item2->id; ?>" value=1 <?php if (isset($item1->upgrade_meta[$item2->id])) checked($item1->upgrade_meta[$item2->id]['disabled'], 1, true); ?> />
												<span class="slider"></span>
											</label>
										</th>
										<th>
											<?php do_action('directorypress_upgrade_meta_html', $item1, $item2); ?>
										</th>
									<?php endif; ?>
								</tr>
							<?php endforeach; 
							
							?>
						</table>
						<div class="id">
				<input type="hidden" name="id" value="">
			</div>
					</form>
</div>
