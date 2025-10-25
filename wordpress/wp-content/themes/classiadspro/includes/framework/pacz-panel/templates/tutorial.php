<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap about-wrap pacz-admin-wrap">
	<?php Pacz_Admin::pacz_dashboard_header(); ?>
	<?php

	$keyses = array(
		'a' => array(
			'href' => array(),
			'title' => array(),
			'target' => array(),
		),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
		'code' => array(
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
		),
	);

	?>

	<div id="pacz-dashboard" class="wrap about-wrap">
		<div class="welcome-content w-clearfix extra">
			<div class="pacz-row">
				<div class="pacz-col-md-4 pacz-col-sm-6 pacz-col-xs-12">
					<div class="panel-Service-box">
						<div class="panel-service-icon"><i class="pacz-flaticon-document58"></i></div>
						<div class="panel-service-title"><?php echo esc_html__('Documentation', 'classiadspro'); ?></div>
						<div class="panel-service-content">
							<p><?php echo esc_html__('Since version 5.5, We are providing complete documentation online. Our detailed knowledge base is ready to answer your all queries. Please visit our knowledge base below', 'classiadspro'); ?></p>
							<a href="http://doska.pizzalaroma.kz" target="_blank"><?php echo esc_html__('knowledge base', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
				<div class="pacz-col-md-4 pacz-col-sm-6 pacz-col-xs-12">
					<div class="panel-Service-box">
						<div class="panel-service-icon"><i class="pacz-flaticon-document58"></i></div>
						<div class="panel-service-title"><?php echo esc_html__('Support Desk', 'classiadspro'); ?></div>
						<div class="panel-service-content">
							<p><?php echo esc_html__('Although our knowledge base provide complete solutions to any of your query, But do not worry if there is still any problem. You can contact our Premium Support Desk below', 'classiadspro'); ?></p>
							<a href="http://doska.pizzalaroma.kz" target="_blank"><?php echo esc_html__('Support Desk', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
				<div class="pacz-col-md-4 pacz-col-sm-6 pacz-col-xs-12">
					<div class="panel-Service-box">
						<div class="panel-service-icon"><i class="pacz-flaticon-document58"></i></div>
						<div class="panel-service-title"><?php echo esc_html__('Suggestions', 'classiadspro'); ?></div>
						<div class="panel-service-content">
							<p><?php echo esc_html__('Since version 5.5 Classiads offer ultimate feature and flexibility, But we are still open for any feature suggestion. you can send us your suggestions by filling the form below', 'classiadspro'); ?></p>
							<a href="http://doska.pizzalaroma.kz" target="_blank"><?php echo esc_html__('Feature Suggestions', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="pacz-row">
				<div class="pacz-col-sm-4 pacz-col-sm-6 pacz-col-xs-12">
					<!--<div class="pacz-box doc">
						
					</div>-->
				</div>	
			</div>
		</div>
	</div>

</div> <!-- end wrap -->