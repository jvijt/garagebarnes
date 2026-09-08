<?php
/**
 * Garage Barnes - mobile topbar content.
 */
if (!defined('ABSPATH')) { exit; }

add_action('wp_head', function () {
    ?>
    <style id="gb-mobile-topbar-css">
      @media(max-width:900px){
        .gb-topbar-inner{
          min-height:58px!important;
          flex-direction:column!important;
          align-items:flex-start!important;
          justify-content:center!important;
          gap:2px!important;
        }
        .gb-topbar-inner>div:first-child{
          font-size:0!important;
          line-height:1!important;
        }
        .gb-topbar-inner>div:first-child::after{
          content:"GARAGE & TAKELDIENST";
          display:block;
          color:#5dc01d;
          font-size:11px;
          line-height:1.2;
          font-weight:800;
          letter-spacing:.11em;
        }
        .gb-topbar-links{
          display:block!important;
          line-height:1.2;
        }
        .gb-topbar-links>a:first-child{
          display:inline-block;
          font-size:0!important;
          color:#fff!important;
          text-decoration:none!important;
        }
        .gb-topbar-links>a:first-child::after{
          content:"+32 57 05 57";
          font-size:14px;
          font-weight:400;
          letter-spacing:0;
          color:#fff;
        }
        .gb-topbar-towing{display:none!important}
      }
    </style>
    <?php
}, 130);
