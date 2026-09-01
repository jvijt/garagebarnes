<?php
/**
 * Garage Barnes vehicle card details.
 * Adds battery capacity for EVs and enriches overview cards with year, fuel and engine/battery data.
 */
if (!defined('ABSPATH')) { exit; }

add_action('add_meta_boxes_gb_vehicle', function() {
    add_meta_box('gb_vehicle_battery', 'Elektrische wagen', function($post) {
        wp_nonce_field('gb_vehicle_battery_save', 'gb_vehicle_battery_nonce');
        $value = get_post_meta($post->ID, 'gb_battery_kwh', true);
        echo '<p><label for="gb_battery_kwh"><strong>Batterijcapaciteit (kWh)</strong></label></p>';
        echo '<input type="number" min="0" step="0.1" id="gb_battery_kwh" name="gb_battery_kwh" value="' . esc_attr($value) . '" style="width:100%" placeholder="bv. 64">';
        echo '<p class="description">Invullen voor elektrische wagens. Op de overzichtskaart wordt dit getoond in plaats van cilinderinhoud.</p>';
    }, 'gb_vehicle', 'side', 'default');
});

add_action('save_post_gb_vehicle', function($post_id) {
    if (!isset($_POST['gb_vehicle_battery_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gb_vehicle_battery_nonce'])), 'gb_vehicle_battery_save')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!current_user_can('edit_post', $post_id)) { return; }
    update_post_meta($post_id, 'gb_battery_kwh', sanitize_text_field(wp_unslash($_POST['gb_battery_kwh'] ?? '')));
});

add_action('wp_footer', function() {
    if (is_admin()) { return; }
    $ids = get_posts(array('post_type'=>'gb_vehicle','post_status'=>'publish','posts_per_page'=>-1,'fields'=>'ids'));
    if (!$ids) { return; }
    $data = array();
    foreach ($ids as $id) {
        $fuel = function_exists('gbv2_term_name') ? gbv2_term_name($id, 'gb_vehicle_fuel') : '';
        $data[get_permalink($id)] = array(
            'year' => (string) get_post_meta($id, 'gb_year', true),
            'fuel' => $fuel,
            'displacement' => (string) get_post_meta($id, 'gb_displacement', true),
            'battery' => (string) get_post_meta($id, 'gb_battery_kwh', true),
        );
    }
    ?>
    <script>
    (function(){
      const vehicles=<?php echo wp_json_encode($data); ?>;
      document.querySelectorAll('.gbv-card').forEach(function(card){
        const link=card.querySelector('.gbv-card-media');
        if(!link) return;
        const key=Object.keys(vehicles).find(function(url){return link.href.replace(/\/$/,'')===url.replace(/\/$/,'');});
        if(!key) return;
        const v=vehicles[key], meta=card.querySelector('.gbv-card-meta');
        if(!meta) return;
        meta.innerHTML='';
        function add(label,value){if(!value)return;const s=document.createElement('span');s.innerHTML='<b>'+label+'</b>'+value;meta.appendChild(s);}
        add('Bouwjaar',v.year);
        add('Brandstof',v.fuel);
        if((v.fuel||'').toLowerCase()==='elektrisch') add('Batterij',v.battery ? v.battery+' kWh' : '—');
        else add('Cilinderinhoud',v.displacement ? Number(v.displacement).toLocaleString('nl-BE')+' cc' : '—');
      });
    })();
    </script>
    <?php
}, 50);
