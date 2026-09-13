<?php
/**
 * Garage Barnes - uitgebreid werkgebied Takeldienst.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('do_shortcode_tag', function($output, $tag){
    if ($tag !== 'garage_barnes_takeldienst' || !is_page('takeldienst')) {
        return $output;
    }

    $old = 'Vanuit onze vestiging in <strong>9220 Hamme</strong> verzorgen we pechverhelping, depannage en takeldienst in de ruime regio, onder meer richting Moerzeke, Kastel, Dendermonde, Zele, Waasmunster, Lokeren, Buggenhout, Lebbeke, Temse en Sint-Niklaas. Zit je buiten deze plaatsen? Bel ons gerust: we bekijken meteen wat mogelijk is.';

    $new = 'Vanuit onze vestiging in <strong>9220 Hamme</strong> verzorgen we pechverhelping, depannage en takeldienst in de ruime regio. We zijn onder meer actief in en rond <strong>Hamme</strong> (Moerzeke en Kastel), <strong>Waasmunster</strong> (Sombeke), <strong>Zele</strong>, <strong>Temse</strong> (Elversele en Tielrode), <strong>Dendermonde</strong> (Grembergen, Sint-Gillis en Baasrode), <strong>Berlare</strong>, <strong>Lokeren</strong> (Moerbeke), <strong>Sint-Niklaas</strong> (Sinaai), <strong>Lebbeke</strong>, <strong>Buggenhout</strong>, <strong>Beveren</strong>, <strong>Stekene</strong>, <strong>Opwijk</strong> en <strong>Aalst</strong> (Gijzegem).<h2 class="gb-towing-area-callout">Onze diensten nodig buiten deze gemeenten?</h2><p class="gb-towing-area-callout-text">Bel gerust: we bekijken meteen wat mogelijk is.</p>';

    return str_replace($old, $new, $output);
}, 30, 2);

add_action('wp_head', function(){
    if (!is_page('takeldienst')) { return; }
    ?>
    <style id="gb-takeldienst-regio-css">
      .gb-towing-area-callout{margin:30px 0 8px!important;font-size:clamp(24px,3vw,34px)!important;line-height:1.15!important}
      .gb-towing-area-callout-text{margin:0!important}
    </style>
    <?php
}, 130);
