<?php


/**
 * Saves meta along side of posts
 *
 * @param int $post_id The post ID.
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
 */
	function metabox_save_d4seo( $post_id ){

		// verify taxonomies meta box nonce
			if ( ! isset( $_POST['meta_box_nonce_d4seo'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce_d4seo'], 'post_metabox_nonce_d4seo' ) ){
				return;
			}

		// return if autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
				return;
			}

		// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ){
				return;
			}


		// Save meta fields
			if ( isset( $_REQUEST['d4seo_title_field'] ) ) {
				if ( empty( $_REQUEST['d4seo_title_field'] ) ) {
					delete_post_meta( $post_id, 'd4seo_title', sanitize_text_field( $_POST['d4seo_title_field'] ) );
				} else {
					update_post_meta( $post_id, 'd4seo_title', sanitize_text_field( $_POST['d4seo_title_field'] ) );
				}
			}

			if ( isset( $_REQUEST['d4seo_title_overwrite_field'] ) ) {
				update_post_meta( $post_id, 'd4seo_title_overwrite', sanitize_text_field( $_POST['d4seo_title_overwrite_field'] ) );
			} else {
				delete_post_meta( $post_id, 'd4seo_title_overwrite', 1 );
			}
			
			if ( isset( $_REQUEST['d4seo_description_field'] ) ) {
				if ( empty( $_REQUEST['d4seo_description_field'] ) ) {
					delete_post_meta( $post_id, 'd4seo_description', sanitize_text_field( $_POST['d4seo_description_field'] ) );
				} else {
					update_post_meta( $post_id, 'd4seo_description', sanitize_text_field( $_POST['d4seo_description_field'] ) );
				}
			}

			if ( isset( $_REQUEST['d4seo_keywords_field'] ) ) {
				if ( empty( $_REQUEST['d4seo_keywords_field'] ) ) {
					delete_post_meta( $post_id, 'd4seo_keywords', sanitize_text_field( $_POST['d4seo_keywords_field'] ) );
				} else {
					update_post_meta( $post_id, 'd4seo_keywords', sanitize_text_field( $_POST['d4seo_keywords_field'] ) );
				}
			}

	} add_action( 'save_post', 'metabox_save_d4seo' );