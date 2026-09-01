<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Structured option checklist for Garage Barnes Vehicles.
 * Stores selected labels in the existing gb_options meta key (one per line)
 * so current frontend rendering remains compatible.
 */

function gbv_options_catalog() {
    return array(
        'Comfort & interieur' => array(
            'Airconditioning','Automatische airco','Automatische airco 2 zones','Automatische airco 3 zones','Automatische airco 4 zones',
            'Verwarmde voorzetels','Verwarmde achterzetels','Geventileerde voorzetels','Massagestoelen','Elektrisch verstelbare voorzetels',
            'Elektrisch verstelbare bestuurderszetel','Elektrisch verstelbare passagierszetel','Zetels met geheugenfunctie','Lendensteun','Sportzetels',
            'Lederen interieur','Halflederen interieur','Alcantara interieur','Stoffen interieur','Verwarmd stuurwiel','Lederen stuurwiel',
            'Multifunctioneel stuurwiel','Sportstuurwiel','Stuurwiel met schakelpaddles','Armsteun voor','Armsteun achter','Isofix',
            'Neerklapbare achterbank','Skiluik','Sfeerverlichting','Keyless entry','Keyless start','Startknop','Elektrische ramen voor',
            'Elektrische ramen achter','Elektrische achterklep','Handsfree achterklep','Panoramisch dak','Open dak','Schuifdak','Getinte ramen'
        ),
        'Multimedia & connectiviteit' => array(
            'Navigatiesysteem','Touchscreen','Digitaal instrumentenpaneel','Head-up display','Boordcomputer','Bluetooth','Apple CarPlay',
            'Android Auto','Draadloze Apple CarPlay','Draadloze Android Auto','Draadloos smartphone laden','USB-aansluiting','USB-C aansluiting',
            'AUX-aansluiting','DAB-radio','Radio','CD-speler','MP3','Spraakbediening','WiFi hotspot','Connected services',
            'Premium audiosysteem','Harman Kardon audiosysteem','Bose audiosysteem','Bang & Olufsen audiosysteem','JBL audiosysteem'
        ),
        'Rijhulpsystemen & veiligheid' => array(
            'Cruise control','Adaptieve cruise control','Snelheidsbegrenzer','Lane assist','Lane keeping assist','Dodehoekdetectie',
            'Verkeersbordherkenning','Vermoeidheidsdetectie','Front collision warning','Automatische noodrem','Parkeersensoren voor',
            'Parkeersensoren achter','Parkeersensoren voor en achter','Achteruitrijcamera','360° camera','Parkeerassistent','Automatisch inparkeren',
            'ABS','ESP','Tractiecontrole','Hill hold','Hill descent control','Bandenspanningscontrole','Airbag bestuurder','Airbag passagier',
            'Zij-airbags','Hoofdairbags','Knie-airbag','Botswaarschuwing achter','Cross traffic alert','Nachtzicht','Driver attention assist'
        ),
        'Verlichting & zicht' => array(
            'LED-koplampen','Full LED-koplampen','Matrix LED-koplampen','Laserlicht','Xenon koplampen','Bi-xenon koplampen',
            'LED-dagrijlichten','LED-achterlichten','Automatische verlichting','Grootlichtassistent','Adaptieve verlichting','Bochtverlichting',
            'Mistlichten voor','Regensensor','Lichtsensor','Automatisch dimmende binnenspiegel','Automatisch dimmende buitenspiegels',
            'Elektrisch verstelbare buitenspiegels','Elektrisch inklapbare buitenspiegels','Verwarmde buitenspiegels','Verwarmde voorruit',
            'Ruitensproeiers verwarmd'
        ),
        'Exterieur & praktisch' => array(
            'Lichtmetalen velgen','Sportvelgen','Trekhaak vast','Trekhaak afneembaar','Elektrische trekhaak','Dakrails','Dakdragers',
            'Sportpakket','M-sportpakket','AMG-pakket','S line pakket','R-Line pakket','GT Line pakket','Achterspoiler','Sportuitlaat',
            'Metaalkleur','Parelmoerlak','Tweekleurige carrosserie','Schuifdeuren','Elektrische schuifdeur links','Elektrische schuifdeur rechts',
            'Centrale vergrendeling','Afstandsbediening centrale vergrendeling','Alarmsysteem','Startonderbreker'
        ),
        'Techniek & rijgedrag' => array(
            'Automatische versnellingsbak','Dubbele koppeling','Vierwielaandrijving','Voorwielaandrijving','Achterwielaandrijving',
            'Adaptief onderstel','Sportonderstel','Luchtvering','Rijmodi','Sportmodus','Eco-modus','Start-stop systeem','Schakelpaddles',
            'Elektronisch sperdifferentieel','Sperdifferentieel','Stuurbekrachtiging','Progressieve stuurbekrachtiging'
        ),
        'Elektrisch & hybride' => array(
            'Warmtepomp','Snelladen DC','AC-laden 11 kW','AC-laden 22 kW','Laadkabel type 2','Laadkabel huishoudelijk stopcontact',
            'Vehicle-to-load (V2L)','Vehicle-to-grid (V2G)','Batterijvoorverwarming','Regeneratief remmen','One pedal driving','Laadtimer',
            'Plug-in hybride','Hybride aandrijving','Elektrische aandrijving'
        ),
        'Overige' => array(
            'Niet-rokerswagen','Onderhoudsboekje aanwezig','Volledige onderhoudshistoriek','Dealer onderhouden','Eerste eigenaar',
            'Belgische wagen','Car-Pass aanwezig','Reservewiel','Bandenreparatieset','Winterbanden inbegrepen','Zomerbanden inbegrepen',
            'Twee sleutels','Garantie','Pechbijstand'
        ),
    );
}

function gbv_options_add_box() {
    add_meta_box('gbv_options_checklist', 'Opties & uitrusting', 'gbv_options_render_box', 'gb_vehicle', 'normal', 'default');
}
add_action('add_meta_boxes', 'gbv_options_add_box', 30);

function gbv_options_render_box($post) {
    wp_nonce_field('gbv_options_save', 'gbv_options_nonce');
    $stored = array_filter(array_map('trim', preg_split('/\R/', (string) get_post_meta($post->ID, 'gb_options', true))));
    $stored_lookup = array_fill_keys($stored, true);
    $catalog = gbv_options_catalog();

    echo '<style>
    #gbv2_details .gbv2-wide:has(#gb_options){display:none!important}
    .gbv-options-toolbar{display:flex;gap:10px;align-items:center;margin:0 0 18px;flex-wrap:wrap}
    .gbv-options-groups{display:grid;grid-template-columns:repeat(3,minmax(220px,1fr));gap:16px}
    .gbv-option-group{border:1px solid #dcdcde;background:#fff;padding:16px;border-radius:4px}
    .gbv-option-group h3{margin:0 0 12px;font-size:14px;border-bottom:1px solid #eee;padding-bottom:9px}
    .gbv-option-group label{display:flex;gap:8px;align-items:flex-start;margin:7px 0;line-height:1.3}
    .gbv-option-group input{margin-top:2px}
    .gbv-option-custom{margin-top:18px;padding-top:16px;border-top:1px solid #dcdcde}
    .gbv-option-custom textarea{width:100%;min-height:80px}
    @media(max-width:1200px){.gbv-options-groups{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:780px){.gbv-options-groups{grid-template-columns:1fr}}
    </style>';

    echo '<div class="gbv-options-toolbar"><button type="button" class="button" id="gbv-options-select-all">Alles aanvinken</button><button type="button" class="button" id="gbv-options-clear">Alles uitvinken</button><span class="description">Vink de aanwezige opties aan. Deze gegevens kunnen later ook als filters gebruikt worden.</span></div>';
    echo '<div class="gbv-options-groups">';
    foreach ($catalog as $group => $options) {
        echo '<div class="gbv-option-group"><h3>' . esc_html($group) . '</h3>';
        foreach ($options as $option) {
            $checked = isset($stored_lookup[$option]) ? ' checked' : '';
            echo '<label><input type="checkbox" name="gbv_options[]" value="' . esc_attr($option) . '"' . $checked . '> <span>' . esc_html($option) . '</span></label>';
        }
        echo '</div>';
    }
    echo '</div>';

    $known = array();
    foreach ($catalog as $options) { foreach ($options as $option) { $known[$option] = true; } }
    $custom = array();
    foreach ($stored as $option) { if (!isset($known[$option])) { $custom[] = $option; } }
    echo '<div class="gbv-option-custom"><label for="gbv_options_custom"><strong>Andere / specifieke opties</strong></label><p class="description">Eén extra optie per regel.</p><textarea id="gbv_options_custom" name="gbv_options_custom">' . esc_textarea(implode("\n", $custom)) . '</textarea></div>';

    echo '<script>jQuery(function($){$("#gbv-options-select-all").on("click",function(){ $("input[name=\\\"gbv_options[]\\\"]").prop("checked",true); });$("#gbv-options-clear").on("click",function(){ $("input[name=\\\"gbv_options[]\\\"]").prop("checked",false); });});</script>';
}

function gbv_options_save($post_id) {
    if (!isset($_POST['gbv_options_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gbv_options_nonce'])), 'gbv_options_save')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (get_post_type($post_id) !== 'gb_vehicle' || !current_user_can('edit_post', $post_id)) { return; }

    $selected = array();
    if (isset($_POST['gbv_options']) && is_array($_POST['gbv_options'])) {
        foreach (wp_unslash($_POST['gbv_options']) as $option) {
            $option = sanitize_text_field($option);
            if ($option !== '') { $selected[$option] = $option; }
        }
    }
    $custom_raw = sanitize_textarea_field(wp_unslash($_POST['gbv_options_custom'] ?? ''));
    foreach (preg_split('/\R/', $custom_raw) as $option) {
        $option = trim($option);
        if ($option !== '') { $selected[$option] = $option; }
    }
    update_post_meta($post_id, 'gb_options', implode("\n", array_values($selected)));
}
add_action('save_post_gb_vehicle', 'gbv_options_save', 50);
