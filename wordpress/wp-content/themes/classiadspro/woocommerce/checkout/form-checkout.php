<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'classiadspro' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() ); ?>
<div class="checkout-form-wrap">
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">
<div class="row clearfix">
<div class="woocommerce-checkout-form col-lg-8 col-md-12 col-sm-12">
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div id="customer_details" class="pacz-checkout-details">

				<?php do_action( 'woocommerce_checkout_billing' ); ?>
				<div class="clearfix"></div>
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>

		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>
<div id="order_review" class="woocommerce-checkout-review-order col-lg-4 col-md-12 col-sm-12">
<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	
	<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'classiadspro' ); ?></h3>
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>
	</div>
</form>
</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>