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
        margin:34px auto 24px;
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
      .gb-contact-admin-info{
        width:min(1180px,calc(100% - 40px));
        box-sizing:border-box;
        margin:0 auto 64px;
        padding:28px 32px;
        border:1px solid #dfe3dc;
        background:#fff;
      }
      .gb-contact-admin-info h3{
        margin:0 0 12px;
        font-size:20px;
        line-height:1.25;
        color:#252525;
      }
      .gb-contact-admin-info p{
        margin:0;
        color:#555;
        line-height:1.7;
      }
      @media(max-width:700px){
        .gb-contact-map-fixed{
          width:calc(100% - 28px);
          margin:24px auto 18px;
        }
        .gb-contact-map-fixed iframe{height:360px}
        .gb-contact-admin-info{
          width:calc(100% - 28px);
          margin:0 auto 44px;
          padding:24px 22px;
        }
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

      var info=document.createElement('div');
      info.className='gb-contact-admin-info';
      info.innerHTML='<h3>Administratieve informatie</h3><p>Garage Barnes BV - Zonneke 4 - 9220 HAMME - BTW BE 0726.909.090<br>Bestuurder: Peter Barnes</p>';
      wrap.insertAdjacentElement('afterend',info);
    })();
    </script>
    <?php
}, 120);
