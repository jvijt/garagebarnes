<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Garage Barnes Vehicles
 * Structured vehicle inventory for WordPress admin and frontend.
 */

function gb_register_vehicle_content() {
    register_post_type('gb_vehicle', array(
        'labels' => array(
            'name' => 'Garage Barnes Vehicles',
            'singular_name' => 'Wagen',
            'add_new' => 'Nieuwe wagen',
            'add_new_item' => 'Nieuwe wagen toevoegen',
            'edit_item' => 'Wagen bewerken',
            'new_item' => 'Nieuwe wagen',
            'view_item' => 'Wagen bekijken',
            'search_items' => 'Wagens zoeken',
            'not_found' => 'Geen wagens gevonden',
            'menu_name' => 'Barnes Vehicles',
        ),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-car',
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'wagen', 'with_front' => false),
        'has_archive' => false,
        'menu_position' => 22,
    ));

    $taxonomies = array(
        'gb_vehicle_make' => array('Merk', 'Merken'),
        'gb_vehicle_model' => array('Model', 'Modellen'),
        'gb_vehicle_fuel' => array('Brandstof', 'Brandstoffen'),
        'gb_vehicle_transmission' => array('Transmissie', 'Transmissies'),
        'gb_vehicle_status' => array('Status', 'Statussen'),
    );
    foreach ($taxonomies as $taxonomy => $labels) {
        register_taxonomy($taxonomy, 'gb_vehicle', array(
            'labels' => array('singular_name' => $labels[0], 'name' => $labels[1]),
            'public' => false,
            'show_ui' => false,
            'show_in_rest' => true,
            'hierarchical' => false,
            'rewrite' => false,
        ));
    }
}
add_action('init', 'gb_register_vehicle_content', 5);

function gb_seed_vehicle_terms() {
    if (get_option('gb_vehicle_terms_seeded_v1')) return;

    $makes = array('Abarth','Alfa Romeo','Alpine','Audi','BMW','BYD','Citroën','Cupra','Dacia','DS Automobiles','Fiat','Ford','Honda','Hyundai','Jaguar','Jeep','Kia','Land Rover','Lexus','Mazda','Mercedes-Benz','MG','MINI','Mitsubishi','Nissan','Opel','Peugeot','Polestar','Porsche','Renault','SEAT','Škoda','Smart','Subaru','Suzuki','Tesla','Toyota','Volkswagen','Volvo');
    $fuels = array('Benzine','Diesel','Hybride','Plug-in hybride','Elektrisch','LPG','CNG');
    $transmissions = array('Manueel','Automaat','Halfautomaat');
    $statuses = array('Beschikbaar','Gereserveerd','Verkocht');

    foreach ($makes as $term) if (!term_exists($term, 'gb_vehicle_make')) wp_insert_term($term, 'gb_vehicle_make');
    foreach ($fuels as $term) if (!term_exists($term, 'gb_vehicle_fuel')) wp_insert_term($term, 'gb_vehicle_fuel');
    foreach ($transmissions as $term) if (!term_exists($term, 'gb_vehicle_transmission')) wp_insert_term($term, 'gb_vehicle_transmission');
    foreach ($statuses as $term) if (!term_exists($term, 'gb_vehicle_status')) wp_insert_term($term, 'gb_vehicle_status');

    update_option('gb_vehicle_terms_seeded_v1', 1, false);
}
add_action('init', 'gb_seed_vehicle_terms', 20);

function gb_vehicle_admin_assets($hook) {
    global $post_type;
    if ($post_type !== 'gb_vehicle') return;
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'gb_vehicle_admin_assets');

function gb_vehicle_add_meta_boxes() {
    add_meta_box('gb_vehicle_details', 'Voertuiggegevens', 'gb_vehicle_details_box', 'gb_vehicle', 'normal', 'high');
    add_meta_box('gb_vehicle_gallery', 'Fotogalerij', 'gb_vehicle_gallery_box', 'gb_vehicle', 'normal', 'default');
}
add_action('add_meta_boxes', 'gb_vehicle_add_meta_boxes');

function gb_vehicle_term_name($post_id, $taxonomy) {
    $terms = wp_get_post_terms($post_id, $taxonomy);
    return (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';
}

function gb_vehicle_terms_for_datalist($taxonomy) {
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    return is_wp_error($terms) ? array() : $terms;
}

function gb_vehicle_details_box($post) {
    wp_nonce_field('gb_vehicle_save', 'gb_vehicle_nonce');
    $make = gb_vehicle_term_name($post->ID, 'gb_vehicle_make');
    $model = gb_vehicle_term_name($post->ID, 'gb_vehicle_model');
    $fuel = gb_vehicle_term_name($post->ID, 'gb_vehicle_fuel');
    $transmission = gb_vehicle_term_name($post->ID, 'gb_vehicle_transmission');
    $status = gb_vehicle_term_name($post->ID, 'gb_vehicle_status');
    if (!$status) $status = 'Beschikbaar';

    $makes = gb_vehicle_terms_for_datalist('gb_vehicle_make');
    $models = gb_vehicle_terms_for_datalist('gb_vehicle_model');
    $fuels = gb_vehicle_terms_for_datalist('gb_vehicle_fuel');
    $transmissions = gb_vehicle_terms_for_datalist('gb_vehicle_transmission');
    $statuses = gb_vehicle_terms_for_datalist('gb_vehicle_status');

    $fields = array(
        'gb_variant' => 'Uitvoering / versie',
        'gb_stock_ref' => 'Interne referentie / stocknummer',
        'gb_first_registration' => 'Eerste inschrijving',
        'gb_year' => 'Bouwjaar',
        'gb_mileage' => 'Kilometerstand',
        'gb_price' => 'Verkoopprijs (€)',
        'gb_power_kw' => 'Vermogen (kW)',
        'gb_power_hp' => 'Vermogen (pk)',
        'gb_displacement' => 'Cilinderinhoud (cc)',
        'gb_color' => 'Kleur',
        'gb_co2' => 'CO₂ (g/km)',
        'gb_doors' => 'Aantal deuren',
        'gb_seats' => 'Aantal zitplaatsen',
        'gb_warranty' => 'Garantie',
    );
    ?>
    <style>
      .gbv-grid{display:grid;grid-template-columns:repeat(3,minmax(180px,1fr));gap:16px}.gbv-field label{display:block;font-weight:700;margin-bottom:6px}.gbv-field input,.gbv-field select,.gbv-field textarea{width:100%}.gbv-wide{grid-column:1/-1}.gbv-note{color:#646970;margin:6px 0 0}.gbv-options{min-height:150px}@media(max-width:1100px){.gbv-grid{grid-template-columns:repeat(2,1fr)}}
    </style>
    <div class="gbv-grid">
      <div class="gbv-field"><label for="gb_make">Merk</label><input id="gb_make" name="gb_make" list="gb_make_list" value="<?php echo esc_attr($make); ?>" autocomplete="off" placeholder="Begin te typen…"><datalist id="gb_make_list"><?php foreach($makes as $term): ?><option value="<?php echo esc_attr($term->name); ?>"></option><?php endforeach; ?></datalist></div>
      <div class="gbv-field"><label for="gb_model">Model / type</label><input id="gb_model" name="gb_model" list="gb_model_list" value="<?php echo esc_attr($model); ?>" autocomplete="off" placeholder="Begin te typen…"><datalist id="gb_model_list"></datalist><p class="gbv-note">Bestaande modellen van het gekozen merk verschijnen automatisch.</p></div>
      <div class="gbv-field"><label for="gb_variant">Uitvoering / versie</label><input id="gb_variant" name="gb_variant" value="<?php echo esc_attr(get_post_meta($post->ID,'gb_variant',true)); ?>" placeholder="bv. 1.5 TSI Style DSG"></div>

      <div class="gbv-field"><label for="gb_fuel">Brandstof</label><select id="gb_fuel" name="gb_fuel"><option value="">— Kies —</option><?php foreach($fuels as $term): ?><option <?php selected($fuel,$term->name); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></div>
      <div class="gbv-field"><label for="gb_transmission">Transmissie</label><select id="gb_transmission" name="gb_transmission"><option value="">— Kies —</option><?php foreach($transmissions as $term): ?><option <?php selected($transmission,$term->name); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></div>
      <div class="gbv-field"><label for="gb_status">Status</label><select id="gb_status" name="gb_status"><?php foreach($statuses as $term): ?><option <?php selected($status,$term->name); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></div>

      <?php foreach($fields as $key=>$label): $type = ($key==='gb_first_registration') ? 'date' : (($key==='gb_year'||$key==='gb_mileage'||$key==='gb_price'||$key==='gb_power_kw'||$key==='gb_power_hp'||$key==='gb_displacement'||$key==='gb_co2'||$key==='gb_doors'||$key==='gb_seats') ? 'number' : 'text'); ?>
      <div class="gbv-field"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label><input type="<?php echo esc_attr($type); ?>" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_post_meta($post->ID,$key,true)); ?>"<?php echo $key==='gb_price' ? ' step="0.01"' : ''; ?>></div>
      <?php endforeach; ?>

      <div class="gbv-field"><label for="gb_vat">BTW-status</label><select id="gb_vat" name="gb_vat"><option value="marge" <?php selected(get_post_meta($post->ID,'gb_vat',true),'marge'); ?>>Margewagen / geen BTW aftrekbaar</option><option value="btw" <?php selected(get_post_meta($post->ID,'gb_vat',true),'btw'); ?>>BTW aftrekbaar</option></select></div>
      <div class="gbv-field gbv-wide"><label for="gb_options">Opties</label><textarea class="gbv-options" id="gb_options" name="gb_options" placeholder="Eén optie per regel"><?php echo esc_textarea(get_post_meta($post->ID,'gb_options',true)); ?></textarea><p class="gbv-note">Gebruik één optie per regel. Dat kunnen we later ook omzetten naar gestandaardiseerde filters indien gewenst.</p></div>
    </div>
    <?php
    $model_data = array();
    foreach ($models as $term) {
        $model_data[] = array('name'=>$term->name, 'make'=>(string)get_term_meta($term->term_id,'gb_vehicle_make_slug',true));
    }
    ?>
    <script>
    (function(){
      const models=<?php echo wp_json_encode($model_data); ?>;
      const make=document.getElementById('gb_make'), list=document.getElementById('gb_model_list');
      function slugify(s){return s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');}
      function fill(){const slug=slugify(make.value||''); list.innerHTML=''; models.filter(x=>!slug||!x.make||x.make===slug).forEach(x=>{const o=document.createElement('option');o.value=x.name;list.appendChild(o);});}
      make.addEventListener('input',fill); fill();
    })();
    </script>
    <?php
}

function gb_vehicle_gallery_box($post) {
    $ids = array_filter(array_map('absint', explode(',', (string)get_post_meta($post->ID,'gb_gallery_ids',true))));
    ?>
    <p><strong>Hoofdfoto:</strong> gebruik rechts het WordPress-vak <em>Uitgelichte afbeelding</em>.</p>
    <input type="hidden" id="gb_gallery_ids" name="gb_gallery_ids" value="<?php echo esc_attr(implode(',',$ids)); ?>">
    <div id="gb_gallery_preview" style="display:flex;gap:10px;flex-wrap:wrap;margin:12px 0">
      <?php foreach($ids as $id): echo wp_get_attachment_image($id,'thumbnail',false,array('style'=>'width:100px;height:75px;object-fit:cover')); endforeach; ?>
    </div>
    <button type="button" class="button button-secondary" id="gb_gallery_choose">Foto's kiezen / wijzigen</button>
    <button type="button" class="button" id="gb_gallery_clear">Galerij leegmaken</button>
    <script>
    jQuery(function($){let frame; $('#gb_gallery_choose').on('click',function(e){e.preventDefault(); if(frame){frame.open();return;} frame=wp.media({title:'Selecteer voertuigfoto’s',button:{text:'Gebruik deze foto’s'},multiple:true}); frame.on('select',function(){const a=frame.state().get('selection').toJSON(); $('#gb_gallery_ids').val(a.map(x=>x.id).join(',')); $('#gb_gallery_preview').html(a.map(x=>'<img src="'+(x.sizes&&x.sizes.thumbnail?x.sizes.thumbnail.url:x.url)+'" style="width:100px;height:75px;object-fit:cover">').join(''));}); frame.open();}); $('#gb_gallery_clear').on('click',function(){ $('#gb_gallery_ids').val(''); $('#gb_gallery_preview').empty();});});
    </script>
    <?php
}

function gb_vehicle_assign_single_term($post_id, $taxonomy, $name) {
    $name = trim(wp_strip_all_tags((string)$name));
    if ($name === '') { wp_set_object_terms($post_id, array(), $taxonomy, false); return 0; }
    $exists = term_exists($name, $taxonomy);
    if (!$exists) $exists = wp_insert_term($name, $taxonomy);
    if (is_wp_error($exists)) return 0;
    $term_id = is_array($exists) ? (int)$exists['term_id'] : (int)$exists;
    wp_set_object_terms($post_id, array($term_id), $taxonomy, false);
    return $term_id;
}

function gb_vehicle_save($post_id) {
    if (!isset($_POST['gb_vehicle_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gb_vehicle_nonce'])), 'gb_vehicle_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'gb_vehicle' || !current_user_can('edit_post',$post_id)) return;

    $make_id = gb_vehicle_assign_single_term($post_id,'gb_vehicle_make',$_POST['gb_make'] ?? '');
    $model_id = gb_vehicle_assign_single_term($post_id,'gb_vehicle_model',$_POST['gb_model'] ?? '');
    gb_vehicle_assign_single_term($post_id,'gb_vehicle_fuel',$_POST['gb_fuel'] ?? '');
    gb_vehicle_assign_single_term($post_id,'gb_vehicle_transmission',$_POST['gb_transmission'] ?? '');
    gb_vehicle_assign_single_term($post_id,'gb_vehicle_status',$_POST['gb_status'] ?? 'Beschikbaar');

    if ($model_id && $make_id) {
        $make = get_term($make_id,'gb_vehicle_make');
        if ($make && !is_wp_error($make)) update_term_meta($model_id,'gb_vehicle_make_slug',$make->slug);
    }

    $text_fields = array('gb_variant','gb_stock_ref','gb_first_registration','gb_color','gb_warranty','gb_vat');
    $number_fields = array('gb_year','gb_mileage','gb_price','gb_power_kw','gb_power_hp','gb_displacement','gb_co2','gb_doors','gb_seats');
    foreach($text_fields as $key) update_post_meta($post_id,$key,sanitize_text_field(wp_unslash($_POST[$key] ?? '')));
    foreach($number_fields as $key) update_post_meta($post_id,$key,sanitize_text_field(wp_unslash($_POST[$key] ?? '')));
    update_post_meta($post_id,'gb_options',sanitize_textarea_field(wp_unslash($_POST['gb_options'] ?? '')));
    $gallery = implode(',', array_filter(array_map('absint', explode(',', sanitize_text_field(wp_unslash($_POST['gb_gallery_ids'] ?? ''))))));
    update_post_meta($post_id,'gb_gallery_ids',$gallery);

    $make = gb_vehicle_term_name($post_id,'gb_vehicle_make');
    $model = gb_vehicle_term_name($post_id,'gb_vehicle_model');
    $variant = get_post_meta($post_id,'gb_variant',true);
    if ($make && $model) {
        $generated = trim($make.' '.$model.' '.$variant);
        remove_action('save_post_gb_vehicle','gb_vehicle_save');
        wp_update_post(array('ID'=>$post_id,'post_title'=>$generated));
        add_action('save_post_gb_vehicle','gb_vehicle_save');
    }
}
add_action('save_post_gb_vehicle','gb_vehicle_save');

function gb_vehicle_admin_columns($columns) {
    return array(
        'cb'=>$columns['cb'],
        'title'=>'Wagen',
        'gb_status'=>'Status',
        'gb_price'=>'Prijs',
        'gb_mileage'=>'Km-stand',
        'gb_stock'=>'Stocknr.',
        'date'=>$columns['date'],
    );
}
add_filter('manage_gb_vehicle_posts_columns','gb_vehicle_admin_columns');
function gb_vehicle_admin_column_content($column,$post_id) {
    if ($column==='gb_status') echo esc_html(gb_vehicle_term_name($post_id,'gb_vehicle_status'));
    if ($column==='gb_price') { $v=get_post_meta($post_id,'gb_price',true); echo $v!=='' ? esc_html('€ '.number_format_i18n((float)$v,0)) : '—'; }
    if ($column==='gb_mileage') { $v=get_post_meta($post_id,'gb_mileage',true); echo $v!=='' ? esc_html(number_format_i18n((int)$v,0).' km') : '—'; }
    if ($column==='gb_stock') echo esc_html(get_post_meta($post_id,'gb_stock_ref',true));
}
add_action('manage_gb_vehicle_posts_custom_column','gb_vehicle_admin_column_content',10,2);

function gb_vehicle_price($post_id) {
    $price = get_post_meta($post_id,'gb_price',true);
    return $price!=='' ? '€ '.number_format_i18n((float)$price,0) : 'Prijs op aanvraag';
}

function gb_vehicle_card($post_id) {
    $make=gb_vehicle_term_name($post_id,'gb_vehicle_make'); $model=gb_vehicle_term_name($post_id,'gb_vehicle_model');
    $fuel=gb_vehicle_term_name($post_id,'gb_vehicle_fuel'); $trans=gb_vehicle_term_name($post_id,'gb_vehicle_transmission');
    $year=get_post_meta($post_id,'gb_year',true); $km=get_post_meta($post_id,'gb_mileage',true); $variant=get_post_meta($post_id,'gb_variant',true);
    $image=get_the_post_thumbnail($post_id,'large',array('class'=>'gbv-card-image','loading'=>'lazy'));
    if(!$image) $image='<div class="gbv-card-image gbv-card-image-empty">Foto volgt</div>';
    ob_start(); ?>
    <article class="gbv-card"><a class="gbv-card-media" href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo $image; ?></a><div class="gbv-card-body"><span class="gbv-card-make"><?php echo esc_html($make); ?></span><h3><a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(trim($model.' '.$variant)); ?></a></h3><div class="gbv-card-meta"><?php if($year): ?><span><?php echo esc_html($year); ?></span><?php endif; ?><?php if($km!==''): ?><span><?php echo esc_html(number_format_i18n((int)$km,0).' km'); ?></span><?php endif; ?><?php if($fuel): ?><span><?php echo esc_html($fuel); ?></span><?php endif; ?><?php if($trans): ?><span><?php echo esc_html($trans); ?></span><?php endif; ?></div><strong class="gbv-price"><?php echo esc_html(gb_vehicle_price($post_id)); ?></strong><a class="gbv-more" href="<?php echo esc_url(get_permalink($post_id)); ?>">Bekijk wagen →</a></div></article>
    <?php return ob_get_clean();
}

function gb_render_vehicle_archive() {
    $tax_query=array('relation'=>'AND');
    foreach(array('merk'=>'gb_vehicle_make','brandstof'=>'gb_vehicle_fuel','transmissie'=>'gb_vehicle_transmission') as $key=>$tax){ if(!empty($_GET[$key])) $tax_query[]=array('taxonomy'=>$tax,'field'=>'slug','terms'=>sanitize_title(wp_unslash($_GET[$key]))); }
    $meta_query=array(); if(!empty($_GET['max_prijs'])) $meta_query[]=array('key'=>'gb_price','value'=>(float)$_GET['max_prijs'],'type'=>'NUMERIC','compare'=>'<=');
    $args=array('post_type'=>'gb_vehicle','post_status'=>'publish','posts_per_page'=>24,'orderby'=>'date','order'=>'DESC');
    if(count($tax_query)>1)$args['tax_query']=$tax_query; if($meta_query)$args['meta_query']=$meta_query;
    $q=new WP_Query($args);
    $makes=get_terms(array('taxonomy'=>'gb_vehicle_make','hide_empty'=>true)); $fuels=get_terms(array('taxonomy'=>'gb_vehicle_fuel','hide_empty'=>true)); $trans=get_terms(array('taxonomy'=>'gb_vehicle_transmission','hide_empty'=>true));
    ob_start(); ?>
    <form class="gbv-filters" method="get"><select name="merk"><option value="">Alle merken</option><?php if(!is_wp_error($makes)) foreach($makes as $t): ?><option value="<?php echo esc_attr($t->slug); ?>" <?php selected($_GET['merk']??'',$t->slug); ?>><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select><select name="brandstof"><option value="">Alle brandstoffen</option><?php if(!is_wp_error($fuels)) foreach($fuels as $t): ?><option value="<?php echo esc_attr($t->slug); ?>" <?php selected($_GET['brandstof']??'',$t->slug); ?>><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select><select name="transmissie"><option value="">Alle transmissies</option><?php if(!is_wp_error($trans)) foreach($trans as $t): ?><option value="<?php echo esc_attr($t->slug); ?>" <?php selected($_GET['transmissie']??'',$t->slug); ?>><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select><input type="number" name="max_prijs" min="0" step="500" placeholder="Max. prijs" value="<?php echo esc_attr($_GET['max_prijs']??''); ?>"><button class="gb-button gb-button-dark" type="submit">Filter</button></form>
    <?php if($q->have_posts()): ?><div class="gbv-grid-front"><?php while($q->have_posts()):$q->the_post(); echo gb_vehicle_card(get_the_ID()); endwhile; ?></div><?php else: ?><div class="gb-empty-state"><span class="gb-kicker">Garage Barnes Vehicles</span><h2>Nog geen wagens gevonden.</h2><p>Pas eventueel de filters aan of contacteer ons voor het actuele aanbod.</p></div><?php endif; wp_reset_postdata(); return ob_get_clean();
}

function gb_vehicle_single_content($content) {
    if (!is_singular('gb_vehicle') || !in_the_loop() || !is_main_query()) return $content;
    $id=get_the_ID(); $make=gb_vehicle_term_name($id,'gb_vehicle_make'); $model=gb_vehicle_term_name($id,'gb_vehicle_model'); $variant=get_post_meta($id,'gb_variant',true);
    $gallery=array_filter(array_map('absint',explode(',',(string)get_post_meta($id,'gb_gallery_ids',true))));
    $specs=array(
      'Eerste inschrijving'=>get_post_meta($id,'gb_first_registration',true), 'Bouwjaar'=>get_post_meta($id,'gb_year',true), 'Kilometerstand'=>get_post_meta($id,'gb_mileage',true)!==''?number_format_i18n((int)get_post_meta($id,'gb_mileage',true),0).' km':'', 'Brandstof'=>gb_vehicle_term_name($id,'gb_vehicle_fuel'), 'Transmissie'=>gb_vehicle_term_name($id,'gb_vehicle_transmission'), 'Vermogen'=>get_post_meta($id,'gb_power_hp',true)!==''?get_post_meta($id,'gb_power_hp',true).' pk':'', 'Kleur'=>get_post_meta($id,'gb_color',true), 'CO₂'=>get_post_meta($id,'gb_co2',true)!==''?get_post_meta($id,'gb_co2',true).' g/km':'', 'Garantie'=>get_post_meta($id,'gb_warranty',true)
    );
    $options=array_filter(array_map('trim',preg_split('/\R/',(string)get_post_meta($id,'gb_options',true))));
    ob_start(); ?><main class="gbv-single"><section class="gbv-single-head"><div class="gb-shell"><span class="gb-kicker"><?php echo esc_html($make); ?></span><h1><?php echo esc_html(trim($model.' '.$variant)); ?></h1><strong class="gbv-single-price"><?php echo esc_html(gb_vehicle_price($id)); ?></strong></div></section><section class="gb-shell gbv-single-layout"><div><div class="gbv-main-image"><?php echo get_the_post_thumbnail($id,'full'); ?></div><?php if($gallery): ?><div class="gbv-gallery"><?php foreach($gallery as $aid) echo wp_get_attachment_image($aid,'large',false,array('loading'=>'lazy')); ?></div><?php endif; ?><div class="gbv-description"><?php echo wpautop(wp_kses_post($content)); ?></div><?php if($options): ?><h2>Opties & uitrusting</h2><div class="gbv-options-list"><?php foreach($options as $o): ?><span><?php echo esc_html($o); ?></span><?php endforeach; ?></div><?php endif; ?></div><aside><div class="gbv-spec-card"><h2>Specificaties</h2><?php foreach($specs as $label=>$value) if($value!==''): ?><div><span><?php echo esc_html($label); ?></span><strong><?php echo esc_html($value); ?></strong></div><?php endforeach; ?><a class="gb-button gb-button-green" href="<?php echo esc_url(home_url('/contact/?wagen='.rawurlencode(get_the_title($id)))); ?>">Interesse in deze wagen</a></div></aside></section></main><?php return ob_get_clean();
}
add_filter('the_content','gb_vehicle_single_content',20);
