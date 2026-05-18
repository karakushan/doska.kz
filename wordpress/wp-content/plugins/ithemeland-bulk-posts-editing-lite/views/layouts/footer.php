<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<input type="hidden" id="wpbe-last-modal-opened" value="">

<?php

include_once WPBEL_VIEWS_DIR . "bulk_new_posts/bulk_new_posts_form.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/bulk_edit_form.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form/filter_form.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modal/new_post_taxonomy.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modal/select_post.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modal/select_user.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modal/custom_field_files.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modal/post_taxonomy.php";
include_once WPBEL_VIEWS_DIR . "column_manager/main.php";
include_once WPBEL_VIEWS_DIR . "column_manager/edit_preset.php";
include_once WPBEL_VIEWS_DIR . "import_export/main.php";
include_once WPBEL_VIEWS_DIR . "meta_field/main.php";
include_once WPBEL_VIEWS_DIR . "settings/main.php";

include_once WPBEL_VIEWS_DIR . "modals/text_editor.php";
include_once WPBEL_VIEWS_DIR . "modals/numeric_calculator.php";
include_once WPBEL_VIEWS_DIR . "modals/duplicate_item.php";
include_once WPBEL_VIEWS_DIR . "modals/new_item.php";
include_once WPBEL_VIEWS_DIR . "modals/image.php";
include_once WPBEL_VIEWS_DIR . "modals/filter_profiles.php";
include_once WPBEL_VIEWS_DIR . "modals/column_profiles.php";
include_once WPBEL_VIEWS_DIR . "history/main.php";
do_action('wpbe_layout_footer');
