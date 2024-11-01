<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordherd.io
 * @since      1.0
 *
 * @package    Content_Moover
 * @subpackage Content_Moover/admin/partials
 */

 $builder = '';
/**
 * Get total Page and Post counts
 */
$count_posts = wp_count_posts( $post_type = 'post' );
$count_pages = wp_count_posts( $post_type = 'page' );
/**
 * Get total count for posts using WPB
 */
$args = array('posts_per_page'=> -1, 'fields' => 'ids');
$posts = get_posts($args);
$count_builder_posts = 0;
foreach ($posts as $post_id) {
    if(get_post_meta($post_id, '_wpb_vc_js_status', true)) {
        $count_builder_posts++;
        $builder = 'wpbakery';
    }
    if(class_exists("\\Elementor\\Plugin")) {
        if(\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)) {
            $count_builder_posts++;
            $builder = 'elementor';
        }
    }
	if(class_exists("FLBuilderModel")) {
    		if(FLBuilderModel::is_builder_enabled()) {
        		$count_builder_posts++;
        		$builder = 'beaver';
    		}
	}
}
/**
 * Get total count for pages using WPB
 */
$page_ids = get_all_page_ids();
$count_builder_pages = 0;
foreach ($page_ids as $page_id) {
    if(get_post_meta($page_id, '_wpb_vc_js_status', true)) {
        $count_builder_pages++;
        $builder = 'wpbakery';
    }
    if(class_exists("\\Elementor\\Plugin")) {
        if(\Elementor\Plugin::$instance->db->is_built_with_elementor($page_id)) {
            $count_builder_pages++;
            $builder = 'elementor';
        }
    }
	if(class_exists("FLBuilderModel")) {
    		if(FLBuilderModel::is_builder_enabled()) {
        		$count_builder_pages++;
        		$builder = 'beaver';
    		}
	}
}
/**
 * Get current user 
 */
$current_user = wp_get_current_user();
/**
 * Build query string for iframe
 */
$url = 'https://gutenbergmoover.com/start-your-migraton/';
$url .= '?posts='.$count_posts->publish;
$url .= '&pages='.$count_pages->publish;
$url .= '&firstname='.esc_html( $current_user->user_firstname );
$url .= '&lastname='.esc_html( $current_user->user_lastname );
$url .= '&email='.esc_html( $current_user->user_email );
$url .= '&website='.get_home_url();
$url .= '&builder='.$builder;
$url .= '&builderposts='.$count_builder_posts;
$url .= '&builderpages='.$count_builder_pages;
?>

<div id="wordherd-iframe-container">
    <iframe id="wordherd-iframe" src="<?php echo $url; ?>">
</div>