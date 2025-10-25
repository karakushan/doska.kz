<?php
if(is_single() || is_page()){
	if(!function_exists('pacz_theme_comments')){
		function pacz_theme_comments( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment; 
			global $post;
			if( $comment->user_id === $post->post_author ) {
				$userClass = 'selfresponse';
			}else{
				$userClass = 'userresponse';
			}
			require_once PACZ_THEME_PLUGINS_CONFIG . "/image-cropping.php";

			if(have_comments()): 
			?>
				<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent-comment') ?> id="li-comment-<?php comment_ID() ?>">
					<div class="pacz-single-comment <?php echo esc_attr($userClass); ?> clearfix" id="comment-<?php comment_ID(); ?>">
						<div class="pacz-post-comment-author-img">
							<?php
								$avatar_id = 	get_user_meta( $comment->user_id, 'avatar_id', true );
								if(!empty($avatar_id) && is_numeric($avatar_id)) {
									$author_avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
									$image_src_array = $author_avatar_url[0];
									$params = array( 'width' => 70, 'height' => 70, 'crop' => true );
									echo '<img src="' . bfi_thumb( $image_src_array, $params ).'" alt="'. esc_attr__('author', 'classiadspro') .'" />';
								} else { 
									$avatar_url = get_avatar_url($comment->user_id, ['size' => '70']);
									echo'<img src="'.$avatar_url.'" alt="'. esc_attr__('author', 'classiadspro') .'" />';
								}
							?>
						</div>
						<div class="pacz-post-comment-content-area clearfix">
							<div class="pacz-post-comment-meta">
								<?php printf( '<div class="pacz-post-comment-author">%s</div>', get_comment_author_link() ); ?>	
								<div><time class="pacz-post-comment-time"><?php echo get_comment_time('F jS, Y h:i A'); ?></time></div>
								<span class="pacz-post-comment-reply">
									<?php 
										$current_user = wp_get_current_user();
										if(is_user_logged_in()) {
											//$usercomment = get_comments( array (
												//'user_id' => $current_user->ID,
											//	'post_id' => $post->ID,
											//) );		
											//if($current_user->display_name != get_comment_author() && $current_user->display_name == get_the_author()) {
												comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__('reply', 'classiadspro') ) ) );
											//}
										}
									?>
								</span>
							</div>
							<div class="comment-content">
								<?php comment_text() ?>
								<?php if ( $comment->comment_approved == '0' ) : ?>
									<span class="unapproved"><?php esc_html_e( 'Your comment is awaiting moderation.', 'classiadspro' );?></span>
								<?php endif; ?>	
							</div>
						</div>     
					</div>	
				</li>
			<?php endif; ?>
		<?php } ?>
	<?php } ?>
	<?php function pacz_list_pings( $comment, $args, $depth ) { ?>
		<?php $GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>"> 
			<div id="comment-<?php comment_ID(); ?>" class="comment-wrap comments-pings">
				<div class="comment-content clearfix">
					<div class="comment-meta">
						<?php printf( '<span class="comment_author"><b>%s</b></span>', get_comment_author_link() ) ?>
					</div>
					<div class="comment-data">
						<?php comment_text() ?>
						<time class="comment-time"><?php echo get_comment_time('F jS, Y h:i A'); ?></time>
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<span class="unapproved"><?php esc_html__('Your comment is awaiting moderation.', 'classiadspro'); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</li>
	<?php } ?>
	<?php 
		function pacz_move_comment_field_to_bottom( $fields ) {
			$comment_field = $fields['comment'];
			//$cookies = (isset($fields['cookies']))? $fields['cookies'] : '';
			unset( $fields['comment'] );
			if(isset($fields['cookies'])){
				unset( $fields['cookies'] );
			}
			$fields['comment'] = $comment_field;
			if(isset($fields['cookies'])){
				$fields['cookies'] = $fields['cookies'];
			}
			return $fields;
		}
		 
		add_filter( 'comment_form_fields', 'pacz_move_comment_field_to_bottom' );
	?>
	<section id="pacz-post-comments">
		<?php if ( post_password_required() ) : ?>
			<p class="nopassword"><?php esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'classiadspro' );?></p>
		<?php return; endif; ?>
	
		<?php if ( have_comments() ) : ?>
			<div class="pacz-post-single-comments-heading"><?php echo esc_attr__('Comments', 'classiadspro').' <span class="comments_numbers">('. number_format_i18n( get_comments_number() ).')</span>'; ?></div>
			<div class="inner-content">
				<ul class="pacz-commentlist">
					<?php wp_list_comments( 'callback=pacz_theme_comments&type=comment' ); ?>
				</ul>
			</div>
			<?php if ( have_comments() ) : ?>
				<?php if ( ! empty( $comments_by_type['pings'] ) ) : ?>
					<div class="single-post-fancy-title"><span><?php esc_html_e( 'pingbacks / trackbacks', 'classiadspro' ); ?></span></div>
					<ul class="pacz-commentlist">
						<?php wp_list_comments( 'callback=pacz_list_pings&type=pings' ); ?>
					</ul>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="comments-navigation">
				<div class="comments-previous"><?php previous_comments_link(); ?></div>
				<div class="comments-next"><?php next_comments_link(); ?></div>
			</nav>
		<?php endif;?>

		<?php if ( comments_open() ) : ?>
			<div class="inner-content">
				<?php
					$fields =  array(
						'author'=> '<div class="comment-form-name comment-form-row"><label>'. esc_html__('Name', 'classiadspro') .'</label><input type="text" name="author" class="text-input" id="author" tabindex="54" placeholder="'.esc_html__('FULL NAME', 'classiadspro').'"  /></div>',
						'email' => '<div class="comment-form-email comment-form-row"><label>'. esc_html__('Email', 'classiadspro') .'</label><input type="text" name="email" class="text-input" id="email" tabindex="56" placeholder="'.esc_html__('EMAIL ADDRESS', 'classiadspro').'" /></div>',
					);

					//Comment Form Args
					$comments_args = array(
						'class_form' => 'pacz-post-comment-form clearfix',
						'fields' => $fields,
						//'title_reply'=> esc_html__('Leave a Comment', 'classiadspro'),
						'comment_field' => '<div class="comment-textarea"><label>'. esc_html__('Comment', 'classiadspro') .'</label><textarea placeholder="'.esc_html__('Your Comment', 'classiadspro').'" class="textarea" name="comment" rows="6" id="comment" tabindex="58"></textarea></div>',
						'comment_notes_before' => '',
						'comment_notes_after' => '',
						'label_submit' => esc_html__('Post Comment','classiadspro'),
					);
					global $current_user, $post;

					if ( !is_user_logged_in() ) {
						$current_user = wp_get_current_user();
						comment_form($comments_args); 
					} elseif(is_user_logged_in()) { // The user is logged in...
						$usercomment = get_comments( array (
							'user_id' => $current_user->ID,
							'post_id' => $post->ID,
						) );
						comment_form($comments_args);
					}
			
				?>
			</div>
		<?php endif; ?>
	</section>
<?php } ?>