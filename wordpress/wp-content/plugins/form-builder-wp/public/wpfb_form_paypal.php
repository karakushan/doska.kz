<?php
extract(shortcode_atts(array(
	'item_text'=>'Item',
	'qty_text'=>'Qty',
	'price_text'=>'Price',
	'item_list'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );
if(empty($item_list)){
	return;
}
global $wpfb_form;
$operators = array('+','-','*',':');
$items = json_decode(base64_decode($item_list));
$items = (array) apply_filters('wpfb_form_paypal_items_list', $items, $wpfb_form);
?>
<div class="wpfb-form-group<?php echo ' '.$css_class?><?php echo wpfb_form_shortcode_custom_css_class($input_css,' ')?>">
	<input type="hidden" name="_wpfb_form_paypal" value="1">
	<div class="wpfb-form-paypal">
		<table class="wpfb-form-paypal-table">
			<thead>
				<tr>
					<th class="paypal-table-label"><?php echo esc_html($item_text) ?></th>
					<th class="paypal-table-qty-text"><?php echo esc_html($qty_text) ?></th>
					<th class="paypal-table-price-text"><?php echo esc_html($price_text) ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i=0;?>
				<?php foreach ($items as $item):?>
				<?php 
				$i++;
				?>
				<tr>
					<td class="paypal-item-name-value" data-title="<?php echo esc_attr($item_text) ?>">
						<?php echo esc_html($item->item_label)?>
					</td>
					<td class="paypal-item-qty-value wpfb-paypal-calculation" data-value-math="<?php echo trim(esc_attr($item->item_qty))?>" data-title="<?php echo esc_attr($qty_text)?>">
						<?php echo $item->item_qty?>
					</td>
					<td class="paypal-item-price-value wpfb-paypal-calculation" data-result-math="<?php echo $item->item_price?>" data-value-math="<?php echo trim(esc_attr($item->item_price))?>" data-title="<?php echo esc_attr($price_text)?>">
						<?php echo wpfb_form_price($wpfb_form->ID,$item->item_price)?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
			<tfoot>
				<tr class="paypal-order-total">
					<td colspan="2" class="paypal-order-total-text"><?php _e( 'Total', 'form-builder-wp' ); ?></td>
					<td data-title="<?php esc_attr_e( 'Total', 'form-builder-wp' ); ?>" class="paypal-total-value"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>