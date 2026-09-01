<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Garage Barnes Vehicles v2
 * Safe, structured vehicle inventory module.
 */

function gbv2_register_content() {
    register_post_type('gb_vehicle', array(
        'labels' => array(
            'name' => 'Garage Barnes Vehicles',
            'singular_name' => 'Wagen',
            'menu_name' => 'Barnes Vehicles',
            'add_new' => 'Nieuwe wagen',
            'add_new_item' => 'Nieuwe wagen toevoegen',
            'edit_item' => 'Wagen bewerken',
            'view_item' => 'Wagen bekijken',
            'search_items' => 'Wagens zoeken',
            'not_found' => 'Geen wagens gevonden',
        ),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-car',
        'menu_position' => 22,
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'wagen', 'with_front' => false),
        'has_archive' => false,
    ));

    $taxonomies = array(
        'gb_vehicle_make' => array('Merk', 'Merken'),
        'gb_vehicle_model' => array('Model', 'Modellen'),
        'gb_vehicle_fuel' => array('Brandstof', 'Brandstoffen'),
        'gb_vehicle_transmission' => array('Transmissie', 'Transmissies'),
        'gb_vehicle_status' => array('Status', 'Statussen'),
    );

    foreach ($taxonomies as $taxonomy => $label_pair) {
        register_taxonomy($taxonomy, 'gb_vehicle', array(
            'labels' => array('singular_name' => $label_pair[0], 'name' => $label_pair[1]),
            'public' => false,
            'show_ui' => false,
            'show_in_rest' => true,
            'hierarchical' => false,
            'rewrite' => false,
        ));
    }
}
add_action('init', 'gbv2_register_content', 5);

function gbv2_seed_terms() {
    if (get_option('gb_vehicle_terms_seeded_v2')) { return; }

    $makes = array('Abarth','Alfa Romeo','Alpine','Audi','BMW','BYD','Citroën','Cupra','Dacia','DS Automobiles','Fiat','Ford','Honda','Hyundai','Jaguar','Jeep','Kia','Land Rover','Lexus','Mazda','Mercedes-Benz','MG','MINI','Mitsubishi','Nissan','Opel','Peugeot','Polestar','Porsche','Renault','SEAT','Škoda','Smart','Subaru','Suzuki','Tesla','Toyota','Volkswagen','Volvo');
    $fuels = array('Benzine','Diesel','Hybride','Plug-in hybride','Elektrisch','LPG','CNG');
    $transmissions = array('Manueel','Automaat','Halfautomaat');
    $statuses = array('Beschikbaar','Gereserveerd','Verkocht');

    $groups = array(
        'gb_vehicle_make' => $makes,
        'gb_vehicle_fuel' => $fuels,
        'gb_vehicle_transmission' => $transmissions,
        'gb_vehicle_status' => $statuses,
    );

    foreach ($groups as $taxonomy => $terms) {
        foreach ($terms as $term) {
            if (!term_exists($term, $taxonomy)) {
                wp_insert_term($term, $taxonomy);
            }
        }
    }

    update_option('gb_vehicle_terms_seeded_v2', 1, false);
}
add_action('init', 'gbv2_seed_terms', 20);

function gbv2_admin_assets() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'gb_vehicle') { return; }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'gbv2_admin_assets');

function gbv2_term_name($post_id, $taxonomy) {
    $terms = wp_get_post_terms($post_id, $taxonomy);
    if (is_wp_error($terms) || empty($terms)) { return ''; }
    return $terms[0]->name;
}

function gbv2_all_terms($taxonomy) {
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    return is_wp_error($terms) ? array() : $terms;
}

function gbv2_add_meta_boxes() {
    add_meta_box('gbv2_details', 'Voertuiggegevens', 'gbv2_details_box', 'gb_vehicle', 'normal', 'high');
    add_meta_box('gbv2_gallery', 'Fotogalerij', 'gbv2_gallery_box', 'gb_vehicle', 'normal', 'default');
}
add_action('add_meta_boxes', 'gbv2_add_meta_boxes');

function gbv2_details_box($post) {
    wp_nonce_field('gbv2_save_vehicle', 'gbv2_nonce');

    $make = gbv2_term_name($post->ID, 'gb_vehicle_make');
    $model = gbv2_term_name($post->ID, 'gb_vehicle_model');
    $fuel = gbv2_term_name($post->ID, 'gb_vehicle_fuel');
    $transmission = gbv2_term_name($post->ID, 'gb_vehicle_transmission');
    $status = gbv2_term_name($post->ID, 'gb_vehicle_status');
    if (!$status) { $status = 'Beschikbaar'; }

    $makes = gbv2_all_terms('gb_vehicle_make');
    $models = gbv2_all_terms('gb_vehicle_model');
    $fuels = gbv2_all_terms('gb_vehicle_fuel');
    $transmissions = gbv2_all_terms('gb_vehicle_transmission');
    $statuses = gbv2_all_terms('gb_vehicle_status');

    $model_data = array();
    foreach ($models as $term) {
        $model_data[] = array(
            'name' => $term->name,
            'make' => (string) get_term_meta($term->term_id, 'gb_vehicle_make_slug', true),
        );
    }

    echo '<style>.gbv2-grid{display:grid;grid-template-columns:repeat(3,minmax(180px,1fr));gap:16px}.gbv2-field label{display:block;font-weight:700;margin-bottom:6px}.gbv2-field input,.gbv2-field select,.gbv2-field textarea{width:100%}.gbv2-wide{grid-column:1/-1}.gbv2-note{color:#646970;margin:6px 0 0}.gbv2-options{min-height:150px}@media(max-width:1100px){.gbv2-grid{grid-template-columns:repeat(2,1fr)}}</style>';
    echo '<div class="gbv2-grid">';

    echo '<div class="gbv2-field"><label for="gb_make">Merk</label><input id="gb_make" name="gb_make" list="gb_make_list" value="' . esc_attr($make) . '" autocomplete="off" placeholder="Begin te typen…"><datalist id="gb_make_list">';
    foreach ($makes as $term) { echo '<option value="' . esc_attr($term->name) . '"></option>'; }
    echo '</datalist></div>';

    echo '<div class="gbv2-field"><label for="gb_model">Model / type</label><input id="gb_model" name="gb_model" list="gb_model_list" value="' . esc_attr($model) . '" autocomplete="off" placeholder="Begin te typen…"><datalist id="gb_model_list"></datalist><p class="gbv2-note">Bestaande modellen van het gekozen merk verschijnen automatisch.</p></div>';

    $fields = array(
        'gb_variant' => array('Uitvoering / versie', 'text'),
        'gb_stock_ref' => array('Interne referentie / stocknummer', 'text'),
        'gb_first_registration' => array('Eerste inschrijving', 'date'),
        'gb_year' => array('Bouwjaar', 'number'),
        'gb_mileage' => array('Kilometerstand', 'number'),
        'gb_price' => array('Verkoopprijs (€)', 'number'),
        'gb_power_kw' => array('Vermogen (kW)', 'number'),
        'gb_power_hp' => array('Vermogen (pk)', 'number'),
        'gb_displacement' => array('Cilinderinhoud (cc)', 'number'),
        'gb_color' => array('Kleur', 'text'),
        'gb_co2' => array('CO₂ (g/km)', 'number'),
        'gb_doors' => array('Aantal deuren', 'number'),
        'gb_seats' => array('Aantal zitplaatsen', 'number'),
        'gb_warranty' => array('Garantie', 'text'),
    );

    echo '<div class="gbv2-field"><label for="gb_fuel">Brandstof</label><select id="gb_fuel" name="gb_fuel"><option value="">— Kies —</option>';
    foreach ($fuels as $term) { echo '<option value="' . esc_attr($term->name) . '" ' . selected($fuel, $term->name, false) . '>' . esc_html($term->name) . '</option>'; }
    echo '</select></div>';

    echo '<div class="gbv2-field"><label for="gb_transmission">Transmissie</label><select id="gb_transmission" name="gb_transmission"><option value="">— Kies —</option>';
    foreach ($transmissions as $term) { echo '<option value="' . esc_attr($term->name) . '" ' . selected($transmission, $term->name, false) . '>' . esc_html($term->name) . '</option>'; }
    echo '</select></div>';

    echo '<div class="gbv2-field"><label for="gb_status">Status</label><select id="gb_status" name="gb_status">';
    foreach ($statuses as $term) { echo '<option value="' . esc_attr($term->name) . '" ' . selected($status, $term->name, false) . '>' . esc_html($term->name) . '</option>'; }
    echo '</select></div>';

    foreach ($fields as $key => $cfg) {
        $step = ($key === 'gb_price') ? ' step="0.01"' : '';
        echo '<div class="gbv2-field"><label for="' . esc_attr($key) . '">' . esc_html($cfg[0]) . '</label><input type="' . esc_attr($cfg[1]) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '"' . $step . '></div>';
    }

    $vat = get_post_meta($post->ID, 'gb_vat', true);
    echo '<div class="gbv2-field"><label for="gb_vat">BTW-status</label><select id="gb_vat" name="gb_vat"><option value="marge" ' . selected($vat, 'marge', false) . '>Margewagen / geen BTW aftrekbaar</option><option value="btw" ' . selected($vat, 'btw', false) . '>BTW aftrekbaar</option></select></div>';

    echo '<div class="gbv2-field gbv2-wide"><label for="gb_options">Opties</label><textarea class="gbv2-options" id="gb_options" name="gb_options" placeholder="Eén optie per regel">' . esc_textarea(get_post_meta($post->ID, 'gb_options', true)) . '</textarea><p class="gbv2-note">Gebruik één optie per regel.</p></div>';
    echo '</div>';

    echo '<script>(function(){const models=' . wp_json_encode($model_data) . ';const make=document.getElementById("gb_make");const list=document.getElementById("gb_model_list");function slugify(s){return (s||"").toLowerCase().normalize("NFD").replace(/[\\u0300-\\u036f]/g,"").replace(/[^a-z0-9]+/g,"-").replace(/^-|-$/g,"");}function fill(){const slug=slugify(make.value);list.innerHTML="";models.filter(function(x){return !slug||!x.make||x.make===slug;}).forEach(function(x){const o=document.createElement("option");o.value=x.name;list.appendChild(o);});}make.addEventListener("input",fill);fill();})();</script>';
}

function gbv2_gallery_box($post) {
    $ids = array_filter(array_map('absint', explode(',', (string) get_post_meta($post->ID, 'gb_gallery_ids', true))));
    echo '<p><strong>Hoofdfoto:</strong> gebruik rechts het WordPress-vak <em>Uitgelichte afbeelding</em>.</p>';
    echo '<input type="hidden" id="gb_gallery_ids" name="gb_gallery_ids" value="' . esc_attr(implode(',', $ids)) . '">';
    echo '<div id="gb_gallery_preview" style="display:flex;gap:10px;flex-wrap:wrap;margin:12px 0">';
    foreach ($ids as $id) { echo wp_get_attachment_image($id, 'thumbnail', false, array('style' => 'width:100px;height:75px;object-fit:cover')); }
    echo '</div><button type="button" class="button button-secondary" id="gb_gallery_choose">Foto\'s kiezen / wijzigen</button> <button type="button" class="button" id="gb_gallery_clear">Galerij leegmaken</button>';
    echo '<script>jQuery(function($){let frame;$("#gb_gallery_choose").on("click",function(e){e.preventDefault();if(frame){frame.open();return;}frame=wp.media({title:"Selecteer voertuigfoto’s",button:{text:"Gebruik deze foto’s"},multiple:true});frame.on("select",function(){const a=frame.state().get("selection").toJSON();$("#gb_gallery_ids").val(a.map(x=>x.id).join(","));$("#gb_gallery_preview").html(a.map(x=>"<img src=\\\""+(x.sizes&&x.sizes.thumbnail?x.sizes.thumbnail.url:x.url)+"\\\" style=\\\"width:100px;height:75px;object-fit:cover\\\">").join(""));});frame.open();});$("#gb_gallery_clear").on("click",function(){$("#gb_gallery_ids").val("");$("#gb_gallery_preview").empty();});});</script>';
}

function gbv2_assign_term($post_id, $taxonomy, $name) {
    $name = trim(wp_strip_all_tags((string) $name));
    if ($name === '') {
        wp_set_object_terms($post_id, array(), $taxonomy, false);
        return 0;
    }
    $exists = term_exists($name, $taxonomy);
    if (!$exists) { $exists = wp_insert_term($name, $taxonomy); }
    if (is_wp_error($exists)) { return 0; }
    $term_id = is_array($exists) ? (int) $exists['term_id'] : (int) $exists;
    wp_set_object_terms($post_id, array($term_id), $taxonomy, false);
    return $term_id;
}

function gbv2_save_vehicle($post_id) {
    if (!isset($_POST['gbv2_nonce'])) { return; }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gbv2_nonce'])), 'gbv2_save_vehicle')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (get_post_type($post_id) !== 'gb_vehicle' || !current_user_can('edit_post', $post_id)) { return; }

    $make_id = gbv2_assign_term($post_id, 'gb_vehicle_make', $_POST['gb_make'] ?? '');
    $model_id = gbv2_assign_term($post_id, 'gb_vehicle_model', $_POST['gb_model'] ?? '');
    gbv2_assign_term($post_id, 'gb_vehicle_fuel', $_POST['gb_fuel'] ?? '');
    gbv2_assign_term($post_id, 'gb_vehicle_transmission', $_POST['gb_transmission'] ?? '');
    gbv2_assign_term($post_id, 'gb_vehicle_status', $_POST['gb_status'] ?? 'Beschikbaar');

    if ($make_id && $model_id) {
        $make_term = get_term($make_id, 'gb_vehicle_make');
        if ($make_term && !is_wp_error($make_term)) {
            update_term_meta($model_id, 'gb_vehicle_make_slug', $make_term->slug);
        }
    }

    $text_fields = array('gb_variant','gb_stock_ref','gb_first_registration','gb_color','gb_warranty','gb_vat');
    $number_fields = array('gb_year','gb_mileage','gb_price','gb_power_kw','gb_power_hp','gb_displacement','gb_co2','gb_doors','gb_seats');
    foreach ($text_fields as $key) { update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key] ?? ''))); }
    foreach ($number_fields as $key) { update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key] ?? ''))); }
    update_post_meta($post_id, 'gb_options', sanitize_textarea_field(wp_unslash($_POST['gb_options'] ?? '')));
    $gallery = implode(',', array_filter(array_map('absint', explode(',', sanitize_text_field(wp_unslash($_POST['gb_gallery_ids'] ?? ''))))));
    update_post_meta($post_id, 'gb_gallery_ids', $gallery);

    $make = gbv2_term_name($post_id, 'gb_vehicle_make');
    $model = gbv2_term_name($post_id, 'gb_vehicle_model');
    $variant = get_post_meta($post_id, 'gb_variant', true);
    if ($make && $model) {
        remove_action('save_post_gb_vehicle', 'gbv2_save_vehicle');
        wp_update_post(array('ID' => $post_id, 'post_title' => trim($make . ' ' . $model . ' ' . $variant)));
        add_action('save_post_gb_vehicle', 'gbv2_save_vehicle');
    }
}
add_action('save_post_gb_vehicle', 'gbv2_save_vehicle');

function gbv2_price($post_id) {
    $price = get_post_meta($post_id, 'gb_price', true);
    return $price !== '' ? '€ ' . number_format_i18n((float) $price, 0) : 'Prijs op aanvraag';
}

function gbv2_vehicle_card($post_id) {
    $make = gbv2_term_name($post_id, 'gb_vehicle_make');
    $model = gbv2_term_name($post_id, 'gb_vehicle_model');
    $variant = get_post_meta($post_id, 'gb_variant', true);
    $year = get_post_meta($post_id, 'gb_year', true);
    $km = get_post_meta($post_id, 'gb_mileage', true);
    $fuel = gbv2_term_name($post_id, 'gb_vehicle_fuel');
    $image = get_the_post_thumbnail($post_id, 'large', array('class' => 'gbv-card-image', 'loading' => 'lazy'));
    if (!$image) { $image = '<div class="gbv-card-image gbv-card-image-empty">Foto volgt</div>'; }

    $html = '<article class="gbv-card">';
    $html .= '<a class="gbv-card-media" href="' . esc_url(get_permalink($post_id)) . '">' . $image . '</a>';
    $html .= '<div class="gbv-card-body"><span class="gbv-card-make">' . esc_html($make) . '</span>';
    $html .= '<h3><a href="' . esc_url(get_permalink($post_id)) . '">' . esc_html(trim($model . ' ' . $variant)) . '</a></h3><div class="gbv-card-meta">';
    if ($year) { $html .= '<span>' . esc_html($year) . '</span>'; }
    if ($km !== '') { $html .= '<span>' . esc_html(number_format_i18n((int) $km, 0) . ' km') . '</span>'; }
    if ($fuel) { $html .= '<span>' . esc_html($fuel) . '</span>'; }
    $html .= '</div><strong class="gbv-price">' . esc_html(gbv2_price($post_id)) . '</strong><a class="gbv-more" href="' . esc_url(get_permalink($post_id)) . '">Bekijk wagen →</a></div></article>';
    return $html;
}

function gbv2_render_archive() {
    $tax_query = array('relation' => 'AND');
    $map = array('merk' => 'gb_vehicle_make', 'brandstof' => 'gb_vehicle_fuel', 'transmissie' => 'gb_vehicle_transmission');
    foreach ($map as $key => $taxonomy) {
        if (!empty($_GET[$key])) {
            $tax_query[] = array('taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => sanitize_title(wp_unslash($_GET[$key])));
        }
    }

    $args = array('post_type' => 'gb_vehicle', 'post_status' => 'publish', 'posts_per_page' => 24, 'orderby' => 'date', 'order' => 'DESC');
    if (count($tax_query) > 1) { $args['tax_query'] = $tax_query; }
    if (!empty($_GET['max_prijs'])) {
        $args['meta_query'] = array(array('key' => 'gb_price', 'value' => (float) $_GET['max_prijs'], 'type' => 'NUMERIC', 'compare' => '<='));
    }

    $q = new WP_Query($args);
    $makes = get_terms(array('taxonomy' => 'gb_vehicle_make', 'hide_empty' => true));
    $fuels = get_terms(array('taxonomy' => 'gb_vehicle_fuel', 'hide_empty' => true));
    $trans = get_terms(array('taxonomy' => 'gb_vehicle_transmission', 'hide_empty' => true));

    $html = '<form class="gbv-filters" method="get">';
    $html .= gbv2_filter_select('merk', 'Alle merken', $makes);
    $html .= gbv2_filter_select('brandstof', 'Alle brandstoffen', $fuels);
    $html .= gbv2_filter_select('transmissie', 'Alle transmissies', $trans);
    $html .= '<input type="number" name="max_prijs" min="0" step="500" placeholder="Max. prijs" value="' . esc_attr($_GET['max_prijs'] ?? '') . '">';
    $html .= '<button class="gb-button gb-button-dark" type="submit">Filter</button></form>';

    if ($q->have_posts()) {
        $html .= '<div class="gbv-grid-front">';
        while ($q->have_posts()) {
            $q->the_post();
            $html .= gbv2_vehicle_card(get_the_ID());
        }
        $html .= '</div>';
    } else {
        $html .= '<div class="gb-empty-state"><span class="gb-kicker">Garage Barnes Vehicles</span><h2>Nog geen wagens gevonden.</h2><p>Pas eventueel de filters aan of contacteer ons voor het actuele aanbod.</p></div>';
    }
    wp_reset_postdata();
    return $html;
}

function gbv2_filter_select($name, $placeholder, $terms) {
    $current = sanitize_title(wp_unslash($_GET[$name] ?? ''));
    $html = '<select name="' . esc_attr($name) . '"><option value="">' . esc_html($placeholder) . '</option>';
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $html .= '<option value="' . esc_attr($term->slug) . '" ' . selected($current, $term->slug, false) . '>' . esc_html($term->name) . '</option>';
        }
    }
    $html .= '</select>';
    return $html;
}

function gbv2_cars_shortcode() {
    $content = gbv2_render_archive();
    if (function_exists('gb_page_shell')) {
        return gb_page_shell('Tweedehandswagens', 'Selecteerde tweedehandswagens', 'Een wisselend aanbod voertuigen van Garage Barnes.', $content, 'Vraag naar ons aanbod', '/contact/');
    }
    return $content;
}
remove_shortcode('garage_barnes_tweedehands');
add_shortcode('garage_barnes_tweedehands', 'gbv2_cars_shortcode');

function gbv2_single_content($content) {
    if (!is_singular('gb_vehicle') || !in_the_loop() || !is_main_query()) { return $content; }

    $id = get_the_ID();
    $make = gbv2_term_name($id, 'gb_vehicle_make');
    $model = gbv2_term_name($id, 'gb_vehicle_model');
    $variant = get_post_meta($id, 'gb_variant', true);
    $gallery = array_filter(array_map('absint', explode(',', (string) get_post_meta($id, 'gb_gallery_ids', true))));
    $options = array_filter(array_map('trim', preg_split('/\R/', (string) get_post_meta($id, 'gb_options', true))));

    $specs = array(
        'Eerste inschrijving' => get_post_meta($id, 'gb_first_registration', true),
        'Bouwjaar' => get_post_meta($id, 'gb_year', true),
        'Kilometerstand' => get_post_meta($id, 'gb_mileage', true) !== '' ? number_format_i18n((int) get_post_meta($id, 'gb_mileage', true), 0) . ' km' : '',
        'Brandstof' => gbv2_term_name($id, 'gb_vehicle_fuel'),
        'Transmissie' => gbv2_term_name($id, 'gb_vehicle_transmission'),
        'Vermogen' => get_post_meta($id, 'gb_power_hp', true) !== '' ? get_post_meta($id, 'gb_power_hp', true) . ' pk' : '',
        'Kleur' => get_post_meta($id, 'gb_color', true),
        'CO₂' => get_post_meta($id, 'gb_co2', true) !== '' ? get_post_meta($id, 'gb_co2', true) . ' g/km' : '',
        'Garantie' => get_post_meta($id, 'gb_warranty', true),
    );

    $html = '<main class="gbv-single"><section class="gbv-single-head"><div class="gb-shell"><span class="gb-kicker">' . esc_html($make) . '</span><h1>' . esc_html(trim($model . ' ' . $variant)) . '</h1><strong class="gbv-single-price">' . esc_html(gbv2_price($id)) . '</strong></div></section>';
    $html .= '<section class="gb-shell gbv-single-layout"><div><div class="gbv-main-image">' . get_the_post_thumbnail($id, 'full') . '</div>';
    if ($gallery) {
        $html .= '<div class="gbv-gallery">';
        foreach ($gallery as $aid) { $html .= wp_get_attachment_image($aid, 'large', false, array('loading' => 'lazy')); }
        $html .= '</div>';
    }
    $html .= '<div class="gbv-description">' . wpautop(wp_kses_post($content)) . '</div>';
    if ($options) {
        $html .= '<h2>Opties & uitrusting</h2><div class="gbv-options-list">';
        foreach ($options as $option) { $html .= '<span>' . esc_html($option) . '</span>'; }
        $html .= '</div>';
    }
    $html .= '</div><aside><div class="gbv-spec-card"><h2>Specificaties</h2>';
    foreach ($specs as $label => $value) {
        if ($value !== '') { $html .= '<div><span>' . esc_html($label) . '</span><strong>' . esc_html($value) . '</strong></div>'; }
    }
    $html .= '<a class="gb-button gb-button-green" href="' . esc_url(home_url('/contact/?wagen=' . rawurlencode(get_the_title($id)))) . '">Interesse in deze wagen</a></div></aside></section></main>';
    return $html;
}
add_filter('the_content', 'gbv2_single_content', 20);

function gbv2_flush_rewrite_once() {
    if (get_option('gb_vehicle_rewrite_v2_done')) { return; }
    flush_rewrite_rules(false);
    update_option('gb_vehicle_rewrite_v2_done', 1, false);
}
add_action('init', 'gbv2_flush_rewrite_once', 99);
