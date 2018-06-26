<?php

/**
 * Register default XML Sitemaps
 * 
 * adds 'sitemap-posts.xml' to the sitemap index 'sitemap.xml'
 * 
 * @param array $sitemaps List of registered sitemaps for the D4SEO sitemap.xml
 *
 * @since 1.1
 *
 * @return array $sitemaps
 */
function register_d4seo_default_sitemaps() {

	register_d4sitemap('posts',array(
		'priority'   => '0.6',
		'changefreq' => 'monthly',
		'query_args' => array(
			'post_type' => array('post'),
		)
	));

	register_d4sitemap('pages',array(
		'priority'   => '0.7',
		'changefreq' => 'monthly',
		'query_args' => array(
			'post_type' => array('page'),
		)
	));

} add_action( 'init', 'register_d4seo_default_sitemaps' );



function filter_d4seo_sitemap_items_pages ($item, $variables) {

	if (  $variables[0] == 'pages' ) {

		$item_id = get_the_id();

		$frontpage_id = get_option( 'page_on_front' ); 
		if ( $item_id == $frontpage_id ) { 
			$item['changefreq'] = apply_filters('d4seo_home_frequency', 'monthly'); 
			$item['priority']   = apply_filters('d4seo_home_priority',  '1.0'); 
		}

		$blog_id = get_option( 'page_for_posts' ); 
		if ( $item_id == $blog_id ) {
			$item['changefreq'] = apply_filters('d4seo_blog_frequency', 'weekly');
		}  

	}

	return $item;
 
} add_filter( 'd4seo_sitemap_items', 'filter_d4seo_sitemap_items_pages', 10, 2);



/**
 * registers a site map to the $d4seo_sitemap object
 * 
 * The $d4seo_sitemap is used to render the index and post type site maps.
 *
 * @param string $sitemap slug for the unique sitemap, also part of "sitemap-$sitemap.xml" schema
 *
 * @since 1.1
 * 
 * @global array $d4seo_sitemapes
 * 
 * @return array $d4seo_sitemaps
 */
	function register_d4sitemap( $sitemap, $args = array() ) {

		global $d4seo_sitemaps;

		if ( ! is_array( $d4seo_sitemaps ) ) {
			$d4seo_sitemaps = array();
		}

		$sitemap = sanitize_key( $sitemap );

		// Set defaults & compare to args
			$default_args = array(
				'priority'   => '0.5',
				'changefreq' => 'monthly',
				'query_args' => array(),
			);
			$default_args = apply_filters('d4sitemap_args', $default_args);

			$args = wp_parse_args( $args, $default_args );

			if ( ! isset($args['query_args']['posts_per_page']) ) {
				$args['query_args']['posts_per_page'] = -1;
			}

		$d4seo_sitemaps[ $sitemap ] = $args;

	}



/**
 * unregisters a sitemap
 * 
 * removes a registered sitemap from the $d4seo_sitemap object
 *
 * @param string $sitemap slug for the unique sitemap, also part of "sitemap-$sitemap.xml" schema
 *
 * @since 1.1
 * 
 * @return array $d4seo_sitemaps
 */
	function unregister_d4sitemap( $sitemap ) {

		global $d4seo_sitemaps;

		$sitemap = sanitize_key( $sitemap );

		if ( ! in_array( $sitemap, $d4seo_sitemaps ) ) {
			return; // nothing to do. I don't wanna make an error for this.
		}

		unset( $d4seo_sitemaps[ $sitemap ] );

	}


