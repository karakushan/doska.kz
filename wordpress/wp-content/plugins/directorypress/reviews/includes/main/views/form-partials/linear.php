<?php defined('ABSPATH') or die;
	/* @var $form DiRF */
	/* @var $conf DiRM */

	/* @var $f DiRF */
	$f = &$form;
?>

<?php foreach ($conf->get('fields', array()) as $fieldname): ?>

	<?php echo $f->field($fieldname) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already
		->addmeta('special_sekrit_property', '!!')
		->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped already ?>

<?php endforeach; ?>
