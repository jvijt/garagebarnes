<?php
/**
 * Garage Barnes - expanded partner sidebar on Auto Service page.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('do_shortcode_tag', function($output, $tag, $attr, $m){
    if ($tag !== 'garage_barnes_auto_service' || !is_page('auto-service')) { return $output; }

    $network = GB_PLUGIN_URL . 'assets/img/123-autoservice-logo.png';
    $eurol = 'https://garagebarnes.be/wp-content/uploads/2026/09/Logo-Eurol-Service-Point.png';
    $xten = 'https://garagebarnes.be/wp-content/uploads/2026/09/logo-X-ten-additieven.png';

    $old = '<aside class="gb-side-card"><img class="gb-page-network-logo" src="'.esc_url($network).'" alt="1,2,3 AutoService"><h3>Persoonlijke garageservice</h3><p>Een lokaal aanspreekpunt met technische kennis, snelle service en transparante communicatie.</p><a href="tel:+32477353547">+32 477 35 35 47</a></aside>';

    $new = '<aside class="gb-side-card gb-auto-partners">'
         . '<div class="gb-auto-partner gb-auto-partner-123">'
         . '<a class="gb-auto-partner-logo-link" href="https://www.123autoservice.be/nl/" target="_blank" rel="noopener noreferrer" aria-label="Bezoek de website van 1,2,3 AutoService">'
         . '<img class="gb-page-network-logo" src="'.esc_url($network).'" alt="1,2,3 AutoService">'
         . '</a>'
         . '<h3>Persoonlijke garageservice</h3>'
         . '<p>Een lokaal aanspreekpunt met technische kennis, snelle service en transparante communicatie.</p>'
         . '<a href="tel:+32477353547">+32 477 35 35 47</a>'
         . '</div>'
         . '<div class="gb-auto-partner">'
         . '<img class="gb-auto-partner-logo gb-auto-partner-logo-eurol" src="'.esc_url($eurol).'" alt="Eurol Service Point">'
         . '<h3>Eurol Service Point</h3>'
         . '</div>'
         . '<div class="gb-auto-partner gb-auto-partner-last">'
         . '<img class="gb-auto-partner-logo gb-auto-partner-logo-xten" src="'.esc_url($xten).'" alt="X-ten additieven">'
         . '<h3>Verdeler X-ten additieven</h3>'
         . '</div>'
         . '</aside>';

    return str_replace($old, $new, $output);
}, 20, 4);

add_action('wp_head', function(){
    if (!is_page('auto-service')) { return; }
    ?>
    <style id="gb-auto-service-partners-css">
      .gb-auto-partners{padding:0!important;overflow:hidden}
      .gb-auto-partner{padding:30px 28px}
      .gb-auto-partner+.gb-auto-partner{border-top:1px solid #dfe3dc}
      .gb-auto-partner-123{padding-top:28px}
      .gb-auto-partner-logo-link{display:block;text-decoration:none!important}
      .gb-auto-partner-logo-link:hover{opacity:.88}
      .gb-auto-partner .gb-page-network-logo{display:block;max-width:210px;width:100%;height:auto;margin:0 0 24px}
      .gb-auto-partner-logo{display:block;max-width:210px;max-height:105px;width:auto;height:auto;object-fit:contain;margin:0 0 22px}
      .gb-auto-partner-logo-eurol{max-height:92px}
      .gb-auto-partner-logo-xten{max-height:90px}
      .gb-auto-partner h3{margin:0 0 12px;font-size:22px;line-height:1.2}
      .gb-auto-partner-last h3{margin-bottom:0}
      @media(max-width:700px){
        .gb-auto-partner{padding:26px 24px}
        .gb-auto-partner .gb-page-network-logo,.gb-auto-partner-logo{max-width:190px}
      }
    </style>
    <?php
}, 140);
