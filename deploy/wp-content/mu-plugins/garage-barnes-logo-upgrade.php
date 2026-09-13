<?php
/**
 * Garage Barnes - use the new high-resolution logo files everywhere on the public site.
 */
if (!defined('ABSPATH')) { exit; }

function gb_new_logo_urls() {
    $uploads = wp_upload_dir();
    $base = !empty($uploads['baseurl']) ? trailingslashit($uploads['baseurl']) . '2026/09/' : trailingslashit(home_url('/wp-content/uploads/2026/09/'));

    return array(
        'garage_black' => $base . 'Barnes-Garage-Logo-Zwart.png',
        'garage_white' => $base . 'Barnes-Garage-Logo-Wit.png',
        'towing_black' => $base . 'Barnes-Takeldienst-Logo-Zwart.png',
        'towing_white' => $base . 'Barnes-Takeldienst-Logo-Wit.png',
    );
}

/**
 * Replace every legacy Barnes logo URL in rendered frontend HTML.
 * This also covers older shortcodes/MU plugins that still point to the original plugin assets.
 */
function gb_replace_legacy_logo_urls($html) {
    if (!is_string($html) || $html === '') { return $html; }

    $new = gb_new_logo_urls();
    $base = plugins_url('garage-barnes/assets/img/');

    $search = array(
        $base . 'garage-barnes-logo.png',
        $base . 'garage-barnes-logo-wit.png',
        $base . 'takeldienst-barnes-logo.png',
        $base . 'takeldienst-barnes-logo-wit.png',
        home_url('/wp-content/plugins/garage-barnes/assets/img/garage-barnes-logo.png'),
        home_url('/wp-content/plugins/garage-barnes/assets/img/garage-barnes-logo-wit.png'),
        home_url('/wp-content/plugins/garage-barnes/assets/img/takeldienst-barnes-logo.png'),
        home_url('/wp-content/plugins/garage-barnes/assets/img/takeldienst-barnes-logo-wit.png'),
    );

    $replace = array(
        $new['garage_black'],
        $new['garage_white'],
        $new['towing_black'],
        $new['towing_white'],
        $new['garage_black'],
        $new['garage_white'],
        $new['towing_black'],
        $new['towing_white'],
    );

    return str_replace($search, $replace, $html);
}

add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) { return; }
    ob_start('gb_replace_legacy_logo_urls');
}, 1);
