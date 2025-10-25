<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     9.7.0
  *
 * @package This template is overrided by theme
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product, $pacz_settings;

$attachment_ids = $product->get_gallery_image_ids();
$image = wp_get_attachment_image_src( get_post_thumbnail_id( ), 'single-post-thumbnail' );

$single_image_size = isset($pacz_settings['woo_single_image_size']) ? $pacz_settings['woo_single_image_size'] : 'crop';

$image_height = isset($pacz_settings['woo-single-thumb-height']) ? $pacz_settings['woo-single-thumb-height'] : 800;
$image_width = 570;
$quality = isset($pacz_settings['woo-image-quality']) ? $pacz_settings['woo-image-quality'] : 1;
$rtl = (is_rtl())? 'true':'false';
	echo '<div class="images pacz-gallery thumb-style">';
		echo '<div class="pacz-woo-gallery">';
			if ( $product->is_on_sale() ){
				echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'SALE', 'classiadspro' ) . '</span>', $post, $product );
			}
			echo '<div class="slick-woo-single" data-rtl="'.$rtl.'">';
				if ( has_post_thumbnail() ) {

					switch ($single_image_size) {
						case 'full':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
							$image = $image_src_array[0];
							break;
						case 'crop':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;            
						case 'large':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true);
							$image = $image_src_array[0];
							break;
						case 'medium':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium', true);
							$image = $image_src_array[0];
							break;        
						default:
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;
					}

					$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );

					echo '<div class="slick-slide"><img src="'.pacz_thumbnail_image_gen($image, $image_width, $image_height).'" alt="'.$image_title.'" /><a href="'.$image_src_array[ 0 ].'" class="pacz-lightbox product-single-lightbox" rel="product-image" title="'.$image_title.'"><i class="pacz-flaticon-square53"></i></a></div>';

				}
				foreach ( $attachment_ids as $attachment_id ) {
					
					$image_link = wp_get_attachment_url( $attachment_id );
					$image_title = esc_attr( get_the_title( $attachment_id ) );

					switch ($single_image_size) {
						case 'full':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'full', true);
							$image = $image_src_array[0];
							break;
						case 'crop':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;            
						case 'large':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'large', true);
							$image = $image_src_array[0];
							break;
						case 'medium':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'medium', true);
							$image = $image_src_array[0];
							break;        
						default:
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;
					}
					
					echo '<div class="slick-slide"><img src="'.pacz_thumbnail_image_gen($image, $image_width, $image_height).'" alt="'.$image_title.'" /><a href="'.$image_src_array[ 0 ].'" class="pacz-lightbox product-single-lightbox" rel="product-image" title="'.$image_title.'"><i class="pacz-flaticon-square53"></i></a></div>';

				}

			echo '</div>';
			//here
			echo '<div class="slick-woo-single-nav" data-rtl="'.$rtl.'">';
				if ( has_post_thumbnail() ) {

					switch ($single_image_size) {
						case 'full':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
							$image = $image_src_array[0];
							break;
						case 'crop':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;            
						case 'large':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true);
							$image = $image_src_array[0];
							break;
						case 'medium':
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium', true);
							$image = $image_src_array[0];
							break;        
						default:
							$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;
					}

					$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );

					echo '<div class="slick-slide"><img src="'.pacz_thumbnail_image_gen($image, $image_width, $image_height).'" alt="'.$image_title.'" /></div>';

				}
				foreach ( $attachment_ids as $attachment_id ) {
					
					$image_link = wp_get_attachment_url( $attachment_id );
					$image_title = esc_attr( get_the_title( $attachment_id ) );

					switch ($single_image_size) {
						case 'full':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'full', true);
							$image = $image_src_array[0];
							break;
						case 'crop':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;            
						case 'large':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'large', true);
							$image = $image_src_array[0];
							break;
						case 'medium':
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'medium', true);
							$image = $image_src_array[0];
							break;        
						default:
							$image_src_array = wp_get_attachment_image_src( $attachment_id, 'full', true);
							$image = bfi_thumb($image_src_array[0], array(
								'width' => $image_width*$quality,
								'height' => $image_height*$quality
							));
							break;
					}
					
					echo '<div class="slick-slide"><img src="'.pacz_thumbnail_image_gen($image, $image_width, $image_height).'" alt="'.$image_title.'" /></div>';

				}

			echo '</div>';

		echo '</div>';

	echo '</div>';


