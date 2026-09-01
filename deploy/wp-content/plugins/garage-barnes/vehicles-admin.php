<?php
if (!defined('ABSPATH')) { exit; }
require_once plugin_dir_path(__FILE__) . 'vehicles-options.php';

/**
 * Garage Barnes Vehicles admin UX fixes.
 * Keep vehicle editing out of Gutenberg and show the structured form directly.
 */

function gbv_admin_disable_block_editor($use_block_editor, $post_type) {
    if ($post_type === 'gb_vehicle') {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'gbv_admin_disable_block_editor', 20, 2);

function gbv_admin_remove_generic_editor_supports() {
    if (!post_type_exists('gb_vehicle')) { return; }
    remove_post_type_support('gb_vehicle', 'title');
    remove_post_type_support('gb_vehicle', 'editor');
}
add_action('init', 'gbv_admin_remove_generic_editor_supports', 30);

function gbv_admin_screen_styles() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'gb_vehicle') { return; }
    echo '<style>
      #post-body-content{margin-bottom:12px}
      #poststuff #post-body.columns-2{margin-right:300px}
      #gbv2_details .inside,#gbv2_gallery .inside,#gbv_options_checklist .inside{padding:18px}
      #gbv2_details h2.hndle,#gbv_options_checklist h2.hndle{font-size:16px}
      .postbox-container .postbox{border-radius:4px}
      @media(max-width:1000px){#poststuff #post-body.columns-2{margin-right:0}}
    </style>';
}
add_action('admin_head-post.php', 'gbv_admin_screen_styles');
add_action('admin_head-post-new.php', 'gbv_admin_screen_styles');