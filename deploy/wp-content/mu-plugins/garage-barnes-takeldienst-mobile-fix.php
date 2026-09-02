<?php
/**
 * Garage Barnes – Takeldienst mobile overflow fix.
 * Prevents the landing page from inheriting OceanWP's boxed content width
 * and removes horizontal scrolling on narrow screens.
 */
if (!defined('ABSPATH')) { exit; }

function gb_takeldienst_mobile_overflow_fix() {
    if (!is_page('takeldienst')) { return; }
    ?>
    <style id="gb-takeldienst-mobile-overflow-fix">
        .gb-towing-page {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
            overflow-x: hidden !important;
        }

        .gb-towing-page section,
        .gb-towing-page .gb-shell,
        .gb-towing-page .gb-towing-partners-grid,
        .gb-towing-page .gb-towing-partners-grid > div {
            max-width: 100%;
            min-width: 0;
        }

        @media (max-width: 700px) {
            .gb-towing-page .gb-shell {
                width: calc(100% - 28px) !important;
                max-width: calc(100% - 28px) !important;
            }

            .gb-towing-page .gb-towing-partners-grid {
                width: 100%;
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .gb-towing-page .gb-towing-partners-grid > div {
                width: 100%;
                padding: 38px 24px;
            }

            .gb-towing-page .gb-towing-partners a,
            .gb-towing-page .gb-towing-phone-card a {
                max-width: 100%;
                overflow-wrap: anywhere;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'gb_takeldienst_mobile_overflow_fix', 999);
