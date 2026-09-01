<?php
/**
 * Garage Barnes – OceanWP cleanup
 * Hides the OceanWP page title/header area site-wide.
 */
if (!defined('ABSPATH')) { exit; }

function gb_hide_oceanwp_page_header_sitewide() {
    if (is_admin()) { return; }
    ?>
    <style id="gb-hide-oceanwp-page-header">
      #page-header,
      .page-header,
      .page-header.background-image-page-header,
      .page-header.centered-page-header,
      .page-header.title-only,
      .background-image-page-header,
      .centered-page-header {
        display:none !important;
      }
    </style>
    <?php
}
add_action('wp_head', 'gb_hide_oceanwp_page_header_sitewide', 100);
