<?php

$config  = array(
  'title' => sprintf( '%s Portfolio Options', PACZ_THEME_NAME ),
  'id' => 'pacz-metaboxes-tabs',
  'pages' => array(
    'portfolio'
  ),
  'callback' => '',
  'context' => 'normal',
  'priority' => 'core'
);
$options = array(
  array(
    "name" => esc_html__( "Gallery Images", "classiadspro" ),
    "subtitle" => esc_html__( "Add Images for the gallery post type", "classiadspro" ),
    "desc" => esc_html__( "You can re-arrange images by drag and drop as well as deleting images.", "classiadspro" ),
    "id" => "_gallery_images",
    "default" => '',
    "type" => "gallery"
  ),
  array(
    "name" => esc_html__( "Video URL", "classiadspro" ),
    "subtitle" => esc_html__( "URL to the video site to feed from.", "classiadspro" ),
    "id" => "_video_url",
    "type" => "text"
  ),

  array(
    "name" => esc_html__( "Upload MP3 File", "classiadspro" ),
    "desc" => esc_html__( "Upload MP3 your file or paste the full URL for external files. This file formate needed for Safari, Internet Explorer, Chrome. ", "classiadspro" ),
    "id" => "_mp3_file",
    "preview" => false,
    "default" => "",
    "type" => "upload"
  ),
  array(
    "name" => esc_html__( "Upload OGG File", "classiadspro" ),
    "desc" => esc_html__( "Upload OGG your file or paste the full URL for external files. This file formate needed for Firefox, Opera, Chrome. ", "classiadspro" ),
    "id" => "_ogg_file",
    "preview" => false,
    "default" => "",
    "type" => "upload"
  ),
  array(
    "name" => esc_html__( "Ajax Description", "classiadspro" ),
    "desc" => esc_html__( "You are allowed to use HTML tags as well as shortcodes.", "classiadspro" ),
    "subtitle" => esc_html__( "Short description for ajax content. This content will be shown if you have enabled ajax feature for your portfolio loop.", "classiadspro" ),
    "id" => "_portfolio_short_desc",
    "default" => '',
    "type" => "editor"
  ),
  array(
    "name" => esc_html__( "Show Featured Image in Single Post?", "classiadspro" ),
    "desc" => esc_html__( "Please note that this option will disable featured image, video player (when video post type chosen) and gallery slideshow (when gallery post type chosen).", "classiadspro" ),
    "id" => "_single_featured",
    "default" => "true",
    "type" => "toggle"
  ),
  array(
    "name" => esc_html__( "Custom URL", "classiadspro" ),
    "desc" => esc_html__( "If you may choose to change the permalink to a page, post or external URL. If left empty the single post permalink will be used instead.", "classiadspro" ),
    "subtitle" => esc_html__( "External link other than the single post.", "classiadspro" ),
    "id" => "_portfolio_permalink",
    "default" => "",
    "type" => "superlink"
  ),
);
new pacz_metaboxesGenerator( $config, $options );
