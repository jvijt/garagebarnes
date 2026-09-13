<?php
/**
 * Garage Barnes - Auto Service hero matching the Takeldienst landing style.
 */
if (!defined('ABSPATH')) { exit; }

function gb_auto_service_hero_shortcode(){
    $network = GB_PLUGIN_URL . 'assets/img/123-autoservice-logo.png';
    $hero = 'https://garagebarnes.be/wp-content/uploads/2026/09/Motor-Garage-Barnes-Hamme-scaled.jpg';
    $eurol = 'https://garagebarnes.be/wp-content/uploads/2026/09/Logo-Eurol-Service-Point.png';
    $xten = 'https://garagebarnes.be/wp-content/uploads/2026/09/logo-X-ten-additieven.png';

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
        </div><aside class="gb-side-card gb-auto-partners">
          <div class="gb-auto-partner gb-auto-partner-123">
            <a class="gb-auto-partner-logo-link" href="https://www.123autoservice.be/nl/" target="_blank" rel="noopener noreferrer" aria-label="Bezoek de website van 1,2,3 AutoService">
              <img class="gb-page-network-logo" src="<?php echo esc_url($network); ?>" alt="1,2,3 AutoService">
            </a>
            <h3>Persoonlijke garageservice</h3>
            <p>Een lokaal aanspreekpunt met technische kennis, snelle service en transparante communicatie.</p>
          </div>

          <div class="gb-auto-partner">
            <img class="gb-auto-partner-logo gb-auto-partner-logo-eurol" src="<?php echo esc_url($eurol); ?>" alt="Eurol Service Point">
            <h3>Eurol Service Point</h3>
          </div>

          <div class="gb-auto-partner gb-auto-partner-last">
            <img class="gb-auto-partner-logo gb-auto-partner-logo-xten" src="<?php echo esc_url($xten); ?>" alt="X-ten additieven">
            <h3>Verdeler X-ten additieven</h3>
          </div>
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
      .gb-auto-partners{padding:0!important;overflow:hidden}
      .gb-auto-partner{padding:30px 28px}
      .gb-auto-partner+.gb-auto-partner{border-top:1px solid #dfe3dc}
      .gb-auto-partner-123{padding-top:28px}
      .gb-auto-service-page .gb-auto-partner-logo-link{display:block!important;text-align:center!important;text-decoration:none!important}
      .gb-auto-service-page .gb-auto-partner-logo-link:hover{opacity:.88}
      .gb-auto-service-page .gb-auto-partner .gb-page-network-logo{display:block!important;max-width:210px!important;width:100%!important;height:auto!important;margin:0 auto 24px!important}
      .gb-auto-service-page .gb-auto-partner-logo{display:block!important;max-width:210px!important;max-height:105px!important;width:auto!important;height:auto!important;object-fit:contain!important;margin:0 auto 22px!important}
      .gb-auto-service-page .gb-auto-partner-logo-eurol{max-width:285px!important;max-height:145px!important;width:100%!important}
      .gb-auto-service-page .gb-auto-partner-logo-xten{max-height:90px!important}
      .gb-auto-service-page .gb-auto-partner h3{margin:0 0 12px!important;font-size:22px!important;line-height:1.2!important;text-align:center!important}
      .gb-auto-service-page .gb-auto-partner-123 p{text-align:left!important}
      .gb-auto-partner-last h3{margin-bottom:0!important}
      @media(max-width:700px){
        .gb-auto-service-hero{min-height:560px;background-position:58% center}
        .gb-auto-service-hero-inner{padding-top:70px;padding-bottom:70px}
        .gb-auto-service-hero h1{font-size:48px}
        .gb-auto-partner{padding:26px 24px}
        .gb-auto-service-page .gb-auto-partner .gb-page-network-logo,.gb-auto-service-page .gb-auto-partner-logo{max-width:190px!important}
        .gb-auto-service-page .gb-auto-partner-logo-eurol{max-width:250px!important;max-height:130px!important}
      }
    </style>
    <?php
}, 125);
