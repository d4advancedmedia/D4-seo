<?php


// Adds a warning box to the settings page Uses the same style box as the WordPress Update "update-nag"
	function admin_notice_d4seo(){

		global $current_screen;

		$discourage_search_engines = get_option('blog_public');
		if ( isset($discourage_search_engines) && $discourage_search_engines == 0 ){
			$output  = '<div id="admin-settings-warning-box">';
				$output .= '<strong>Warning</strong> -';
				$output .= ' The website is not visible to search engines.';
				$output .= ' Please <a href="' . admin_url('options-reading.php') . '">fix it</a>!';
			$output .= '</div>';

			echo $output;
		}

	} add_action('admin_notices', 'admin_notice_d4seo');