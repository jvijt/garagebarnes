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
          min-height:44px!important;
          flex-direction:row!important;
          align-items:center!important;
          justify-content:space-between!important;
          gap:12px!important;
        }
        .gb-topbar-inner>div:first-child{
          font-size:0!important;
          line-height:1!important;
          flex:0 1 auto;
        }
        .gb-topbar-inner>div:first-child::after{
          content:"GARAGE & TAKELDIENST";
          display:block;
          color:#5dc01d;
          font-size:11px;
          line-height:1.2;
          font-weight:800;
          letter-spacing:.11em;
          white-space:nowrap;
        }
        .gb-topbar-links{
          display:block!important;
          line-height:1.2;
          margin-left:auto;
          flex:0 0 auto;
        }
        .gb-topbar-links>a:first-child{
          display:inline-block;
          font-size:0!important;
          color:#fff!important;
          text-decoration:none!important;
          white-space:nowrap;
        }
        .gb-topbar-links>a:first-child::after{
          content:"+32 52 57 05 57";
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
