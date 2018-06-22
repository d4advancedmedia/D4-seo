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
 * 
 * @return array $title
 */
	function filter_document_title_parts_cmg( $title ) {

		$post_id = get_the_ID();
		$d4seo_title = get_post_meta( $post_id, 'd4seo_title', true);
		if ( ! empty($d4seo_title) ) {

			$d4seo_title = apply_filters( 'd4seo_title', $d4seo_title );
			$d4seo_title = esc_attr($d4seo_title);
			$title['title'] = $d4seo_title;


			$d4seo_title_overwrite = get_post_meta( $post_id, 'd4seo_title_overwrite', true);
			$d4seo_title_overwrite = apply_filters( 'd4seo_title_overwrite', $d4seo_title_overwrite );
			if ($d4seo_title_overwrite == '1') {
				$title['page']     = apply_filters( 'd4seo_title_overwrite_page', '' );
				$title['tagline']  = apply_filters( 'd4seo_title_overwrite_tagline', '' );
				$title['site']     = apply_filters( 'd4seo_title_overwrite_site', '' );
			}

		} 


		/*
						if ( is_home()       ) : echo get_the_title( get_option( 'page_for_posts' ) );
					elseif ( is_post_type_archive() ) : post_type_archive_title();
					elseif ( is_day()        ) : printf( __( 'Day: %s', 'skivvy' ), get_the_date() );
					elseif ( is_month()      ) : printf( __( 'Month: %s', 'skivvy' ), get_the_date( 'F Y' ) );
					elseif ( is_year()       ) : printf( __( 'Year: %s', 'skivvy' ), get_the_date( 'Y' ) );
					elseif ( is_tax()        ) : $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name;
					elseif ( is_tag()        ) : single_tag_title();
					elseif ( is_category()   ) : single_cat_title();
					elseif ( is_author()     ) : printf( __( 'Posts by %s', 'skivvy' ), sprintf( '<span class="vcard"><a href="%1$s" rel="me">%2$s</a></span>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() ) );
					elseif ( is_archive()    ) : echo get_the_title( get_option( 'page_for_posts' ) );
					elseif ( is_search()     ) : _e( 'Search Results', 'skivvy' );
					elseif ( is_attachment() ) : the_title();
					elseif ( is_single()     ) : the_title();
					elseif ( is_front_page() ) : #the_title();
					elseif ( is_page()       ) : the_title();
					elseif ( is_404()        ) : _e( '404 | Page not found' , 'skivvy' );
					else                       : // Ninja Silence....
					endif;

		//*/

		return $title;

	} add_filter('document_title_parts', 'filter_document_title_parts_cmg', 15);
