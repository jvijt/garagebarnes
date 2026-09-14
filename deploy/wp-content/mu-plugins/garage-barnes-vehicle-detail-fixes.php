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

      /* Desktop: fixed gallery dimensions, independent of the selected image ratio. */
      @media (min-width:981px) {
        body.single-gb_vehicle .gbvd-top{align-items:start!important}
        body.single-gb_vehicle .gbvd-gallery-wrap{
          display:flex;
          flex-direction:column;
          height:auto;
          min-height:0;
        }
        body.single-gb_vehicle .gbvd-main-image{
          aspect-ratio:auto!important;
          height:var(--gbvd-gallery-photo-height,520px)!important;
          min-height:0!important;
          flex:0 0 var(--gbvd-gallery-photo-height,520px)!important;
          background:#fff!important;
          overflow:hidden!important;
        }
        body.single-gb_vehicle .gbvd-main-image img{
          display:block!important;
          width:100%!important;
          height:100%!important;
          min-height:0!important;
          max-height:none!important;
          object-fit:cover!important;
          object-position:50% 50%!important;
        }
        body.single-gb_vehicle .gbvd-thumbs{
          flex:0 0 82px;
          height:82px;
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
        }
      }
    </style>
    <script id="gb-vehicle-gallery-height-fix">
    document.addEventListener('DOMContentLoaded',function(){
      if(!window.matchMedia('(min-width:981px)').matches)return;
      var top=document.querySelector('.gbvd-top');
      var summary=document.querySelector('.gbvd-summary');
      var gallery=document.querySelector('.gbvd-gallery-wrap');
      var thumbs=gallery?gallery.querySelector('.gbvd-thumbs'):null;
      if(!top||!summary||!gallery)return;
      function lockGalleryHeight(){
        var summaryHeight=Math.round(summary.getBoundingClientRect().height);
        var thumbsHeight=thumbs?Math.round(thumbs.getBoundingClientRect().height):0;
        var photoHeight=Math.max(300,summaryHeight-thumbsHeight);
        gallery.style.setProperty('--gbvd-gallery-photo-height',photoHeight+'px');
      }
      lockGalleryHeight();
      window.addEventListener('load',lockGalleryHeight,{once:true});
      window.addEventListener('resize',lockGalleryHeight);
    });
    </script>
    <?php
}, 60);
