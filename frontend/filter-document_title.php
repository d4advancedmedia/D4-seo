<?php

/**
 * Set the title separator to "|" (pipe)
 *
 * @since 1.0
 *
 * @return string '|' 
 */
	add_filter( 'document_title_separator', function(){
		return '|';
	}, 15 );






/**
 * Filter the title with D4 SEO overwrites
 *
 * @param title $title The title array being filtered
 *
 * @since 1.0
 * @since 2001 Limited the title overwrite to singulars only
 * 
 * @return array $title
 */
	function filter_document_title_parts_cmg( $title ) {

		if ( is_singular() ) {

			$post_id = get_the_ID();
			$d4seo_title = get_post_meta( $post_id, 'd4seo_title', true);
			if ( ! empty($d4seo_title) ) {
				/**
				 * Filters the d4seo_title 
				 *
				 * @since  1.0
				 *
				 * @param string $d4seo_title
				 */
				$d4seo_title = apply_filters( 'd4seo_title', $d4seo_title );
				$d4seo_title = esc_attr($d4seo_title);
				$title['title'] = $d4seo_title;


				$d4seo_title_overwrite = get_post_meta( $post_id, 'd4seo_title_overwrite', true);
				/**
				 * Filters the overwrite title
				 *
				 * @since 1.0
				 *
				 * @param string $d4seo_title_overwrite
				 */
				$d4seo_title_overwrite = apply_filters( 'd4seo_title_overwrite', $d4seo_title_overwrite );
				if ($d4seo_title_overwrite == '1') {
					/**
					 * Filters the page variable
					 *
					 * @since 1.0
					 *
					 * @param string $blank
					 */
					$title['page']     = apply_filters( 'd4seo_title_overwrite_page', '' );
					/**
					 * Filters the tagline variable
					 *
					 * @since 1.0
					 *
					 * @param string $blank
					 */
					$title['tagline']  = apply_filters( 'd4seo_title_overwrite_tagline', '' );
					/**
					 * Filters the site variable
					 *
					 * @since 1.0
					 *
					 * @param string $blank
					 */
					$title['site']     = apply_filters( 'd4seo_title_overwrite_site', '' );
				}

			} 
		}

		return $title;

	} add_filter('document_title_parts', 'filter_document_title_parts_cmg', 15);
