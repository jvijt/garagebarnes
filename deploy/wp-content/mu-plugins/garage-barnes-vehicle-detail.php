<?php
/**
 * Garage Barnes - redesigned vehicle detail page.
 */
if (!defined('ABSPATH')) { exit; }

add_action('plugins_loaded', function () {
    $options_file = WP_PLUGIN_DIR . '/garage-barnes/vehicles-options.php';
    if (file_exists($options_file)) { require_once $options_file; }

    if (function_exists('gbv2_single_content')) {
        remove_filter('the_content', 'gbv2_single_content', 20);
    }
    add_filter('the_content', 'gb_vehicle_detail_content', 99);
}, 20);

function gb_vehicle_detail_grouped_options($post_id) {
    $stored = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) get_post_meta($post_id, 'gb_options', true)))));
    if (!$stored) { return array(); }

    $groups = array();
    $used = array();
    if (function_exists('gbv_options_catalog')) {
        foreach (gbv_options_catalog() as $group => $catalog_options) {
            $matches = array_values(array_intersect($stored, $catalog_options));
            if ($matches) {
                $groups[$group] = $matches;
                foreach ($matches as $option) { $used[$option] = true; }
            }
        }
    }

    $other = array();
    foreach ($stored as $option) {
        if (!isset($used[$option])) { $other[] = $option; }
    }
    if ($other) { $groups['Overige'] = isset($groups['Overige']) ? array_merge($groups['Overige'], $other) : $other; }
    return $groups;
}

function gb_vehicle_detail_content($content) {
    if (!is_singular('gb_vehicle') || !in_the_loop() || !is_main_query()) { return $content; }

    $id = get_the_ID();
    $make = function_exists('gbv2_term_name') ? gbv2_term_name($id, 'gb_vehicle_make') : '';
    $model = function_exists('gbv2_term_name') ? gbv2_term_name($id, 'gb_vehicle_model') : '';
    $variant = get_post_meta($id, 'gb_variant', true);
    $year = get_post_meta($id, 'gb_year', true);
    $mileage = get_post_meta($id, 'gb_mileage', true);
    $fuel = function_exists('gbv2_term_name') ? gbv2_term_name($id, 'gb_vehicle_fuel') : '';
    $transmission = function_exists('gbv2_term_name') ? gbv2_term_name($id, 'gb_vehicle_transmission') : '';
    $price = function_exists('gbv2_price') ? gbv2_price($id) : '';
    $gallery = array_values(array_filter(array_map('absint', explode(',', (string) get_post_meta($id, 'gb_gallery_ids', true)))));
    $groups = gb_vehicle_detail_grouped_options($id);

    $hero_id = get_post_thumbnail_id($id);
    $all_images = array_values(array_unique(array_filter(array_merge(array($hero_id), $gallery))));

    $specs = array(
        'Bouwjaar' => $year,
        'Kilometerstand' => $mileage !== '' ? number_format_i18n((int) $mileage, 0) . ' km' : '',
        'Brandstof' => $fuel,
        'Transmissie' => $transmission,
        'Vermogen' => get_post_meta($id, 'gb_power_hp', true) !== '' ? get_post_meta($id, 'gb_power_hp', true) . ' pk' : '',
        'Cilinderinhoud' => get_post_meta($id, 'gb_displacement', true) !== '' ? number_format_i18n((int) get_post_meta($id, 'gb_displacement', true), 0) . ' cc' : '',
        'Eerste inschrijving' => get_post_meta($id, 'gb_first_registration', true),
        'Kleur' => get_post_meta($id, 'gb_color', true),
        'CO₂-uitstoot' => get_post_meta($id, 'gb_co2', true) !== '' ? get_post_meta($id, 'gb_co2', true) . ' g/km' : '',
        'Garantie' => get_post_meta($id, 'gb_warranty', true),
    );

    ob_start(); ?>
    <main class="gbvd-page">
      <div class="gb-shell gbvd-breadcrumb"><a href="<?php echo esc_url(home_url('/tweedehands/')); ?>">← Terug naar tweedehandswagens</a></div>
      <section class="gb-shell gbvd-top">
        <div class="gbvd-gallery-wrap">
          <div class="gbvd-main-image">
            <?php if ($hero_id): echo wp_get_attachment_image($hero_id, 'full', false, array('id'=>'gbvd-main-photo','loading'=>'eager')); else: ?><div class="gbvd-no-image">Foto volgt</div><?php endif; ?>
          </div>
          <?php if (count($all_images) > 1): ?>
          <div class="gbvd-thumbs">
            <?php foreach ($all_images as $index => $aid): $full = wp_get_attachment_image_url($aid, 'full'); ?>
              <button type="button" class="gbvd-thumb<?php echo $index === 0 ? ' is-active' : ''; ?>" data-full="<?php echo esc_url($full); ?>"><?php echo wp_get_attachment_image($aid, 'thumbnail'); ?></button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <aside class="gbvd-summary">
          <span class="gbvd-label">Garage Barnes Vehicles</span>
          <h1><?php echo esc_html(trim($make . ' ' . $model)); ?></h1>
          <?php if ($variant): ?><p class="gbvd-variant"><?php echo esc_html($variant); ?></p><?php endif; ?>
          <strong class="gbvd-price"><?php echo esc_html($price); ?></strong>

          <div class="gbvd-keyfacts">
            <?php if ($make): ?><div><span>Merk</span><strong><?php echo esc_html($make); ?></strong></div><?php endif; ?>
            <?php if ($model): ?><div><span>Type</span><strong><?php echo esc_html($model); ?></strong></div><?php endif; ?>
            <?php if ($year): ?><div><span>Bouwjaar</span><strong><?php echo esc_html($year); ?></strong></div><?php endif; ?>
            <?php if ($mileage !== ''): ?><div><span>Kilometerstand</span><strong><?php echo esc_html(number_format_i18n((int) $mileage, 0) . ' km'); ?></strong></div><?php endif; ?>
            <?php if ($fuel): ?><div><span>Brandstof</span><strong><?php echo esc_html($fuel); ?></strong></div><?php endif; ?>
            <?php if ($transmission): ?><div><span>Transmissie</span><strong><?php echo esc_html($transmission); ?></strong></div><?php endif; ?>
          </div>

          <a class="gb-button gb-button-green gbvd-contact" href="<?php echo esc_url(home_url('/contact/?wagen=' . rawurlencode(get_the_title($id)))); ?>">Interesse in deze wagen</a>
          <a class="gbvd-phone" href="tel:+3252570557">Bel +32 52 57 05 57</a>
        </aside>
      </section>

      <section class="gb-shell gbvd-section">
        <div class="gbvd-card">
          <h2>Technische gegevens</h2>
          <div class="gbvd-spec-grid">
            <?php foreach ($specs as $label => $value): if ($value === '') continue; ?>
              <div><span><?php echo esc_html($label); ?></span><strong><?php echo esc_html($value); ?></strong></div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <?php if ($groups): ?>
      <section class="gb-shell gbvd-section">
        <div class="gbvd-card">
          <h2>Opties &amp; uitrusting</h2>
          <div class="gbvd-option-groups">
            <?php foreach ($groups as $group => $options): ?>
              <div class="gbvd-option-group"><h3><?php echo esc_html($group); ?></h3><ul>
                <?php foreach ($options as $option): ?><li><?php echo esc_html($option); ?></li><?php endforeach; ?>
              </ul></div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
      <?php endif; ?>

      <?php if (trim(wp_strip_all_tags($content)) !== ''): ?>
      <section class="gb-shell gbvd-section"><div class="gbvd-card gbvd-description"><h2>Beschrijving</h2><?php echo wpautop(wp_kses_post($content)); ?></div></section>
      <?php endif; ?>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
      var main=document.getElementById('gbvd-main-photo');
      if(!main) return;
      document.querySelectorAll('.gbvd-thumb').forEach(function(btn){btn.addEventListener('click',function(){
        main.src=btn.getAttribute('data-full');
        main.removeAttribute('srcset');
        document.querySelectorAll('.gbvd-thumb').forEach(function(x){x.classList.remove('is-active')});
        btn.classList.add('is-active');
      });});
    });
    </script>
    <?php return ob_get_clean();
}

add_action('wp_head', function () {
    if (!is_singular('gb_vehicle')) { return; }
    ?>
    <style id="gb-vehicle-detail-css">
    body.single-gb_vehicle #right-sidebar,body.single-gb_vehicle .widget-area,body.single-gb_vehicle aside.sidebar-container{display:none!important}
    body.single-gb_vehicle #primary,body.single-gb_vehicle .content-area{width:100%!important;max-width:none!important;float:none!important;margin:0!important;padding:0!important}
    body.single-gb_vehicle #content-wrap{width:100%!important;max-width:none!important;padding:0!important}
    body.single-gb_vehicle .entry-header,body.single-gb_vehicle .single-post-title,body.single-gb_vehicle .post-tags,body.single-gb_vehicle .meta{display:none!important}
    body.single-gb_vehicle{background:#f1f3f5}.gbvd-page{padding:34px 0 90px;color:#202020}.gbvd-breadcrumb{margin-bottom:22px}.gbvd-breadcrumb a{font-weight:700;font-size:14px}.gbvd-top{display:grid;grid-template-columns:minmax(0,1.65fr) minmax(330px,.75fr);gap:28px;align-items:start}.gbvd-gallery-wrap,.gbvd-summary,.gbvd-card{background:#fff;border:1px solid #dfe3dc;border-radius:14px;overflow:hidden}.gbvd-main-image{aspect-ratio:4/3;background:#e9ecef;display:flex;align-items:center;justify-content:center}.gbvd-main-image img{width:100%;height:100%;object-fit:contain;display:block}.gbvd-no-image{color:#777}.gbvd-thumbs{display:flex;gap:8px;padding:12px;overflow-x:auto;border-top:1px solid #eceeea}.gbvd-thumb{width:92px;height:68px;flex:0 0 92px;padding:0;border:2px solid transparent;border-radius:7px;overflow:hidden;background:#fff;cursor:pointer}.gbvd-thumb.is-active{border-color:#5dc01d}.gbvd-thumb img{width:100%;height:100%;object-fit:cover}.gbvd-summary{position:sticky;top:24px;padding:30px}.gbvd-label{display:block;margin-bottom:8px;color:#469714;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.12em}.gbvd-summary h1{margin:0;font-size:34px;line-height:1.05}.gbvd-variant{margin:8px 0 22px;color:#666;font-size:16px}.gbvd-price{display:block;margin:0 0 24px;font-size:34px}.gbvd-keyfacts{display:grid;grid-template-columns:1fr 1fr;border-top:1px solid #e5e8e2;border-left:1px solid #e5e8e2;margin-bottom:24px}.gbvd-keyfacts>div{padding:15px;border-right:1px solid #e5e8e2;border-bottom:1px solid #e5e8e2}.gbvd-keyfacts span,.gbvd-spec-grid span{display:block;margin-bottom:4px;color:#777;font-size:11px;text-transform:uppercase;letter-spacing:.06em}.gbvd-keyfacts strong{font-size:15px}.gbvd-contact{width:100%;margin-bottom:12px}.gbvd-phone{display:block;text-align:center;font-weight:800}.gbvd-section{margin-top:28px}.gbvd-card{padding:32px}.gbvd-card h2{margin:0 0 26px;font-size:28px}.gbvd-spec-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));border-top:1px solid #e5e8e2;border-left:1px solid #e5e8e2}.gbvd-spec-grid>div{padding:18px 20px;border-right:1px solid #e5e8e2;border-bottom:1px solid #e5e8e2}.gbvd-spec-grid strong{font-size:15px}.gbvd-option-groups{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}.gbvd-option-group{padding:22px;background:#f6f7f5;border-radius:8px}.gbvd-option-group h3{margin:0 0 14px;font-size:18px}.gbvd-option-group ul{list-style:none;margin:0;padding:0}.gbvd-option-group li{position:relative;padding:7px 0 7px 24px;border-bottom:1px solid #e5e8e2}.gbvd-option-group li:last-child{border-bottom:0}.gbvd-option-group li:before{content:'✓';position:absolute;left:0;color:#469714;font-weight:900}.gbvd-description{line-height:1.75}.gbvd-description p:last-child{margin-bottom:0}
    @media(max-width:980px){.gbvd-top{grid-template-columns:1fr}.gbvd-summary{position:static}.gbvd-spec-grid,.gbvd-option-groups{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:620px){.gbvd-page{padding-top:20px}.gbvd-top{gap:16px}.gbvd-summary,.gbvd-card{padding:22px}.gbvd-summary h1{font-size:29px}.gbvd-price{font-size:30px}.gbvd-keyfacts,.gbvd-spec-grid,.gbvd-option-groups{grid-template-columns:1fr}.gbvd-main-image{aspect-ratio:1/1}.gbvd-thumb{width:78px;height:58px;flex-basis:78px}}
    </style>
    <?php
}, 50);
