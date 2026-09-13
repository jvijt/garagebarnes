<?php
/**
 * Garage Barnes - Auto Service services grid refinements.
 */
if (!defined('ABSPATH')) { exit; }

add_action('wp_head', function () {
    if (!is_page('auto-service')) { return; }
    ?>
    <style id="gb-auto-service-grid-css">
      .gb-page .gb-service-list{
        grid-template-columns:repeat(2,minmax(0,1fr))!important;
        background:transparent!important;
        gap:1px!important;
      }
      .gb-page .gb-service-list>span{
        display:block;
        min-width:0;
        background:#fff!important;
        border:1px solid var(--gb-line);
        font-weight:700!important;
        line-height:1.45;
      }
      .gb-page .gb-service-list>span small{
        display:block;
        margin-top:8px;
        font-size:13px;
        line-height:1.55;
        font-weight:400!important;
        color:#666;
      }

      /* Eerste rij: herstellingen links, pechverhelping aan huis rechts. */
      .gb-page .gb-service-list>span:nth-child(2){order:1}
      .gb-page .gb-service-list>span:nth-child(8){order:2}

      /* Daarna 10 diensten: exact 6 items per kolom. */
      .gb-page .gb-service-list>span:nth-child(1){order:3}
      .gb-page .gb-service-list>span:nth-child(3){order:4}
      .gb-page .gb-service-list>span:nth-child(4){order:5}
      .gb-page .gb-service-list>span:nth-child(5){order:6}
      .gb-page .gb-service-list>span:nth-child(6){order:7}
      .gb-page .gb-service-list>span:nth-child(7){order:8}
      .gb-page .gb-service-list>span:nth-child(9){order:9}
      .gb-page .gb-service-list>span:nth-child(10){order:10}
      .gb-page .gb-service-list>span:nth-child(11){order:11}
      .gb-page .gb-service-list>span:nth-child(12){order:12}

      @media(max-width:700px){
        .gb-page .gb-service-list{grid-template-columns:1fr!important}
      }
    </style>
    <?php
}, 120);
