<?php



function register_d4seo_posts_sitemap( $sitemaps ) {

	$args = array(
		'posts_per_page'   => 1,
		'post_type'        => array('post'),
	);

	$post_query = new WP_Query( $args );
	if ( $post_query->have_posts() ) {
		while ( $post_query->have_posts() ) {
			$post_query->the_post();
			$lastmod = get_the_modified_date('c');
		}
		$sitemaps['posts'] = array(
			'slug'    => 'posts',
			'lastmod' => $lastmod,
		);
	} wp_reset_postdata();



	return $sitemaps;

} add_filter('d4seo_sitemaps', 'register_d4seo_posts_sitemap');




function register_d4seo_pages_sitemap( $sitemaps ) {

	$sitemaps['pages'] = array(
		'slug'    => 'pages',
		'lastmod' => '',
	);

	return $sitemaps;

}# add_filter('d4seo_sitemaps', 'register_d4seo_pages_sitemap');




function get_d4seo_sitemap_items_posts( $items, $variables ) {

	if ( $variables[0] == 'posts' ) {

		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => array('post'),
		);

		// if /sitemap-posts-xxxx.xml (year)
		if ( isset($variables[1]) && strlen($variables[1]) == 4 ) {
			$args['year'] = $variables[1];
		}


		// if /sitemap-posts-2018-xx.xml (month)
		if ( isset($variables[2]) ) {
			$args['monthnum'] = $variables[2];
		}

		$post_query = new WP_Query( $args );
		if ( $post_query->have_posts() ) {

			$posts_priority  = apply_filters('d4seo_posts_priority', '0.6');
			$posts_frequency = apply_filters('d4seo_posts_frequency', 'weekly');

			while ( $post_query->have_posts() ) {
				$post_query->the_post();
				$items[] = array(
					'loc'         => get_the_permalink(),
					'lastmod'     => get_the_modified_date('c'),
					'changefreq'  => $posts_frequency,
					'priority'    => $posts_priority,
				);
			}

		} wp_reset_postdata();

	}

	return $items;

} add_filter('d4seo_sitemap_items', 'get_d4seo_sitemap_items_posts', 10, 2 );

