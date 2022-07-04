<?php
/*
Plugin Name: Sugar FAQs for WordPress
Plugin URI: http://pippinsplugins.com/sugar-faqs-wordpress-faqs-management
Description: Provides a robust FAQs management system for WordPress
Version: 1.2.3
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/

/************************************
global variables
************************************/

$sf_version = '1.2.3';

$sfBaseDir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));

$sf_options = get_option( 'sf_settings' );

$sf_load_scripts = false;

/************************************
includes
************************************/

include_once('includes/post-types.php');
include_once('includes/taxonomies.php');
include_once('includes/scripts.php');
include_once('includes/shortcodes.php');
include_once('includes/misc-functions.php');
include_once('includes/submission-form.php');
include_once( 'includes/widget-topics.php' );

if(is_admin()) {
	include_once('includes/settings.php');
	include_once('includes/metabox.php');
	include_once('includes/help.php');
}
