<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should
 * provide the user interface to the end user.
 *
 * @package   designinvento
 * @author    Designinvento <help.designinvento@gmail.com>
 * @license   GPL-2.0+
 * @link      http://designinvento.net
 * @copyright 2013 Designinvento
 */

$config = include direviews::pluginpath() . 'dir-config' . EXT;

// invoke processor
$processor = direviews::processor( $config );
$status    = $processor->status();
$errors    = $processor->errors(); ?>

<div class="wrap" id="direviews_form">

	<div id="icon-options-general" class="icon32"><br></div>

	<h2><?php esc_html_e( 'Designinvento Reviews', 'DIRECTORYPRESS' ); ?></h2>

	<?php if ( $processor->ok() ): ?>

		<?php if ( ! empty( $errors ) ): ?>
			<br/>
			<p class="update-nag">
				<strong><?php esc_html_e( 'Unable to save settings.', 'DIRECTORYPRESS' ); ?></strong>
				<?php esc_html_e( 'Please check the fields for errors and typos.', 'DIRECTORYPRESS' ); ?>
			</p>
		<?php endif;

		if ( $processor->performed_update() ): ?>
			<br/>
			<p class="update-nag">
				<?php esc_html_e( 'Settings have been updated.', 'DIRECTORYPRESS' ); ?>
			</p>
		<?php endif;
		echo $f = direviews::form( $config, $processor ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already
		echo $f->field( 'hiddens' )->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already
		echo $f->field( 'labels' )->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already
		echo $f->field( 'general' )->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already ?>
		<?php echo 'test'; ?>
		<button type="submit" class="button button-primary">
			<?php esc_html_e( 'Save Changes', 'DIRECTORYPRESS' ); ?>
		</button>

		<?php echo $f->endform(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already

	elseif ( $status['state'] == 'error' ): ?>

		<h3>Critical Error</h3>

		<p><?php echo esc_html($status['message']) ?></p>

	<?php endif; ?>
</div>