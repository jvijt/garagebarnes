<?php
/**
 * Plugin Name: Garage Barnes SMTP
 * Description: Verzend alle WordPress e-mail via een configureerbare SMTP-server.
 * Version: 1.1.0
 * Author: Garage Barnes / Murceke Media
 */

if (!defined('ABSPATH')) { exit; }

class Garage_Barnes_SMTP {
    const OPTION = 'garage_barnes_smtp_settings';

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
        add_action('admin_init', array(__CLASS__, 'handle_save'));
        add_action('admin_init', array(__CLASS__, 'handle_test'));
        add_action('phpmailer_init', array(__CLASS__, 'configure_phpmailer'));
        add_filter('wp_mail_from', array(__CLASS__, 'mail_from'));
        add_filter('wp_mail_from_name', array(__CLASS__, 'mail_from_name'));
    }

    public static function defaults() {
        return array(
            'enabled'              => 0,
            'host'                 => '',
            'port'                 => 587,
            'encryption'           => 'tls',
            'auth'                 => 1,
            'username'             => '',
            'password'             => '',
            'from_email'           => '',
            'from_name'            => 'Garage Barnes',
            'force_from'           => 1,
            'recaptcha_site_key'   => '',
            'recaptcha_secret_key' => '',
            'recaptcha_threshold'  => '0.5',
        );
    }

    public static function settings() {
        $saved = get_option(self::OPTION, array());
        return wp_parse_args(is_array($saved) ? $saved : array(), self::defaults());
    }

    public static function admin_menu() {
        add_options_page(
            'Garage Barnes SMTP',
            'Garage Barnes SMTP',
            'manage_options',
            'garage-barnes-smtp',
            array(__CLASS__, 'render_page')
        );
    }

    public static function handle_save() {
        if (!is_admin() || !current_user_can('manage_options')) { return; }
        if (empty($_POST['gb_smtp_action']) || $_POST['gb_smtp_action'] !== 'save') { return; }
        check_admin_referer('gb_smtp_save');

        $old = self::settings();
        $encryption = isset($_POST['encryption']) ? sanitize_key(wp_unslash($_POST['encryption'])) : 'tls';
        if (!in_array($encryption, array('none','ssl','tls'), true)) { $encryption = 'tls'; }

        $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
        if ($password === '') { $password = $old['password']; }

        $recaptcha_secret = isset($_POST['recaptcha_secret_key']) ? trim((string) wp_unslash($_POST['recaptcha_secret_key'])) : '';
        if ($recaptcha_secret === '') { $recaptcha_secret = $old['recaptcha_secret_key']; }

        $threshold = isset($_POST['recaptcha_threshold']) ? (float) wp_unslash($_POST['recaptcha_threshold']) : 0.5;
        if ($threshold < 0) { $threshold = 0; }
        if ($threshold > 1) { $threshold = 1; }

        $new = array(
            'enabled'              => !empty($_POST['enabled']) ? 1 : 0,
            'host'                 => isset($_POST['host']) ? sanitize_text_field(wp_unslash($_POST['host'])) : '',
            'port'                 => isset($_POST['port']) ? absint($_POST['port']) : 587,
            'encryption'           => $encryption,
            'auth'                 => !empty($_POST['auth']) ? 1 : 0,
            'username'             => isset($_POST['username']) ? sanitize_text_field(wp_unslash($_POST['username'])) : '',
            'password'             => $password,
            'from_email'           => isset($_POST['from_email']) ? sanitize_email(wp_unslash($_POST['from_email'])) : '',
            'from_name'            => isset($_POST['from_name']) ? sanitize_text_field(wp_unslash($_POST['from_name'])) : 'Garage Barnes',
            'force_from'           => !empty($_POST['force_from']) ? 1 : 0,
            'recaptcha_site_key'   => isset($_POST['recaptcha_site_key']) ? sanitize_text_field(wp_unslash($_POST['recaptcha_site_key'])) : '',
            'recaptcha_secret_key' => $recaptcha_secret,
            'recaptcha_threshold'  => (string) $threshold,
        );

        update_option(self::OPTION, $new, false);
        wp_safe_redirect(add_query_arg(array('page'=>'garage-barnes-smtp','updated'=>'1'), admin_url('options-general.php')));
        exit;
    }

    public static function handle_test() {
        if (!is_admin() || !current_user_can('manage_options')) { return; }
        if (empty($_POST['gb_smtp_action']) || $_POST['gb_smtp_action'] !== 'test') { return; }
        check_admin_referer('gb_smtp_test');

        $to = isset($_POST['test_email']) ? sanitize_email(wp_unslash($_POST['test_email'])) : '';
        if (!$to) {
            wp_safe_redirect(add_query_arg(array('page'=>'garage-barnes-smtp','test'=>'invalid'), admin_url('options-general.php')));
            exit;
        }

        $ok = wp_mail(
            $to,
            'Garage Barnes SMTP test',
            "Dit is een testmail van Garage Barnes.\n\nAls je deze ontvangt, werkt de SMTP-configuratie correct."
        );

        wp_safe_redirect(add_query_arg(array('page'=>'garage-barnes-smtp','test'=>$ok ? 'success' : 'failed'), admin_url('options-general.php')));
        exit;
    }

    public static function configure_phpmailer($phpmailer) {
        $s = self::settings();
        if (empty($s['enabled']) || empty($s['host'])) { return; }

        $phpmailer->isSMTP();
        $phpmailer->Host = $s['host'];
        $phpmailer->Port = (int) $s['port'];
        $phpmailer->SMTPAuth = !empty($s['auth']);

        if ($phpmailer->SMTPAuth) {
            $phpmailer->Username = $s['username'];
            $phpmailer->Password = $s['password'];
        }

        if ($s['encryption'] === 'ssl') {
            $phpmailer->SMTPSecure = 'ssl';
            $phpmailer->SMTPAutoTLS = false;
        } elseif ($s['encryption'] === 'tls') {
            $phpmailer->SMTPSecure = 'tls';
            $phpmailer->SMTPAutoTLS = true;
        } else {
            $phpmailer->SMTPSecure = '';
            $phpmailer->SMTPAutoTLS = false;
        }

        $phpmailer->Timeout = 20;
    }

    public static function mail_from($email) {
        $s = self::settings();
        if (empty($s['enabled']) || empty($s['force_from']) || empty($s['from_email'])) { return $email; }
        return $s['from_email'];
    }

    public static function mail_from_name($name) {
        $s = self::settings();
        if (empty($s['enabled']) || empty($s['force_from']) || empty($s['from_name'])) { return $name; }
        return $s['from_name'];
    }

    public static function render_page() {
        if (!current_user_can('manage_options')) { return; }
        $s = self::settings();
        ?>
        <div class="wrap">
          <h1>Garage Barnes SMTP</h1>
          <p>Deze instellingen sturen alle e-mail die via <code>wp_mail()</code> wordt verzonden via dezelfde SMTP-server. Dit geldt normaal ook voor Elementor-formulieren.</p>

          <?php if (!empty($_GET['updated'])): ?>
            <div class="notice notice-success is-dismissible"><p>SMTP- en beveiligingsinstellingen opgeslagen.</p></div>
          <?php endif; ?>
          <?php if (isset($_GET['test'])): ?>
            <?php if ($_GET['test'] === 'success'): ?>
              <div class="notice notice-success is-dismissible"><p>Testmail werd door WordPress succesvol aangeboden voor verzending.</p></div>
            <?php elseif ($_GET['test'] === 'failed'): ?>
              <div class="notice notice-error"><p>Testmail kon niet worden verzonden. Controleer server, poort, encryptie, login en wachtwoord.</p></div>
            <?php else: ?>
              <div class="notice notice-error"><p>Vul een geldig test-e-mailadres in.</p></div>
            <?php endif; ?>
          <?php endif; ?>

          <form method="post">
            <?php wp_nonce_field('gb_smtp_save'); ?>
            <input type="hidden" name="gb_smtp_action" value="save">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row">SMTP inschakelen</th>
                <td><label><input type="checkbox" name="enabled" value="1" <?php checked($s['enabled'],1); ?>> Alle WordPress e-mail via SMTP versturen</label></td>
              </tr>
              <tr><th scope="row"><label for="gb-smtp-host">SMTP-server</label></th><td><input class="regular-text" id="gb-smtp-host" name="host" value="<?php echo esc_attr($s['host']); ?>" placeholder="smtp.jouwdomein.be"></td></tr>
              <tr><th scope="row"><label for="gb-smtp-port">Poort</label></th><td><input type="number" id="gb-smtp-port" name="port" value="<?php echo esc_attr($s['port']); ?>" min="1" max="65535" class="small-text"></td></tr>
              <tr><th scope="row">Encryptie</th><td><select name="encryption"><option value="tls" <?php selected($s['encryption'],'tls'); ?>>TLS / STARTTLS</option><option value="ssl" <?php selected($s['encryption'],'ssl'); ?>>SSL</option><option value="none" <?php selected($s['encryption'],'none'); ?>>Geen</option></select></td></tr>
              <tr><th scope="row">Authenticatie</th><td><label><input type="checkbox" name="auth" value="1" <?php checked($s['auth'],1); ?>> Gebruikersnaam en wachtwoord gebruiken</label></td></tr>
              <tr><th scope="row"><label for="gb-smtp-user">Gebruikersnaam</label></th><td><input class="regular-text" id="gb-smtp-user" name="username" value="<?php echo esc_attr($s['username']); ?>" autocomplete="username"></td></tr>
              <tr><th scope="row"><label for="gb-smtp-pass">Wachtwoord</label></th><td><input class="regular-text" type="password" id="gb-smtp-pass" name="password" value="" autocomplete="new-password" placeholder="Ongewijzigd laten = huidig wachtwoord behouden"><p class="description">Het wachtwoord wordt niet opnieuw weergegeven in het beheerscherm.</p></td></tr>
              <tr><th scope="row"><label for="gb-smtp-from">Afzender e-mail</label></th><td><input class="regular-text" type="email" id="gb-smtp-from" name="from_email" value="<?php echo esc_attr($s['from_email']); ?>" placeholder="info@garagebarnes.com"></td></tr>
              <tr><th scope="row"><label for="gb-smtp-name">Afzendernaam</label></th><td><input class="regular-text" id="gb-smtp-name" name="from_name" value="<?php echo esc_attr($s['from_name']); ?>"></td></tr>
              <tr><th scope="row">Afzender forceren</th><td><label><input type="checkbox" name="force_from" value="1" <?php checked($s['force_from'],1); ?>> Voor alle formulieren dezelfde afzender gebruiken</label><p class="description">Aanbevolen voor goede SMTP-aflevering. Antwoorden op formulieren kunnen nog steeds via Reply-To naar de klant gaan.</p></td></tr>
            </table>

            <hr>
            <h2>reCAPTCHA v3</h2>
            <p>Deze sleutels worden gebruikt om het eigen tweedehandswagen-infoformulier tegen spam te beveiligen. Gebruik reCAPTCHA v3-sleutels voor <strong>garagebarnes.be</strong>.</p>
            <table class="form-table" role="presentation">
              <tr><th scope="row"><label for="gb-recaptcha-site">Site Key</label></th><td><input class="regular-text" id="gb-recaptcha-site" name="recaptcha_site_key" value="<?php echo esc_attr($s['recaptcha_site_key']); ?>" autocomplete="off"></td></tr>
              <tr><th scope="row"><label for="gb-recaptcha-secret">Secret Key</label></th><td><input class="regular-text" type="password" id="gb-recaptcha-secret" name="recaptcha_secret_key" value="" autocomplete="new-password" placeholder="Ongewijzigd laten = huidige secret behouden"><p class="description">De secret key wordt niet opnieuw weergegeven.</p></td></tr>
              <tr><th scope="row"><label for="gb-recaptcha-threshold">Minimumscore</label></th><td><input type="number" id="gb-recaptcha-threshold" name="recaptcha_threshold" value="<?php echo esc_attr($s['recaptcha_threshold']); ?>" min="0" max="1" step="0.1" class="small-text"><p class="description">0,5 is een gebruikelijke startwaarde. Hogere waarden zijn strenger.</p></td></tr>
            </table>

            <?php submit_button('Instellingen opslaan'); ?>
          </form>

          <hr>
          <h2>Testmail</h2>
          <form method="post">
            <?php wp_nonce_field('gb_smtp_test'); ?>
            <input type="hidden" name="gb_smtp_action" value="test">
            <p><input type="email" class="regular-text" name="test_email" value="<?php echo esc_attr(get_option('admin_email')); ?>" required> <?php submit_button('Testmail verzenden','secondary','submit',false); ?></p>
          </form>
        </div>
        <?php
    }
}

Garage_Barnes_SMTP::init();
