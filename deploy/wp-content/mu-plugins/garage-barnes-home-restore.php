<?php
/**
 * Restore the original Garage Barnes homepage styling that existed before the Takeldienst stylesheet changes.
 */
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    if (!is_front_page()) return;
    wp_enqueue_style(
        'garage-barnes-home-restore',
        plugin_dir_url(__FILE__) . 'garage-barnes-home-restore.css',
        array('garage-barnes-site'),
        filemtime(__DIR__ . '/garage-barnes-home-restore.css')
    );
}, 50);
