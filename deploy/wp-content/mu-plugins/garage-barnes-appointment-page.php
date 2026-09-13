<?php
/**
 * Garage Barnes - Maak afspraak page shell.
 * Keeps the main content editable in WordPress/Elementor for the future form.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $page = get_page_by_path('maak-afspraak', OBJECT, 'page');
    if (!$page) {
        wp_insert_post(array(
            'post_title'   => 'Maak afspraak',
            'post_name'    => 'maak-afspraak',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ));
    }
}, 50);

add_filter('the_content', function ($content) {
    if (is_admin() || !is_page('maak-afspraak') || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    $hero = '<section class="gb-page-hero gb-appointment-hero"><div class="gb-shell"><span class="gb-kicker">Garage Barnes · Hamme</span><h1>Maak een afspraak</h1><p>Vraag hier eenvoudig een afspraak aan voor onderhoud, herstelling, diagnose of een andere garagedienst.</p></div></section>';

    $body = '<section class="gb-appointment-content"><div class="gb-shell"><div class="gb-appointment-form-area">' . $content . '</div></div></section>';

    return '<main class="gb-page gb-appointment-page">' . $hero . $body . '</main>';
}, 999);

add_action('wp_head', function () {
    if (!is_page('maak-afspraak')) { return; }
    ?>
    <style id="gb-appointment-page-css">
      .gb-appointment-content{padding:80px 0 100px;background:#fff}
      .gb-appointment-form-area{max-width:900px;margin:0 auto}
      .gb-appointment-form-area:empty:before{content:'Hier komt het afspraakformulier.';display:block;padding:42px;border:1px dashed #cfd5ca;background:#f7f8f5;color:#777;text-align:center}
      @media(max-width:700px){.gb-appointment-content{padding:55px 0 70px}}
    </style>
    <?php
}, 120);
