<?php
if (!defined('ABSPATH')) { exit; }

function gb_over_ons_team_shortcode(){
    $uploads = wp_upload_dir();
    $photo_base = !empty($uploads['baseurl']) ? trailingslashit($uploads['baseurl']) . '2026/09/' : trailingslashit(home_url('/wp-content/uploads/2026/09/'));

    $team = array(
        array('name'=>'Peter Barnes','roles'=>array('Oprichter','Eigenaar','Takelaar','Onthaal'),'phone'=>'+32 492 06 15 45','phone_href'=>'+32492061545','email'=>'peter@garagebarnes.com','photo'=>$photo_base.'peter-barnes-garage-barnes-takeldienst.jpg'),
        array('name'=>'David Barnes','roles'=>array('Administratie','Boekhouding'),'email'=>'info@garagebarnes.com','photo'=>$photo_base.'david-barnes-garage-barnes-takeldienst.jpg'),
        array('name'=>'Melvin Barnes','roles'=>array('Onthaal','Administratie'),'email'=>'info@garagebarnes.com','photo'=>$photo_base.'melvin-barnes-garage-barnes-takeldienst.jpg'),
        array('name'=>'Gene Van Hove','roles'=>array('Technieker','Takelaar'),'photo'=>$photo_base.'gene-van-hove-garage-barnes-takeldienst.jpg'),
    );

    ob_start(); ?>
    <main class="gb-page gb-about-page">
      <section class="gb-page-hero"><div class="gb-shell"><span class="gb-kicker">Over ons</span><h1>Garage Barnes</h1><p>Lokale service, technische kennis en één aanspreekpunt voor uw wagen.</p></div></section>
      <section class="gb-about-intro"><div class="gb-shell gb-about-intro-grid"><div><span class="gb-kicker">Ons verhaal</span><h2>Een lokale garage met een eigen verhaal</h2><p>Garage Barnes groeide vanuit passie voor techniek en persoonlijke service. Peter Barnes startte de garage in bijberoep in 2012, ging in 2015 voltijds verder en verhuisde in 2019 naar de KMO-zone ’t Zonneke in Hamme.</p><p>Doorheen de jaren werd het aanbod uitgebreid met een eigen takeldienst, zodat klanten voor onderhoud, herstellingen én pechhulp bij één vertrouwd aanspreekpunt terechtkunnen.</p></div><aside class="gb-side-card"><h3>Garage Barnes BV</h3><p>Zonneke 4<br>9220 Hamme</p><p>Ma–Vr<br>08:30–12:00<br>13:00–18:00<br>Za–Zo gesloten</p></aside></div></section>
      <section class="gb-team-section"><div class="gb-shell"><div class="gb-section-heading"><span class="gb-kicker">Ons team</span><h2>De mensen achter Garage Barnes</h2><p>Een compact team met elk zijn eigen expertise, samen gericht op persoonlijke service en vakwerk.</p></div><div class="gb-team-cards">
      <?php foreach ($team as $member): ?>
        <article class="gb-team-card"><div class="gb-team-photo"><img src="<?php echo esc_url($member['photo']); ?>" alt="<?php echo esc_attr($member['name']); ?> - Garage Barnes" loading="lazy"></div><div class="gb-team-card-body"><h3><?php echo esc_html($member['name']); ?></h3><div class="gb-team-role-list"><?php foreach($member['roles'] as $role): ?><span><?php echo esc_html($role); ?></span><?php endforeach; ?></div><?php if (!empty($member['phone']) || !empty($member['email'])): ?><div class="gb-team-contact"><?php if (!empty($member['phone'])): ?><a href="tel:<?php echo esc_attr($member['phone_href']); ?>"><span class="gb-team-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.7 3.3 3.3 4.9 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.3 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.8 21 3 13.2 3 3.7c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2.2 2.2z"/></svg></span><span><?php echo esc_html($member['phone']); ?></span></a><?php endif; ?><?php if (!empty($member['email'])): ?><a href="mailto:<?php echo esc_attr($member['email']); ?>"><span class="gb-team-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg></span><span><?php echo esc_html($member['email']); ?></span></a><?php endif; ?></div><?php endif; ?></div></article>
      <?php endforeach; ?>
      </div></div></section>
      <section class="gb-page-cta"><div class="gb-shell gb-page-cta-inner"><div><span class="gb-kicker">Garage Barnes · Hamme</span><h2>We helpen u graag verder.</h2></div><a class="gb-button gb-button-green" href="<?php echo esc_url(home_url('/contact/')); ?>">Contacteer Garage Barnes</a></div></section>
    </main>
    <?php return ob_get_clean();
}

add_action('init', function(){
    remove_shortcode('garage_barnes_over_ons');
    add_shortcode('garage_barnes_over_ons','gb_over_ons_team_shortcode');
}, 100);

add_action('wp_head', function(){
    if (!is_page('over-ons')) { return; }
    ?>
    <style>
    .gb-about-intro{padding:90px 0 80px}.gb-about-intro-grid{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(280px,.7fr);gap:70px;align-items:start}.gb-about-intro h2,.gb-team-section h2{margin:0 0 24px;font-size:clamp(34px,4.4vw,56px);line-height:1.05;letter-spacing:-.035em}.gb-about-intro p,.gb-team-section .gb-section-heading p{font-size:18px;line-height:1.75;color:#5f5f5f}.gb-team-section{padding:90px 0 105px;background:#f4f5f2}.gb-team-section .gb-section-heading{max-width:760px;margin-bottom:42px}.gb-team-cards{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:22px}.gb-team-card{background:#fff;border:1px solid #dfe3dc;border-radius:6px;overflow:hidden;box-shadow:0 8px 28px rgba(0,0,0,.05);transition:transform .2s ease,box-shadow .2s ease}.gb-team-card:hover{transform:translateY(-4px);box-shadow:0 12px 34px rgba(0,0,0,.08)}.gb-team-photo{width:100%;aspect-ratio:1/1;overflow:hidden;background:#eef0eb}.gb-team-photo img{display:block;width:100%;height:100%;aspect-ratio:1/1;object-fit:cover;object-position:center center}.gb-team-card-body{padding:25px 24px 28px;border-top:4px solid var(--gb-green)}.gb-team-card h3{margin:0 0 16px;font-size:24px;line-height:1.15;color:#202020}.gb-team-role-list{display:flex;flex-wrap:wrap;gap:8px}.gb-team-role-list span{display:inline-flex;padding:7px 10px;border-radius:999px;background:#eef4e9;color:#365c1d;font-size:12px;font-weight:700}.gb-team-contact{display:flex;flex-direction:column;gap:7px;margin-top:18px;padding-top:16px;border-top:1px solid #e3e6df}.gb-team-contact a{display:flex;align-items:center;gap:9px;font-size:14px;line-height:1.4;font-weight:400!important;color:#2f6f16;text-decoration:none;overflow-wrap:anywhere}.gb-team-contact a:hover{text-decoration:underline}.gb-team-contact-icon{display:inline-flex;align-items:center;justify-content:center;width:17px;height:17px;flex:0 0 17px;color:#2f6f16}.gb-team-contact-icon svg{display:block;width:17px;height:17px;fill:currentColor}.gb-about-page .gb-page-hero p{max-width:720px;font-size:19px;line-height:1.6;color:#5f5f5f}
    @media(max-width:1000px){.gb-team-cards{grid-template-columns:repeat(2,1fr)}.gb-about-intro-grid{grid-template-columns:1fr}}
    @media(max-width:700px){.gb-about-intro,.gb-team-section{padding:65px 0 75px}.gb-team-cards{grid-template-columns:1fr}.gb-team-card{max-width:520px;width:100%;margin:0 auto}.gb-team-card h3{font-size:22px}}
    </style>
    <?php
});
