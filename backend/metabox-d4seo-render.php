<?php


/**
 * Register meta box
 *
 * @param post $post The post object
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
 */
function meta_box_d4seo( $post ){

	$post_types = array(
		'post',
		'page'
	);

	$post_types = apply_filters( 'd4seo_post_types', $post_types );

	add_meta_box(
		'food_meta_box',
		__( 'SEO Information', 'd4am' ),
		'metabox_render_d4seo',
		$post_types,
		'normal',
		'default'
	);

} add_action( 'add_meta_boxes', 'meta_box_d4seo' );


/**
 * Render meta box
 *
 * @param post $post The post object
 */
function metabox_render_d4seo( $post ){ ?>

	<?php wp_nonce_field( 'post_metabox_nonce_d4seo', 'meta_box_nonce_d4seo' ); ?>

	<?php $d4seo_title = get_post_meta( $post->ID, 'd4seo_title', true); ?>
	<label for="d4seo_title_field">Title</label>
	<input type="text" name="d4seo_title_field" value="<?php echo $d4seo_title; ?>">

	<?php $d4seo_description = get_post_meta( $post->ID, 'd4seo_description', true); ?>
	<label for="d4seo_description_field">Description</label>
	<textarea name="d4seo_description_field"><?php echo $d4seo_description; ?></textarea>

	<?php $d4seo_keywords = get_post_meta( $post->ID, 'd4seo_keywords', true); ?>
	<label for="d4seo_keywords_field">Keywords</label>
	<input type="text" name="d4seo_keywords_field" value="<?php echo $d4seo_keywords; ?>">	

<?php }


