<?php
/**
 * Plugin Name: Garage Barnes
 * Description: Custom functionality and design prototypes for the Garage Barnes WordPress website.
 * Version: 0.2.1
 * Author: Garage Barnes
 */

if (!defined('ABSPATH')) {
    exit;
}

define('GB_PLUGIN_VERSION', '0.2.1');
define('GB_PLUGIN_URL', plugin_dir_url(__FILE__));

function gb_enqueue_assets() {
    $css_file = plugin_dir_path(__FILE__) . 'assets/css/garage-barnes.css';
    $css_version = file_exists($css_file) ? (string) filemtime($css_file) : GB_PLUGIN_VERSION;

    wp_register_style(
        'garage-barnes-site',
        GB_PLUGIN_URL . 'assets/css/garage-barnes.css',
        array(),
        $css_version
    );
}
add_action('wp_enqueue_scripts', 'gb_enqueue_assets');

function gb_homepage_shortcode() {
    wp_enqueue_style('garage-barnes-site');

    $garage_url = home_url('/auto-service/');
    $towing_url = home_url('/takeldienst/');
    $cars_url = home_url('/tweedehands/');
    $contact_url = home_url('/contact/');

    ob_start();
    ?>
    <main class="gb-home">
        <section class="gb-split-hero" aria-label="Garage Barnes en Takeldienst Barnes">
            <a class="gb-hero-panel gb-hero-garage" href="<?php echo esc_url($garage_url); ?>">
                <div class="gb-hero-overlay"></div>
                <div class="gb-hero-content">
                    <span class="gb-eyebrow">Garage Barnes · Hamme</span>
                    <h1>Garage &amp;<br>Auto Service</h1>
                    <p>Onderhoud, herstellingen en diagnose voor alle merken.</p>
                    <span class="gb-button gb-button-light">Ontdek de garage</span>
                </div>
            </a>

            <a class="gb-hero-panel gb-hero-towing" href="<?php echo esc_url($towing_url); ?>">
                <div class="gb-hero-overlay"></div>
                <div class="gb-hero-content">
                    <span class="gb-eyebrow">Takeldienst Barnes · 24/7</span>
                    <h2>Pech of ongeval?<br>Wij helpen.</h2>
                    <p>Voor particulieren, bedrijven, verzekeringen en pechbijstand.</p>
                    <span class="gb-button gb-button-green">Takeldienst 24/7</span>
                </div>
            </a>
        </section>

        <section class="gb-intro gb-container">
            <div class="gb-section-heading">
                <span class="gb-kicker">Één partner voor uw wagen</span>
                <h2>Van onderhoud tot pechverhelping</h2>
            </div>
            <p class="gb-lead">Garage Barnes combineert een persoonlijke garage voor onderhoud en herstellingen met een eigen professionele takeldienst. Snel geholpen, duidelijke communicatie en geen werken zonder uw toestemming.</p>
        </section>

        <section class="gb-services gb-container">
            <article class="gb-service-card">
                <span class="gb-card-number">01</span>
                <h3>Auto Service</h3>
                <p>Klein en groot onderhoud, herstellingen, elektrische diagnose, airco, banden, batterijen, elektrische wagens en voorbereiding op de keuring.</p>
                <a href="<?php echo esc_url($garage_url); ?>">Alle garagediensten →</a>
            </article>
            <article class="gb-service-card gb-service-card-dark">
                <span class="gb-card-number">02</span>
                <h3>Takeldienst 24/7</h3>
                <p>Rechtstreeks bereikbaar voor particulieren bij panne, ongeval of pech. Ook actief voor bedrijven, verzekeraars en pechbijstandsorganisaties.</p>
                <a href="<?php echo esc_url($towing_url); ?>">Naar de takeldienst →</a>
            </article>
            <article class="gb-service-card">
                <span class="gb-card-number">03</span>
                <h3>Tweedehandswagens</h3>
                <p>Een wisselend aanbod zorgvuldig geselecteerde tweedehandswagens, binnenkort volledig beheerd via onze eigen Garage Barnes voertuigenmodule.</p>
                <a href="<?php echo esc_url($cars_url); ?>">Bekijk het aanbod →</a>
            </article>
        </section>

        <section class="gb-towing-band">
            <div class="gb-container gb-towing-band-inner">
                <div>
                    <span class="gb-kicker">Takeldienst Barnes</span>
                    <h2>Ook als particulier kunt u ons rechtstreeks bellen.</h2>
                    <p>Pech, panne of ongeval in Hamme en ruime omgeving? Onze takeldienst staat klaar.</p>
                </div>
                <div class="gb-towing-actions">
                    <a class="gb-button gb-button-green" href="tel:+32477353547">Bel +32 477 35 35 47</a>
                    <a class="gb-text-link" href="<?php echo esc_url($towing_url); ?>">Meer over takeldienst →</a>
                </div>
            </div>
        </section>

        <section class="gb-vehicles gb-container">
            <div class="gb-section-heading gb-heading-row">
                <div>
                    <span class="gb-kicker">Recent aanbod</span>
                    <h2>Tweedehandswagens</h2>
                </div>
                <a class="gb-text-link" href="<?php echo esc_url($cars_url); ?>">Bekijk alle wagens →</a>
            </div>
            <div class="gb-vehicle-placeholder-grid">
                <?php for ($i = 0; $i < 3; $i++) : ?>
                    <article class="gb-vehicle-placeholder">
                        <div class="gb-placeholder-image"><span>Voertuigfoto</span></div>
                        <div class="gb-placeholder-body">
                            <span class="gb-placeholder-label">Binnenkort via Garage Barnes Vehicles</span>
                            <h3>Merk &amp; model</h3>
                            <div class="gb-placeholder-meta"><span>Bouwjaar</span><span>Km-stand</span><span>Brandstof</span></div>
                            <strong>€ —</strong>
                        </div>
                    </article>
                <?php endfor; ?>
            </div>
        </section>

        <section class="gb-trust">
            <div class="gb-container gb-trust-grid">
                <div>
                    <span class="gb-kicker">Waarom Barnes?</span>
                    <h2>Lokale service. Technische kennis. Eén aanspreekpunt.</h2>
                </div>
                <div class="gb-trust-points">
                    <div><strong>Alle merken</strong><span>Onderhoud en herstellingen</span></div>
                    <div><strong>Eigen takeldienst</strong><span>Snel en vertrouwd geholpen</span></div>
                    <div><strong>Transparant</strong><span>Geen werken zonder toestemming</span></div>
                    <div><strong>1,2,3 AutoService</strong><span>Aangesloten bij het garagenetwerk</span></div>
                </div>
            </div>
        </section>

        <section class="gb-news gb-container">
            <div class="gb-section-heading gb-heading-row">
                <div>
                    <span class="gb-kicker">Nieuws &amp; acties</span>
                    <h2>Bij Garage Barnes</h2>
                </div>
            </div>
            <div class="gb-news-grid">
                <?php
                $posts = get_posts(array('numberposts' => 3, 'post_status' => 'publish'));
                if ($posts) :
                    foreach ($posts as $post) :
                        ?>
                        <article class="gb-news-card">
                            <span><?php echo esc_html(get_the_date('d.m.Y', $post)); ?></span>
                            <h3><?php echo esc_html(get_the_title($post)); ?></h3>
                            <a href="<?php echo esc_url(get_permalink($post)); ?>">Lees meer →</a>
                        </article>
                        <?php
                    endforeach;
                else :
                    ?>
                    <article class="gb-news-card gb-news-placeholder">
                        <span>WordPress berichten</span>
                        <h3>Hier verschijnen binnenkort nieuws, acties en promoties.</h3>
                        <p>Nieuwe berichten worden automatisch op de homepage getoond.</p>
                    </article>
                <?php endif; ?>
            </div>
        </section>

        <section class="gb-contact-strip">
            <div class="gb-container gb-contact-grid">
                <div><span>Garage &amp; afspraak</span><a href="tel:+32477353547">+32 477 35 35 47</a></div>
                <div><span>Vaste lijn</span><a href="tel:+3252570557">+32 52 57 05 57</a></div>
                <div><span>Adres</span><strong>Zonneke 4 · 9220 Hamme</strong></div>
                <div><span>Open</span><strong>Ma–Vr 08:00–12:00 &amp; 13:00–18:00</strong></div>
                <a class="gb-button gb-button-dark" href="<?php echo esc_url($contact_url); ?>">Contact &amp; afspraak</a>
            </div>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
add_shortcode('garage_barnes_home', 'gb_homepage_shortcode');
