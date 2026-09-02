<?php
/**
 * Garage Barnes – remove theme spacing between the custom header and homepage hero.
 */
if (!defined('ABSPATH')) { exit; }

function gb_home_gap_fix() {
    if (is_admin() || !is_front_page()) { return; }
    ?>
    <style id="gb-home-gap-fix">
      body.home #content-wrap,
      body.home #primary,
      body.home #main,
      body.home .content-area,
      body.home .site-main,
      body.home article,
      body.home .entry-content {
        margin-top: 0 !important;
        padding-top: 0 !important;
      }

      body.home .gb-home,
      body.home .gb-split-hero {
        margin-top: 0 !important;
      }

      @media (max-width: 900px) {
        body.home #content-wrap,
        body.home #primary,
        body.home #main,
        body.home .content-area,
        body.home .site-main,
        body.home article,
        body.home .entry-content {
          margin-top: 0 !important;
          padding-top: 0 !important;
        }
      }
    </style>
    <?php
}
add_action('wp_head', 'gb_home_gap_fix', 110);
