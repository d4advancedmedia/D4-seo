<?php

function wp_head_meta_keywords_d4seo() {

	$post_id = get_the_ID();

	$d4seo_keywords = get_post_meta( $post_id, 'd4seo_keywords', true);

	if ( ! empty($d4seo_keywords) ) {

		$keywords = apply_filters( 'd4seo_keywords', $d4seo_keywords );
		echo '<meta name="keywords" content="' . esc_attr($keywords) . '">';

	} 

} add_action('wp_head', 'wp_head_meta_keywords_d4seo', 1);