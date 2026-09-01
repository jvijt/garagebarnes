<?php
/**
 * Plugin Name: Garage Barnes
 * Description: Custom functionality and design for the Garage Barnes WordPress website.
 * Version: 0.3.4
 * Author: Garage Barnes
 */

if (!defined('ABSPATH')) { exit; }

define('GB_PLUGIN_VERSION', '0.3.4');
define('GB_PLUGIN_URL', plugin_dir_url(__FILE__));
require_once plugin_dir_path(__FILE__) . 'site-pages.php';

function gb_enqueue_assets() {
    $css_file = plugin_dir_path(__FILE__) . 'assets/css/garage-barnes.css';
    $css_version = file_exists($css_file) ? (string) filemtime($css_file) : GB_PLUGIN_VERSION;
    wp_register_style('garage-barnes-site', GB_PLUGIN_URL . 'assets/css/garage-barnes.css', array(), $css_version);
    wp_enqueue_style('garage-barnes-site');
}
add_action('wp_enqueue_scripts', 'gb_enqueue_assets');

function gb_bootstrap_site_pages() {
    if (get_option('gb_site_pages_v1_done')) return;
    $pages = array(
        'home' => array('title' => 'Home', 'content' => '[garage_barnes_home]'),
        'auto-service' => array('title' => 'Auto Service', 'content' => '[garage_barnes_auto_service]'),
        'takeldienst' => array('title' => 'Takeldienst', 'content' => '[garage_barnes_takeldienst]'),
        'tweedehands' => array('title' => 'Tweedehands', 'content' => '[garage_barnes_tweedehands]'),
        'over-ons' => array('title' => 'Over ons', 'content' => '[garage_barnes_over_ons]'),
        'contact' => array('title' => 'Contact', 'content' => '[garage_barnes_contact]'),
    );
    $created = array();
    foreach ($pages as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'page');
        if ($existing) { $created[$slug] = (int)$existing->ID; continue; }
        $page_id = wp_insert_post(array('post_title'=>$data['title'],'post_name'=>$slug,'post_content'=>$data['content'],'post_status'=>'publish','post_type'=>'page'));
        if (!is_wp_error($page_id)) $created[$slug] = (int)$page_id;
    }
    if (!empty($created['home']) && get_option('show_on_front') !== 'page') {
        update_option('show_on_front','page');
        update_option('page_on_front',$created['home']);
    }
    update_option('gb_site_page_ids',$created,false);
    update_option('gb_site_pages_v1_done',1,false);
}
add_action('init','gb_bootstrap_site_pages');

function gb_render_global_header() {
    if (is_admin()) return;
    $home=home_url('/'); $garage=home_url('/auto-service/'); $towing=home_url('/takeldienst/'); $cars=home_url('/tweedehands/'); $about=home_url('/over-ons/'); $contact=home_url('/contact/');
    $garage_logo = GB_PLUGIN_URL . 'assets/img/garage-barnes-logo.png';
    $towing_logo = GB_PLUGIN_URL . 'assets/img/takeldienst-barnes-logo.png';
    ?>
    <div class="gb-topbar"><div class="gb-shell gb-topbar-inner"><div>Zonneke 4 · 9220 Hamme</div><div class="gb-topbar-links"><a href="tel:+3252570557">+32 52 57 05 57</a><a class="gb-topbar-towing" href="<?php echo esc_url($towing); ?>">Takeldienst 24/7</a></div></div></div>
    <header class="gb-site-header"><div class="gb-shell gb-header-inner">
        <a class="gb-logo-link gb-garage-logo-link" href="<?php echo esc_url($home); ?>" aria-label="Garage Barnes home"><img class="gb-garage-logo" src="<?php echo esc_url($garage_logo); ?>" alt="Garage Barnes"></a>
        <nav class="gb-main-nav" aria-label="Hoofdnavigatie"><a href="<?php echo esc_url($home); ?>">Home</a><a href="<?php echo esc_url($garage); ?>">Auto Service</a><a href="<?php echo esc_url($towing); ?>">Takeldienst</a><a href="<?php echo esc_url($cars); ?>">Tweedehands</a><a href="<?php echo esc_url($about); ?>">Over ons</a><a href="<?php echo esc_url($contact); ?>">Contact</a></nav>
        <div class="gb-header-actions"><a class="gb-logo-link gb-towing-logo-link" href="<?php echo esc_url($towing); ?>" aria-label="Takeldienst Barnes 24/7"><img class="gb-towing-logo" src="<?php echo esc_url($towing_logo); ?>" alt="Takeldienst Barnes 24/7"></a><a class="gb-button gb-button-green" href="<?php echo esc_url($contact); ?>">Afspraak maken</a></div>
    </div></header>
    <?php
}
add_action('wp_body_open','gb_render_global_header',5);

function gb_render_global_footer() {
    if (is_admin()) return;
    $garage_logo = GB_PLUGIN_URL . 'assets/img/garage-barnes-logo-wit.png';
    $towing_logo = GB_PLUGIN_URL . 'assets/img/takeldienst-barnes-logo-wit.png';
    $network_logo = GB_PLUGIN_URL . 'assets/img/123-autoservice-logo.png';
    ?>
    <footer class="gb-site-footer"><div class="gb-shell gb-footer-grid">
        <div class="gb-footer-brand"><img class="gb-footer-garage-logo" src="<?php echo esc_url($garage_logo); ?>" alt="Garage Barnes"><p>Garage, onderhoud, herstellingen, tweedehandswagens en takeldienst vanuit Hamme.</p><img class="gb-network-logo" src="<?php echo esc_url($network_logo); ?>" alt="1,2,3 AutoService"><span class="gb-network-note">Aangesloten bij 1,2,3 AutoService</span></div>
        <div><span class="gb-footer-label">Adres</span><strong>Zonneke 4</strong><strong>9220 Hamme</strong></div>
        <div><span class="gb-footer-label">Garage</span><a href="tel:+3252570557">+32 52 57 05 57</a><a href="tel:+32477353547">+32 477 35 35 47</a></div>
        <div><span class="gb-footer-label">Openingsuren</span><strong>Maandag – vrijdag</strong><span>08:30 – 12:00</span><span>13:00 – 18:00</span><span>Zaterdag – zondag gesloten</span></div>
        <div class="gb-footer-towing"><img class="gb-footer-towing-logo" src="<?php echo esc_url($towing_logo); ?>" alt="Takeldienst Barnes 24/7"><strong>24 uur / 7 dagen</strong><a class="gb-button gb-button-green" href="<?php echo esc_url(home_url('/takeldienst/')); ?>">Pechhulp &amp; takeldienst</a></div>
    </div><div class="gb-shell gb-footer-bottom"><span>© <?php echo esc_html(wp_date('Y')); ?> Garage Barnes BV</span><span>Hamme, België</span></div></footer>
    <?php
}
add_action('wp_footer','gb_render_global_footer',5);

function gb_homepage_shortcode() {
    $garage_url=home_url('/auto-service/'); $towing_url=home_url('/takeldienst/'); $cars_url=home_url('/tweedehands/');
    ob_start(); ?>
    <main class="gb-home">
        <section class="gb-split-hero" aria-label="Garage Barnes en Takeldienst Barnes"><a class="gb-hero-panel gb-hero-garage" href="<?php echo esc_url($garage_url); ?>"><div class="gb-hero-overlay"></div><div class="gb-hero-content"><span class="gb-eyebrow">Garage Barnes · Hamme</span><h1>Garage &amp;<br>Auto Service</h1><p>Onderhoud, herstellingen en diagnose voor alle merken.</p><span class="gb-button gb-button-light">Ontdek de garage</span></div></a><a class="gb-hero-panel gb-hero-towing" href="<?php echo esc_url($towing_url); ?>"><div class="gb-hero-overlay"></div><div class="gb-hero-content"><span class="gb-eyebrow">Takeldienst Barnes · 24/7</span><h2>Pech of ongeval?<br>Wij helpen.</h2><p>Voor particulieren, bedrijven, verzekeringen en pechbijstand.</p><span class="gb-button gb-button-green">Takeldienst 24/7</span></div></a></section>
        <section class="gb-intro gb-container"><div class="gb-section-heading"><span class="gb-kicker">Één partner voor uw wagen</span><h2>Van onderhoud tot pechverhelping</h2></div><p class="gb-lead">Garage Barnes combineert een persoonlijke garage voor onderhoud en herstellingen met een eigen professionele takeldienst. Snel geholpen, duidelijke communicatie en geen werken zonder uw toestemming.</p></section>
        <section class="gb-services gb-container"><article class="gb-service-card"><h3>Auto Service</h3><p>Klein en groot onderhoud, herstellingen, elektrische diagnose, airco, banden, batterijen, elektrische wagens en voorbereiding op de keuring.</p><a href="<?php echo esc_url($garage_url); ?>">Alle garagediensten →</a></article><article class="gb-service-card gb-service-card-dark"><h3>Takeldienst 24/7</h3><p>Rechtstreeks bereikbaar voor particulieren bij panne, ongeval of pech. Ook actief voor bedrijven, verzekeraars en pechbijstandsorganisaties.</p><a href="<?php echo esc_url($towing_url); ?>">Naar de takeldienst →</a></article><article class="gb-service-card"><h3>Tweedehandswagens</h3><p>Een wisselend aanbod zorgvuldig geselecteerde tweedehandswagens, rechtstreeks uit ons actuele Garage Barnes Vehicles-aanbod.</p><a href="<?php echo esc_url($cars_url); ?>">Bekijk het aanbod →</a></article></section>
        <section class="gb-towing-band"><div class="gb-container gb-towing-band-inner"><div><span class="gb-kicker">Takeldienst Barnes</span><h2>Ook als particulier kunt u ons rechtstreeks bellen.</h2><p>Pech, panne of ongeval in Hamme en ruime omgeving? Onze takeldienst staat klaar.</p></div><div class="gb-towing-actions"><a class="gb-button gb-button-green" href="tel:+32477353547">Bel +32 477 35 35 47</a><a class="gb-text-link" href="<?php echo esc_url($towing_url); ?>">Meer over takeldienst →</a></div></div></section>
        <section class="gb-vehicles gb-container"><div class="gb-section-heading gb-heading-row"><div><span class="gb-kicker">Recent aanbod</span><h2>Tweedehandswagens</h2></div><a class="gb-text-link" href="<?php echo esc_url($cars_url); ?>">Bekijk alle wagens →</a></div><?php
        $vehicle_query = new WP_Query(array(
            'post_type' => 'gb_vehicle',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(array(
                'taxonomy' => 'gb_vehicle_status',
                'field' => 'name',
                'terms' => array('Beschikbaar','Gereserveerd'),
            )),
        ));
        if ($vehicle_query->have_posts() && function_exists('gbv2_vehicle_card')): ?>
            <div class="gbv-grid-front gb-home-vehicle-grid"><?php while ($vehicle_query->have_posts()): $vehicle_query->the_post(); echo gbv2_vehicle_card(get_the_ID()); endwhile; ?></div>
        <?php else: ?>
            <div class="gb-empty-state"><span class="gb-kicker">Garage Barnes Vehicles</span><h3>Momenteel geen tweedehandswagens beschikbaar.</h3><p>Bekijk later opnieuw ons aanbod of neem contact met ons op.</p></div>
        <?php endif; wp_reset_postdata(); ?></section>
        <section class="gb-trust"><div class="gb-container gb-trust-grid"><div><span class="gb-kicker">Waarom Barnes?</span><h2>Lokale service. Technische kennis. Eén aanspreekpunt.</h2></div><div class="gb-trust-points"><div><strong>Alle merken</strong><span>Onderhoud en herstellingen</span></div><div><strong>Eigen takeldienst</strong><span>Snel en vertrouwd geholpen</span></div><div><strong>Transparant</strong><span>Geen werken zonder toestemming</span></div><div><strong>1,2,3 AutoService</strong><span>Aangesloten bij het garagenetwerk</span></div></div></div></section>
        <section class="gb-news gb-container"><div class="gb-section-heading gb-heading-row"><div><span class="gb-kicker">Nieuws &amp; acties</span><h2>Bij Garage Barnes</h2></div></div><div class="gb-news-grid"><?php $posts=get_posts(array('numberposts'=>3,'post_status'=>'publish')); if($posts): foreach($posts as $post): ?><article class="gb-news-card"><span><?php echo esc_html(get_the_date('d.m.Y',$post)); ?></span><h3><?php echo esc_html(get_the_title($post)); ?></h3><a href="<?php echo esc_url(get_permalink($post)); ?>">Lees meer →</a></article><?php endforeach; else: ?><article class="gb-news-card gb-news-placeholder"><span>WordPress berichten</span><h3>Hier verschijnen binnenkort nieuws, acties en promoties.</h3><p>Nieuwe berichten worden automatisch op de homepage getoond.</p></article><?php endif; ?></div></section>
    </main><?php return ob_get_clean();
}
add_shortcode('garage_barnes_home','gb_homepage_shortcode');
