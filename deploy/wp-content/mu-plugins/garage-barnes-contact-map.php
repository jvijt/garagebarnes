<?php
/**
 * Garage Barnes - full-width contact map directly below contact cards.
 */
if (!defined('ABSPATH')) { exit; }

add_action('wp_head', function () {
    if (!is_page('contact')) { return; }
    ?>
    <style id="gb-contact-map-fixed-css">
      .gb-contact-map-fixed{
        width:min(1180px,calc(100% - 40px));
        margin:34px auto 0;
        background:#eef0eb;
        overflow:hidden;
        border:1px solid #dfe3dc;
      }
      .gb-contact-map-fixed iframe{
        display:block;
        width:100%;
        height:460px;
        border:0;
      }
      @media(max-width:700px){
        .gb-contact-map-fixed{
          width:calc(100% - 28px);
          margin-top:24px;
        }
        .gb-contact-map-fixed iframe{height:360px}
      }
    </style>
    <?php
}, 120);

add_action('wp_footer', function () {
    if (!is_page('contact')) { return; }
    ?>
    <script id="gb-contact-map-fixed-js">
    (function(){
      var section=document.querySelector('.gb-contact-cards-section');
      if(!section || document.querySelector('.gb-contact-map-fixed')) return;

      var wrap=document.createElement('div');
      wrap.className='gb-contact-map-fixed';
      wrap.innerHTML='<iframe title="Garage Barnes op Google Maps" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Zonneke%204%2C%209220%20Hamme%2C%20Belgium&output=embed"></iframe>';
      section.insertAdjacentElement('afterend',wrap);
    })();
    </script>
    <?php
}, 120);
