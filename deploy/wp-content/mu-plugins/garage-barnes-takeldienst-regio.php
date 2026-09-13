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

    $new = 'Vanuit onze vestiging in <strong>9220 Hamme</strong> verzorgen we pechverhelping, depannage en takeldienst in de ruime regio. We zijn onder meer actief in en rond <strong>Hamme</strong> (Moerzeke en Kastel), <strong>Waasmunster</strong> (Sombeke), <strong>Zele</strong>, <strong>Temse</strong> (Elversele en Tielrode), <strong>Dendermonde</strong> (Baasrode), <strong>Berlare</strong>, <strong>Lokeren</strong> (Moerbeke), <strong>Sint-Niklaas</strong> (Sinaai), <strong>Lebbeke</strong>, <strong>Buggenhout</strong>, <strong>Beveren</strong>, <strong>Stekene</strong>, <strong>Opwijk</strong> en <strong>Aalst</strong> (Gijzegem). Zit je buiten deze plaatsen? Bel ons gerust: we bekijken meteen wat mogelijk is.';

    return str_replace($old, $new, $output);
}, 30, 2);
