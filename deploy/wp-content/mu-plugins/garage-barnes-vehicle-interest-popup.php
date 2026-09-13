<?php
/**
 * Garage Barnes - interesseformulier op detailpagina's van tweedehandswagens.
 */
if (!defined('ABSPATH')) { exit; }

function gb_vehicle_interest_popup_nonce() {
    return wp_create_nonce('gb_vehicle_interest_popup');
}

add_action('wp_footer', function () {
    if (!is_singular('gb_vehicle')) { return; }

    $vehicle_id = get_queried_object_id();
    $vehicle_title = get_the_title($vehicle_id);
    ?>
    <div class="gbvi-modal" id="gbvi-modal" aria-hidden="true">
      <div class="gbvi-modal-backdrop" data-gbvi-close></div>
      <div class="gbvi-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="gbvi-title">
        <button type="button" class="gbvi-close" data-gbvi-close aria-label="Sluiten">&times;</button>
        <span class="gbvi-kicker">Interesse in deze wagen</span>
        <h2 id="gbvi-title"><?php echo esc_html($vehicle_title); ?></h2>
        <p class="gbvi-intro">Laat uw gegevens achter. We nemen zo snel mogelijk contact met u op.</p>

        <form id="gbvi-form" novalidate>
          <input type="hidden" name="action" value="gb_vehicle_interest_submit">
          <input type="hidden" name="nonce" value="<?php echo esc_attr(gb_vehicle_interest_popup_nonce()); ?>">
          <input type="hidden" name="vehicle_id" value="<?php echo esc_attr($vehicle_id); ?>">
          <input type="text" name="website" value="" class="gbvi-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

          <div class="gbvi-field">
            <label for="gbvi-name">Naam <span>*</span></label>
            <input id="gbvi-name" type="text" name="name" required autocomplete="name">
          </div>

          <div class="gbvi-contact-grid">
            <div class="gbvi-field">
              <label for="gbvi-email">E-mail</label>
              <input id="gbvi-email" type="email" name="email" autocomplete="email">
            </div>
            <div class="gbvi-field">
              <label for="gbvi-phone">GSM</label>
              <input id="gbvi-phone" type="tel" name="phone" autocomplete="tel">
            </div>
          </div>
          <p class="gbvi-helper">Vul minstens uw e-mailadres of GSM-nummer in.</p>

          <div class="gbvi-field">
            <label for="gbvi-message">Boodschap</label>
            <textarea id="gbvi-message" name="message" rows="4" maxlength="1000" placeholder="Ik ontvang graag meer informatie over deze wagen."></textarea>
          </div>

          <div class="gbvi-status" id="gbvi-status" aria-live="polite"></div>
          <button type="submit" class="gb-button gb-button-green gbvi-submit">Verzend info-vraag</button>
        </form>
      </div>
    </div>

    <style id="gb-vehicle-interest-popup-css">
      .gbvi-modal{position:fixed;inset:0;z-index:999999;display:none;align-items:center;justify-content:center;padding:24px}
      .gbvi-modal.is-open{display:flex}
      .gbvi-modal-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.68);backdrop-filter:blur(2px)}
      .gbvi-modal-dialog{position:relative;z-index:1;width:min(600px,100%);max-height:calc(100vh - 48px);overflow:auto;background:#fff;border-radius:14px;padding:34px;box-shadow:0 24px 70px rgba(0,0,0,.28)}
      .gbvi-close{position:absolute;top:13px;right:15px;width:38px;height:38px;border:0;background:transparent;color:#222;font-size:32px;line-height:1;cursor:pointer}
      .gbvi-kicker{display:block;margin:0 42px 7px 0;color:#469714;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.11em}
      .gbvi-modal-dialog h2{margin:0 42px 8px 0;font-size:30px;line-height:1.1}
      .gbvi-intro{margin:0 0 24px;color:#666;line-height:1.55}
      .gbvi-field{margin-bottom:17px}
      .gbvi-field label{display:block;margin-bottom:7px;font-size:14px;font-weight:700;color:#202020}
      .gbvi-field label span{color:#469714}
      .gbvi-field input,.gbvi-field textarea{display:block;width:100%;margin:0;border:1px solid #cfd5ca;border-radius:7px;background:#fff;color:#202020;font:inherit;padding:12px 13px;box-sizing:border-box;outline:none}
      .gbvi-field input:focus,.gbvi-field textarea:focus{border-color:#5dc01d;box-shadow:0 0 0 3px rgba(93,192,29,.12)}
      .gbvi-contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
      .gbvi-helper{margin:-7px 0 18px;color:#707070;font-size:12px}
      .gbvi-status{display:none;margin:0 0 15px;padding:11px 13px;border-radius:6px;font-size:14px;line-height:1.45}
      .gbvi-status.is-error{display:block;background:#fff0f0;color:#9c2424}
      .gbvi-status.is-success{display:block;background:#edf8e8;color:#2f6f16}
      .gbvi-submit{width:100%;border:0;cursor:pointer}
      .gbvi-submit[disabled]{opacity:.65;cursor:wait}
      .gbvi-hp{position:absolute!important;left:-9999px!important;width:1px!important;height:1px!important;opacity:0!important;pointer-events:none!important}
      body.gbvi-modal-open{overflow:hidden}
      @media(max-width:620px){.gbvi-modal{padding:14px}.gbvi-modal-dialog{padding:27px 20px}.gbvi-modal-dialog h2{font-size:25px}.gbvi-contact-grid{grid-template-columns:1fr;gap:0}}
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
      var modal=document.getElementById('gbvi-modal');
      var form=document.getElementById('gbvi-form');
      var status=document.getElementById('gbvi-status');
      var submit=form ? form.querySelector('.gbvi-submit') : null;
      var trigger=document.querySelector('.gbvd-contact');
      if(!modal || !form || !trigger) return;

      trigger.setAttribute('href','#');
      trigger.setAttribute('role','button');

      function openModal(){
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden','false');
        document.body.classList.add('gbvi-modal-open');
        setTimeout(function(){var name=document.getElementById('gbvi-name'); if(name) name.focus();},50);
      }
      function closeModal(){
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden','true');
        document.body.classList.remove('gbvi-modal-open');
        trigger.focus();
      }

      trigger.addEventListener('click',function(e){e.preventDefault();openModal();});
      modal.querySelectorAll('[data-gbvi-close]').forEach(function(el){el.addEventListener('click',closeModal);});
      document.addEventListener('keydown',function(e){if(e.key==='Escape' && modal.classList.contains('is-open')) closeModal();});

      form.addEventListener('submit',function(e){
        e.preventDefault();
        status.className='gbvi-status';
        status.textContent='';

        var name=form.elements.name.value.trim();
        var email=form.elements.email.value.trim();
        var phone=form.elements.phone.value.trim();
        if(!name){status.className='gbvi-status is-error';status.textContent='Vul uw naam in.';return;}
        if(!email && !phone){status.className='gbvi-status is-error';status.textContent='Vul minstens uw e-mailadres of GSM-nummer in.';return;}
        if(email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){status.className='gbvi-status is-error';status.textContent='Vul een geldig e-mailadres in.';return;}

        submit.disabled=true;
        submit.textContent='Verzenden…';
        var data=new FormData(form);
        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>',{method:'POST',body:data,credentials:'same-origin'})
          .then(function(r){return r.json();})
          .then(function(resp){
            if(resp && resp.success){
              status.className='gbvi-status is-success';
              status.textContent='Bedankt. Uw info-vraag is verzonden.';
              form.reset();
              setTimeout(closeModal,2200);
            }else{
              status.className='gbvi-status is-error';
              status.textContent=(resp && resp.data && resp.data.message) ? resp.data.message : 'Verzenden is niet gelukt. Probeer opnieuw.';
            }
          })
          .catch(function(){status.className='gbvi-status is-error';status.textContent='Verzenden is niet gelukt. Probeer opnieuw.';})
          .finally(function(){submit.disabled=false;submit.textContent='Verzend info-vraag';});
      });
    });
    </script>
    <?php
}, 80);

function gb_vehicle_interest_submit() {
    if (!check_ajax_referer('gb_vehicle_interest_popup', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Uw sessie is verlopen. Vernieuw de pagina en probeer opnieuw.'), 403);
    }

    if (!empty($_POST['website'])) {
        wp_send_json_success();
    }

    $vehicle_id = isset($_POST['vehicle_id']) ? absint($_POST['vehicle_id']) : 0;
    if (!$vehicle_id || get_post_type($vehicle_id) !== 'gb_vehicle') {
        wp_send_json_error(array('message' => 'De gekozen wagen kon niet worden gevonden.'), 400);
    }

    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    if ($name === '') {
        wp_send_json_error(array('message' => 'Vul uw naam in.'), 400);
    }
    if ($email === '' && $phone === '') {
        wp_send_json_error(array('message' => 'Vul minstens uw e-mailadres of GSM-nummer in.'), 400);
    }
    if ($email !== '' && !is_email($email)) {
        wp_send_json_error(array('message' => 'Vul een geldig e-mailadres in.'), 400);
    }

    $vehicle_title = get_the_title($vehicle_id);
    $vehicle_url = get_permalink($vehicle_id);
    $subject = 'Info-vraag tweedehandswagen: ' . $vehicle_title;

    $body = "Nieuwe info-vraag via garagebarnes.be\n\n";
    $body .= "Wagen: " . $vehicle_title . "\n";
    $body .= "Pagina: " . $vehicle_url . "\n\n";
    $body .= "Naam: " . $name . "\n";
    $body .= "E-mail: " . ($email !== '' ? $email : '-') . "\n";
    $body .= "GSM: " . ($phone !== '' ? $phone : '-') . "\n\n";
    $body .= "Boodschap:\n" . ($message !== '' ? $message : 'Geen boodschap ingevuld.') . "\n";

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    if ($email !== '') {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    // wp_mail() loopt via de ingestelde Garage Barnes SMTP-plugin.
    $sent = wp_mail('info@vijt.be', $subject, $body, $headers);

    if (!$sent) {
        wp_send_json_error(array('message' => 'De info-vraag kon niet worden verzonden. Probeer later opnieuw.'), 500);
    }

    wp_send_json_success(array('message' => 'Bedankt. Uw info-vraag is verzonden.'));
}
add_action('wp_ajax_gb_vehicle_interest_submit', 'gb_vehicle_interest_submit');
add_action('wp_ajax_nopriv_gb_vehicle_interest_submit', 'gb_vehicle_interest_submit');
