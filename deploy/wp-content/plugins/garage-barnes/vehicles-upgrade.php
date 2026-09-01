<?php
if (!defined('ABSPATH')) { exit; }

function gb_vehicle_enqueue_frontend_assets() {
    $file = plugin_dir_path(__FILE__) . 'assets/css/garage-barnes-vehicles.css';
    $version = file_exists($file) ? (string) filemtime($file) : GB_PLUGIN_VERSION;
    wp_enqueue_style('garage-barnes-vehicles', GB_PLUGIN_URL . 'assets/css/garage-barnes-vehicles.css', array('garage-barnes-site'), $version);
}
add_action('wp_enqueue_scripts', 'gb_vehicle_enqueue_frontend_assets', 20);

function gb_vehicle_flush_rewrites_once() {
    if (get_option('gb_vehicle_rewrite_v1_done')) return;
    flush_rewrite_rules(false);
    update_option('gb_vehicle_rewrite_v1_done', 1, false);
}
add_action('init', 'gb_vehicle_flush_rewrites_once', 99);
