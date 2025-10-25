<?php 
add_action('pacz_dashboad_panel', 'pacz_panel_intro');
function pacz_panel_intro(){
	$theme_data = wp_get_theme("classiadspro");
	$pacz_theme = wp_get_theme();
	$theme_version = $pacz_theme->get( 'Version' );
	$theme_name = $pacz_theme->get( 'Name' );
	$mem_limit = ini_get('memory_limit');
	$mem_limit_byte = wp_convert_hr_to_bytes($mem_limit);
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_byte = wp_convert_hr_to_bytes($upload_max_filesize);
	$post_max_size = ini_get('post_max_size');
	$post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);
	$mem_limit_byte_boolean = ($mem_limit_byte < 268435456);
	$upload_max_filesize_byte_boolean = ($upload_max_filesize_byte < 67108864);
	$post_max_size_byte_boolean = ($post_max_size_byte < 67108864);
	$execution_time = ini_get('max_execution_time');
	$execution_time_boolean = ($execution_time < 300);
	$input_vars = ini_get('max_input_vars');
	$input_vars_boolean = ($input_vars < 2000);
	$input_time = ini_get('max_input_time');
	$input_time_boolean = ($input_time < 1000);
	if( class_exists('ZipArchive', false) == false ){
		$ziparchive = 'Disabled';
	} else {
		$ziparchive = 'Enabled';
	}
	$memory_limit = ini_get('memory_limit');
?>
<div class="wrap about-wrap pacz-admin-wrap">
	<?php Pacz_Admin::pacz_dashboard_header(); ?>
	<div id="pacz-dashboard" class="wrap about-wrap">
		<div class="pacz-row">
				<div class="pacz-col-md-4 pacz-col-sm-6 pacz-col-xs-12">
					<div class="panel-Service-box">
						<div class="panel-service-icon"><i class="pacz-flaticon-document58"></i></div>
						<div class="panel-service-title"><?php echo esc_html__('Documentation', 'classiadspro'); ?></div>
						<div class="panel-service-content">
							<p><?php echo esc_html__('Since version 5.5, We are providing complete documentation online. Our detailed knowledge base is ready to answer your all queries. Please visit our knowledge base below', 'classiadspro'); ?></p>
							<a href="https://help.designinvento.net/docs/classiads-5/installations/wordpress-installation/" target="_blank"><?php echo esc_html__('knowledge base', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
				<div class="pacz-col-md-4 pacz-col-sm-6 pacz-col-xs-12">
					<div class="panel-Service-box">
						<div class="panel-service-icon"><i class="pacz-flaticon-document58"></i></div>
						<div class="panel-service-title"><?php echo esc_html__('Support Desk', 'classiadspro'); ?></div>
						<div class="panel-service-content">
							<p><?php echo esc_html__('Although our knowledge base provide complete solutions to any of your query, But do not worry if there is still any problem. You can contact our Premium Support Desk below', 'classiadspro'); ?></p>
							<a href="https://help.designinvento.net/" target="_blank"><?php echo esc_html__('Support Desk', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
				<div class="pacz-col-md-4 pacz-col-sm-6 pacz-col-xs-12">
					<div class="panel-Service-box">
						<div class="panel-service-icon"><i class="pacz-flaticon-document58"></i></div>
						<div class="panel-service-title"><?php echo esc_html__('Suggestions', 'classiadspro'); ?></div>
						<div class="panel-service-content">
							<p><?php echo esc_html__('Since version 5.5 Classiads offer ultimate feature and flexibility, But we are still open for any feature suggestion. you can send us your suggestions by filling the form below', 'classiadspro'); ?></p>
							<a href="https://help.designinvento.net/" target="_blank"><?php echo esc_html__('Feature Suggestions', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
		</div>
		<div id="wSystemStatus" class="pacz-row">
				<div class="pacz-col-sm-12">
					<div class="pacz-box">
						<div class="pacz-box-head">
							<?php esc_html_e('System Status','classiadspro'); ?>
						</div>
						<div class="pacz-box-content">
							<?php esc_html_e('When you install a demo it provides pages, images, theme options, posts, slider, widgets and etc. IMPORTANT: Please check below status to see if your server meets all essential requirements for a successful import.','classiadspro') ?>
							<!-- PHP Version -->
							<div class="w-system-info">
								<span> <?php esc_html_e('PHP Version','classiadspro'); ?> </span>
								<?php
								if( version_compare(phpversion(), '8.1', '<') ){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '. phpversion() ?> </span>
									<span class="w-min"> <?php esc_html_e('(min: 8.1)','classiadspro') ?> </span>
									<label class="hero button" for="php-version"> <?php esc_html_e('Please contact Host provider to fix it.','classiadspro') ?> </label>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '. phpversion() ?> </span>
								<?php } ?>
							</div>
							<!-- PHP ZipArchive -->
							<div class="w-system-info">
								<span> <?php esc_html_e('PHP ZipArchive extension','classiadspro'); ?> </span>
								<?php
								if( class_exists('ZipArchive', false) == false ){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '. $ziparchive ?> </span>
									<span class="w-min"></span>
									<label class="hero button" for="php-version"> <?php esc_html_e('Please contact Host provider to fix it.','classiadspro') ?> </label>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '. $ziparchive ?> </span>
								<?php } ?>
							</div>
							<!-- PHP Maximum Execution Time -->
							<div class="w-system-info">
								<span> <?php esc_html_e('PHP Maximum Execution Time','classiadspro'); ?> </span>
								<?php
								if($execution_time_boolean){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i>
									<span class="w-current"> <?php echo esc_html('Currently:','classiadspro').' '.$execution_time; ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:300)','classiadspro') ?> </span>
									<label class="hero button" for="execution-time"> <?php esc_html_e('How to fix it','classiadspro') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="execution-time" />
										<article class="content">
											<header class="header">
												<label class="button" for="execution-time"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting max execution time to at least 180. you can read below link for more information:' , 'classiadspro' ) ?> </p>
												<a href="http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded" target="_blank"> <?php esc_html_e( 'Increasing Max. Execution Time' , 'classiadspro' ) ?> </a>
											</main>
										</article>
										<label class="backdrop" for="execution-time"></label>
									</aside>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$execution_time; ?> </span>
								<?php } ?>
							</div>
							<!-- PHP Maximum Input Vars -->
							<div class="w-system-info">
								<span> <?php esc_html_e('PHP Maximum Input Vars','classiadspro') ?> </span>
								<?php
								if($input_vars_boolean){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i>
									<span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$input_vars; ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:2000)','classiadspro') ?> </span>
									<label class="hero button" for="input-variables"><?php esc_html_e('How to fix it','classiadspro') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="input-variables" />
										<article class="content">
											<header class="header">
												<label class="button" for="input-variables"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting max input vars to at least 2000. Please follow below steps:' , 'classiadspro' ) ?></p>
												<p>There are several ways to do it. First one to check would be to login to your server's cPanel and look there for PHP settings. Often there's an option to edit PHP settings "per host" or "per domain" and you may find it there.
												<br>
												If there's no such option:
												<br>
												- create a file named "php.ini"<br>
												- put following line inside
												<br>
												<code class="red">max_input_vars = 3000;</code>
												<br>
												- save the file and upload it to your server to the root (main) folder of your domain
												<br>
												On some servers it's not possible to use "php.ini" file that way so if above doesn't work, there's another way to check:
												<br>
												- edit the ".htaccess" file of your site<br>
												- add following lines at the very top of it (do not remove anything that's already there)
												<br>
												<code class="red">php_value max_input_vars 3000</code>
												<br>
												- save the file.
												<br>
												If that doesn't work either or breaks the site, edit the file again to remove the line and get in touch with your host asking them if they could increase that value for you.</p>
											</main>
										</article>
										<label class="backdrop" for="input-variables"></label>
									</aside>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$input_vars; ?> </span>
								<?php } ?>
							</div>
							<!-- Upload Maximum Filesize -->
							<div class="w-system-info">
								<span> <?php esc_html_e('Upload Maximum Filesize','classiadspro') ?> </span>
								<?php
								if($upload_max_filesize_byte_boolean){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$upload_max_filesize; ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:64M)','classiadspro') ?> </span>
									<label class="hero button" for="php-upload-size"> <?php esc_html_e('How to fix it','classiadspro') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="php-upload-size" />
										<article class="content">
											<header class="header">
												<label class="button" for="php-upload-size"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting Upload Max. Filesize to at least 10MB. you can read below link for more information:' , 'classiadspro' ) ?></p>
												<a href="https://premium.wpmudev.org/blog/increase-memory-limit/?ench=b&utm_expid=3606929-78.ZpdulKKETQ6NTaUGxBaTgQ.1&utm_referrer=https%3A%2F%2Fpremium.wpmudev.org%2Fblog%2F%3Fench%3Db%26s%3Dmemory_limit" target="_blank"> <?php esc_html_e( 'Increasing Upload Max. Filesize' , 'classiadspro' ) ?></a><br>
											</main>
										</article>
										<label class="backdrop" for="php-upload-size"></label>
									</aside>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$upload_max_filesize; ?> </span>
								<?php } ?>
							</div>
							<!-- Maximum Post Size -->
							<div class="w-system-info">
								<span> <?php esc_html_e('Maximum Post Size','classiadspro') ?> </span>
								<?php
								if($post_max_size_byte_boolean){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$post_max_size; ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:64M)','classiadspro') ?> </span>
									<label class="hero button" for="php-post-upload-size"> <?php esc_html_e('How to fix it','classiadspro') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="php-post-upload-size" />
										<article class="content">
											<header class="header">
												<label class="button" for="php-post-upload-size"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting Max. Post Size to at least 30MB. you can read below link for more information:' , 'classiadspro' ) ?> </p>
												<a href="https://premium.wpmudev.org/blog/increase-memory-limit/?ench=b&utm_expid=3606929-78.ZpdulKKETQ6NTaUGxBaTgQ.1&utm_referrer=https%3A%2F%2Fpremium.wpmudev.org%2Fblog%2F%3Fench%3Db%26s%3Dmemory_limit" target="_blank">Increasing Max. Post Size</a><br>
											</main>
										</article>
										<label class="backdrop" for="php-post-upload-size"></label>
									</aside>
								<?php }else{ ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$post_max_size; ?> </span>
								<?php } ?>
							</div>
							<!-- WP Memory Limit -->
							<div class="w-system-info">
								<span> <?php esc_html_e('WP Memory Limit','classiadspro'); ?> </span>
								<?php
								$wp_memory_limit = WP_MEMORY_LIMIT;
								$wp_memory_limit_value = preg_replace("/[^0-9]/", '', $wp_memory_limit);
								if( $wp_memory_limit_value < 256 ){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$wp_memory_limit ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:256M)','classiadspro') ?> </span>
									<label class="hero button" for="wp-memory-limit"> <?php esc_html_e('How to fix it','classiadspro') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="wp-memory-limit" />
										<article class="content">
											<header class="header">
												<label class="button" for="wp-memory-limit"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting memory to at least 256MB. Please define memory limit in wp-config.php file. you can read below link for more information:' , 'classiadspro' ) ?></p>
												<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank"> <?php esc_html_e( 'Increasing Wp Memory Limit' , 'classiadspro' ) ?> </a>
											</main>
										</article>
										<label class="backdrop" for="wp-memory-limit"></label>
									</aside>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$wp_memory_limit; ?> </span>
								<?php } ?>
							</div>
							<!-- Physical Memory Limit -->
							<div class="w-system-info">
								<span> <?php esc_html_e('Physical Memory Limit','classiadspro'); ?> </span>
								<?php
								
								if( $memory_limit < 256 ){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$memory_limit ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:256M)','classiadspro') ?> </span>
									<label class="hero button" for="memory-limit"> <?php esc_html_e('How to fix it','classiadspro') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="memory-limit" />
										<article class="content">
											<header class="header">
												<label class="button" for="memory-limit"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting Memory Limit at least to 256m. Please follow below steps:' , 'classiadspro' ) ?></p>
												<p>There are several ways to do it. First one to check would be to login to your server's cPanel and look there for PHP settings. Often there's an option to edit PHP settings "per host" or "per domain" and you may find it there.
												<br>
												If there's no such option:
												<br>
												- create a file named "php.ini"<br>
												- put following line inside
												<br>
												<code class="red">memory_limit = 256;</code>
												<br>
												- save the file and upload it to your server to the root (main) folder of your domain
												<br>
												On some servers it's not possible to use "php.ini" file that way so if above doesn't work, there's another way to check:
												<br>
												- edit the ".htaccess" file of your site<br>
												- add following lines at the very top of it (do not remove anything that's already there)
												<br>
												<code class="red">php_value memory_limit 256</code>
												<br>
												- save the file.
												<br>
												If that doesn't work either or breaks the site, edit the file again to remove the line and get in touch with your host asking them if they could increase that value for you.</p>
											</main>
										</article>
										<label class="backdrop" for="memory-limit"></label>
									</aside>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$memory_limit; ?> </span>
								<?php } ?>
							</div>
							<!-- PHP Max Input Time -->
							<!-- <div class="w-system-info">
								<span> <?php esc_html_e('PHP Max. Input Time','classiadspro') ?> </span>
								<?php
								if($input_time_boolean){ ?>
									<i class="w-icon w-icon-red pacz-icon-close"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$input_time; ?> </span>
									<span class="w-min"> <?php esc_html_e('(min:1000)','classiadspro') ?></span>
									<label class="hero button" for="php-input-time"> <?php esc_html('How to fix it','classiadspro') ?></label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="php-input-time" />
										<article class="content">
											<header class="header">
												<label class="button" for="php-input-time"><i class="pacz-icon-close"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e('It may not work with some shared hosts in which case you would have to ask your hosting service provider for support.' , 'classiadspro' ) ?> </p>
												<strong> <?php esc_html_e('1- Create or Edit an existing PHP.INI file' , 'classiadspro' ) ?> </strong><br>
												<?php esc_html_e('In most cases if you are on a shared host, you will not see a php.ini file in your directory. If you do not see one, then create a file called php.ini and upload it in the root folder. In that file add the following code:' , 'classiadspro' ) ?><br>
												<code class="red"> <?php esc_html_e('max_input_time' , 'classiadspro' ) ?> = 1000 </code><br><br>
												<strong> <?php esc_html_e('2- htaccess Method' , 'classiadspro' ) ?></strong><br>
												<?php esc_html_e('Some people have tried using the htaccess method where by modifying the .htaccess file in the root directory, you can increase the Max. Input Time in WordPress. Open or create the .htaccess file in the root folder and add the following code:' , 'classiadspro' ) ?><br>
												<code class="red"> <?php esc_html_e('php_value max_input_time' , 'classiadspro' ) ?> 1000</code><br>
											</main>
										</article>
										<label class="backdrop" for="php-input-time"></label>
									</aside>
								<?php } else { ?>
									<i class="w-icon w-icon-green pacz-icon-check"></i> <span class="w-current"> <?php echo esc_html__('Currently:','classiadspro').' '.$input_time; ?> </span>
								<?php }	?>
							</div> -->
						</div>
					</div>
				</div>
			</div>
	</div>

</div>
<?php }
