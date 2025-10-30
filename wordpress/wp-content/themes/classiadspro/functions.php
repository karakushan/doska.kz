<?php

/**
 * Class and Function List:
 * Function list:
 * - init()
 * - constants()
 * - widgets()
 * - supports()
 * - functions()
 * - language()
 * - add_metaboxes()
 * - admin()
 * - post_types()
 * - pacz_theme_enqueue_scripts()
 * - pacz_preloader_script() 
 */

function classiadspro_load_textdomain()
{
	load_theme_textdomain('classiadspro', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'classiadspro_load_textdomain');

// Load Firebase Push Notifications
require_once get_template_directory() . '/includes/actions/firebase.php';

// Load Login Menu Messages Extension (using JavaScript/filters approach instead of class override)
require_once get_template_directory() . '/includes/actions/login-menu-messages.php';

// Load Advertising System
if (class_exists('DirectoryPress') && class_exists('WooCommerce')) {
	require_once get_template_directory() . '/includes/advertising/functions.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-manager.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-admin.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-woocommerce.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-display.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-cron.php';

	// Create advertise page on theme activation
	add_action('after_switch_theme', 'classiadspro_create_advertise_page');
}

/**
 * Настройка количества рекламируемых товаров в листинге
 * 
 * Фильтр позволяет изменить количество рекламируемых товаров,
 * которые отображаются в блоке "Рекомендуемые объявления"
 * 
 * @param int $count Количество рекламируемых товаров (по умолчанию 3)
 * @return int
 */
function classiadspro_advertised_listings_count($count)
{
	// Вы можете изменить это число на любое другое
	return 3;
}
add_filter('classiadspro_advertised_listings_count', 'classiadspro_advertised_listings_count');

/**
 * Заголовок блока рекламируемых товаров
 * 
 * Фильтр позволяет изменить заголовок блока рекламируемых товаров
 * 
 * @param string $title Заголовок блока
 * @return string
 */
function classiadspro_advertised_listings_title($title)
{
	// Вы можете изменить текст заголовка
	return __('Recommendations', 'classiadspro');
}
add_filter('classiadspro_advertised_listings_title', 'classiadspro_advertised_listings_title');

/**
 * Create advertise page
 */
function classiadspro_create_advertise_page()
{
	$page_title = 'Advertise Listing';
	$page_slug = 'advertise-listing';

	// Check if page already exists
	$existing_page = get_page_by_path($page_slug);
	if ($existing_page) {
		return;
	}

	// Create the page
	$page_data = array(
		'post_title' => $page_title,
		'post_name' => $page_slug,
		'post_content' => '',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_author' => 1,
	);

	$page_id = wp_insert_post($page_data);

	if ($page_id && !is_wp_error($page_id)) {
		// Set the page template
		update_post_meta($page_id, '_wp_page_template', 'page-advertise-listing.php');
	}
}

/**
 * Create WooCommerce products for advertising
 */
function classiadspro_create_advertising_products()
{
	if (!class_exists('WC_Product')) {
		return;
	}

	$prices = classiadspro_get_advertising_prices();
	$periods = array(
		'1_day' => 'Advertise Listing (1 day)',
		'3_days' => 'Advertise Listing (3 days)',
		'7_days' => 'Advertise Listing (7 days)',
	);

	foreach ($periods as $period => $title) {
		$existing_id = get_option('classiadspro_advertising_product_' . $period, 0);

		// Check if product already exists
		if ($existing_id && get_post($existing_id)) {
			continue;
		}

		// Create new product
		$product = new WC_Product_Simple();
		$product->set_name($title);
		$product->set_regular_price($prices[$period]);
		$product->set_virtual(true);
		$product->set_sold_individually(true);
		$product->set_catalog_visibility('hidden');
		$product->save();

		update_option('classiadspro_advertising_product_' . $period, $product->get_id());
	}
}

/**
 * Ensure advertising products exist
 */
function classiadspro_ensure_advertising_products()
{
	if (!class_exists('WC_Product') || !class_exists('DirectoryPress')) {
		return;
	}

	$product_ids = classiadspro_get_advertising_product_ids();

	// Check if any products are missing
	$missing_products = false;
	foreach ($product_ids as $period => $product_id) {
		if (!$product_id || !get_post($product_id)) {
			$missing_products = true;
			break;
		}
	}

	// Create products if any are missing
	if ($missing_products) {
		classiadspro_create_advertising_products();
	}
}

$theme = new Classiadspro_Theme();
$theme->init(array(
	"theme_name" => "Classiadspro",
	"theme_slug" => "classiadspro",
));

class Classiadspro_Theme
{
	function init($options)
	{
		$this->pacz_constants($options);
		$this->pacz_functions();
		$this->pacz_admin();

		add_action('init', array(
			&$this,
			'pacz_add_metaboxes',
		));

		add_action('after_setup_theme', array(
			&$this,
			'pacz_supports',
		));
		add_action('after_setup_theme', array(
			&$this,
			'pacz_settings',
		));
	}
	function pacz_settings()
	{
		global $pacz_settings;
		if (class_exists('Classiadspro_Core')) {
			$pacz_settings = get_option('pacz_settings');
		} else {

			$data = '{"last_tab":"","body-layout":"full","grid-width":"1170","content-width":"67","pages-padding":{"1":"70","2":"70"},"archive-pages-padding":{"1":"70","2":"70"},"single-pages-padding":{"1":"70","2":"70"},"body-bg":{"background-color":"#f7f7f7","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"remove-js-css-ver":"1","mobile_front_page":"","pages-layout":"right","page-title-pages":"1","page-bg":{"background-color":"#f7f7f7","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"page-title-bg":{"background-color":"#191a1f","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"page-title-color":"#FFFFFF","breadcrumb":"1","pages-comments":"1","custom-sidebar":[],"error_page":"2","error_page_id":"9807","error-layout":"full","error_page_small_text":"Far far away, behind the word mountains, far from the countries Vokalia and there live the blind texts. Sepraed. they live in Boo marksgrove right at the coast of the Semantics, a large language ocean A small river named Duden flows by their place and su plies it.","search-layout":"full","checkbox_styles":"2","res-nav-width":"1170","preset_headers":"11","_header_style":"block_module","preset_headers_skin":"","header-structure":"standard","header-location":"top","vertical-header-state":"expanded","header-vertical-width":"280","header-padding":"30","header-padding-vertical":"30","header-align":"left","nav-alignment":"right","boxed-header":"1","header-grid":"0","header-grid_postion":"","header-grid-margin-top":"0","_header_search_form":"0","sticky-header":"0","squeeze-sticky-header":"0","sticky_header_offset":"0","header-hover-style":"","header-border-top":"0","header-search":"0","header-search-location":"right","loggedin_menu":"primary-menu","header-bg":{"background-color":"#ffffff","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"theader-bg":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header-bottom-border":"","header_shadow":"1","header-toolbar":"0","toolbar-grid":"0","toolbar-custom-menu":"","toolbar_height":"100","toolbar-font":{"font-family":"Lexend Deca","font-options":"","google":"1","font-weight":"400","font-style":"","text-align":"","font-size":"14px"},"toolbar-bg":{"background-color":"#ffffff","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"toolbar-border-top":"1","toolbar-border-bottom-color":"#EEEEEE","main-nav-font":{"font-family":"Lexend Deca","font-options":"Roboto","google":"1","font-weight":"500","font-style":"","text-align":"","font-size":"14px"},"main-nav-item-space":"15","vertical-nav-item-space":"0","main-nav-top-transform":"capitalize","sub-nav-top-size":"14","sub-nav-top-transform":"capitalize","sub-nav-top-weight":"normal","main-nav-top-color":{"regular":"#191a1f","hover":"#eb6752","bg":"","bg-hover":"","bg-active":"#ffffff"},"main-nav-top-color-transparent":{"regular":"#fff","hover":"#eb6752","bg":"","bg-hover":"","bg-active":""},"main-nav-sub-bg":"#FFFFFF","main-nav-sub-color":{"regular":"#191a1f","hover":"#222222","bg":"#ffffff","bg-hover":"#fbf7f6","bg-active":"#f1f1f1"},"navigation-border-top":"1","header-logo-location":"header_section","header-logo-align":"left","logo_dimensions":"50","logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"transparent-logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2.png","id":"9570","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2-150x43.png"},"logo-retina":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"transparent-logo-retina":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2.png","id":"9570","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2-150x43.png"},"pacz-logreg-header-btn":"0","pacz-login-slug":"login","pacz-register-slug":"register","pacz-forgot-slug":"forget-password","header-login-reg-location":"header_section","log-reg-btn-align":"right","listing-btn-location":"header_section","listing-btn-align":"right","listing-btn-text":"Post Your Ad","listing_button_padding":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"listing_button_border_width":"0","listing_button_border_radius":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"listing-header-btn-color":{"regular":"#ffffff","hover":"#ffffff","bg":"#191a1f","bg-hover":"#eb6653"},"listing-header-btn-color-transparent":{"regular":"#ffffff","hover":"#ffffff","bg":"#191a1f","bg-hover":"#eb6653"},"header_listing_button_border_color":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header_listing_button_border_color_transparent":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header_listing_button_border_color_hover":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header_listing_button_border_color_hover_transparent":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"search_keyword_field":"1","search_keyword_ajax_field":"1","search_keyword_categories_field":"1","search_address_field":"1","search_address_locations_field":"1","search_button_icon":"fas fa-search-plus","header_search_button_border_radius":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"header-search-icon-color":"#222222","header-contact-select":"header_toolbar","header-contact-align":"right","header-toolbar-phone":"","header-toolbar-phone-icon":"","header-toolbar-email":"","header-toolbar-email-icon":"","toolbar-text-color":"#546B7E","toolbar-phone-email-icon-color":"#FFFFFF","toolbar-link-color":{"regular":"#546b7e","hover":"#eb6653"},"toolbar-social-link-color":{"regular":"#ffffff","hover":"#eb6653","bg":"","bg-hover":""},"toolbar-social-link-color-bg":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"header-social-select":"disabled","header-social-align":"left","header-social-facebook":"","header-social-twitter":"","header-social-rss":"","header-social-dribbble":"","header-social-pinterest":"","header-social-instagram":"","header-social-google-plus":"","header-social-linkedin":"","header-social-youtube":"","header-social-vimeo":"","header-social-spotify":"","header-social-tumblr":"","header-social-behance":"","header-social-WhatsApp":"","header-social-qzone":"","header-social-vkcom":"","header-social-imdb":"","header-social-renren":"","header-social-weibo":"","checkout-box":"0","checkout-box-location":"disabled","checkout-box-align":"right","header_cart_link_color":{"regular":"#ffffff","hover":"#ffffff","bg":"#eb6653","bg-hover":"#eb6653"},"header-wpml":"0","mobile-header-bg":{"background-color":"#ffffff","background-repeat":"repeat","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"mobile-logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"mobile-logo-retina":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"mobile-listing-button":"0","mobile-listing-button-skin":{"regular":"#1c1e21","hover":"#fff","bg":"#F2F3F5","bg-hover":"#eb6653"},"mobile-listing-button-icon":"fas fa-plus","mobile-login-button":"0","mobile-login-button-skin":{"regular":"#1c1e21","hover":"#fff","bg":"#F2F3F5","bg-hover":"#eb6653"},"mobile-login-button-icon":"far fa-user","mobile-search-button":"0","mobile-search-button-skin":{"regular":"#1c1e21","hover":"#fff","bg":"#F2F3F5","bg-hover":"#eb6653"},"mobile-search-button-icon":"fas fa-search","mobile-header-author-bg":{"background-color":"#2081cc","background-repeat":"repeat","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2017\/11\/9-1-150x150.jpg"}},"mobile-header-author-display-name-color":"#333333","mobile-header-author-nickname-color":"#FFFFFF","mobile-header-author-links-color":{"regular":"#393c71","hover":"#393c71"},"mobile-header-menu-icon-color":{"regular":"#1c1e21","hover":"#1c1e21","active":"#eb6653"},"mobile-header-menu-wrapper-bg":{"background-color":"#fff","background-repeat":"repeat","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"mobile-nav-top-color":{"regular":"#333333","hover":"#eb6653","bg":"#fff","bg-hover":"","bg-active":""},"mobile-top-menu-border-color":"#EEEEEE","mobile-nav-sub-menu-color":{"regular":"#333","hover":"#fff","bg":"#f5f5f5","bg-hover":"#555","bg-active":"#333"},"footer":"1","footer-layout":"5","top-footer":"0","footer_form_style":"4","footer_top_logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"form_id":"5509","sub-footer":"1","back-to-top":"0","back_to_top_style":"4","footer_sell_btn":"1","sell_btn_text":"Sell","footer-copyright":"All Copyrights reserved @ 2022 - Design by Designinvento","subfooter-logos-src":{"url":"","id":"","height":"","width":"","thumbnail":""},"subfooter-logos-link":"","footer-bg":{"background-color":"#191a1f","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"sub-footer-bg":"#191A1F","top-footer-bg":"#FFFFFF","footer-title-color":"#FFFFFF","footer-txt-color":"#A9A9A9","footer-link-color":{"regular":"#a9a9a9","hover":"#ffffff","active":"eb6653"},"footer-recent-lisitng-border-color":"transparent","sub-footer-border-top":"1","sub-footer-border-top-color":{"color":"#ffffff","alpha":"0.1","rgba":"rgba(255,255,255,0.1)"},"footer-col-border":"0","footer-col-border-color":"#EEEEEE","footer-social-color":{"regular":"#ffffff","hover":"#ffffff","bg":"#24252a","bg-hover":"#eb6653"},"footer-socket-color":"#A9A9A9","footer-social-location":"1","social-facebook":"#","social-twitter":"#","social-rss":"","social-dribbble":"#","social-pinterest":"","social-instagram":"","social-google-plus":"","social-linkedin":"#","social-youtube":"#","social-vimeo":"","social-spotify":"","social-tumblr":"","social-behance":"","social-whatsapp":"","social-wechat":"","social-qzone":"","social-vkcom":"","social-imdb":"","social-renren":"","social-weibo":"","widget-title":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","subsets":"","text-align":"","font-size":"18px"},"sidebar-title-color":"#333333","sidebar-txt-color":"#546B7E","sidebar-link-color":{"regular":"#546b7e","hover":"#546b7e","active":"#eb6653"},"sidebar-widget-background-color":"#FFFFFF","sidebar-widget-border":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"sidebar-widget-box-shadow":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"sidebar-widget-border-radius":"4","body-font":{"font-family":"DM Sans","font-options":"Roboto","google":"1","font-backup":"","font-weight":"400","font-style":"","subsets":"","text-align":"","font-size":"14px"},"heading-font":{"font-family":"DM Sans","font-options":"Roboto","google":"1","font-weight":"700","font-style":"","subsets":"latin","text-align":""},"heading-font-h2":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h3":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h4":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h5":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h6":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"headings_font_family":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"","font-style":"","subsets":"","text-align":""},"buttons_font_family":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"","font-style":"","subsets":"","text-align":""},"page-title-size":"36","p-text-size":"14","p-line-height":"26","footer-p-text-size":"14","typekit-id":"","typekit-font-family":"","typekit-element-names":"","accent-color":"#EB6653","secondary-color":"","third-color":"","body-txt-color":"#546B7E","heading-color":"#191A1F","link-color":{"regular":"#546b7e","hover":"#546b7e","active":"#eb6653"},"btn-hover":"#EB6653","subs-btn-hover":"#EB6653","breadcrumb-skin":"light","breadcrumb-skin-custom":{"regular":"#ffffff","hover":"#ffffff"},"custom-css":"","custom-js":"","preloader-bg-color":"#FFFFFF","preloader-logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"page-title-blog":"1","blog-featured-image":"1","blog-image-crop":"1","blog-single-image-height":"380","blog-grid-image-width":"370","blog-grid-image-height":"230","blog-single-about-author":"1","blog-single-social-share":"1","blog-single-comments":"1","archive-layout":"right","archive-columns":"1","archive-loop-style":"classic","archive-page-title":"1","single-post-content-box-background":"#FFFFFF","single-post-comments-box-background":"#FFFFFF","single-post-content-box-border":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"single-post-content-box-shadow":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"single-post-content-box-border-radius":"4","woo-shop-layout":"full","woo-shop-columns":"4","woo-loop-thumb-height":"270","woo_loop_image_size":"crop","woo-single-thumb-height":"480","woo_single_image_size":"crop","woo-single-layout":"full","woo-single-related-columns":"4","woo-image-quality":"1","woo-single-title":"1","woo-single-show-title":"1","woo-shop-loop-title":"1","woo-bg":{"background-color":"#ffffff","background-repeat":"repeat","background-size":"","background-attachment":"scroll","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"pacz-woo-loop-product_title":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","text-align":"","font-size":"","line-height":""},"pacz-woo-loop-product_title-color":"#333333","pacz-woo-loop-product_title-color-hover":"","pacz-woo-loop-product_cat":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","text-align":"","font-size":"","line-height":""},"pacz-woo-loop-product_cat-color":"#546B7E","pacz-woo-loop-product_price":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","text-align":"","font-size":"","line-height":""},"pacz-woo-loop-product_price-color":"#EF5D50","pacz-woo-product_sale-tag-color":"#FFFFFF","pacz-woo-product_sale-tag-background-color":"#0B93D7","pacz-woo-product_addtocart-icon-color":"#546B7E","pacz-woo-product_addtocart-icon-color-hover":"#FFFFFF","pacz-woo-product_addtocart-background-color":"#FFFFFF","pacz-woo-product_addtocart-background-color-hover":"#EF5D50","pacz-woo-product_addtocart-border":{"border-top":"2px","border-right":"2px","border-bottom":"2px","border-left":"2px","border-style":"solid","border-color":"#cfd9e0"},"pacz-woo-product_addtocart-border-hover":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"pacz-woo-product_addtocart-border-radius":"4","pacz-woo-product_wishlist-icon-color":"#8C969B","pacz-woo-product_wishlist-icon-color-hover":"#EF5D50","pacz-woo-product_wishlist-background-color":"#FFFFFF","pacz-woo-product_wishlist-background-color-hover":"#FFFFFF","pacz-woo-product_wishlist-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid","border-color":"#cfd9e0"},"pacz-woo-product_wishlist-border-hover":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid","border-color":"#ef5d50"},"pacz-woo-product_wishlist-border-radius":"4","product-loop-wrapper-bg":"#FFFFFF","product-loop-wrapper-bg-hover":"#FFFFFF","product-loop-wrapper-border":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"product-loop-wrapper-border-hover":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"product-loop-wrapper-border-radius":"4","product-loop-wrapper-box-shadow":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"product-loop-wrapper-box-shadow-hover":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"product-loop-wrapper_padding":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"product-loop-content_padding":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"redux_font_control":{"convert":""},"typekit-info":"","redux-backup":1}';
			$pacz_settings = json_decode($data, true);
		}
	}
	function pacz_constants($options)
	{
		$theme_data = wp_get_theme("classiadspro");
		$pacz_parent_theme = get_file_data(
			get_template_directory() . '/style.css',
			array('Asset Version'),
			get_template()
		);
		define("PACZ_THEME_DIR", get_template_directory());
		define("PACZ_THEME_DIR_URI", get_template_directory_uri());
		define("PACZ_THEME_NAME", $options["theme_name"]);
		define("PACZ_THEME_VERSION", $theme_data['Version']);
		define("CLASSIADSPRO_THEME_OPTIONS_BUILD", $options["theme_name"] . '_options_build');
		define("PACZ_THEME_SLUG", $options["theme_slug"]);
		define("PACZ_THEME_STYLES_DYNAMIC", PACZ_THEME_DIR_URI . "/styles/dynamic");
		define("PACZ_THEME_STYLES", PACZ_THEME_DIR_URI . "/styles/css");
		define("PACZ_THEME_IMAGES", PACZ_THEME_DIR_URI . "/images");
		define("PACZ_THEME_JS", PACZ_THEME_DIR_URI . "/js");
		define("PACZ_THEME_INCLUDES", PACZ_THEME_DIR . "/includes");
		define("PACZ_THEME_FRAMEWORK", PACZ_THEME_INCLUDES . "/framework");
		define("PACZ_THEME_ACTIONS", PACZ_THEME_INCLUDES . "/actions");
		define("PACZ_THEME_PLUGINS_CONFIG", PACZ_THEME_INCLUDES . "/plugins-config");
		define("PACZ_THEME_PLUGINS_CONFIG_URI", PACZ_THEME_DIR_URI . "/includes/plugins-config");
		define('PACZ_THEME_METABOXES', PACZ_THEME_FRAMEWORK . '/metaboxes');
		define('PACZ_THEME_ADMIN_URI', PACZ_THEME_DIR_URI . '/includes');
		define('PACZ_THEME_ADMIN_ASSETS_URI', PACZ_THEME_DIR_URI . '/includes/assets');
		define('THEME_VERSION', $pacz_parent_theme[0]);
		define("PACZ_THEME_SETTINGS", 'classiads_settings');
		define("PACZ_THEME_DASHBOARD_STRING", esc_attr__('Classiads Dashboard', 'classiadspro'));
		define('PACZ_THEME_CONTROL_PANEL', PACZ_THEME_FRAMEWORK . '/pacz-panel');
		define('PACZ_THEME_CONTROL_PANEL_URI', PACZ_THEME_DIR_URI . '/includes/framework/pacz-panel');
	}

	function pacz_supports()
	{
		global $pacz_settings;
		$content_width = '';
		if (!isset($content_width)) {
			$content_width = $pacz_settings['grid-width'];
		}

		if (function_exists('add_theme_support')) {
			add_theme_support('automatic-feed-links');
			add_theme_support('editor-style');
			add_theme_support('title-tag');
			add_theme_support('custom-header');
			add_theme_support('custom-background');
			add_theme_support('wc-product-gallery-zoom');
			add_theme_support('wc-product-gallery-lightbox');
			add_theme_support('wc-product-gallery-slider');
			/* Add Woocmmerce support */
			add_theme_support('woocommerce');

			add_theme_support('post-formats', array(
				'image',
				'video',
				'quote',
				'link'
			));
			register_nav_menus(array(
				'primary-menu' => 'Primary Navigation',
				'second-menu' => 'Second Navigation',
				'third-menu' => 'Third Navigation',
				'fourth-menu' => 'Fourth Navigation',
				'fifth-menu' => 'Fifth Navigation',
				'sixth-menu' => 'Sixth Navigation',
				'seventh-menu' => 'Seventh Navigation',
			));

			add_theme_support('post-thumbnails');
		}
	}

	function pacz_functions()
	{

		require_once PACZ_THEME_FRAMEWORK . "/general.php";
		if (class_exists('Classiadspro_Core')) {
			require_once PACZ_THEME_FRAMEWORK . "/options-config.php";
		}
		require_once PACZ_THEME_FRAMEWORK . "/woocommerce.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/ajax-search.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/wp-nav-custom-walker.php";
		require_once PACZ_THEME_FRAMEWORK . '/sidebar-generator.php';
		require_once PACZ_THEME_PLUGINS_CONFIG . "/pagination.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/image-cropping.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/tgm-plugin-activation/request-plugins.php";


		require_once PACZ_THEME_PLUGINS_CONFIG . "/love-this.php";
		require_once PACZ_THEME_INCLUDES . "/thirdparty-integration/wpml-fix/pacz-wpml.php";
		if (class_exists('DirectoryPress')) {
			require_once PACZ_THEME_DIR . "/directorypress/functions.php";
		}
		/*
				Theme elements hooks
				*/
		require_once(trailingslashit(get_template_directory()) . "includes/actions/header.php");
		require_once(trailingslashit(get_template_directory()) . "includes/actions/posts.php");
		require_once(trailingslashit(get_template_directory()) . "includes/actions/general.php");

		/* Blog Styles @since V1.0 */
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/classic.php");

		/* Blog Styles @since V1.0 */
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/thumb.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/tile.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/tile-elegant.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/tile-modern.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/scroller.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/masonry.php");
	}


	function pacz_add_metaboxes()
	{
		require_once PACZ_THEME_FRAMEWORK . '/metabox-generator.php';
		require_once PACZ_THEME_METABOXES . '/metabox-layout.php';
		require_once PACZ_THEME_METABOXES . '/metabox-posts.php';
		require_once PACZ_THEME_METABOXES . '/metabox-employee.php';
		require_once PACZ_THEME_METABOXES . '/metabox-pages.php';
		require_once PACZ_THEME_METABOXES . '/metabox-clients.php';
		require_once PACZ_THEME_METABOXES . '/metabox-testimonials.php';
		include_once PACZ_THEME_METABOXES . '/metabox-skinning.php';
	}

	function pacz_admin()
	{
		if (is_admin()) {

			require_once PACZ_THEME_FRAMEWORK . '/admin.php';
			require_once PACZ_THEME_PLUGINS_CONFIG . '/mega-menu.php';
			require_once PACZ_THEME_CONTROL_PANEL . "/pacz-admin.php";
			require_once PACZ_THEME_FRAMEWORK . '/pacz-panel/index.php';
		}
	}
}

function pacz_theme_enqueue_scripts()
{
	if (!is_admin()) {

		global $pacz_settings;
		$theme_data = wp_get_theme("classiadspro");

		wp_enqueue_script('jquery-ui-tabs');
		wp_register_script('jquery-jplayer', PACZ_THEME_JS . '/jquery.jplayer.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		wp_register_script('instafeed', PACZ_THEME_JS . '/instafeed.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		if (! wp_script_is('bootstrap', 'enqueued')) {
			wp_enqueue_script('bootstrap', PACZ_THEME_JS . '/bootstrap.min.js', array(
				'jquery'
			), $theme_data['Version'], true);
		}
		wp_enqueue_script('masonry', PACZ_THEME_JS . '/masonry.pkgd.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		//if ( ! wp_script_is( 'select2', 'enqueued' ) ) {
		wp_enqueue_script('select2', PACZ_THEME_JS . '/select2.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		//}
		wp_enqueue_script('slick-js', PACZ_THEME_JS . '/slick.min.js', array(
			'jquery'
		), $theme_data['Version'], true);

		wp_enqueue_script('pacz-theme-plugins', PACZ_THEME_JS . '/plugins.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		wp_enqueue_script('pacz-theme-scripts', PACZ_THEME_JS . '/theme-scripts.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		wp_enqueue_script('pacz-slick-triger', PACZ_THEME_JS . '/triger.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		$custom_js_file = get_stylesheet_directory() . '/custom.js';
		$custom_js_file_uri = get_stylesheet_directory_uri() . '/custom.js';

		if (file_exists($custom_js_file)) {
			wp_enqueue_script('pacz-custom-js', $custom_js_file_uri, array(
				'jquery'
			), $theme_data['Version'], true);
		}

		if (is_singular()) {
			wp_enqueue_script('comment-reply');
		}
		global $pacz_settings, $pacz_accent_color, $post, $classiadspro_json, $level_num, $uID;
		$post_id = global_get_post_id();
		$pacz_header_trans_offset = (!empty(get_post_meta($post_id, '_trans_header_offset', true))) ? get_post_meta($post_id, '_trans_header_offset', true) : $pacz_settings['sticky_header_offset'];
		$rtl = (is_rtl()) ? 'true' : 'false';
		wp_localize_script(
			'pacz-theme-scripts',
			'pacz_js',
			array(
				'pacz_images_dir' => PACZ_THEME_IMAGES,
				'pacz_theme_js_path' => PACZ_THEME_JS,
				'pacz_header_toolbar' => (get_post_meta($post_id, '_header_toolbar', true) == 'true') ?  get_post_meta($post_id, '_header_toolbar', true) : $pacz_settings['header-toolbar'],
				'pacz_nav_res_width' => (isset($pacz_settings['res-nav-width'])) ? $pacz_settings['res-nav-width'] : '',
				'pacz_header_sticky' => (get_post_meta($post_id, '_custom_bg', true) == 'true') ? get_post_meta($post_id, 'sticky-header', true) : $pacz_settings['sticky-header'],
				'pacz_grid_width' => esc_attr($pacz_settings['grid-width']),
				//'pacz_preloader_logo' => esc_url($pacz_settings['preloader-logo']['url']),
				'pacz_header_padding' => esc_attr($pacz_settings['header-padding']),
				'pacz_accent_color' => esc_attr($pacz_accent_color),
				'pacz_squeeze_header' => esc_attr($pacz_settings['squeeze-sticky-header']),
				//'pacz_logo_height' => ($pacz_settings['logo']['height']) ? $pacz_settings['logo']['height'] : 50,
				//'pacz_preloader_txt_color' => ($pacz_settings['preloader-txt-color']) ? $pacz_settings['preloader-txt-color'] : '#fff',
				//'pacz_preloader_bg_color' => ($pacz_settings['preloader-bg-color']) ? $pacz_settings['preloader-bg-color'] : '#272e43',
				//'pacz_preloader_bar_color' => (isset($pacz_settings['preloader-bar-color']) && !empty($pacz_settings['preloader-bar-color'])) ? $pacz_settings['preloader-bar-color'] : $pacz_accent_color,
				'pacz_no_more_posts' => esc_html__('No More Posts', 'classiadspro'),
				'pacz_header_structure' => (get_post_meta($post_id, '_custom_bg', true) == 'true') ? get_post_meta($post_id, 'header-structure', true) : $pacz_settings['header-structure'],
				'pacz_boxed_header' => $pacz_settings['boxed-header'],
				'pacz_header_trans_offset' => $pacz_header_trans_offset,
				'pacz_is_rtl' => $rtl
			)
		);

		if (! wp_style_is('bootstrap', 'enqueued')) {
			wp_enqueue_style('bootstrap', PACZ_THEME_STYLES . '/bootstrap.min.css', false, $theme_data['Version'], 'all');
		}
		if (! wp_style_is('slick', 'enqueued')) {
			wp_enqueue_style('slick-css', PACZ_THEME_STYLES . '/slick/slick.css', false, $theme_data['Version'], 'all');
			wp_enqueue_style('slick-theme', PACZ_THEME_STYLES . '/slick/slick-theme.css', false, $theme_data['Version'], 'all');
		}

		//wp_enqueue_style('pacz-styles-default', PACZ_THEME_STYLES . '/styles.css', false, $theme_data['Version'], 'all');
		wp_register_style('material-icons', PACZ_THEME_DIR_URI . '/styles/material-icons/material-icons.min.css');
		wp_enqueue_style('material-icons');
		wp_enqueue_style('select2', PACZ_THEME_STYLES . '/select2.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-styles', PACZ_THEME_STYLES . '/pacz-styles.css', false, $theme_data['Version'], 'all');
		//wp_enqueue_style('pacz-blog', PACZ_THEME_STYLES . '/pacz-blog.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-post', PACZ_THEME_STYLES . '/post.css', false, $theme_data['Version'], 'all');

		if (!class_exists('Pacz_Static_Files')) {
			$font_family = $pacz_settings['body-font']['font-family'];
			wp_enqueue_style($font_family, 'https://fonts.googleapis.com/css?family=' . $font_family . ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900', false, false, 'all');
			wp_enqueue_style('pacz-dynamic-css', PACZ_THEME_STYLES . '/classiadspro-dynamic.css', false, $theme_data['Version'], 'all');
			wp_add_inline_style('pacz-dynamic-css', pacz_enqueue_font_icons());
		}

		wp_enqueue_style('pacz-common-shortcode', PACZ_THEME_STYLES . '/shortcode/common-shortcode.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-fonticon-custom', PACZ_THEME_STYLES . '/fonticon-custom.min.css', false, $theme_data['Version'], 'all');


		do_action('directorypress_register_listing_styles');
	}
}
add_action('wp_enqueue_scripts', 'pacz_dynamic_css_injection');
add_action('wp_enqueue_scripts', 'pacz_theme_enqueue_scripts', 1);

/**
 * wpmail_content_type
 * allow html emails
 *
 * @author Joe Sexton <joe@webtipblog.com>
 * @return string
 */
function wpmail_content_type()
{

	return 'text/html';
}

/* header script */

add_action('wp_enqueue_scripts', 'pacz_header_scripts', 1);
function pacz_header_scripts()
{
	echo '<script>
		var classiadspro = {};
		var php = {};
	 </script>';
}

/* footer scripts */
add_action('wp_footer', 'pacz_footer_elements', 1);
function pacz_footer_elements()
{
	global $pacz_settings, $pacz_accent_color, $post, $classiadspro_json, $classiadspro_dynamic_styles;
	$post_id = global_get_post_id();
	if ($post_id) {
		$preloader = get_post_meta($post_id, '_preloader', true);
		if ($preloader == 'true') {
			echo '<div class="pacz-preloader"></div>';
		}
	}
?>
	<?php if ($pacz_settings['custom-js']) : ?>
		<script>
			<?php echo esc_js($pacz_settings['custom-js']); ?>
		</script>
	<?php endif; ?>

	<?php
	$classiadspro_dynamic_styles_ids = array();
	$classiadspro_dynamic_styles_inject = '';
	if (!empty($classiadspro_dynamic_styles)) {
		$classiadspro_styles_length = count($classiadspro_dynamic_styles);
	} else {
		$classiadspro_styles_length = 0;
	}
	if ($classiadspro_styles_length > 0) {
		foreach ($classiadspro_dynamic_styles as $key => $val) {
			$classiadspro_dynamic_styles_ids[] = $val["id"];
			$classiadspro_dynamic_styles_inject .= $val["inject"];
		};
	}

	?>
	<script>
		window.$ = jQuery
		var dynamic_styles = '<?php echo pacz_clean_init_styles($classiadspro_dynamic_styles_inject); ?>';
		var dynamic_styles_ids = (<?php echo json_encode($classiadspro_dynamic_styles_ids); ?> != null) ? <?php echo json_encode($classiadspro_dynamic_styles_ids); ?> : [];

		var styleTag = document.createElement('style'),
			head = document.getElementsByTagName('head')[0];

		styleTag.type = 'text/css';
		styleTag.setAttribute('data-ajax', '');
		styleTag.innerHTML = dynamic_styles;
		head.appendChild(styleTag);


		$('.pacz-dynamic-styles').each(function() {
			$(this).remove();
		});

		function ajaxStylesInjector() {
			$('.pacz-dynamic-styles').each(function() {
				var $this = $(this),
					id = $this.attr('id'),
					commentedStyles = $this.html();
				styles = commentedStyles
					.replace('<!--', '')
					.replace('-->', '');

				if (dynamic_styles_ids.indexOf(id) === -1) {
					$('style[data-ajax]').append(styles);
					$this.remove();
				}

				dynamic_styles_ids.push(id);
			});
		};
	</script>

<?php }
add_action('after_setup_theme', 'pacz_add_image_size');
function pacz_add_image_size($name = '', $width = '', $height = '', $crop = false)
{
	add_theme_support($name);
	add_image_size($name, $width, $height, $crop);
}

// Looking to send emails in production? Check out our Email API/SMTP product!
// function mailtrap($phpmailer) {
// 	$phpmailer->isSMTP();
// 	$phpmailer->Host = 'sandbox.smtp.mailtrap.io';
// 	$phpmailer->SMTPAuth = true;
// 	$phpmailer->Port = 2525;
// 	$phpmailer->Username = '221f66dfa5dc86';
// 	$phpmailer->Password = '03a18f3433fc8a';
//   }

//   add_action('phpmailer_init', 'mailtrap'); 

/**
 * Handle avatar upload during user registration
 * Hooks into wpfb_form_register_new_user_success action from form-builder-wp plugin
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_handle_registration_avatar($user_id, $data)
{
	// Debug: log all data
	error_log('Registration avatar handler called for user: ' . $user_id);
	error_log('Form data: ' . print_r($data, true));
	error_log('FILES data: ' . print_r($_FILES, true));

	// Check if avatar field exists in form data (processed by Form Builder WP)
	if (empty($data['avatar']) || !is_array($data['avatar'])) {
		error_log('Avatar field not found in form data or not array');
		return;
	}

	$avatar_data = $data['avatar'];
	error_log('Avatar data found: ' . print_r($avatar_data, true));

	// Check if we have file info
	if (empty($avatar_data['file_url'])) {
		error_log('Avatar file_url missing');
		return;
	}

	// Convert file URL to local file path
	$upload_base_url = wp_get_upload_dir()['baseurl'];
	$upload_base_dir = wp_get_upload_dir()['basedir'];

	// Remove base URL and construct file path
	$file_relative_path = str_replace($upload_base_url, '', $avatar_data['file_url']);
	$file_path = $upload_base_dir . $file_relative_path;

	error_log('Avatar file_url: ' . $avatar_data['file_url']);
	error_log('Avatar file_path constructed: ' . $file_path);

	// Check if file exists
	if (!file_exists($file_path)) {
		error_log('Avatar file does not exist: ' . $file_path);
		// Try alternate path construction
		$alt_file_path = $upload_base_dir . '/' . ltrim($file_relative_path, '/');
		if (file_exists($alt_file_path)) {
			$file_path = $alt_file_path;
			error_log('Avatar found at alternate path: ' . $file_path);
		} else {
			return;
		}
	}

	error_log('Avatar file found at: ' . $file_path);

	// Get file info
	$file_info = wp_check_filetype($file_path);
	$file_type = $file_info['type'];

	// Validate file type (only images)
	$allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');

	if (!in_array($file_type, $allowed_types)) {
		error_log('Avatar: invalid file type - ' . $file_type);
		return;
	}

	// Validate file size (max 5MB)
	$file_size = filesize($file_path);
	$max_size = 5 * 1024 * 1024; // 5MB in bytes
	if ($file_size > $max_size) {
		error_log('Avatar: file too large - ' . $file_size);
		return;
	}

	error_log('Avatar file validation passed. Type: ' . $file_type . ', Size: ' . $file_size);

	// Load WordPress file handling functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Prepare attachment data
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title' => sprintf('Avatar for user %d', $user_id),
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $user_id,
	);

	// Insert attachment into media library
	$attach_id = wp_insert_attachment($attachment, $file_path);

	if (is_wp_error($attach_id)) {
		error_log('Avatar attachment insert error: ' . $attach_id->get_error_message());
		return;
	}

	// Generate attachment metadata (thumbnails, etc)
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	// Get the attachment URL
	$file_url = wp_get_attachment_url($attach_id);

	// Save attachment ID to user meta for our custom avatar
	update_user_meta($user_id, 'avatar_id', $attach_id);
	update_user_meta($user_id, 'user_avatar_url', $file_url);

	// For compatibility with popular avatar plugins
	update_user_meta($user_id, 'wp_user_avatar', $attach_id);
	update_user_meta($user_id, 'simple_local_avatar', $attach_id);

	error_log('Avatar uploaded successfully for user ' . $user_id . ': attachment ID ' . $attach_id . ', URL: ' . $file_url);
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_handle_registration_avatar', 10, 2);

/**
 * Handle document upload during user registration
 * Hooks into wpfb_form_register_new_user_success action from form-builder-wp plugin
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_handle_registration_document($user_id, $data)
{
	// Debug: log document data
	error_log('Registration document handler called for user: ' . $user_id);
	error_log('Form data: ' . print_r($data, true));

	// Check if document field exists in form data (processed by Form Builder WP)
	if (empty($data['document']) || !is_array($data['document'])) {
		error_log('Document field not found in form data or not array');
		return;
	}

	$document_data = $data['document'];
	error_log('Document data found: ' . print_r($document_data, true));

	// Check if we have file info
	if (empty($document_data['file_url'])) {
		error_log('Document file_url missing');
		return;
	}

	// Convert file URL to local file path
	$upload_base_url = wp_get_upload_dir()['baseurl'];
	$upload_base_dir = wp_get_upload_dir()['basedir'];

	// Remove base URL and construct file path
	$file_relative_path = str_replace($upload_base_url, '', $document_data['file_url']);
	$file_path = $upload_base_dir . $file_relative_path;

	error_log('Document file_url: ' . $document_data['file_url']);
	error_log('Document file_path constructed: ' . $file_path);

	// Check if file exists
	if (!file_exists($file_path)) {
		error_log('Document file does not exist: ' . $file_path);
		// Try alternate path construction
		$alt_file_path = $upload_base_dir . '/' . ltrim($file_relative_path, '/');
		if (file_exists($alt_file_path)) {
			$file_path = $alt_file_path;
			error_log('Document found at alternate path: ' . $file_path);
		} else {
			return;
		}
	}

	error_log('Document file found at: ' . $file_path);

	// Get file info
	$file_info = wp_check_filetype($file_path);
	$file_type = $file_info['type'];

	// Validate file type (documents)
	$allowed_types = array(
		'application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'text/plain',
		'image/jpeg',
		'image/jpg',
		'image/png',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
	);

	if (!in_array($file_type, $allowed_types)) {
		error_log('Document: invalid file type - ' . $file_type);
		return;
	}

	// Validate file size (max 10MB)
	$file_size = filesize($file_path);
	$max_size = 10 * 1024 * 1024; // 10MB in bytes
	if ($file_size > $max_size) {
		error_log('Document: file too large - ' . $file_size);
		return;
	}

	error_log('Document file validation passed. Type: ' . $file_type . ', Size: ' . $file_size);

	// Load WordPress file handling functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Prepare attachment data
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title' => sprintf('Registration document for user %d', $user_id),
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $user_id,
	);

	// Insert attachment into media library
	$attach_id = wp_insert_attachment($attachment, $file_path);

	if (is_wp_error($attach_id)) {
		error_log('Document attachment insert error: ' . $attach_id->get_error_message());
		return;
	}

	// Generate attachment metadata
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	// Get the attachment URL
	$file_url = wp_get_attachment_url($attach_id);

	// Save attachment ID to user meta
	update_user_meta($user_id, 'registration_document_id', $attach_id);
	update_user_meta($user_id, 'registration_document_url', $file_url);

	// Set user as not verified by default
	update_user_meta($user_id, 'user_verified', '0');

	error_log('Document uploaded successfully for user ' . $user_id . ': attachment ID ' . $attach_id . ', URL: ' . $file_url);
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_handle_registration_document', 10, 2);

/**
 * Send welcome email to newly registered user
 * Hooks into wpfb_form_register_new_user_success action
 * Sends welcome email in English
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_send_welcome_email($user_id, $data)
{
	// Get user data
	$user = get_userdata($user_id);

	if (!$user) {
		error_log('Welcome email: User not found - ID ' . $user_id);
		return;
	}

	// Get site info
	$site_name = get_bloginfo('name');
	$site_url = get_home_url();
	$login_url = wp_login_url();

	// Prepare email content in English
	$to = $user->user_email;
	$subject = sprintf('Welcome to %s', $site_name);

	// Build HTML email
	$message = '<html><body>';
	$message .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f5f5f5;">';
	$message .= '<tr><td style="padding: 40px 0;">';
	$message .= '<table cellpadding="0" cellspacing="0" border="0" width="600" align="center" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';

	// Header
	$message .= '<tr style="background-color: #191a1f;">';
	$message .= '<td style="padding: 30px 30px; text-align: center; border-radius: 8px 8px 0 0;">';
	$message .= '<h1 style="color: #ffffff; margin: 0; font-size: 24px;">Welcome, ' . esc_html($user->display_name) . '!</h1>';
	$message .= '</td>';
	$message .= '</tr>';

	// Content
	$message .= '<tr>';
	$message .= '<td style="padding: 40px 30px;">';
	$message .= '<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">';
	$message .= 'Thank you for registering with us! Your account has been successfully created.';
	$message .= '</p>';

	$message .= '<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">';
	$message .= 'Here is your account information:';
	$message .= '</p>';

	// Account info
	$message .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 20px 0; border: 1px solid #eeeeee; border-radius: 4px;">';
	$message .= '<tr style="background-color: #f9f9f9;">';
	$message .= '<td style="padding: 15px; font-weight: bold; color: #191a1f; width: 40%;">Username:</td>';
	$message .= '<td style="padding: 15px; color: #666666;">' . esc_html($user->user_login) . '</td>';
	$message .= '</tr>';
	$message .= '<tr>';
	$message .= '<td style="padding: 15px; font-weight: bold; color: #191a1f; background-color: #f9f9f9;">Email:</td>';
	$message .= '<td style="padding: 15px; color: #666666; background-color: #f9f9f9;">' . esc_html($user->user_email) . '</td>';
	$message .= '</tr>';
	$message .= '</table>';

	// Verification notice
	$message .= '<div style="margin: 25px 0; padding: 20px; background-color: #fff8e6; border-left: 4px solid #EB6653; border-radius: 4px;">';
	$message .= '<p style="margin: 0; font-size: 13px; font-weight: bold; color: #EB6653;">⚠️ ACCOUNT VERIFICATION REQUIRED</p>';
	$message .= '<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">';
	$message .= 'Your account is currently under verification. Our team will review your information and verify your account within 24-48 hours. ';
	$message .= 'You will receive a confirmation email once your account is verified.';
	$message .= '</p>';
	$message .= '</div>';

	// Listings notice
	$message .= '<div style="margin: 25px 0; padding: 20px; background-color: #e8f4f8; border-left: 4px solid #2081cc; border-radius: 4px;">';
	$message .= '<p style="margin: 0; font-size: 13px; font-weight: bold; color: #2081cc;">📋 POSTING LISTINGS</p>';
	$message .= '<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">';
	$message .= 'After your account is verified, you will be able to post listings and manage your ads from your dashboard. ';
	$message .= 'Please note that all listings must comply with our community guidelines.';
	$message .= '</p>';
	$message .= '</div>';

	// Call to action
	$message .= '<p style="margin: 30px 0; text-align: center;">';
	$message .= '<a href="' . esc_url(trailingslashit($site_url) . 'my-dashboard/') . '" style="display: inline-block; padding: 12px 30px; background-color: #EB6653; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Your Dashboard</a>';
	$message .= '</p>';

	// Additional info
	$message .= '<p style="margin: 30px 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">';
	$message .= 'If you have any questions or need assistance, please don\'t hesitate to contact us.';
	$message .= '</p>';

	$message .= '<p style="margin: 0; font-size: 13px; color: #999999; border-top: 1px solid #eeeeee; padding-top: 20px;">';
	$message .= 'Best regards,<br>';
	$message .= 'The ' . esc_html($site_name) . ' Team<br>';
	$message .= '<a href="' . esc_url($site_url) . '" style="color: #EB6653; text-decoration: none;">' . esc_html($site_url) . '</a>';
	$message .= '</p>';

	$message .= '</td>';
	$message .= '</tr>';

	$message .= '</table>';
	$message .= '</td></tr>';
	$message .= '</table>';
	$message .= '</body></html>';

	// Set email headers
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option('siteurl') . ' <' . get_option('admin_email') . '>',
	);

	// Send email
	$sent = wp_mail($to, $subject, $message, $headers);

	if ($sent) {
		error_log('Welcome email sent to ' . $user->user_email . ' for user ID ' . $user_id);
	} else {
		error_log('Failed to send welcome email to ' . $user->user_email . ' for user ID ' . $user_id);
	}
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_send_welcome_email', 15, 2);

/**
 * Filter to use custom avatar instead of Gravatar
 * Replaces default WordPress avatar with user uploaded photo
 * Works in admin panel and frontend
 * 
 * @param string $avatar The default avatar HTML
 * @param mixed $id_or_email User ID, email, or user object
 * @param int $size Avatar size in pixels
 * @param string $default Default avatar URL
 * @param string $alt Alt text for avatar
 * @return string Modified avatar HTML
 */
function classiadspro_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
	$user_id = 0;

	// Get user ID from different input types
	if (is_numeric($id_or_email)) {
		$user_id = (int) $id_or_email;
	} elseif (is_string($id_or_email)) {
		$user = get_user_by('email', $id_or_email);
		if ($user) {
			$user_id = $user->ID;
		}
	} elseif (is_object($id_or_email)) {
		if (isset($id_or_email->user_id)) {
			$user_id = (int) $id_or_email->user_id;
		} elseif (isset($id_or_email->ID)) {
			$user_id = (int) $id_or_email->ID;
		}
	}

	if (!$user_id) {
		return $avatar;
	}

	// Get custom avatar attachment ID
	$custom_avatar_id = get_user_meta($user_id, 'avatar_id', true);

	if (!$custom_avatar_id) {
		return $avatar;
	}

	// Get avatar image URL
	$custom_avatar_url = wp_get_attachment_image_url($custom_avatar_id, array($size, $size));

	if (!$custom_avatar_url) {
		return $avatar;
	}

	// Build custom avatar HTML
	$avatar = sprintf(
		'<img alt="%s" src="%s" class="avatar avatar-%d photo" height="%d" width="%d" loading="lazy" decoding="async" />',
		esc_attr($alt),
		esc_url($custom_avatar_url),
		(int) $size,
		(int) $size,
		(int) $size
	);

	return $avatar;
}
add_filter('get_avatar', 'classiadspro_custom_avatar', 10, 5);

/**
 * Add custom avatar field to user profile in admin
 * Allows admins to view and change user avatar
 * 
 * @param WP_User $user Current user object
 */
function classiadspro_admin_avatar_field($user)
{
	$avatar_id = get_user_meta($user->ID, 'avatar_id', true);
	$avatar_url = get_user_meta($user->ID, 'user_avatar_url', true);
?>
	<h3><?php _e('Profile Photo', 'classiadspro'); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="custom_avatar"><?php _e('Current Avatar', 'classiadspro'); ?></label></th>
			<td>
				<?php if ($avatar_id): ?>
					<?php echo wp_get_attachment_image($avatar_id, array(150, 150), false, array('style' => 'border-radius: 50%;')); ?>
					<p>
						<button type="button" class="button" id="remove_avatar_button"><?php _e('Remove Avatar', 'classiadspro'); ?></button>
					</p>
					<input type="hidden" name="remove_avatar" id="remove_avatar" value="0" />
				<?php else: ?>
					<p><?php _e('No custom avatar uploaded yet.', 'classiadspro'); ?></p>
				<?php endif; ?>

				<p class="description"><?php _e('Avatar uploaded during registration. To change it, user needs to upload a new one through the registration form.', 'classiadspro'); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="new_avatar_upload"><?php _e('Upload New Avatar', 'classiadspro'); ?></label></th>
			<td>
				<input type="file" name="new_avatar" id="new_avatar_upload" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" />
				<p class="description"><?php _e('Upload new avatar (JPG, PNG, GIF, WEBP - max 5MB)', 'classiadspro'); ?></p>
			</td>
		</tr>
	</table>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#remove_avatar_button').on('click', function() {
				if (confirm('<?php _e('Are you sure you want to remove the avatar?', 'classiadspro'); ?>')) {
					$('#remove_avatar').val('1');
					$(this).closest('tr').find('img').fadeOut();
					$(this).parent().html('<p><?php _e('Avatar will be removed on save.', 'classiadspro'); ?></p>');
				}
			});
		});
	</script>
<?php
}
// add_action('show_user_profile', 'classiadspro_admin_avatar_field');
// add_action('edit_user_profile', 'classiadspro_admin_avatar_field');

/**
 * Save custom avatar from admin profile
 * Handles avatar upload and removal from user profile page
 * 
 * @param int $user_id User ID
 */
function classiadspro_admin_save_avatar_field($user_id)
{
	if (!current_user_can('edit_user', $user_id)) {
		return false;
	}

	// Handle avatar removal
	if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] == '1') {
		$old_avatar_id = get_user_meta($user_id, 'avatar_id', true);
		if ($old_avatar_id) {
			wp_delete_attachment($old_avatar_id, true);
		}
		delete_user_meta($user_id, 'avatar_id');
		delete_user_meta($user_id, 'user_avatar_url');
		delete_user_meta($user_id, 'wp_user_avatar');
		delete_user_meta($user_id, 'simple_local_avatar');
		return;
	}

	// Handle new avatar upload
	if (empty($_FILES['new_avatar']) || empty($_FILES['new_avatar']['name'])) {
		return;
	}

	$avatar_file = $_FILES['new_avatar'];

	// Validate file upload
	if ($avatar_file['error'] !== UPLOAD_ERR_OK) {
		return;
	}

	// Verify this is a valid uploaded file
	if (!is_uploaded_file($avatar_file['tmp_name'])) {
		return;
	}

	// Validate file type
	$allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');
	if (!in_array($avatar_file['type'], $allowed_types)) {
		return;
	}

	// Validate file size (max 5MB)
	$max_size = 5 * 1024 * 1024;
	if ($avatar_file['size'] > $max_size) {
		return;
	}

	// Load WordPress file handling functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Delete old avatar if exists
	$old_avatar_id = get_user_meta($user_id, 'avatar_id', true);
	if ($old_avatar_id) {
		wp_delete_attachment($old_avatar_id, true);
	}

	// Upload new avatar
	$upload_overrides = array(
		'test_form' => false,
		'test_type' => true,
	);

	$uploaded_file = wp_handle_upload($avatar_file, $upload_overrides);

	if (isset($uploaded_file['error'])) {
		return;
	}

	$file_path = $uploaded_file['file'];
	$file_url = $uploaded_file['url'];
	$file_type = $uploaded_file['type'];

	// Create attachment
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title' => sprintf('Avatar for user %d', $user_id),
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $user_id,
	);

	$attach_id = wp_insert_attachment($attachment, $file_path);

	if (is_wp_error($attach_id)) {
		return;
	}

	// Generate metadata
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	// Update user meta
	update_user_meta($user_id, 'avatar_id', $attach_id);
	update_user_meta($user_id, 'user_avatar_url', $file_url);
	update_user_meta($user_id, 'wp_user_avatar', $attach_id);
	update_user_meta($user_id, 'simple_local_avatar', $attach_id);
}
// add_action('personal_options_update', 'classiadspro_admin_save_avatar_field');
// add_action('edit_user_profile_update', 'classiadspro_admin_save_avatar_field');

/**
 * Add avatar column to users list in admin
 * Shows custom avatar in users table
 * 
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function classiadspro_add_avatar_column($columns)
{
	$columns['avatar'] = __('Avatar', 'classiadspro');
	return $columns;
}
// add_filter('manage_users_columns', 'classiadspro_add_avatar_column');

/**
 * Display avatar in users list column
 * 
 * @param string $output Column output
 * @param string $column_name Column name
 * @param int $user_id User ID
 * @return string Column content
 */
function classiadspro_show_avatar_column($output, $column_name, $user_id)
{
	if ($column_name === 'avatar') {
		$avatar_id = get_user_meta($user_id, 'avatar_id', true);
		if ($avatar_id) {
			return wp_get_attachment_image($avatar_id, array(32, 32), false, array('style' => 'border-radius: 50%;'));
		} else {
			return get_avatar($user_id, 32);
		}
	}
	return $output;
}
// add_filter('manage_users_custom_column', 'classiadspro_show_avatar_column', 10, 3);

/**
 * Add verification and document fields to user profile in admin
 * Allows admins to view registration document and verify user
 * 
 * @param WP_User $user Current user object
 */
function classiadspro_admin_verification_fields($user)
{
	$document_id = get_user_meta($user->ID, 'registration_document_id', true);
	$avatar_id = get_user_meta($user->ID, 'avatar_id', true);
	$is_verified = get_user_meta($user->ID, 'user_verified', true);
?>
	<h3><?php _e('User Verification', 'classiadspro'); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="user_verified"><?php _e('Account Status', 'classiadspro'); ?></label></th>
			<td>
				<label>
					<input type="checkbox" name="user_verified" id="user_verified" value="1" <?php checked($is_verified, '1'); ?> />
					<span><?php _e('Verified - User can post listings', 'classiadspro'); ?></span>
				</label>
				<p class="description">
					<?php
					if ($is_verified) {
						_e('✓ User account is verified and can post listings', 'classiadspro');
					} else {
						_e('✗ User account is not verified. User cannot post listings until verified.', 'classiadspro');
					}
					?>
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e('User Avatar (Profile Photo)', 'classiadspro'); ?></label></th>
			<td>
				<?php if ($avatar_id): ?>
					<div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
						<?php
						$avatar_url = wp_get_attachment_url($avatar_id);
						$avatar_title = get_the_title($avatar_id);
						$avatar_type = get_post_mime_type($avatar_id);
						?>

						<!-- Avatar Preview -->
						<div style="margin-bottom: 15px; text-align: center;">
							<a href="<?php echo esc_url($avatar_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-block; cursor: pointer;">
								<img src="<?php echo esc_url($avatar_url); ?>"
									alt="<?php echo esc_attr($avatar_title); ?>"
									style="max-width: 400px; max-height: 500px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: opacity 0.3s ease;"
									onmouseover="this.style.opacity='0.8';"
									onmouseout="this.style.opacity='1';" />
							</a>
						</div>

						<!-- Avatar Info -->
						<table style="width: 100%; margin: 15px 0 0 0;">
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold; width: 30%;"><?php _e('File Name:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($avatar_title); ?></td>
							</tr>
							<tr>
								<td style="padding: 8px; font-weight: bold; background-color: #f0f0f0;"><?php _e('File Type:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($avatar_type); ?></td>
							</tr>
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold;"><?php _e('Uploaded:', 'classiadspro'); ?></td>
								<td style="padding: 8px;">
									<?php
									$attachment = get_post($avatar_id);
									echo esc_html(date_i18n('F d, Y H:i', strtotime($attachment->post_date)));
									?>
								</td>
							</tr>
						</table>

						<!-- Actions -->
						<p style="margin: 15px 0 0 0;">
							<a href="<?php echo esc_url($avatar_url); ?>" target="_blank" class="button button-primary">
								<?php _e('View/Download Avatar', 'classiadspro'); ?>
							</a>
						</p>
					</div>
				<?php else: ?>
					<div style="padding: 20px; background-color: #fff8e6; border: 1px solid #ddd; border-left: 4px solid #ffc107; border-radius: 4px;">
						<p style="margin: 0; color: #856404;">
							⚠️ <?php _e('No avatar uploaded.', 'classiadspro'); ?>
						</p>
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php _e('Registration Document (Passport Photo)', 'classiadspro'); ?></label></th>
			<td>
				<?php if ($document_id): ?>
					<div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
						<?php
						$document_url = wp_get_attachment_url($document_id);
						$document_title = get_the_title($document_id);
						$document_type = get_post_mime_type($document_id);
						$is_image = strpos($document_type, 'image/') === 0;
						?>

						<!-- Document Preview -->
						<?php if ($is_image): ?>
							<div style="margin-bottom: 15px; text-align: center;">
								<a href="<?php echo esc_url($document_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-block; cursor: pointer;">
									<img src="<?php echo esc_url($document_url); ?>"
										alt="<?php echo esc_attr($document_title); ?>"
										style="max-width: 400px; max-height: 500px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: opacity 0.3s ease;"
										onmouseover="this.style.opacity='0.8';"
										onmouseout="this.style.opacity='1';" />
								</a>
							</div>
						<?php else: ?>
							<div style="margin-bottom: 15px; padding: 20px; background-color: #e8f4f8; border-radius: 4px; text-align: center;">
								<p style="margin: 0; font-size: 48px;">📄</p>
								<p style="margin: 5px 0 0 0; color: #666;"><?php _e('Document preview not available', 'classiadspro'); ?></p>
							</div>
						<?php endif; ?>

						<!-- Document Info -->
						<table style="width: 100%; margin: 15px 0 0 0;">
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold; width: 30%;"><?php _e('File Name:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($document_title); ?></td>
							</tr>
							<tr>
								<td style="padding: 8px; font-weight: bold; background-color: #f0f0f0;"><?php _e('File Type:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($document_type); ?></td>
							</tr>
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold;"><?php _e('Uploaded:', 'classiadspro'); ?></td>
								<td style="padding: 8px;">
									<?php
									$attachment = get_post($document_id);
									echo esc_html(date_i18n('F d, Y H:i', strtotime($attachment->post_date)));
									?>
								</td>
							</tr>
						</table>

						<!-- Actions -->
						<p style="margin: 15px 0 0 0;">
							<a href="<?php echo esc_url($document_url); ?>" target="_blank" class="button button-primary">
								<?php _e('View/Download Document', 'classiadspro'); ?>
							</a>
						</p>
					</div>
				<?php else: ?>
					<div style="padding: 20px; background-color: #fff8e6; border: 1px solid #ddd; border-left: 4px solid #ffc107; border-radius: 4px;">
						<p style="margin: 0; color: #856404;">
							⚠️ <?php _e('No registration document uploaded.', 'classiadspro'); ?>
						</p>
					</div>
				<?php endif; ?>
			</td>
		</tr>
	</table>
<?php
}
add_action('show_user_profile', 'classiadspro_admin_verification_fields');
add_action('edit_user_profile', 'classiadspro_admin_verification_fields');

/**
 * Save verification status from admin profile
 * Sends email to user when account is verified
 * 
 * @param int $user_id User ID
 */
function classiadspro_admin_save_verification_field($user_id)
{
	if (!current_user_can('edit_user', $user_id)) {
		return false;
	}

	// Get current verification status
	$old_verified = get_user_meta($user_id, 'user_verified', true);
	$new_verified = isset($_POST['user_verified']) ? '1' : '0';

	// Update verification status
	update_user_meta($user_id, 'user_verified', $new_verified);

	// If verification status changed to verified, send email
	if ($old_verified !== '1' && $new_verified === '1') {
		classiadspro_send_verification_email($user_id);
	}
}
add_action('personal_options_update', 'classiadspro_admin_save_verification_field');
add_action('edit_user_profile_update', 'classiadspro_admin_save_verification_field');

/**
 * Send account verification email to user
 * Notifies user that their account has been verified and they can post listings
 * 
 * @param int $user_id User ID
 */
function classiadspro_send_verification_email($user_id)
{
	$user = get_userdata($user_id);

	if (!$user) {
		error_log('Verification email: User not found - ID ' . $user_id);
		return;
	}

	// Get site info
	$site_name = get_bloginfo('name');
	$site_url = get_home_url();
	$dashboard_url = trailingslashit($site_url) . 'my-dashboard/';

	// Prepare email
	$to = $user->user_email;
	$subject = sprintf('Your Account Has Been Verified - %s', $site_name);

	// Build HTML email
	$message = '<html><body>';
	$message .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f5f5f5;">';
	$message .= '<tr><td style="padding: 40px 0;">';
	$message .= '<table cellpadding="0" cellspacing="0" border="0" width="600" align="center" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';

	// Header
	$message .= '<tr style="background-color: #28a745;">';
	$message .= '<td style="padding: 30px 30px; text-align: center; border-radius: 8px 8px 0 0;">';
	$message .= '<h1 style="color: #ffffff; margin: 0; font-size: 24px;">✓ Account Verified!</h1>';
	$message .= '</td>';
	$message .= '</tr>';

	// Content
	$message .= '<tr>';
	$message .= '<td style="padding: 40px 30px;">';

	// Main message
	$message .= '<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">';
	$message .= 'Hello ' . esc_html($user->display_name) . ',';
	$message .= '</p>';

	$message .= '<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">';
	$message .= 'Great news! Your account has been verified by our team. You can now post listings and manage your ads on our platform.';
	$message .= '</p>';

	// Success box
	$message .= '<div style="margin: 25px 0; padding: 20px; background-color: #e8f5e9; border-left: 4px solid #28a745; border-radius: 4px;">';
	$message .= '<p style="margin: 0; font-size: 13px; font-weight: bold; color: #28a745;">✓ VERIFICATION COMPLETE</p>';
	$message .= '<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">';
	$message .= 'Your account is now fully active. You can start posting listings right away!';
	$message .= '</p>';
	$message .= '</div>';

	// What you can do now
	$message .= '<div style="margin: 25px 0; padding: 20px; background-color: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">';
	$message .= '<p style="margin: 0; font-size: 13px; font-weight: bold; color: #1976d2;">📋 YOU CAN NOW:</p>';
	$message .= '<ul style="margin: 10px 0 0 0; padding-left: 20px; font-size: 13px; line-height: 1.8; color: #333333;">';
	$message .= '<li>Post new listings and manage your ads</li>';
	$message .= '<li>Update your profile and account information</li>';
	$message .= '<li>Interact with potential buyers/renters</li>';
	$message .= '<li>Monitor listing views and inquiries</li>';
	$message .= '</ul>';
	$message .= '</div>';

	// Call to action
	$message .= '<p style="margin: 30px 0; text-align: center;">';
	$message .= '<a href="' . esc_url($dashboard_url) . '" style="display: inline-block; padding: 12px 30px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Your Dashboard</a>';
	$message .= '</p>';

	// Additional info
	$message .= '<p style="margin: 30px 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">';
	$message .= 'If you have any questions or need assistance, please don\'t hesitate to contact us.';
	$message .= '</p>';

	// Footer
	$message .= '<p style="margin: 0; font-size: 13px; color: #999999; border-top: 1px solid #eeeeee; padding-top: 20px;">';
	$message .= 'Best regards,<br>';
	$message .= 'The ' . esc_html($site_name) . ' Team<br>';
	$message .= '<a href="' . esc_url($site_url) . '" style="color: #28a745; text-decoration: none;">' . esc_html($site_url) . '</a>';
	$message .= '</p>';

	$message .= '</td>';
	$message .= '</tr>';

	$message .= '</table>';
	$message .= '</td></tr>';
	$message .= '</table>';
	$message .= '</body></html>';

	// Set email headers
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option('siteurl') . ' <' . get_option('admin_email') . '>',
	);

	// Send email
	$sent = wp_mail($to, $subject, $message, $headers);

	if ($sent) {
		error_log('Verification email sent to ' . $user->user_email . ' for user ID ' . $user_id);
	} else {
		error_log('Failed to send verification email to ' . $user->user_email . ' for user ID ' . $user_id);
	}
}

/**
 * Add verification status column to users list in admin
 * Shows verification status in users table
 * 
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function classiadspro_add_verification_column($columns)
{
	$columns['verified'] = __('Status', 'classiadspro');
	return $columns;
}
add_filter('manage_users_columns', 'classiadspro_add_verification_column');

/**
 * Display verification status in users list column
 * 
 * @param string $output Column output
 * @param string $column_name Column name
 * @param int $user_id User ID
 * @return string Column content
 */
function classiadspro_show_verification_column($output, $column_name, $user_id)
{
	if ($column_name === 'verified') {
		$is_verified = get_user_meta($user_id, 'user_verified', true);
		if ($is_verified) {
			return '<span style="color: #28a745; font-weight: bold;">✓ ' . __('Verified', 'classiadspro') . '</span>';
		} else {
			return '<span style="color: #dc3545; font-weight: bold;">✗ ' . __('Not Verified', 'classiadspro') . '</span>';
		}
	}
	return $output;
}
add_filter('manage_users_custom_column', 'classiadspro_show_verification_column', 10, 3);

// Collapse Filters by default
// add_action('wp_footer', 'classiadspro_collapse_filters', 999);
/**
 * Collapse filters section and individual filter fields by default on page load
 */
function classiadspro_collapse_filters()
{
?>
	<script>
		(function($) {
			'use strict';

			function initFiltersCollapse() {
				var $searchForm = $('.directorypress-search-form');

				if (!$searchForm.length) {
					return;
				}

				// Проверяем размер экрана
				var isMobile = $(window).width() <= 768;

				// Находим кастомный враппер фильтров (уже добавлен в шаблон)
				var $filtersWrapper = $searchForm.find('.custom-filters-wrapper');
				var $filtersHeader = $searchForm.find('.custom-filters-header');

				if ($filtersWrapper.length && $filtersHeader.length) {
					// Скрываем все внутренние элементы с текстом "Filters" чтобы избежать дублирования
					$filtersWrapper.find('*').each(function() {
						var $element = $(this);
						if ($element.text().trim() === "Filters" && !$element.hasClass('custom-filters-header')) {
							$element.hide();
						}
					});

					// Устанавливаем начальное состояние в зависимости от размера экрана
					if (isMobile) {
						$filtersWrapper.addClass('collapsed').hide();
						$filtersHeader.addClass('collapsed');
					} else {
						$filtersWrapper.removeClass('collapsed').show();
						$filtersHeader.removeClass('collapsed');
						// Показываем все фильтры на десктопе
						$filtersWrapper.find('.search-field-content-wrapper, .field-input-wrapper').show();
					}

					// Обработчик клика по общему заголовку
					$filtersHeader.off('click.filtersToggle').on('click.filtersToggle', function(e) {
						e.preventDefault();
						e.stopPropagation();

						console.log('Filters header clicked'); // Отладка

						var $header = $(this);
						var $wrapper = $searchForm.find('.custom-filters-wrapper'); // Ищем в контексте формы
						var isCollapsed = $wrapper.hasClass('collapsed');

						console.log('Is collapsed:', isCollapsed); // Отладка

						$header.toggleClass('collapsed');
						$wrapper.toggleClass('collapsed');

						if (isCollapsed) {
							// Разворачиваем все фильтры
							$wrapper.stop(true, true).slideDown(300);
							$wrapper.find('.search-field-content-wrapper, .field-input-wrapper').show();
							console.log('Expanding filters'); // Отладка
						} else {
							// Сворачиваем все фильтры
							$wrapper.stop(true, true).slideUp(300);
							console.log('Collapsing filters'); // Отладка
						}

						return false;
					});

					// Дополнительный обработчик для клика в любом месте заголовка
					$filtersHeader.css('cursor', 'pointer');
					console.log('Filters header initialized:', $filtersHeader.length); // Отладка
				}
			}

			// Альтернативный обработчик через делегирование событий
			$(document).on('click', '.custom-filters-header', function(e) {
				e.preventDefault();
				e.stopPropagation();

				console.log('Alternative click handler triggered'); // Отладка

				var $header = $(this);
				var $wrapper = $header.siblings('.custom-filters-wrapper');

				if (!$wrapper.length) {
					$wrapper = $header.next('.custom-filters-wrapper');
				}

				if (!$wrapper.length) {
					$wrapper = $header.parent().find('.custom-filters-wrapper');
				}

				var isCollapsed = $wrapper.hasClass('collapsed');

				$header.toggleClass('collapsed');
				$wrapper.toggleClass('collapsed');

				if (isCollapsed) {
					// Разворачиваем все фильтры
					$wrapper.stop(true, true).slideDown(300);
					$wrapper.find('.search-field-content-wrapper, .field-input-wrapper').show();
				} else {
					// Сворачиваем все фильтры
					$wrapper.stop(true, true).slideUp(300);
				}

				return false;
			});

			$(function() {
				// Small delay to ensure DOM is fully loaded
				setTimeout(function() {
					initFiltersCollapse();
				}, 500);

				// Re-initialize on AJAX content load
				$(document).on('directorypress_content_loaded', function() {
					setTimeout(function() {
						initFiltersCollapse();
					}, 500);
				});

				// Переинициализация при изменении размера окна
				$(window).on('resize', function() {
					setTimeout(function() {
						initFiltersCollapse();
					}, 100);
				});
			});
		})(jQuery);
	</script>
	<?php
}

/**
 * Добавляет счетчик непрочитанных сообщений к мобильной иконке чата
 */
function add_mobile_chat_counter()
{
	// Проверяем что пользователь авторизован
	if (!is_user_logged_in()) {
		return;
	}

	$current_user_id = get_current_user_id();
	$unread_count = 0;

	// Проверяем разные возможные таблицы сообщений
	global $wpdb;

	// Возможные названия таблиц
	$possible_tables = array(
		$wpdb->prefix . 'directorypress_messages',
		$wpdb->prefix . 'directorypress_frontend_messages',
		$wpdb->prefix . 'dp_messages'
	);

	foreach ($possible_tables as $table_name) {
		$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'");
		if ($table_exists) {
			// Получаем структуру таблицы
			$columns = $wpdb->get_results("SHOW COLUMNS FROM {$table_name}");
			$column_names = array_column($columns, 'Field');

			// Пробуем разные варианты структуры
			if (in_array('recipient_id', $column_names) && in_array('is_read', $column_names)) {
				$unread_count = $wpdb->get_var($wpdb->prepare(
					"SELECT COUNT(*) FROM {$table_name} 
                     WHERE recipient_id = %d AND is_read = 0",
					$current_user_id
				));
			} elseif (in_array('to_user', $column_names) && in_array('read_status', $column_names)) {
				$unread_count = $wpdb->get_var($wpdb->prepare(
					"SELECT COUNT(*) FROM {$table_name} 
                     WHERE to_user = %d AND read_status = 0",
					$current_user_id
				));
			}

			if ($unread_count > 0) {
				break;
			}
		}
	}

	// Для тестирования - показываем счетчик всегда (уберите эту строку после тестирования)
	if ($unread_count == 0) {
		$unread_count = 5; // Тестовое значение
	}

	if ($unread_count > 0) {
	?>
		<script>
			jQuery(document).ready(function($) {
				console.log('Ищем элемент чата для добавления счетчика...');

				// Функция для добавления счетчика
				function addChatCounter() {
					// Расширенный список селекторов для поиска иконки чата
					var selectors = [
						'#mob-messages',
						'a[href*="directorypress_action=messages"]',
						'a[href*="my-dashboard"][href*="messages"]',
						'a:contains("Chat")',
						'.hfb-button:contains("Chat")',
						'[class*="chat"]',
						'[id*="chat"]',
						'[class*="message"]',
						'[id*="message"]',
						'i.dicode-material-icons-message-minus-outline'
					];

					var chatLink = null;

					// Ищем по всем селекторам
					for (var i = 0; i < selectors.length; i++) {
						var found = $(selectors[i]);
						if (found.length > 0) {
							console.log('Найден элемент чата:', selectors[i], found);
							chatLink = found.first();
							break;
						}
					}

					// Если нашли родительский элемент с иконкой, ищем ссылку
					if (chatLink && chatLink.find('a').length > 0) {
						chatLink = chatLink.find('a').first();
					}

					if (chatLink && chatLink.length > 0 && !chatLink.find('.chat-counter').length) {
						console.log('Добавляем счетчик к элементу:', chatLink);
						var counter = $('<span class="chat-counter"><?php echo esc_js($unread_count); ?></span>');
						chatLink.css('position', 'relative').append(counter);
						return true;
					}

					return false;
				}

				// Добавляем счетчик сразу
				var success = addChatCounter();

				if (!success) {
					console.log('Элемент чата не найден, запускаем периодическую проверку...');

					// Проверяем каждые 2 секунды на случай если элемент загружается позже
					var attempts = 0;
					var maxAttempts = 15;
					var interval = setInterval(function() {
						attempts++;
						console.log('Попытка ' + attempts + ' из ' + maxAttempts);

						var success = addChatCounter();

						if (success || attempts >= maxAttempts) {
							clearInterval(interval);
							if (success) {
								console.log('Счетчик успешно добавлен!');
							} else {
								console.log('Не удалось найти элемент чата после ' + maxAttempts + ' попыток');
							}
						}
					}, 2000);
				} else {
					console.log('Счетчик добавлен сразу!');
				}
			});
		</script>

		<style>
			.chat-counter {
				position: absolute;
				top: -8px;
				right: -8px;
				background: #ff4444;
				color: white;
				border-radius: 50%;
				width: 20px;
				height: 20px;
				font-size: 12px;
				font-weight: bold;
				display: flex;
				align-items: center;
				justify-content: center;
				z-index: 999;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
			}

			/* Для случая если ссылка не имеет position: relative */
			a[href*="directorypress_action=messages"] {
				position: relative !important;
			}

			#mob-messages {
				position: relative !important;
			}
		</style>
	<?php
	}
}

// Добавляем в footer для мобильных устройств
add_action('wp_footer', 'add_mobile_chat_counter');
/**
 * Добавляет inline стили для страницы рекламирования
 */
function classiadspro_advertise_page_styles()
{
	if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
	?>
		<style>
			/* Убеждаемся что рекомендуемый пакет выделен */
			.period-popular.period-card {
				background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
				border-color: #28a745 !important;
				color: #fff !important;
				transform: translateY(-3px) !important;
				box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3) !important;
			}

			.period-popular .period-price {
				color: #fff !important;
			}

			.period-popular .popular-badge {
				background: #fff !important;
				color: #28a745 !important;
			}
		</style>
	<?php
	}
}
add_action('wp_head', 'classiadspro_advertise_page_styles');

/**
 * Добавляет inline JavaScript для страницы рекламирования
 */
function classiadspro_advertise_page_scripts()
{
	if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
	?>
		<script>
			jQuery(document).ready(function($) {
				// Убеждаемся что рекомендуемый пакет выбран по умолчанию
				if ($('.advertising-form').length > 0) {
					// Принудительно выбираем 3-дневный пакет если ничего не выбрано
					if (!$('input[name="advertising_period"]:checked').length) {
						$('input[name="advertising_period"][value="3_days"]').prop('checked', true);
					}

					// Добавляем визуальное выделение для выбранного пакета
					$('input[name="advertising_period"]:checked').each(function() {
						$(this).siblings('.period-card').addClass('period-selected');
					});
				}
			});
		</script>
<?php
	}
}
add_action('wp_footer', 'classiadspro_advertise_page_scripts');
/**
 * Подключает стили и скрипты для страницы рекламирования из папки assets
 */
function classiadspro_advertise_page_assets()
{
	// Check if we're on advertise listing page or dashboard with advertise listing
	$is_advertise_page = is_page('advertise-listing') ||
		(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false) ||
		(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'advertise_listing') !== false) ||
		(isset($_GET['listing_id']) && isset($_GET['action']) && $_GET['action'] === 'advertise_listing');

	if ($is_advertise_page) {
		$theme_data = wp_get_theme("classiadspro");

		// Подключаем CSS файл для рекламирования
		wp_enqueue_style(
			'advertise-listing-styles',
			get_template_directory_uri() . '/directorypress/assets/css/advertise-listing.css',
			array(),
			$theme_data['Version'],
			'all'
		);

		// Подключаем JavaScript файл для рекламирования
		wp_enqueue_script(
			'advertise-listing-scripts',
			get_template_directory_uri() . '/directorypress/assets/js/advertise-listing.js',
			array('jquery'),
			$theme_data['Version'],
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'classiadspro_advertise_page_assets');
/**
 * Устанавливает цены рекламирования по умолчанию если они не установлены
 */
function classiadspro_ensure_advertising_prices()
{
	$default_prices = array(
		'classiadspro_advertising_price_1_day' => 10,
		'classiadspro_advertising_price_3_days' => 25,
		'classiadspro_advertising_price_7_days' => 50,
	);

	foreach ($default_prices as $option_name => $default_value) {
		if (get_option($option_name) === false) {
			update_option($option_name, $default_value);
		}
	}

	// Также убеждаемся что продукты WooCommerce созданы
	classiadspro_ensure_advertising_products();
}

// Устанавливаем цены при активации темы
add_action('after_switch_theme', 'classiadspro_ensure_advertising_prices');

// Также устанавливаем при каждой загрузке админки (на случай если опции были удалены)
add_action('admin_init', 'classiadspro_ensure_advertising_prices');
/**
 * Принудительно создает продукты рекламирования и устанавливает цены
 * Вызывается при каждой загрузке страницы рекламирования
 */
function classiadspro_force_setup_advertising()
{
	// Устанавливаем цены
	update_option('classiadspro_advertising_price_1_day', 10);
	update_option('classiadspro_advertising_price_3_days', 25);
	update_option('classiadspro_advertising_price_7_days', 50);

	// Создаем продукты если их нет
	if (class_exists('WC_Product')) {
		classiadspro_create_advertising_products();
	}
}

// Вызываем при загрузке страницы рекламирования
add_action('wp', function () {
	if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
		classiadspro_force_setup_advertising();
	}
});
