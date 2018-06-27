<?php
/*
Plugin Name: D4 SEO
Plugin URI: https://d4am.com/
Description: Dead simple SEO tool for expertly overriding the built in markup. Warning, not for the faint of heart, you need to know what you're doing to use this plugin
Version: 2001
Author: D4 Adv. Media
*/
include('frontend/index.php');
include('backend/index.php');
include('system/index.php');


/**
 * Functions to add upon plugin activation
 * 
 * @since 2000
 */
register_activation_hook(__FILE__, function() {
	rewrite_rules_xmlsitemap_d4seo();
	flush_rewrite_rules();
});


/**
 * Functions to add upon plugin activation
 * 
 * @since 2000
 */
register_deactivation_hook(__FILE__, function(){
	flush_rewrite_rules();
});
