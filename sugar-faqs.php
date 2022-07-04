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

$sf_options = sf_get_options();

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

/**
 * @return array
 */
function sf_get_options() {

	$defaults = array(
		'style'               => 'default',
		'width'               => '',
		'icon'                => '0',
		'single_open'         => '1',
		'email_notifications' => '0',
		'order'               => 'title',
		'direction'           => 'ASC',
		'css'                 => '',
	);

	$sf_options = get_option( 'sf_settings', $defaults );

	if ( ! is_array( $sf_options ) ) {

		return $defaults;
	}

	return wp_parse_args( $sf_options, $defaults );
}
