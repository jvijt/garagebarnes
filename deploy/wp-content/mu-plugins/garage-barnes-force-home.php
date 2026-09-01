<?php
/**
 * Force the WordPress front page/Home page to render the custom Garage Barnes homepage.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('the_content', function($content) {
    if (is_admin() || !is_front_page() || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    if (shortcode_exists('garage_barnes_home')) {
        return do_shortcode('[garage_barnes_home]');
    }

    return $content;
}, 999);
