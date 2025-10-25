<?php $select2_class = (!is_admin())? 'directorypress-select2': ''; ?>
<div class="field-wrap field-input-item submit_field_id_<?php echo $field->id; ?> field-type-<?php echo $field->type; ?> clearfix">
	<p class="directorypress-submit-field-title">
		<?php echo $field->name; ?>
		<?php do_action('directorypress_listing_submit_required_lable', $field); ?>
		<?php do_action('directorypress_listing_submit_user_info', $field->description); ?>
		<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_hours'); ?>
	</p>
	<div class="hours-field-wrap">
		<div class="directorypress-days">
			<ul class="nav nav-tabs">
				<?php foreach ($days AS $key=>$day): 
					if ($key == 1){ ?>
						<li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#<?php echo $field->days_names[$key]; ?>"><?php echo $field->days_names[$key]; ?></a></li>
			   
					<?php }else{ ?>
						<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#<?php echo $field->days_names[$key]; ?>"><?php echo $field->days_names[$key]; ?></a></li>
					<?php } ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="directorypress-days-content tab-content">
			<?php foreach ($days AS $key=>$day): 
				if ($key == 1){ 
					$active = 'show active';
				   
				}else{
					 $active = '';
				}
			?>
				<div id="<?php echo $field->days_names[$key]; ?>" class="directorypress-days-data tab-pane fade <?php echo $active; ?>">
					<div class="row clearfix">
						<div class="opening-hours col-sm-12 col-md-4 col-lg-4">
							<select name="<?php echo $day; ?>_opening_<?php echo $field->id; ?>" class="opening-input <?php echo esc_attr($select2_class); ?>">
								<?php
								$total_time = 1440 - $field->time_interval;
								for ($i = 0; $i <= $total_time; $i+=$field->time_interval) {
									$time = date('h:i A', mktime(0, $i, 0, 0, 0, 0));
								?>
									<option value="<?php echo esc_attr($time); ?>"<?php echo selected($field->value[$day.'_opening'], $time); ?>><?php echo esc_html($time); ?></option>
								<?php } ?>
							</select>
							
						</div>
						<div class="closing-hours col-sm-12 col-md-4 col-lg-4">
							<select name="<?php echo $day; ?>_closing_<?php echo $field->id; ?>" class="closing-input <?php echo esc_attr($select2_class); ?>">
								<?php
								$total_time = 1440 - $field->time_interval;
								for ($i = 0; $i <= $total_time; $i+=$field->time_interval) {
									$time = date('h:i A', mktime(0, $i, 0, 0, 0, 0));
								?>
									<option value="<?php echo esc_attr($time); ?>"<?php echo selected($field->value[$day.'_closing'], $time); ?>><?php echo esc_html($time); ?></option>
								<?php } ?>
							</select>
						</div> 
						<div class="closed col-sm-12 col-md-4 col-lg-4">
							<select name="<?php echo $day; ?>_off_<?php echo $field->id; ?>" class="closed-always-open-input <?php echo esc_attr($select2_class); ?>">
								<option value="0"<?php echo selected($field->value[$day.'_off'], 0); ?>><?php echo esc_html__('custom timing', 'directorypress-advanced-fields'); ?></option>
								<option value="1"<?php echo selected($field->value[$day.'_off'], 1); ?>><?php echo esc_html__('day off', 'directorypress-advanced-fields'); ?></option>
								<option value="2"<?php echo selected($field->value[$day.'_off'], 2); ?>><?php echo esc_html__('Open 24 Hours', 'directorypress-advanced-fields'); ?></option>
							</select>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>