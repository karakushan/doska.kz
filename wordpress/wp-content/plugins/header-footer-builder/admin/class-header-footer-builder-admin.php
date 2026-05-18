<?php

/**
 * @package    Header_Footer_Builder
 * @subpackage Header_Footer_Builder/admin
 * @author     Designinvento <help.designinvento@gmail.com>
 */

use HFB\Lib\HFB_Conditions_Setting;
 
class Header_Footer_Builder_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'elementor/init', array($this, 'load_admin'), 0 );
		add_action( 'init', array($this, 'header_footer_posttype') );
		add_action( 'admin_menu', array($this, 'register_admin_menu'), 50 );
		add_action( 'add_meta_boxes', array($this, 'ehf_register_metabox') );
		add_action( 'save_post', array($this, 'ehf_save_meta') );
		add_action( 'admin_notices', array($this, 'location_notice') );
		add_action( 'template_redirect', array($this, 'block_template_frontend') );
		add_filter( 'single_template', array($this, 'load_canvas_template') );
		add_filter( 'manage_hfb-post_posts_columns', array($this, 'set_shortcode_columns') );
		add_action( 'manage_hfb-post_posts_custom_column', array($this, 'render_shortcode_column'), 10, 2 );
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) && ELEMENTOR_PRO_VERSION > 2.8 ) {
			add_action( 'elementor/editor/footer', array($this, 'register_hfb_epro_script'), 99 );
		}

		if ( is_admin() ) {
			add_action( 'manage_hfb-post_posts_custom_column', array($this, 'column_content'), 10, 2 );
			add_filter( 'manage_hfb-post_posts_columns', array($this, 'column_headings') );
		}
		add_action( 'admin_enqueue_scripts', array($this, 'scripts_styles') );
		//add_action( 'admin_enqueue_scripts', array($this, 'enqueue_styles') );
	}
	
	public function hooks() {
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function scripts_styles() {

		wp_register_style('el-hfb-admin-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'admin/css/admin.css', array(), HEADER_FOOTER_BUILDER_VERSION);

		wp_enqueue_style( 'el-hfb-admin-style' );
	}
	
	public function load_admin() {
		add_action( 'elementor/editor/after_enqueue_styles', array($this, 'scripts_styles'));
	}
	public function register_hfb_epro_script() {
		$ids_array = [
			[
				'id'    => get_hfb_header_id(),
				'value' => 'Header',
			],
			[
				'id'    => get_hfb_footer_id(),
				'value' => 'Footer',
			],
			[
				'id'    => hfb_get_before_footer_id(),
				'value' => 'Before Footer',
			],
		];

		wp_enqueue_script( 'hfb-elementor-pro-compatibility', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/js/hfb-elementor-pro-compatibility.js', array('jquery'), HEADER_FOOTER_BUILDER_VERSION, true );

		wp_localize_script(
			'hfb-elementor-pro-compatibility',
			'hfb_admin',
			[
				'ids_array' => wp_json_encode( $ids_array ),
			]
		);
	}

	/**
	 * Adds or removes list table column headings.
	 *
	 * @param array $columns Array of columns.
	 * @return array
	 */
	public function column_headings( $columns ) {
		unset( $columns['date'] );

		$columns['elementor_hf_display_rules'] = __( 'Display Rules', 'header-footer-builder' );
		$columns['date']                       = __( 'Date', 'header-footer-builder' );

		return $columns;
	}

	/**
	 * Adds the custom list table column content.
	 *
	 * @since 1.2.0
	 * @param array $column Name of column.
	 * @param int   $post_id Post id.
	 * @return void
	 */
	public function column_content( $column, $post_id ) {

		if ( 'elementor_hf_display_rules' == $column ) {

			$locations = get_post_meta( $post_id, 'ehf_target_include_locations', true );
			if ( ! empty( $locations ) ) {
				echo '<div class="ast-advanced-headers-location-wrap" style="margin-bottom: 5px;">';
				echo '<strong>Display: </strong>';
				$this->column_display_location_rules( $locations );
				echo '</div>';
			}

			$locations = get_post_meta( $post_id, 'ehf_target_exclude_locations', true );
			if ( ! empty( $locations ) ) {
				echo '<div class="ast-advanced-headers-exclusion-wrap" style="margin-bottom: 5px;">';
				echo '<strong>Exclusion: </strong>';
				$this->column_display_location_rules( $locations );
				echo '</div>';
			}

			$users = get_post_meta( $post_id, 'ehf_target_user_roles', true );
			if ( isset( $users ) && is_array( $users ) ) {
				if ( isset( $users[0] ) && ! empty( $users[0] ) ) {
					$user_label = [];
					foreach ( $users as $user ) {
						$user_label[] = HFB_Conditions_Setting::get_user_by_key( $user );
					}
					echo '<div class="ast-advanced-headers-users-wrap">';
					echo '<strong>Users: </strong>';
					echo join( ', ', $user_label );
					echo '</div>';
				}
			}
		}
	}

	/**
	 * Get Markup of Location rules for Display rule column.
	 *
	 * @param array $locations Array of locations.
	 * @return void
	 */
	public function column_display_location_rules( $locations ) {

		$location_label = [];
		$index          = array_search( 'specifics', $locations['rule'] );
		if ( false !== $index && ! empty( $index ) ) {
			unset( $locations['rule'][ $index ] );
		}

		if ( isset( $locations['rule'] ) && is_array( $locations['rule'] ) ) {
			foreach ( $locations['rule'] as $location ) {
				$location_label[] = HFB_Conditions_Setting::get_location_by_key( $location );
			}
		}
		if ( isset( $locations['specific'] ) && is_array( $locations['specific'] ) ) {
			foreach ( $locations['specific'] as $location ) {
				$location_label[] = HFB_Conditions_Setting::get_location_by_key( $location );
			}
		}

		echo join( ', ', $location_label );
	}


	/**
	 * Register Post type for header footer & blocks templates
	 */
	public function header_footer_posttype() {
		$labels = [
			'name'               => __( 'Header Footer', 'header-footer-builder' ),
			'singular_name'      => __( 'Header Footer', 'header-footer-builder' ),
			'menu_name'          => __( 'Headers Footers', 'header-footer-builder' ),
			'name_admin_bar'     => __( 'Header Footer Builder', 'header-footer-builder' ),
			'add_new'            => __( 'Create New', 'header-footer-builder' ),
			'add_new_item'       => __( 'Create New Header/Footer', 'header-footer-builder' ),
			'new_item'           => __( 'New Header/Footer', 'header-footer-builder' ),
			'edit_item'          => __( 'Edit Header/Footer', 'header-footer-builder' ),
			'view_item'          => __( 'View Header/Footer', 'header-footer-builder' ),
			'all_items'          => __( 'Templates', 'header-footer-builder' ),
			'search_items'       => __( 'Search Header/Footer', 'header-footer-builder' ),
			'parent_item_colon'  => __( 'Parent Header/Footer:', 'header-footer-builder' ),
			'not_found'          => __( 'No Header/Footer Found.', 'header-footer-builder' ),
			'not_found_in_trash' => __( 'No Header/Footer Found In Trash.', 'header-footer-builder' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'hf_builder',
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_icon'           => 'dashicons-editor-kitchensink',
			'supports'            => [ 'title', 'thumbnail', 'elementor' ],
		];

		register_post_type( 'hfb-post', $args );
		
		// Megamenu
		$labels = array(
				'name' => esc_html_x('MegaMenus', 'MegaMenus', 'bestbug'),
				'singular_name' => esc_html_x('MegaMenu', 'MegaMenu', 'bestbug'),
				'menu_name' => esc_html__('MegaMenu', 'bestbug'),
				'name_admin_bar' => esc_html__('MegaMenu', 'bestbug'),
				'parent_item_colon' => esc_html__('Parent Menu:', 'bestbug'),
				'all_items' => esc_html__('MegaMenus', 'bestbug'),
				'add_new_item' => esc_html__('Add New MegaMenu', 'bestbug'),
				'add_new' => esc_html__('Add New', 'bestbug'),
				'new_item' => esc_html__('New MegaMenu', 'bestbug'),
				'edit_item' => esc_html__('Edit MegaMenu', 'bestbug'),
				'update_item' => esc_html__('Update MegaMenu', 'bestbug'),
				'view_item' => esc_html__('View MegaMenu', 'bestbug'),
				'search_items' => esc_html__('Search MegaMenu', 'bestbug'),
				'not_found' => esc_html__('Not found', 'bestbug'),
				'not_found_in_trash' => esc_html__('Not found in Trash', 'bestbug'),
			);
		$megamenu_args = array(
				'label' => esc_html__('MegaMenu', 'bestbug'),
				'description' => esc_html__('MegaMenu', 'bestbug'),
				'labels' => $labels,
				'supports' => array('title', 'editor', ),
				'hierarchical' => false,
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => 'hf_builder',
				'menu_position' => 21,
				'menu_icon' => 'dashicons-editor-kitchensink',
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'can_export' => true,
				'has_archive' => false,
				'exclude_from_search' => true,
				'publicly_queryable' => true,
				'rewrite' => false,
				'capability_type' => 'page',
		);
		register_post_type( 'hfb_megamenu', $megamenu_args );
	}

	/**
	 * Register the admin menu for Header Footer & Blocks builder.
	 *
	 * @since  1.0.0
	 * @since  1.0.1
	 *         Moved the menu under Appearance -> Header Footer & Blocks Builder
	 */
	public function register_admin_menu() {
		add_menu_page(
			__( 'Header / Footer Builder', 'header-footer-builder' ),
			__( 'Header / Footer Builder', 'header-footer-builder' ),
			'edit_pages',
			'hf_builder',
			array($this, 'hbbuilder_page'),
				'',
				15
		);
		/* add_submenu_page(
			'hf_builder',
			__( 'All Templates', 'header-footer-builder' ),
			__( 'All Templates', 'header-footer-builder' ),
			'edit_pages',
			'hf_builder',
			'edit.php?post_type=hfb-post'
		); */
		/* add_submenu_page(
			'hf_builder',
			__( 'Mega Menu', 'header-footer-builder' ),
			__( 'Mega Menu', 'header-footer-builder' ),
			'edit_pages',
			'edit.php?post_type=hfb_megamenu'
		); */
		add_submenu_page(
			'hf_builder',
			__( 'Settings', 'header-footer-builder' ),
			__( 'Settings', 'header-footer-builder' ),
			'manage_options',
			'hfb-settings',
			[ $this, 'hfb_settings_page' ]
		);
		
	}
	public function hbbuilder_page(){
		
	}
	public function hfb_settings_page() {
		echo '<h1 class="hfb-heading-inline">';
		esc_attr_e( 'Configuration', 'header-footer-builder' );
		echo '</h1>';

		?>
		<?php
		$hfb_radio_button = get_option( 'hfb_compatibility_option', '1' );
		?>
		<form action="options.php" method="post">
			<?php settings_fields( 'hfb-plugin-options' ); ?>
			<?php do_settings_sections( 'Settings' ); ?>
			<?php submit_button(); ?>
		</form></div>
		<?php
	}
	/**
	 * Register meta box(es).
	 */
	function ehf_register_metabox() {
		add_meta_box(
			'ehf-meta-box',
			__( 'Header Footer Settings', 'header-footer-builder' ),
			[
				$this,
				'efh_metabox_render',
			],
			'hfb-post',
			'normal',
			'high'
		);
	}

	/**
	 * Render Meta field.
	 *
	 * @param  POST $post Currennt post object which is being displayed.
	 */
	function efh_metabox_render( $post ) {
		$values            = get_post_custom( $post->ID );
		$template_type     = isset( $values['ehf_template_type'] ) ? esc_attr( $values['ehf_template_type'][0] ) : '';
		$display_on_canvas = isset( $values['display-on-canvas-template'] ) ? true : false;

		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'ehf_meta_nounce', 'ehf_meta_nounce' );
		?>
		<table class="hfb-options-table widefat">
			<tbody>
				<tr class="hfb-options-row type-of-template">
					<td class="hfb-options-row-heading">
						<label for="ehf_template_type"><?php _e( 'Type of Template', 'header-footer-builder' ); ?></label>
					</td>
					<td class="hfb-options-row-content">
						<select name="ehf_template_type" id="ehf_template_type">
							<option value="" <?php selected( $template_type, '' ); ?>><?php _e( 'Select Option', 'header-footer-builder' ); ?></option>
							<option value="type_header" <?php selected( $template_type, 'type_header' ); ?>><?php _e( 'Header', 'header-footer-builder' ); ?></option>
							<option value="type_footer" <?php selected( $template_type, 'type_footer' ); ?>><?php _e( 'Footer', 'header-footer-builder' ); ?></option>
						</select>
					</td>
				</tr>

				<?php $this->display_rules_tab(); ?>
				<tr class="hfb-options-row hfb-shortcode">
					<td class="hfb-options-row-heading">
						<label for="ehf_template_type"><?php _e( 'Shortcode', 'header-footer-builder' ); ?></label>
						<i class="hfb-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'header-footer-builder' ); ?>">
						</i>
					</td>
					<td class="hfb-options-row-content">
						<span class="hfb-shortcode-col-wrap">
							<input type="text" onfocus="this.select();" readonly="readonly" value="[hfb_template id='<?php echo esc_attr( $post->ID ); ?>']" class="hfb-large-text code">
						</span>
					</td>
				</tr>
				<tr class="hfb-options-row enable-for-canvas">
					<td class="hfb-options-row-heading">
						<label for="display-on-canvas-template">
							<?php _e( 'Enable Layout for Elementor Canvas Template?', 'header-footer-builder' ); ?>
						</label>
						<i class="hfb-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Enabling this option will display this layout on pages using Elementor Canvas Template.', 'header-footer-builder' ); ?>"></i>
					</td>
					<td class="hfb-options-row-content">
						<input type="checkbox" id="display-on-canvas-template" name="display-on-canvas-template" value="1" <?php checked( $display_on_canvas, true ); ?> />
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Markup for Display Rules Tabs.
	 *
	 * @since  1.0.0
	 */
	public function display_rules_tab() {
		// Load Target Rule assets.
		HFB_Conditions_Setting::get_instance()->admin_styles();

		$include_locations = get_post_meta( get_the_id(), 'ehf_target_include_locations', true );
		$exclude_locations = get_post_meta( get_the_id(), 'ehf_target_exclude_locations', true );
		$devices             = get_post_meta( get_the_id(), 'ehf_target_devices', true );
		$users             = get_post_meta( get_the_id(), 'ehf_target_user_roles', true );
		?>
		<tr class="hfb-target-rules-row hfb-options-row">
			<td class="hfb-target-rules-row-heading hfb-options-row-heading">
				<label><?php esc_html_e( 'Display On', 'header-footer-builder' ); ?></label>
				<i class="hfb-target-rules-heading-help dashicons dashicons-editor-help"
					title="<?php echo esc_attr__( 'Add locations for where this template should appear.', 'header-footer-builder' ); ?>"></i>
			</td>
			<td class="hfb-target-rules-row-content hfb-options-row-content">
				<?php
				HFB_Conditions_Setting::target_rule_settings_field(
					'hfb-target-rules-location',
					[
						'title'          => __( 'Display Rules', 'header-footer-builder' ),
						'value'          => '[{"type":"basic-global","specific":null}]',
						'tags'           => 'site,enable,target,pages',
						'rule_type'      => 'display',
						'add_rule_label' => __( 'Add Display Rule', 'header-footer-builder' ),
					],
					$include_locations
				);
				?>
			</td>
		</tr>
		<tr class="hfb-target-rules-row hfb-options-row">
			<td class="hfb-target-rules-row-heading hfb-options-row-heading">
				<label><?php esc_html_e( 'Do Not Display On', 'header-footer-builder' ); ?></label>
				<i class="hfb-target-rules-heading-help dashicons dashicons-editor-help"
					title="<?php echo esc_attr__( 'Add locations for where this template should not appear.', 'header-footer-builder' ); ?>"></i>
			</td>
			<td class="hfb-target-rules-row-content hfb-options-row-content">
				<?php
				HFB_Conditions_Setting::target_rule_settings_field(
					'hfb-target-rules-exclusion',
					[
						'title'          => __( 'Exclude On', 'header-footer-builder' ),
						'value'          => '[]',
						'tags'           => 'site,enable,target,pages',
						'add_rule_label' => __( 'Add Exclusion Rule', 'header-footer-builder' ),
						'rule_type'      => 'exclude',
					],
					$exclude_locations
				);
				?>
			</td>
		</tr>
		<tr class="hfb-target-rules-row hfb-options-row">
			<td class="hfb-target-rules-row-heading hfb-options-row-heading">
				<label><?php esc_html_e( 'Devices', 'header-footer-builder' ); ?></label>
				<i class="hfb-target-rules-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__( 'Display custom template based on user role.', 'header-footer-builder' ); ?>"></i>
			</td>
			<td class="hfb-target-rules-row-content hfb-options-row-content">
				<?php
				HFB_Conditions_Setting::target_devices_settings_field(
					'hfb-target-devices',
					[
						'title'          => __( 'Devices', 'header-footer-builder' ),
						'value'          => '[]',
						'tags'           => 'site,enable,target,pages',
						//'add_rule_label' => __( 'Add Device', 'header-footer-builder' ),
					],
					$devices 
				);
				?>
			</td>
		</tr>
		<tr class="hfb-target-rules-row hfb-options-row">
			<td class="hfb-target-rules-row-heading hfb-options-row-heading">
				<label><?php esc_html_e( 'User Roles', 'header-footer-builder' ); ?></label>
				<i class="hfb-target-rules-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__( 'Display custom template based on user role.', 'header-footer-builder' ); ?>"></i>
			</td>
			<td class="hfb-target-rules-row-content hfb-options-row-content">
				<?php
				HFB_Conditions_Setting::target_user_role_settings_field(
					'hfb-target-rules-users',
					[
						'title'          => __( 'Users', 'header-footer-builder' ),
						'value'          => '[]',
						'tags'           => 'site,enable,target,pages',
						'add_rule_label' => __( 'Add User Rule', 'header-footer-builder' ),
					],
					$users
				);
				?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save meta field.
	 *
	 * @param  POST $post_id Currennt post object which is being displayed.
	 *
	 * @return Void
	 */
	public function ehf_save_meta( $post_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST['ehf_meta_nounce'] ) || ! wp_verify_nonce( $_POST['ehf_meta_nounce'], 'ehf_meta_nounce' ) ) {
			return;
		}

		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$target_locations = HFB_Conditions_Setting::get_format_rule_value( $_POST, 'hfb-target-rules-location' );
		$target_exclusion = HFB_Conditions_Setting::get_format_rule_value( $_POST, 'hfb-target-rules-exclusion' );
		$target_users     = [];
		$target_devices     = [];

		if ( isset( $_POST['hfb-target-rules-users'] ) ) {
			$target_users = array_map( 'sanitize_text_field', $_POST['hfb-target-rules-users'] );
		}
		
		if ( isset( $_POST['hfb-target-devices'] ) ) {
			$target_devices = array_map( 'sanitize_text_field', $_POST['hfb-target-devices'] );
		}

		update_post_meta( $post_id, 'ehf_target_include_locations', $target_locations );
		update_post_meta( $post_id, 'ehf_target_exclude_locations', $target_exclusion );
		update_post_meta( $post_id, 'ehf_target_user_roles', $target_users );
		update_post_meta( $post_id, 'ehf_target_devices', $target_devices );

		if ( isset( $_POST['ehf_template_type'] ) ) {
			update_post_meta( $post_id, 'ehf_template_type', esc_attr( $_POST['ehf_template_type'] ) );
		}

		if ( isset( $_POST['display-on-canvas-template'] ) ) {
			update_post_meta( $post_id, 'display-on-canvas-template', esc_attr( $_POST['display-on-canvas-template'] ) );
		} else {
			delete_post_meta( $post_id, 'display-on-canvas-template' );
		}
	}

	/**
	 * Display notice when editing the header or footer when there is one more of similar layout is active on the site.
	 *
	 * @since 1.0.0
	 */
	public function location_notice() {
		global $pagenow;
		global $post;

		if ( 'post.php' != $pagenow || ! is_object( $post ) || 'hfb-post' != $post->post_type ) {
			return;
		}

		$template_type = get_post_meta( $post->ID, 'ehf_template_type', true );

		if ( '' !== $template_type ) {
			$templates = Header_Footer_Builder::get_template_id( $template_type );

			// Check if more than one template is selected for current template type.
			if ( is_array( $templates ) && isset( $templates[1] ) && $post->ID != $templates[0] ) {
				$post_title        = '<strong>' . get_the_title( $templates[0] ) . '</strong>';
				$template_location = '<strong>' . $this->template_location( $template_type ) . '</strong>';
				/* Translators: Post title, Template Location */
				$message = sprintf( __( 'Template %1$s is already assigned to the location %2$s', 'header-footer-builder' ), $post_title, $template_location );

				echo '<div class="error"><p>';
				echo $message;
				echo '</p></div>';
			}
		}
	}

	/**
	 * Convert the Template name to be added in the notice.
	 *
	 * @since  1.0.0
	 *
	 * @param  String $template_type Template type name.
	 *
	 * @return String $template_type Template type name.
	 */
	public function template_location( $template_type ) {
		$template_type = ucfirst( str_replace( 'type_', '', $template_type ) );

		return $template_type;
	}

	/**
	 * Don't display the elementor header footer & blocks templates on the frontend for non edit_posts capable users.
	 *
	 * @since  1.0.0
	 */
	public function block_template_frontend() {
		if ( is_singular( 'hfb-post' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}

	/**
	 * Single template function which will choose our template
	 *
	 * @since  1.0.1
	 *
	 * @param  String $single_template Single template.
	 */
	function load_canvas_template( $single_template ) {
		global $post;

		if ( 'hfb-post' == $post->post_type ) {
			$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

			if ( file_exists( $elementor_2_0_canvas ) ) {
				return $elementor_2_0_canvas;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
			}
		}

		return $single_template;
	}

	/**
	 * Set shortcode column for template list.
	 *
	 * @param array $columns template list columns.
	 */
	function set_shortcode_columns( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );

		$columns['shortcode'] = __( 'Shortcode', 'header-footer-builder' );
		$columns['date']      = $date_column;

		return $columns;
	}

	/**
	 * Display shortcode in template list column.
	 *
	 * @param array $column template list column.
	 * @param int   $post_id post id.
	 */
	function render_shortcode_column( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				ob_start();
				?>
				<span class="hfb-shortcode-col-wrap">
					<input type="text" onfocus="this.select();" readonly="readonly" value="[hfb_template id='<?php echo esc_attr( $post_id ); ?>']" class="hfb-large-text code">
				</span>

				<?php

				ob_get_contents();
				break;
		}
	}

}
