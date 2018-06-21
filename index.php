<?php
/*
Plugin Name: D4 SEO
Plugin URI: https://d4am.com/
Description: 
Version: 1.0
Author: D4 Adv. Media
*/
include('frontend/index.php');
include('backend/index.php');
include('system/index.php');


register_activation_hook(__FILE__, function() {
	flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function(){
	flush_rewrite_rules();
});