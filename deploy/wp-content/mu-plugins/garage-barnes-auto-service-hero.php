<?php
/**
 * Garage Barnes - Auto Service hero matching the Takeldienst landing style.
 */
if (!defined('ABSPATH')) { exit; }

function gb_auto_service_hero_shortcode(){
    $network = GB_PLUGIN_URL . 'assets/img/123-autoservice-logo.png';
    $hero = 'https://garagebarnes.be/wp-content/uploads/2026/09/Motor-Garage-Barnes-Hamme-scaled.jpg';

    ob_start(); ?>
    <main class="gb-page gb-auto-service-page">
      <section class="gb-towing-hero gb-auto-service-hero" style="background-image:linear-gradient(90deg,rgba(13,13,13,.91) 0%,rgba(13,13,13,.72) 48%,rgba(13,13,13,.28) 100%),url('<?php echo esc_url($hero); ?>')">
        <div class="gb-shell gb-towing-hero-inner gb-auto-service-hero-inner">
          <span class="gb-eyebrow">Garage Barnes · Hamme</span>
          <h1>Uw wagen in<br>goede handen.</h1>
          <p>Onderhoud, diagnose en herstellingen voor alle merken, met duidelijke communicatie en persoonlijke service.</p>
          <div class="gb-towing-hero-actions">
            <a class="gb-button gb-button-green" href="<?php echo esc_url(home_url('/maak-afspraak/')); ?>">Maak een afspraak</a>
          </div>
        </div>
      </section>

      <section class="gb-page-content"><div class="gb-shell">
        <div class="gb-content-grid"><div>
          <h2>Onderhoud en herstellingen voor alle merken</h2>
          <p>Garage Barnes staat in voor klein en groot onderhoud, herstellingen en technische diagnose. We communiceren duidelijk en voeren geen bijkomende werken uit zonder uw toestemming.</p>
          <div class="gb-service-list">
            <span>Klein en groot onderhoud</span>
            <span>Allerlei herstellingen <small>Remmen, remschijven, koppeling, versnellingsbak, glasbreuk, distributieriem of ketting, schokdempers, injectoren, waterpompen, uitlaten en nog veel meer…</small></span>
            <span>Motorwissels</span>
            <span>Elektrische diagnose</span>
            <span>Airco onderhoud en herstelling</span>
            <span>Batterijen en banden</span>
            <span>Depannages</span>
            <span>Pechverhelping aan huis <small>Platte batterij/band, sleutels in je wagen</small></span>
            <span>Nazicht keuring</span>
            <span>Keuring</span>
            <span>Reisnazicht</span>
            <span>Expertise na ongeval</span>
          </div>
        </div><aside class="gb-side-card">
          <img class="gb-page-network-logo" src="<?php echo esc_url($network); ?>" alt="1,2,3 AutoService">
          <h3>Persoonlijke garageservice</h3>
          <p>Een lokaal aanspreekpunt met technische kennis, snelle service en transparante communicatie.</p>
          <a href="tel:+32477353547">+32 477 35 35 47</a>
        </aside></div>
      </div></section>

      <section class="gb-page-cta"><div class="gb-shell gb-page-cta-inner"><div><span class="gb-kicker">Garage Barnes · Hamme</span><h2>We helpen u graag verder.</h2></div><a class="gb-button gb-button-green" href="<?php echo esc_url(home_url('/maak-afspraak/')); ?>">Maak een afspraak</a></div></section>
    </main>
    <?php return ob_get_clean();
}

add_action('init', function(){
    remove_shortcode('garage_barnes_auto_service');
    add_shortcode('garage_barnes_auto_service','gb_auto_service_hero_shortcode');
}, 110);

add_action('wp_head', function(){
    if (!is_page('auto-service')) { return; }
    ?>
    <style id="gb-auto-service-hero-css">
      .gb-auto-service-page{width:100%;overflow:visible}
      .gb-auto-service-hero{background-position:center center}
      .gb-auto-service-hero-inner{padding-top:105px;padding-bottom:105px}
      .gb-auto-service-hero .gb-eyebrow{color:#a6ec79}
      .gb-auto-service-hero h1{max-width:760px}
      .gb-auto-service-hero p{max-width:650px}
      @media(max-width:700px){
        .gb-auto-service-hero{min-height:560px;background-position:58% center}
        .gb-auto-service-hero-inner{padding-top:70px;padding-bottom:70px}
        .gb-auto-service-hero h1{font-size:48px}
      }
    </style>
    <?php
}, 125);
