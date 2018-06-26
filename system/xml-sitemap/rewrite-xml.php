<?php

/**
 * Add rewrites for 'sitemap.xml' & 'sitemap-xxxx.xml' urls. As well as xmlurl query variable
 * 
 * the 'add_rewrite_tag' is used to pass the 'xmlurl' variable to load in sub-sitemaps.
 *
 * @since 2000
 *
 * @return void
 */
	function rewrite_rules_xmlsitemap_d4seo() {
		add_rewrite_rule('sitemap(-+([a-zA-Z0-9_-]+))?\.xml$', 'index.php?xmlsitemap=1&xmlurl=$matches[2]', 'top');
		add_rewrite_tag('%xmlurl%', '([^&]+)');
	} add_action('init', 'rewrite_rules_xmlsitemap_d4seo');




/**
 * Add rewrites for 'sitemap.xml' & 'sitemap-xxxx.xml' urls.
 *
 * @since 2000
 *
 * @return void
 */
	function rewrite_xmlsitemap_d4seo( $rewrites ) {

		$rewrites['sitemap(-+([a-zA-Z0-9_-]+))?\.xml$'] = 'index.php?xmlsitemap=1&xmlurl=$matches[2]';
		#$rewrites['sitemap(-+([a-zA-Z0-9_-]+))?\.xml\.gz$'] = 'index.php?xmlsitemap=1params=$matches[2];zip=true';
		#$rewrites[] = 'sitemap(-+([a-zA-Z0-9_-]+))?\.html$' => 'index.php?xmlsitemap=1params=$matches[2];html=true';
		#$rewrites[] = 'sitemap(-+([a-zA-Z0-9_-]+))?\.html.gz$' => 'index.php?xmlsitemap=1params=$matches[2];html=true;zip=true';
		return $rewrites;

	} add_filter('rewrite_rules_array', 'rewrite_xmlsitemap_d4seo');




/**
 * Add query var - 'xmlsitemap'
 *
 * @param array $query_vars list of registered Query Vars
 * 
 * @since 2000
 *
 * @return $query_vars
 */
	function queryvar_xmlsitemap_d4seo($query_vars) {

		$query_vars[] = 'xmlsitemap';
		return $query_vars;

	} add_filter('query_vars', 'queryvar_xmlsitemap_d4seo');




/**
 * Loads in the xml sitemap templates upon virtual page request.
 * 
 * @since 2000
 *
 * @return void
 */
	function template_redirect_xmlsitemap_d4seo() {

		$xmlsitemap_var = intval( get_query_var( 'xmlsitemap' ) );
		if ( $xmlsitemap_var ) {
			render_xmlsitemap_d4seo();
			die;
		}

	} add_filter('template_redirect', 'template_redirect_xmlsitemap_d4seo');
