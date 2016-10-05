<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.optimizepress.com/
 * @since             1.0.0
 * @package           op-make-fix
 *
 * @wordpress-plugin
 * Plugin Name:       OptimizePress Make theme fix
 * Plugin URI:        http://www.optimizepress.com/
 * Description:       Fix for Tiny MCE in Live Editor
 * Version:           1.0.0
 * Author:            OptimizePress
 * Author URI:        http://www.optimizepress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       op-make-fix
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function opFixLiveEditorIssue(){
	$checkIfLEPage = get_post_meta( url_to_postid( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ), '_optimizepress_pagebuilder', true );

	$pageBuilder = false;
	if ( isset($_GET['page']) ) {
		$pageBuilder = ($_GET['page'] == 'optimizepress-page-builder' ) ? true : false;
	}
	$liveEditorAjaxInsert = false;
	if ( isset($_REQUEST['action']) ) {
		$liveEditorAjaxInsert = ($_REQUEST['action'] == 'optimizepress-live-editor-parse' ) ? true : false;
	}

	if ( ($checkIfLEPage == 'Y') || $pageBuilder || $liveEditorAjaxInsert ){
		//ADD CODE HERE

		global $wp_filter;
		foreach ($wp_filter['mce_external_plugins'][10] as $filterKey => $filterValue) {
			if (is_array($filterValue)) {
				if ( is_object( $filterValue['function'][0] ) ) {
					if (get_class($filterValue['function'][0]) == 'MAKE_Formatting_Manager') {
						remove_action('mce_external_plugins',array($filterValue['function'][0],'register_plugins'),10);
					}
				}
			}
		}
	}
}

add_action('init', 'opFixLiveEditorIssue',999999999999);