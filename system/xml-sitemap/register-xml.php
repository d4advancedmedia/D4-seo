<?php


/**
 * Register XML Sitemap - posts
 * 
 * adds 'sitemap-posts.xml' to the sitemap index 'sitemap.xml'
 * 
 * @param array $sitemaps List of registered sitemaps for the D4SEO sitemap.xml
 *
 * @since 1.1
 *
 * @return array $sitemaps
 */
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




/**
 * Register XML Sitemap items - posts
 * 
 * @param array $sitemaps List of registered sitemaps for the D4SEO sitemap-posts.xml, sitemap-posts-YYYY.xml, & sitemap-posts-YYYY-MM.xml
 *
 * @since 1.1
 *
 * @return array $sitemaps
 */
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
				$posts_frequency = apply_filters('d4seo_posts_frequency', 'monthly');

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




/**
 * Register XML Sitemap - pages
 * 
 * adds 'sitemap-pages.xml' to the sitemap index 'sitemap.xml'
 * 
 * @param array $sitemaps List of registered sitemaps for the D4SEO sitemap.xml
 *
 * @since 1.1
 *
 * @return array $sitemaps
 */
	function register_d4seo_pages_sitemap( $sitemaps ) {


		$args = array(
			'posts_per_page'   => 1,
			'post_type'        => array('page'),
		);

		$post_query = new WP_Query( $args );
		if ( $post_query->have_posts() ) {
			while ( $post_query->have_posts() ) {
				$post_query->the_post();
				$lastmod = get_the_modified_date('c');
			}
			$sitemaps['pages'] = array(
				'slug'    => 'pages',
				'lastmod' => $lastmod,
			);
		} wp_reset_postdata();


		return $sitemaps;

	} add_filter('d4seo_sitemaps', 'register_d4seo_pages_sitemap', 5);







/**
 * Register XML Sitemap items - pages
 * 
 * @param array $items feeds items for the D4SEO sitemap-pages.xml
 * 
 * @param array $variables The XML site
 *
 * @since 1.1
 *
 * @return array $items
 */
	function get_d4seo_sitemap_items_pages( $items, $variables ) {

		if ( $variables[0] == 'pages' ) {

			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => array('page'),
			);

			$post_query = new WP_Query( $args );
			if ( $post_query->have_posts() ) {

				$pages_frequency = apply_filters('d4seo_pages_frequency', 'monthly');
				$pages_priority  = apply_filters('d4seo_pages_priority', '0.7');

				$blog_id = get_option( 'page_for_posts' );

				while ( $post_query->have_posts() ) {
					$post_query->the_post();

					$post_id = get_the_id();

					$exclude = get_post_meta( $post_id, 'd4seo_sitemap_exclude', true);
					if ( empty($exclude) ) {
						$item_values = array(
							'loc'         => get_the_permalink(),
							'lastmod'     => get_the_modified_date('c'),
							'changefreq'  => $pages_frequency,
							'priority'    => $pages_priority,
						);
						$items[] = apply_filters( 'd4seo_page_item', $item_values, $post_id );
					}
				}

			} wp_reset_postdata();

		}

		return $items;

	} add_filter('d4seo_sitemap_items', 'get_d4seo_sitemap_items_pages', 10, 2 );





/**
 * Filter for d4seo_page_item. Add homepage defaults
 * 
 * @param array $item_values page values
 * 
 * @param array $post_id ID of the current post
 *
 * @since 1.1
 *
 * @return array $item_values
 */
	function filter_d4seo_page_item_home( $item_values, $post_id ) {

		$frontpage_id = get_option( 'page_on_front' );
		if ( $post_id == $frontpage_id ) {
			$item_values['changefreq'] = apply_filters('d4seo_home_frequency', 'monthly');
			$item_values['priority']   = apply_filters('d4seo_home_priority',  '1.0');
		} 
		return $item_values;

	} add_filter('d4seo_page_item', 'filter_d4seo_page_item_home', 10, 2);




/**
 * Filter for d4seo_page_item. Add blog archive page defaults
 * 
 * @param array $item_values page values
 * 
 * @param array $post_id ID of the current post
 *
 * @since 1.1
 *
 * @return array $item_values
 */
	function filter_d4seo_page_item_blog( $item_values, $post_id ) {

		$blog_id = get_option( 'page_for_posts' );
		if ( $post_id == $blog_id ) {
			$pages_priority  = apply_filters('d4seo_pages_priority', '0.7');
			$item_values['changefreq'] = apply_filters('d4seo_blog_frequency', 'weekly');
			$item_values['priority']   = apply_filters('d4seo_blog_priority',  $pages_priority);
		} 
		return $item_values;

	} add_filter('d4seo_page_item', 'filter_d4seo_page_item_blog', 10, 2);