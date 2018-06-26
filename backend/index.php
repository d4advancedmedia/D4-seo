<?php

include 'metabox-d4seo.php';
include 'admin_notice-d4seo.php';

function enqueue_admin_d4seo( $hook ) {

	wp_register_style( 'd4seo', plugins_url( '/d4seo.css' , __FILE__ ), false, null, 'screen' );
	wp_enqueue_style( 'd4seo' );
	wp_register_script( 'd4seo', plugins_url( '/d4seo.js' , __FILE__ ), false, null);
	wp_enqueue_script( 'd4seo' );

}
add_action( 'admin_enqueue_scripts', 'enqueue_admin_d4seo' );
add_action( 'login_enqueue_scripts', 'enqueue_admin_d4seo' );