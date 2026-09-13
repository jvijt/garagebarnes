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

      <section class="gb-auto-service-highlight-cta">
        <div class="gb-shell">
          <div class="gb-auto-service-highlight-inner">
            <div>
              <span class="gb-kicker">Garage Barnes · Hamme</span>
              <h2>Tijd voor onderhoud of een herstelling?</h2>
              <p>Plan eenvoudig uw afspraak. We nemen uw wagen professioneel onder handen en houden u duidelijk op de hoogte.</p>
            </div>
            <a class="gb-button gb-auto-service-big-button" href="<?php echo esc_url(home_url('/maak-afspraak/')); ?>">Maak een afspraak</a>
          </div>
        </div>
      </section>

      <section class="gb-auto-service-video-section">
        <div class="gb-shell">
          <div class="gb-auto-service-section-heading">
            <span class="gb-kicker">Garage Barnes in beeld</span>
            <h2>Bekijk onze garage</h2>
          </div>
          <div class="gb-auto-service-video-wrap">
            <iframe src="https://www.youtube-nocookie.com/embed/J_81lK_xmek?rel=0" title="Garage Barnes Auto Service" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          </div>
        </div>
      </section>

      <section class="gb-auto-service-gallery-section">
        <div class="gb-shell">
          <div class="gb-auto-service-section-heading">
            <span class="gb-kicker">Onze werkplaats</span>
            <h2>Foto's van Garage Barnes</h2>
            <p>Hier komt binnenkort een fotogalerij met beelden van onze werkplaats, techniekers en uitgevoerde werken.</p>
          </div>
          <div class="gb-auto-service-gallery-placeholder" aria-label="Fotogalerij wordt binnenkort toegevoegd">
            <span>Fotogalerij binnenkort beschikbaar</span>
          </div>
        </div>
      </section>

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

      .gb-auto-service-highlight-cta{padding:26px 0 70px;background:#fff}
      .gb-auto-service-highlight-inner{display:flex;align-items:center;justify-content:space-between;gap:40px;padding:42px 46px;background:#111;color:#fff;border-left:7px solid #5dc01d;box-shadow:0 18px 46px rgba(0,0,0,.14)}
      .gb-auto-service-highlight-inner .gb-kicker{color:#a6ec79}
      .gb-auto-service-highlight-inner h2{margin:5px 0 8px!important;color:#fff!important;font-size:clamp(30px,4vw,44px)!important;line-height:1.08!important}
      .gb-auto-service-highlight-inner p{max-width:650px;margin:0;color:#d7d7d7;font-size:17px;line-height:1.55}
      .gb-auto-service-big-button{display:inline-flex!important;align-items:center!important;justify-content:center!important;flex:0 0 auto;min-width:245px;min-height:60px;padding:17px 28px!important;background:#5dc01d!important;color:#fff!important;border-radius:3px!important;font-size:18px!important;font-weight:800!important;text-decoration:none!important}
      .gb-auto-service-big-button:hover{background:#469714!important;color:#fff!important}

      .gb-auto-service-video-section{padding:75px 0;background:#f4f5f2}
      .gb-auto-service-section-heading{max-width:760px;margin:0 auto 30px;text-align:center}
      .gb-auto-service-section-heading .gb-kicker{color:#469714}
      .gb-auto-service-section-heading h2{margin:5px 0 12px!important;font-size:clamp(32px,4vw,46px)!important;line-height:1.1!important}
      .gb-auto-service-section-heading p{margin:0;color:#676d64;font-size:16px;line-height:1.6}
      .gb-auto-service-video-wrap{position:relative;max-width:1000px;margin:0 auto;aspect-ratio:16/9;background:#111;border-radius:8px;overflow:hidden;box-shadow:0 18px 44px rgba(0,0,0,.14)}
      .gb-auto-service-video-wrap iframe{position:absolute;inset:0;width:100%;height:100%;border:0}

      .gb-auto-service-gallery-section{padding:75px 0;background:#fff}
      .gb-auto-service-gallery-placeholder{display:flex;align-items:center;justify-content:center;min-height:210px;max-width:1000px;margin:0 auto;border:2px dashed #cbd2c5;border-radius:8px;background:#f7f8f5;color:#71776d;font-size:16px;font-weight:700;text-align:center;padding:30px}

      @media(max-width:800px){
        .gb-auto-service-highlight-inner{flex-direction:column;align-items:flex-start;padding:34px 28px}
        .gb-auto-service-big-button{width:100%;min-width:0}
      }
      @media(max-width:700px){
        .gb-auto-service-hero{min-height:560px;background-position:58% center}
        .gb-auto-service-hero-inner{padding-top:70px;padding-bottom:70px}
        .gb-auto-service-hero h1{font-size:48px}
        .gb-auto-partner{padding:26px 24px}
        .gb-auto-service-page .gb-auto-partner .gb-page-network-logo,.gb-auto-service-page .gb-auto-partner-logo{max-width:190px!important}
        .gb-auto-service-page .gb-auto-partner-logo-eurol{max-width:250px!important;max-height:130px!important}
        .gb-auto-service-highlight-cta{padding:15px 0 55px}
        .gb-auto-service-video-section,.gb-auto-service-gallery-section{padding:58px 0}
      }
    </style>
    <?php
}, 125);
