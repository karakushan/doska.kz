<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     8.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="woo-thankyou-page">
<?php if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="woocommerce-error-text"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'classiadspro' ); ?></p>

		<p class="woocommerce-error-text"><?php
			if ( is_user_logged_in() )
				esc_html_e( 'Please attempt your purchase again or go to your account page.', 'classiadspro' );
			else
				esc_html_e( 'Please attempt your purchase again.', 'classiadspro' );
		?></p>

		<p class="woocommerce-error-text">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'classiadspro' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php esc_html_e( 'My Account', 'classiadspro' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>
		<div class="woocommerce-thankyou-content"><i class="pacz-fic5-check"></i></div>
		<p class="woocommerce-thanks-text"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'classiadspro' ), $order ); ?></p>

		<ul class="order_details clearfix">
			<li class="order">
				<div class="order-details-item-wrapper">
					<div class="order-details-item-label"><?php esc_html_e( 'Order', 'classiadspro' ); ?></div>
					<div class="order-details-item-value"><?php echo esc_attr($order->get_order_number()); ?></div>
				</div>
			</li>
			<li class="date">
				<div class="order-details-item-wrapper">
					<div class="order-details-item-label"><?php esc_html_e( 'Date', 'classiadspro' ); ?></div>
					<div class="order-details-item-value"><?php echo wc_format_datetime( $order->get_date_created() ); ?></div>
				</div>
			</li>
			<li class="total">
				<div class="order-details-item-wrapper">
					<div class="order-details-item-label"><?php esc_html_e( 'Total', 'classiadspro' ); ?></div>
					<?php echo '<div class="order-details-item-value">'. $order->get_formatted_order_total() .'</div>'; ?>
				</div>
			</li>
			<?php if ( $order->get_payment_method_title() ) : ?>
			<li class="method">
				<div class="order-details-item-wrapper">
					<div class="order-details-item-label"><?php esc_html_e( 'Payment Method', 'classiadspro' ); ?></div>
					<div class="order-details-item-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></div>
				</div>
			</li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
	<div class="woocomerce-order-details-wrapper">
		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
	</div>
<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', wp_kses_post(__( 'Thank you. Your order has been received.', 'classiadspro' ), null )); ?></p>

<?php endif; ?>
</div>