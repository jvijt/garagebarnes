<?php
/**
 * Force the existing /takeldienst/ WordPress page to render the custom Garage Barnes Takeldienst landing page.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('the_content', function($content) {
    if (is_admin() || !is_page('takeldienst') || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    if (shortcode_exists('garage_barnes_takeldienst')) {
        return do_shortcode('[garage_barnes_takeldienst]');
    }

    return $content;
}, 999);

add_filter('pre_get_document_title', function($title) {
    if (is_page('takeldienst')) {
        return 'Takeldienst Hamme 24/7 | Pechhulp & Depannage | Garage Barnes';
    }
    return $title;
}, 999);

add_action('wp_head', function() {
    if (!is_page('takeldienst')) return;
    echo '<meta name="description" content="Takeldienst Barnes in Hamme, 24/7 bereikbaar voor pechhulp, depannage, takeling en berging. Rechtstreeks voor particulieren en ook voor verzekeraars en bijstandsbedrijven. Bel +32 477 35 35 47.">';
}, 1);
