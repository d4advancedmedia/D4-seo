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
 * Builds the markup for sitemaps
 *
 * @param string $variables 
 *
 * @since 1.1
 * 
 * @return string $output
 */
	function render_xmlsitemap_urlset($variables = null) {

		$variables = explode('-', $variables);
		
		$items = apply_filters('d4seo_sitemap_items', array(), $variables );

		$output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$output .= "\n";
			foreach ($items as $item) {
				$output .= '<url>';
					$output .= "\n";
					foreach ($item as $key => $value) {
						$output .= "<{$key}>" . $value . "</{$key}>";
						$output .= "\n";
					}
				$output .= '</url>';
				$output .= "\n";
			}
		$output .= '</urlset>';

		return $output;

	}





/**
 * Builds the markup for sitemap index
 *
 * @since 1.1
 * 
 * @return string $output
 */
	function render_xmlsitemap_index() {

		$output = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$output .= "\n";

			$baseURL = trailingslashit(site_url());

			$sitemaps = apply_filters('d4seo_sitemaps', array() );
			if ( ! empty($sitemaps) && is_array($sitemaps) ) {
				foreach ($sitemaps as $key => $value) {
					$output .= '<sitemap>';
						$output .= "\n";
						$output .= '<loc>' . $baseURL . 'sitemap-' . $value['slug'] . '.xml' . '</loc>';
						$output .= "\n";
						$output .= '<lastmod>' . $value['lastmod'] . '</lastmod>';
						$output .= "\n";
					$output .= '</sitemap>';
					$output .= "\n";
				}
			}

		$output .= '</sitemapindex>';

		return $output;

	}
