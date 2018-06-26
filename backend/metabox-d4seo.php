<?php

/**
 * Register meta box
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
 *
 * @param post $post The post object
 *
 * @since 1.0
 */
	function meta_box_d4seo( $post ){

		$post_types = array(
			'post',
			'page'
		);

		$post_types = apply_filters( 'd4seo_post_types', $post_types );

		add_meta_box(
			'food_meta_box',
			__( 'D4 SEO', 'd4am' ),
			'metabox_render_d4seo',
			$post_types,
			'side',
			'high'
		);

	} add_action( 'add_meta_boxes', 'meta_box_d4seo' );


/**
 * Render meta box
 *
 * @since 1.0
 * 
 * @param post $post The post object
 */
	function metabox_render_d4seo( $post ){ ?>

		<?php wp_nonce_field( 'post_metabox_nonce_d4seo', 'meta_box_nonce_d4seo' ); ?>


		<h3>SEO overrides</h3>

		<div class="d4seo_metabox_box">
			<?php $d4seo_title = get_post_meta( $post->ID, 'd4seo_title', true); ?>
			<label for="d4seo_title_field">Title</label>
			<input type="text" name="d4seo_title_field" value="<?php echo $d4seo_title; ?>">
		</div>

		<?php 
			$sep = apply_filters( 'document_title_separator', '-' );
			add_filter('document_title_parts', function( $title){
				unset($title['title']);
				return $title;
			});
			$title_example = " $sep " . wp_get_document_title();
		?>
		<div class="d4seo_metabox_box">
			<?php $d4seo_title_overwrite = get_post_meta( $post->ID, 'd4seo_title_overwrite', true); ?>
			<input type="checkbox" name="d4seo_title_overwrite_field" value="1" <?php checked($d4seo_title_overwrite, 1);?>>
			<label for="d4seo_title_overwrite_field">Remove "<?php echo $title_example; ?>" from title tag.</label>
		</div>

		<div class="d4seo_metabox_box">
			<?php $d4seo_description = get_post_meta( $post->ID, 'd4seo_description', true); ?>
			<label for="d4seo_description_field">Description</label>
			<textarea name="d4seo_description_field"><?php echo $d4seo_description; ?></textarea>
		</div>

		<div class="d4seo_metabox_box">
			<?php $d4seo_keywords = get_post_meta( $post->ID, 'd4seo_keywords', true); ?>
			<label for="d4seo_keywords_field">Keywords</label>
			<input type="text" name="d4seo_keywords_field" value="<?php echo $d4seo_keywords; ?>">	
		</div>


		<h3>XML Sitemap overrides</h3>

		<div class="d4seo_metabox_box">
			<?php $d4seo_sitemap_exclude = get_post_meta( $post->ID, 'd4seo_sitemap_exclude', true); ?>
			<input type="checkbox" name="d4seo_sitemap_exclude_field" value="1" <?php checked($d4seo_sitemap_exclude, 1);?>>
			<label for="d4seo_sitemap_exclude_field">Exclude from Sitemap?</label>
		</div>

		<div class="d4seo_metabox_box">
			<?php $d4seo_priority_override = get_post_meta( $post->ID, 'd4seo_priority_override', true); ?>
			<input type="checkbox" name="d4seo_priority_override_field" value="1" <?php checked($d4seo_priority_override, 1);?>>
			<label for="d4seo_priority_override_field">Override priority?</label>
			<div id="d4seo_metabox_priority">
				<?php $d4seo_priority = get_post_meta( $post->ID, 'd4seo_priority', true); ?>
				<?php if ( empty($d4seo_priority) ) {
					$d4seo_priority = '0.5';
				}  ?>
				<label for="d4seo_priority_field">Priority</label>
				<input type="range" name="d4seo_priority_field" min="0" max="1" step="0.1" value="<?php echo $d4seo_priority; ?>" oninput="update_input_val(this.value, 'd4seo_priority_value')" onchange="update_input_val(this.value, 'd4seo_priority_value')">
				<div id="d4seo_priority_value"><?php echo $d4seo_priority; ?></div>
			</div>
		</div>


		<div class="d4seo_metabox_box">
			<?php $d4seo_changefreq_override = get_post_meta( $post->ID, 'd4seo_changefreq_override', true); ?>
			<input type="checkbox" name="d4seo_changefreq_override_field" value="1" <?php checked($d4seo_changefreq_override, 1);?>>
			<label for="d4seo_changefreq_override_field">Override Change Freqency?</label>
			<div id="d4seo_metabox_changefreq">
				<?php $d4seo_changefreq = get_post_meta( $post->ID, 'd4seo_changefreq', true); ?>
				<?php if ( empty($d4seo_changefreq) ) {
					$d4seo_changefreq = 'monthly';
				}  ?>
				<label for="d4seo_changefreq_field">Change Frequency</label>
				<input type="text" name="d4seo_changefreq_field"value="<?php echo $d4seo_changefreq; ?>">
			</div>
		</div>


	<?php }




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

			if ( isset( $_REQUEST['d4seo_sitemap_exclude_field'] ) ) {
				update_post_meta( $post_id, 'd4seo_sitemap_exclude', sanitize_text_field( $_POST['d4seo_sitemap_exclude_field'] ) );
			} else {
				delete_post_meta( $post_id, 'd4seo_sitemap_exclude', 1 );
			}

			if ( isset( $_REQUEST['d4seo_priority_override_field'] ) ) {
				update_post_meta( $post_id, 'd4seo_priority_override', sanitize_text_field( $_POST['d4seo_priority_override_field'] ) );
				update_post_meta( $post_id, 'd4seo_priority', sanitize_text_field( $_POST['d4seo_priority_field'] ) );
			} else {
				delete_post_meta( $post_id, 'd4seo_priority_override', 1 );
				delete_post_meta( $post_id, 'd4seo_priority' );
			}

			if ( isset( $_REQUEST['d4seo_changefreq_override_field'] ) ) {
				update_post_meta( $post_id, 'd4seo_changefreq_override', sanitize_text_field( $_POST['d4seo_changefreq_override_field'] ) );
				update_post_meta( $post_id, 'd4seo_changefreq', sanitize_text_field( $_POST['d4seo_changefreq_field'] ) );
			} else {
				delete_post_meta( $post_id, 'd4seo_changefreq_override', 1 );
				delete_post_meta( $post_id, 'd4seo_changefreq' );
			}

	} add_action( 'save_post', 'metabox_save_d4seo' );