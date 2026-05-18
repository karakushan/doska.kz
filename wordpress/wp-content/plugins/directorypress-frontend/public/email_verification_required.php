<div class="directorypress-content directorypress-submit-block">
	<div class="col-12 text-center">
			<?php
				$url = '&nbsp;<strong><a class="user-email-verification-link" href="#" data-bs-toggle="modal" data-bs-target="#user-email-verification-modal">'. esc_html__('verify', 'directorypress-frontend') .'</a></strong>';
				echo '<div class="alert alert-warning" style="display:inline-block;">';
					echo '<p>'. sprintf(esc_html__('Email verification is required to submit new listing  %s', 'directorypress-frontend'), $url).'</p>';
				echo '</div>';
			?>
	</div>
</div>