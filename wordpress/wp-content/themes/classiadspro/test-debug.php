<?php
/**
 * Test debug file for theme
 */

// Set breakpoint on this line
$theme_name = wp_get_theme()->get('Name');

// Set breakpoint on this line
$theme_version = wp_get_theme()->get('Version');

// Set breakpoint on this line
echo "Theme: " . $theme_name . " v" . $theme_version;

