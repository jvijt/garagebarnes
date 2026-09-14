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

      /* Desktop: keep the complete photo section visually level with the info card. */
      @media (min-width:981px) {
        body.single-gb_vehicle .gbvd-top{align-items:stretch!important}
        body.single-gb_vehicle .gbvd-gallery-wrap{
          height:100%;
          display:flex;
          flex-direction:column;
        }
        body.single-gb_vehicle .gbvd-main-image{
          aspect-ratio:auto!important;
          flex:1 1 auto;
          min-height:0;
          background:#fff!important;
          overflow:hidden;
        }
        body.single-gb_vehicle .gbvd-main-image img{
          width:100%!important;
          height:100%!important;
          object-fit:cover!important;
          object-position:center!important;
        }
        body.single-gb_vehicle .gbvd-thumbs{
          flex:0 0 82px;
          box-sizing:border-box;
          padding:8px 12px!important;
          align-items:center;
          background:#fff;
        }
        body.single-gb_vehicle .gbvd-thumb{
          width:82px!important;
          height:60px!important;
          flex-basis:82px!important;
        }
        body.single-gb_vehicle .gbvd-summary{
          position:static!important;
          height:auto;
        }
      }
    </style>
    <?php
}, 60);
