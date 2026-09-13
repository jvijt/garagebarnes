<?php
/**
 * Garage Barnes - Maak afspraak page + appointment request form.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $page = get_page_by_path('maak-afspraak', OBJECT, 'page');
    if (!$page) {
        wp_insert_post(array(
            'post_title'   => 'Maak afspraak',
            'post_name'    => 'maak-afspraak',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ));
    }
}, 50);

function gb_appointment_recaptcha_settings() {
    $saved = get_option('garage_barnes_smtp_settings', array());
    return array(
        'site_key'  => isset($saved['recaptcha_site_key']) ? trim((string) $saved['recaptcha_site_key']) : '',
        'secret'    => isset($saved['recaptcha_secret_key']) ? trim((string) $saved['recaptcha_secret_key']) : '',
        'threshold' => isset($saved['recaptcha_threshold']) ? (float) $saved['recaptcha_threshold'] : 0.5,
    );
}

function gb_appointment_verify_recaptcha($token) {
    $settings = gb_appointment_recaptcha_settings();

    if ($settings['site_key'] === '' || $settings['secret'] === '') {
        return new WP_Error('recaptcha_not_configured', 'reCAPTCHA is nog niet volledig geconfigureerd.');
    }
    if ($token === '') {
        return new WP_Error('recaptcha_missing', 'Spamcontrole ontbreekt. Vernieuw de pagina en probeer opnieuw.');
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
        'timeout' => 10,
        'body' => array(
            'secret'   => $settings['secret'],
            'response' => $token,
            'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '',
        ),
    ));

    if (is_wp_error($response)) {
        return new WP_Error('recaptcha_request_failed', 'Spamcontrole kon niet worden uitgevoerd. Probeer opnieuw.');
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($data) || empty($data['success'])) {
        return new WP_Error('recaptcha_failed', 'Spamcontrole niet geslaagd. Probeer opnieuw.');
    }
    if (!empty($data['action']) && $data['action'] !== 'appointment_request') {
        return new WP_Error('recaptcha_action', 'Spamcontrole niet geslaagd.');
    }

    $score = isset($data['score']) ? (float) $data['score'] : 0;
    if ($score < $settings['threshold']) {
        return new WP_Error('recaptcha_score', 'Spamcontrole niet geslaagd. Probeer opnieuw.');
    }

    return true;
}

function gb_appointment_form_html() {
    $status = isset($_GET['afspraak']) ? sanitize_key(wp_unslash($_GET['afspraak'])) : '';
    $message = '';
    $message_class = '';
    $recaptcha = gb_appointment_recaptcha_settings();

    if ($status === 'sent') {
        $message = 'Bedankt. Uw afspraakaanvraag is verzonden. We nemen zo snel mogelijk contact met u op.';
        $message_class = 'gb-appointment-success';
    } elseif ($status === 'error') {
        $message = 'Er ging iets mis bij het verzenden. Probeer opnieuw of neem telefonisch contact met ons op.';
        $message_class = 'gb-appointment-error';
    } elseif ($status === 'invalid') {
        $message = 'Controleer de ingevulde gegevens. Naam, contactvoorkeur en het bijhorende e-mailadres of GSM-nummer zijn verplicht.';
        $message_class = 'gb-appointment-error';
    } elseif ($status === 'recaptcha') {
        $message = 'De spamcontrole kon niet worden voltooid. Probeer opnieuw.';
        $message_class = 'gb-appointment-error';
    }

    ob_start();
    ?>
    <div class="gb-appointment-card">
      <div class="gb-appointment-card-head">
        <span class="gb-kicker">Afspraak aanvragen</span>
        <h2>Waarmee kunnen we u helpen?</h2>
        <p>Vul hieronder uw gegevens in. Dit is een aanvraag; we nemen contact met u op om de afspraak te bevestigen.</p>
      </div>

      <?php if ($message): ?>
        <div class="gb-appointment-notice <?php echo esc_attr($message_class); ?>"><?php echo esc_html($message); ?></div>
      <?php endif; ?>

      <form class="gb-appointment-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" novalidate>
        <input type="hidden" name="action" value="gb_submit_appointment_request">
        <input type="hidden" name="recaptcha_token" value="">
        <?php wp_nonce_field('gb_appointment_request','gb_appointment_nonce'); ?>

        <div class="gb-appointment-field gb-appointment-field-full">
          <label for="gb-appointment-name">Naam <span>*</span></label>
          <input id="gb-appointment-name" type="text" name="name" autocomplete="name" required>
        </div>

        <div class="gb-appointment-grid-2">
          <div class="gb-appointment-field">
            <label for="gb-appointment-phone">GSM</label>
            <input id="gb-appointment-phone" type="tel" name="phone" autocomplete="tel" inputmode="tel">
          </div>
          <div class="gb-appointment-field">
            <label for="gb-appointment-email">E-mail</label>
            <input id="gb-appointment-email" type="email" name="email" autocomplete="email">
          </div>
        </div>

        <fieldset class="gb-appointment-contact-choice">
          <legend>Graag contact via <span>*</span></legend>
          <label><input type="radio" name="contact_method" value="email" required> E-mail</label>
          <label><input type="radio" name="contact_method" value="phone" required> Telefoon</label>
        </fieldset>

        <div class="gb-appointment-field gb-appointment-field-full">
          <label for="gb-appointment-plate">Nummerplaat <small>optioneel</small></label>
          <input id="gb-appointment-plate" type="text" name="plate" autocomplete="off" maxlength="20">
        </div>

        <div class="gb-appointment-field gb-appointment-field-full">
          <label for="gb-appointment-message">Waarvoor had u graag een afspraak? <span>*</span></label>
          <textarea id="gb-appointment-message" name="message" rows="5" maxlength="1200" required></textarea>
        </div>

        <div class="gb-appointment-hp" aria-hidden="true">
          <label for="gb-appointment-website">Website</label>
          <input id="gb-appointment-website" type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <button type="submit" class="gb-button gb-button-green gb-appointment-submit">Vraag afspraak</button>
        <?php if (!empty($recaptcha['site_key'])): ?><p class="gb-appointment-recaptcha-note">Beveiligd met reCAPTCHA.</p><?php endif; ?>
      </form>
    </div>
    <?php
    return ob_get_clean();
}

add_filter('the_content', function ($content) {
    if (is_admin() || !is_page('maak-afspraak') || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    $hero = '<section class="gb-page-hero gb-appointment-hero"><div class="gb-shell"><span class="gb-kicker">Garage Barnes · Hamme</span><h1>Maak een afspraak</h1><p>Vraag hier eenvoudig een afspraak aan voor onderhoud, herstelling, diagnose of een andere garagedienst.</p></div></section>';
    $body = '<section class="gb-appointment-content"><div class="gb-shell"><div class="gb-appointment-form-area">' . gb_appointment_form_html() . '</div></div></section>';

    return '<main class="gb-page gb-appointment-page">' . $hero . $body . '</main>';
}, 999);

function gb_handle_appointment_request() {
    $redirect = home_url('/maak-afspraak/');

    if (!isset($_POST['gb_appointment_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gb_appointment_nonce'])),'gb_appointment_request')) {
        wp_safe_redirect(add_query_arg('afspraak','invalid',$redirect));
        exit;
    }

    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('afspraak','sent',$redirect));
        exit;
    }

    $recaptcha_token = isset($_POST['recaptcha_token']) ? sanitize_text_field(wp_unslash($_POST['recaptcha_token'])) : '';
    $captcha = gb_appointment_verify_recaptcha($recaptcha_token);
    if (is_wp_error($captcha)) {
        wp_safe_redirect(add_query_arg('afspraak','recaptcha',$redirect));
        exit;
    }

    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $contact_method = isset($_POST['contact_method']) ? sanitize_key(wp_unslash($_POST['contact_method'])) : '';
    $plate = isset($_POST['plate']) ? strtoupper(sanitize_text_field(wp_unslash($_POST['plate']))) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    $valid_contact_method = in_array($contact_method,array('email','phone'),true);
    $preferred_contact_ok = ($contact_method === 'email' && is_email($email)) || ($contact_method === 'phone' && $phone !== '');

    if ($name === '' || $message === '' || !$valid_contact_method || !$preferred_contact_ok) {
        wp_safe_redirect(add_query_arg('afspraak','invalid',$redirect));
        exit;
    }

    $contact_label = $contact_method === 'email' ? 'E-mail' : 'Telefoon';
    $subject = 'Nieuwe afspraakaanvraag via garagebarnes.be - ' . $name;
    $body = "Nieuwe afspraakaanvraag via garagebarnes.be\n\n";
    $body .= "Naam: {$name}\n";
    $body .= "GSM: " . ($phone !== '' ? $phone : '-') . "\n";
    $body .= "E-mail: " . ($email !== '' ? $email : '-') . "\n";
    $body .= "Voorkeur contact: {$contact_label}\n";
    $body .= "Nummerplaat: " . ($plate !== '' ? $plate : '-') . "\n\n";
    $body .= "Waarvoor afspraak:\n{$message}\n\n";
    $body .= "Pagina: " . home_url('/maak-afspraak/') . "\n";

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    if (is_email($email)) {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    $sent = wp_mail('info@vijt.be',$subject,$body,$headers);

    wp_safe_redirect(add_query_arg('afspraak',$sent ? 'sent' : 'error',$redirect));
    exit;
}
add_action('admin_post_gb_submit_appointment_request','gb_handle_appointment_request');
add_action('admin_post_nopriv_gb_submit_appointment_request','gb_handle_appointment_request');

add_action('wp_head', function () {
    if (!is_page('maak-afspraak')) { return; }
    ?>
    <style id="gb-appointment-page-css">
      .gb-appointment-content{padding:80px 0 100px;background:#fff}
      .gb-appointment-form-area{max-width:860px;margin:0 auto}
      .gb-appointment-card{background:#fff;border:1px solid #dfe3dc;box-shadow:0 16px 42px rgba(0,0,0,.07);padding:46px}
      .gb-appointment-card-head{margin-bottom:32px}
      .gb-appointment-card-head h2{margin:6px 0 12px!important;font-size:clamp(30px,4vw,43px)!important;line-height:1.08!important}
      .gb-appointment-card-head p{max-width:680px;margin:0;color:#666;line-height:1.65}
      .gb-appointment-form{display:grid;gap:22px}
      .gb-appointment-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px}
      .gb-appointment-field label,.gb-appointment-contact-choice legend{display:block;margin:0 0 8px;font-weight:800;color:#222;font-size:14px}
      .gb-appointment-field label span,.gb-appointment-contact-choice legend span{color:#5dc01d}
      .gb-appointment-field label small{font-weight:500;color:#777}
      .gb-appointment-field input,.gb-appointment-field textarea{display:block;width:100%;box-sizing:border-box;border:1px solid #cfd5ca!important;border-radius:2px!important;background:#fff!important;color:#222!important;padding:13px 14px!important;font-size:16px!important;line-height:1.4!important;box-shadow:none!important}
      .gb-appointment-field textarea{resize:vertical;min-height:135px}
      .gb-appointment-field input:focus,.gb-appointment-field textarea:focus{border-color:#5dc01d!important;outline:2px solid rgba(93,192,29,.15)!important}
      .gb-appointment-contact-choice{margin:0;padding:16px 18px 18px;border:1px solid #dfe3dc;background:#f8f9f6}
      .gb-appointment-contact-choice legend{padding:0 6px;margin-left:-6px}
      .gb-appointment-contact-choice label{display:inline-flex;align-items:center;gap:8px;margin-right:28px;font-weight:700;cursor:pointer}
      .gb-appointment-contact-choice input{accent-color:#5dc01d}
      .gb-appointment-submit{justify-self:start;width:auto!important;min-height:48px!important;padding:0 22px!important;border:0!important;border-radius:4px!important;background:var(--gb-green,#5dc01d)!important;color:#12210b!important;font-size:14px!important;font-weight:700!important;line-height:1.2!important;box-shadow:none!important;cursor:pointer}
      .gb-appointment-submit:hover,.gb-appointment-submit:focus{background:var(--gb-green,#5dc01d)!important;color:#fff!important;filter:brightness(.94)}
      .gb-appointment-submit[disabled]{opacity:.65;cursor:wait!important}
      .gb-appointment-recaptcha-note{margin:-10px 0 0;color:#888;font-size:11px}
      .gb-appointment-notice{margin:0 0 25px;padding:15px 17px;border-left:4px solid;font-weight:700;line-height:1.5}
      .gb-appointment-success{background:#eef8e9;border-color:#5dc01d;color:#315a1c}
      .gb-appointment-error{background:#fff2f2;border-color:#bd2b2b;color:#7e1d1d}
      .gb-appointment-hp{position:absolute!important;left:-9999px!important;width:1px!important;height:1px!important;overflow:hidden!important}
      @media(max-width:700px){
        .gb-appointment-content{padding:55px 0 70px}
        .gb-appointment-card{padding:28px 20px}
        .gb-appointment-grid-2{grid-template-columns:1fr}
        .gb-appointment-contact-choice label{display:flex;margin:10px 0}
      }
    </style>
    <?php
    $recaptcha = gb_appointment_recaptcha_settings();
    if (!empty($recaptcha['site_key'])): ?>
      <script src="https://www.google.com/recaptcha/api.js?render=<?php echo rawurlencode($recaptcha['site_key']); ?>"></script>
    <?php endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
      var form=document.querySelector('.gb-appointment-form');
      if(!form) return;
      var submit=form.querySelector('.gb-appointment-submit');
      var recaptchaSiteKey=<?php echo wp_json_encode($recaptcha['site_key']); ?>;
      var submitting=false;

      form.addEventListener('submit',function(e){
        if(submitting) return;
        e.preventDefault();

        var name=form.querySelector('[name="name"]');
        var phone=form.querySelector('[name="phone"]');
        var email=form.querySelector('[name="email"]');
        var msg=form.querySelector('[name="message"]');
        var choice=form.querySelector('[name="contact_method"]:checked');
        var error='';

        if(!name.value.trim()) error='Vul uw naam in.';
        else if(!choice) error='Kies of u via e-mail of telefoon gecontacteerd wilt worden.';
        else if(choice.value==='email' && !email.value.trim()) error='Vul uw e-mailadres in wanneer u contact via e-mail kiest.';
        else if(choice.value==='phone' && !phone.value.trim()) error='Vul uw GSM-nummer in wanneer u contact via telefoon kiest.';
        else if(!msg.value.trim()) error='Vul kort in waarvoor u een afspraak wenst.';
        if(error){alert(error);return;}

        if(!recaptchaSiteKey){
          alert('reCAPTCHA is nog niet geconfigureerd. Probeer later opnieuw.');
          return;
        }
        if(typeof grecaptcha==='undefined'){
          alert('De spambeveiliging kon niet worden geladen. Probeer opnieuw.');
          return;
        }

        submit.disabled=true;
        submit.textContent='Even wachten…';

        grecaptcha.ready(function(){
          grecaptcha.execute(recaptchaSiteKey,{action:'appointment_request'}).then(function(token){
            form.elements.recaptcha_token.value=token;
            submitting=true;
            form.submit();
          }).catch(function(){
            submit.disabled=false;
            submit.textContent='Vraag afspraak';
            alert('De spambeveiliging kon niet worden uitgevoerd. Probeer opnieuw.');
          });
        });
      });
    });
    </script>
    <?php
}, 120);
