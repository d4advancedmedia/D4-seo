<?php

/**
 * Echoes the meta description in the head tag.
 *
 * @since 1.0
 */
	function wp_head_meta_description_d4seo() {

		$post_id = get_the_ID();

		$d4seo_description = get_post_meta( $post_id, 'd4seo_description', true);

		if ( ! empty($d4seo_description) ) {

			$description = apply_filters( 'd4seo_description', $d4seo_description );

		} elseif ( has_excerpt() ) {

			$description = get_the_excerpt($post_id);
			
		} elseif ( is_singular() && ! is_front_page() ) {

			$text = get_post($post_id);
			$text = $text->post_content;
			#$text = strip_shortcodes( $text );
			$text = do_shortcode($text);
			$text = strip_tags($text);
			$text = trim($text);
			$text = substr( $text, 0, 170);

			$description = $text;

		} else {
			$description = get_bloginfo('description');
		}

		$description = esc_attr($description);
		$description = '<meta name="description" content="' . $description . '">';

		echo $description;

	} add_action('wp_head', 'wp_head_meta_description_d4seo', 1);