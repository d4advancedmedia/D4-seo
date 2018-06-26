<?php



/**
 * Detects whether the request is a sitemap or an index and renders appropriately, then echoes it
 *
 * @since 2000
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
			$variables = explode('-', $variables);
			$output .= render_xmlsitemap_urlset($variables);
		}

	echo $output;

}





/**
 * Builds the markup for sitemap index
 *
 * @since 2000
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
 * @param array $variables url query variables i.e. sitemap-$variables[0]-$variables[1]-$variables[2].xml
 *
 * @since 2000
 * 
 * @return string $output
 */
function render_xmlsitemap_urlset($variables) {

	$sitemap_items = get_xmlsitemap_items($variables);

	// Build render output
	if ( ! empty($sitemap_items) ) {

		$output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$output .= "\n";
			foreach ( $sitemap_items as $item_id => $item_values ) {
				$output .= '<url>';
					$output .= "\n";
					foreach ( $item_values as $tag => $value ) {
						$output .= "\t";
						$output .= "<{$tag}>" . $value . "</{$tag}>";
						$output .= "\n";
					}
				$output .= '</url>';
				$output .= "\n";
			}
		$output .= '</urlset>';

		return $output;
	
	}


}




/**
 * Get the sitemap items from the database using wp_query
 *
 * @param array $variables url query variables i.e. sitemap-$variables[0]-$variables[1]-$variables[2].xml
 *
 * @since 2000
 * 
 * @return array $sitemap_items
 */
function get_xmlsitemap_items($variables) {

	global $d4seo_sitemaps;
	$sitemap = $d4seo_sitemaps[$variables[0]];

	$sitemap_query_args = $sitemap['query_args'];
	$sitemap_query = new WP_Query( $sitemap_query_args );

	if ( $sitemap_query->have_posts() ) {

		$sitemap_items = array();

		// Build sitemap items
			$posts_frequency = $sitemap['changefreq'];
			$posts_priority  = $sitemap['priority'];

			while ( $sitemap_query->have_posts() ) {
				$sitemap_query->the_post();

				$item = array(
					'loc'        => get_the_permalink(),
					'lastmod'    => get_the_modified_date('c'),
					'changefreq' => $posts_frequency,
					'priority'   => $posts_priority,
				);


				/**
				 * Filters the sitemap item
				 *
				 * @since 2000
				 *
				 * @param array $item
				 * @param array $variables
				 */
				$item = apply_filters( 'd4seo_sitemap_items', $item, $variables );

				if ( ! empty($item) ) {
					$item_id = get_the_id();
					$sitemap_items[$item_id] = $item;
				}

			}

			return $sitemap_items;

	} wp_reset_postdata();

}




