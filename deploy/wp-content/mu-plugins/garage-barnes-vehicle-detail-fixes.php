<?php
/**
 * Garage Barnes - small fixes for vehicle detail page.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('the_content', function ($content) {
    if (!is_singular('gb_vehicle')) { return $content; }
    return str_replace('Garage Barnes Vehicles', 'Tweedehandsvoertuig', $content);
}, 100);

add_action('wp_head', function () {
    if (!is_singular('gb_vehicle')) { return; }
    ?>
    <style id="gb-vehicle-detail-fixes-css">
      body.single-gb_vehicle .thumbnail,
      body.single-gb_vehicle .post-thumbnail,
      body.single-gb_vehicle .blog-post-media,
      body.single-gb_vehicle .entry-media,
      body.single-gb_vehicle .post-image,
      body.single-gb_vehicle .featured-image,
      body.single-gb_vehicle .single-post-header,
      body.single-gb_vehicle .page-header-image,
      body.single-gb_vehicle .wp-post-image-wrapper {
        display:none!important;
      }
      body.single-gb_vehicle .gbvd-main-image,
      body.single-gb_vehicle .gbvd-main-image img,
      body.single-gb_vehicle .gbvd-thumb,
      body.single-gb_vehicle .gbvd-thumb img {
        display:block!important;
      }
    </style>
    <?php
}, 60);
