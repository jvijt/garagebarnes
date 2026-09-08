<?php
/**
 * Garage Barnes - SEO-friendly vehicle slugs.
 * Format: /wagen/merk-model-bouwjaar/
 */
if (!defined('ABSPATH')) { exit; }

function gb_vehicle_seo_slug_value($post_id) {
    if (get_post_type($post_id) !== 'gb_vehicle') { return ''; }

    $makes = wp_get_post_terms($post_id, 'gb_vehicle_make');
    $models = wp_get_post_terms($post_id, 'gb_vehicle_model');
    $make = (!is_wp_error($makes) && !empty($makes)) ? $makes[0]->name : '';
    $model = (!is_wp_error($models) && !empty($models)) ? $models[0]->name : '';
    $year = trim((string) get_post_meta($post_id, 'gb_year', true));

    $parts = array_filter(array($make, $model, $year));
    return $parts ? sanitize_title(implode(' ', $parts)) : '';
}

function gb_vehicle_apply_seo_slug($post_id) {
    static $updating = false;
    if ($updating || wp_is_post_revision($post_id) || get_post_type($post_id) !== 'gb_vehicle') { return; }

    $post = get_post($post_id);
    if (!$post || $post->post_status === 'auto-draft') { return; }

    $slug = gb_vehicle_seo_slug_value($post_id);
    if (!$slug || $post->post_name === $slug) { return; }

    $updating = true;
    wp_update_post(array(
        'ID' => $post_id,
        'post_name' => $slug,
    ));
    $updating = false;
}
add_action('save_post_gb_vehicle', 'gb_vehicle_apply_seo_slug', 50);

/** Update existing vehicles once after deployment. */
function gb_vehicle_migrate_existing_seo_slugs() {
    if (get_option('gb_vehicle_seo_slugs_v1_done')) { return; }

    $ids = get_posts(array(
        'post_type' => 'gb_vehicle',
        'post_status' => array('publish', 'draft', 'pending', 'private'),
        'posts_per_page' => -1,
        'fields' => 'ids',
    ));

    foreach ($ids as $post_id) {
        gb_vehicle_apply_seo_slug($post_id);
    }

    update_option('gb_vehicle_seo_slugs_v1_done', 1, false);
}
add_action('init', 'gb_vehicle_migrate_existing_seo_slugs', 100);
