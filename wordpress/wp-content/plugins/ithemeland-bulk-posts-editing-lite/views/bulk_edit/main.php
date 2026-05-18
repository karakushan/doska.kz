<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form/filter_form.php"; ?>
<div class="wpbe-wrap">
    <div class="wpbe-tab-middle-content wpbe-mt64">
        <?php include_once "top_navigation.php"; ?>
        <div class="wpbe-table" id="wpbe-items-table">
            <?php include_once WPBEL_VIEWS_DIR . "data_table/items.php"; ?>
        </div>
        <div class="external-scroll_wrapper">
            <div class="external-scroll_x">
                <div class="scroll-element_outer">
                    <div class="scroll-element_size"></div>
                    <div class="scroll-element_track"></div>
                    <div class="scroll-bar"></div>
                </div>
            </div>
        </div>
        <div class="wpbe-items-pagination wpbe-mt-10">
            <?php include 'pagination.php'; ?>
        </div>
        <div class="wpbe-items-count wpbe-mt-10">

        </div>
    </div>
</div>