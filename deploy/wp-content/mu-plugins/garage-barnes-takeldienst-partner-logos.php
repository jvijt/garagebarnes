<?php
/**
 * Garage Barnes - partner logo strip on Takeldienst page.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('do_shortcode_tag', function($output, $tag, $attr, $m){
    if ($tag !== 'garage_barnes_takeldienst' || !is_page('takeldienst')) { return $output; }

    $logos = array(
        array(
            'src' => 'https://garagebarnes.be/wp-content/uploads/2026/09/logo-VAB-assistance-wegenhulp.png',
            'alt' => 'VAB Assistance Wegenhulp',
        ),
        array(
            'src' => 'https://garagebarnes.be/wp-content/uploads/2026/09/logo-Touring-Wegenhulp-Assistance.png',
            'alt' => 'Touring Wegenhulp Assistance',
        ),
        array(
            'src' => 'https://garagebarnes.be/wp-content/uploads/2026/09/logo-Cardoen.png',
            'alt' => 'Cardoen',
        ),
        array(
            'src' => 'https://garagebarnes.be/wp-content/uploads/2026/09/logo-politezone-hamme-waasmunster.png',
            'alt' => 'Politiezone Hamme Waasmunster',
        ),
    );

    ob_start(); ?>
    <section class="gb-towing-client-logos" aria-label="Professionele partners van Takeldienst Barnes">
      <div class="gb-shell">
        <div class="gb-towing-client-logos-heading">
          <span class="gb-kicker">Professionele samenwerkingen</span>
          <h2>Wij werken voor o.a.</h2>
        </div>
        <div class="gb-towing-client-logo-grid">
          <?php foreach ($logos as $logo): ?>
            <div class="gb-towing-client-logo-card">
              <img src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    $section = ob_get_clean();

    $marker = '<section class="gb-towing-gallery">';
    if (strpos($output, $marker) !== false) {
        return str_replace($marker, $section . $marker, $output);
    }

    return $output;
}, 30, 4);

add_action('wp_head', function(){
    if (!is_page('takeldienst')) { return; }
    ?>
    <style id="gb-takeldienst-client-logos-css">
      .gb-towing-client-logos{
        background:#f3f4f1;
        padding:72px 0 78px;
        border-top:1px solid #e1e4dd;
        border-bottom:1px solid #e1e4dd;
      }
      .gb-towing-client-logos-heading{
        margin-bottom:34px;
      }
      .gb-towing-client-logos-heading .gb-kicker{
        display:block;
        margin-bottom:9px;
      }
      .gb-towing-client-logos-heading h2{
        margin:0;
        font-size:clamp(34px,4vw,52px);
        line-height:1.05;
        letter-spacing:-.03em;
      }
      .gb-towing-client-logo-grid{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:22px;
      }
      .gb-towing-client-logo-card{
        aspect-ratio:1/1;
        display:flex;
        align-items:center;
        justify-content:center;
        background:#fff;
        border:1px solid #e1e4dd;
        padding:20px;
        box-shadow:0 10px 30px rgba(20,25,18,.05);
      }
      .gb-towing-client-logo-card img{
        display:block;
        width:100%;
        height:100%;
        object-fit:contain;
      }
      @media(max-width:900px){
        .gb-towing-client-logo-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        .gb-towing-client-logos{padding:58px 0 64px}
      }
      @media(max-width:520px){
        .gb-towing-client-logo-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
        .gb-towing-client-logo-card{padding:12px}
        .gb-towing-client-logos-heading{margin-bottom:26px}
      }
    </style>
    <?php
}, 135);
