<?php


/**
 * echoes `Sitemap: http://www.siteurl.com/sitemap.xml` to the robots.txt
 *
 * @since 1.1
 *
 */
	add_action('do_robots', function() {

		$sitemap_index = trailingslashit(site_url()) . 'sitemap.xml';
		$sitemap_index = apply_filters('d4seo_sitemapindex_url', $sitemap_index);
		echo "\n" . 'Sitemap: ' . $sitemap_index . "\n";

	}, 100, 0);

