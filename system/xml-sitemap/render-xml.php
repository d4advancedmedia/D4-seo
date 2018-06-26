<?php



/**
 * Detects whether the request is a sitemap or an index and renders appropriately, then echoes it
 *
 * @since 1.1
 */
	function render_xmlsitemap_d4seo() {

		global $wp_query;

		$variables = $wp_query->query_vars['xmlurl'];

		header('Content-Type: text/xml; charset=utf-8');
		$output = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= "\n";

			if ( empty($variables) ) {
				$output .= render_xmlsitemap_index();
			} else {
				$output .= render_xmlsitemap_urlset($variables);
			}

		echo $output;

	}





/**
 * Builds the markup for sitemap index
 *
 * @since 1.1
 * 
 * @return string $output
 */
	function render_xmlsitemap_index() {

		global $d4seo_sitemaps;
		$baseURL = trailingslashit(site_url());

		$output = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$output .= "\n";

			foreach ( $d4seo_sitemaps as $slug => $sitemap_args ) {

				$query_args = $sitemap_args['query_args'];
				$query_args['posts_per_page'] = 1;
				$query_args['orderby']        = 'modified';
				$query_args['order']          = 'DESC';

				$sitemap_query = new WP_Query( $query_args );
				if ( $sitemap_query->have_posts() ) {
					while ( $sitemap_query->have_posts() ) {
						$sitemap_query->the_post();

						$output .= '<sitemap>';
							$output .= "\n";
							$output .= '<loc>' . $baseURL . 'sitemap-' . $slug . '.xml' . '</loc>';
							$output .= "\n";
							$output .= '<lastmod>' . get_the_modified_date('c') . '</lastmod>';
							$output .= "\n";
						$output .= '</sitemap>';
						$output .= "\n";

					} wp_reset_postdata();
				}
			}

		$output .= '</sitemapindex>';

		return $output;

	}





/**
 * Builds the markup for sitemaps
 *
 * @param string $variables 
 *
 * @since 1.1
 * 
 * @return string $output
 */
	function render_xmlsitemap_urlset($variables) {

		global $d4seo_sitemaps;
		$variables = explode('-', $variables);
		$slug = $variables[0];
		$sitemap = $d4seo_sitemaps[$slug];

		$sitemap_query_args = $sitemap['query_args'];

		$sitemap_items = new WP_Query( $sitemap_query_args );

		if ( $sitemap_items->have_posts() ) {

			$posts_frequency = $sitemap['changefreq'];
			$posts_priority  = $sitemap['priority'];

			$output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
				$output .= "\n";

				while ( $sitemap_items->have_posts() ) {
					$sitemap_items->the_post();

					$output .= '<url>';
						$output .= "\n";
						$output .= '<loc>'        . get_the_permalink()        . '</loc>';
						$output .= "\n";
						$output .= '<lastmod>'    . get_the_modified_date('c') . '</lastmod>';
						$output .= "\n";
						$output .= '<changefreq>' . $posts_frequency . '</changefreq>';
						$output .= "\n";
						$output .= '<priority>'   . $posts_priority  . '</priority>';
						$output .= "\n";
					$output .= '</url>';
					$output .= "\n";

				}
			$output .= '</urlset>';

			return $output;

		} wp_reset_postdata();
		


	}

