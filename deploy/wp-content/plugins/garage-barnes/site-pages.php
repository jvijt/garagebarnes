<?php
if (!defined('ABSPATH')) { exit; }

function gb_page_shell($kicker,$title,$intro,$content,$cta_label='Contacteer Garage Barnes',$cta_url='/contact/') {
    $cta_href = (strpos($cta_url,'tel:')===0 || strpos($cta_url,'mailto:')===0 || strpos($cta_url,'http://')===0 || strpos($cta_url,'https://')===0) ? $cta_url : home_url($cta_url);
    ob_start(); ?>
    <main class="gb-page">
        <section class="gb-page-hero"><div class="gb-shell"><span class="gb-kicker"><?php echo esc_html($kicker); ?></span><h1><?php echo esc_html($title); ?></h1><p><?php echo esc_html($intro); ?></p></div></section>
        <section class="gb-page-content"><div class="gb-shell"><?php echo $content; ?></div></section>
        <section class="gb-page-cta"><div class="gb-shell gb-page-cta-inner"><div><span class="gb-kicker">Garage Barnes · Hamme</span><h2>We helpen u graag verder.</h2></div><a class="gb-button gb-button-green" href="<?php echo esc_url($cta_href); ?>"><?php echo esc_html($cta_label); ?></a></div></section>
    </main><?php return ob_get_clean();
}

function gb_auto_service_shortcode(){
    $network=GB_PLUGIN_URL.'assets/img/123-autoservice-logo.svg';
    $content='<div class="gb-content-grid"><div><h2>Onderhoud en herstellingen voor alle merken</h2><p>Garage Barnes staat in voor klein en groot onderhoud, herstellingen en technische diagnose. We communiceren duidelijk en voeren geen bijkomende werken uit zonder uw toestemming.</p><div class="gb-service-list"><span>Klein & groot onderhoud</span><span>Mechanische herstellingen</span><span>Motorwissels</span><span>Elektrische diagnose</span><span>Airco</span><span>Banden & batterijen</span><span>Elektrische voertuigen</span><span>Voorbereiding technische keuring</span><span>Vakantiecheck</span><span>Expertisedossiers</span></div></div><aside class="gb-side-card"><img class="gb-page-network-logo" src="'.esc_url($network).'" alt="1,2,3 AutoService"><h3>Persoonlijke garageservice</h3><p>Een lokaal aanspreekpunt met technische kennis, snelle service en transparante communicatie.</p><a href="tel:+3252570557">+32 52 57 05 57</a></aside></div>';
    return gb_page_shell('Auto Service','Uw wagen in goede handen','Onderhoud, diagnose en herstellingen voor alle merken vanuit onze garage in Hamme.',$content,'Maak een afspraak','/contact/');
}
add_shortcode('garage_barnes_auto_service','gb_auto_service_shortcode');

function gb_towing_shortcode(){
    $logo=GB_PLUGIN_URL.'assets/img/takeldienst-barnes-logo.svg';
    $content='<div class="gb-content-grid"><div><img class="gb-page-towing-logo" src="'.esc_url($logo).'" alt="Takeldienst Barnes 24/7"><h2>Pech, panne of ongeval?</h2><p>Takeldienst Barnes is rechtstreeks bereikbaar voor particulieren en werkt daarnaast voor bedrijven, verzekeraars en pechbijstandsorganisaties. We helpen snel, professioneel en duidelijk.</p><div class="gb-service-list"><span>Depannage en takeling</span><span>Pechverhelping op locatie</span><span>Ongeval en berging</span><span>Transport van voertuigen</span><span>Particulieren en bedrijven</span><span>24/7 bereikbaar</span></div></div><aside class="gb-side-card gb-side-card-dark"><span class="gb-kicker">24 uur / 7 dagen</span><h3>Direct hulp nodig?</h3><a class="gb-big-phone" href="tel:+32477353547">+32 477 35 35 47</a><p>Bel rechtstreeks naar Takeldienst Barnes.</p></aside></div>';
    return gb_page_shell('Takeldienst Barnes','24/7 pechhulp en takeldienst','Ook als particulier kunt u ons rechtstreeks bellen bij panne, pech of ongeval.',$content,'Bel Takeldienst Barnes','tel:+32477353547');
}
add_shortcode('garage_barnes_takeldienst','gb_towing_shortcode');

function gb_about_shortcode(){
    $content='<div class="gb-content-grid"><div><h2>Een lokale garage met een eigen verhaal</h2><p>Garage Barnes groeide vanuit passie voor techniek en persoonlijke service. Peter Barnes startte de garage in bijberoep in 2012, ging in 2015 voltijds verder en verhuisde in 2019 naar de KMO-zone ’t Zonneke in Hamme.</p><p>Doorheen de jaren werd het aanbod uitgebreid met een eigen takeldienst, zodat klanten voor onderhoud, herstellingen én pechhulp bij één vertrouwd aanspreekpunt terechtkunnen.</p><h3>Ons team</h3><div class="gb-team-grid"><div><strong>Peter Barnes</strong><span>Oprichter/eigenaar · Onthaal · Technieker · Takelaar</span></div><div><strong>David Barnes</strong><span>Administratie · Boekhouding</span></div><div><strong>Michael Heylen</strong><span>Technieker · Takelaar · Onthaal</span></div></div></div><aside class="gb-side-card"><h3>Garage Barnes BV</h3><p>Zonneke 4<br>9220 Hamme</p><p>Ma–Vr<br>08:30–12:00<br>13:00–18:00<br>Za–Zo gesloten</p></aside></div>';
    return gb_page_shell('Over ons','Garage Barnes','Lokale service, technische kennis en één aanspreekpunt voor uw wagen.',$content);
}
add_shortcode('garage_barnes_over_ons','gb_about_shortcode');

function gb_contact_shortcode(){
    $content='<div class="gb-contact-cards"><div><span>Garage</span><h3>Afspraak & informatie</h3><a href="tel:+3252570557">+32 52 57 05 57</a><a href="mailto:info@garagebarnes.com">info@garagebarnes.com</a></div><div><span>Takeldienst 24/7</span><h3>Pech of ongeval</h3><a href="tel:+32477353547">+32 477 35 35 47</a></div><div><span>Adres</span><h3>Garage Barnes BV</h3><p>Zonneke 4<br>9220 Hamme<br>België</p></div><div><span>Openingsuren</span><h3>Maandag – vrijdag</h3><p>08:30–12:00<br>13:00–18:00<br>Zaterdag–zondag gesloten</p></div></div>';
    return gb_page_shell('Contact','Contacteer Garage Barnes','Voor een afspraak, technische vraag of directe pechhulp vindt u hier alle contactgegevens.',$content,'Bel de garage','tel:+3252570557');
}
add_shortcode('garage_barnes_contact','gb_contact_shortcode');

function gb_cars_shortcode(){
    $content='<div class="gb-empty-state"><span class="gb-kicker">Garage Barnes Vehicles</span><h2>Ons voertuigaanbod komt hier.</h2><p>We bouwen dit onderdeel uit tot een eigen voertuigenmodule met foto’s, specificaties, prijs en individuele voertuigfiches. Tot dan kunt u ons rechtstreeks contacteren voor het actuele aanbod.</p><a class="gb-button gb-button-dark" href="'.esc_url(home_url('/contact/')).'">Vraag naar het actuele aanbod</a></div>';
    return gb_page_shell('Tweedehandswagens','Selecteerde tweedehandswagens','Een wisselend aanbod voertuigen van Garage Barnes.',$content);
}
add_shortcode('garage_barnes_tweedehands','gb_cars_shortcode');

function gb_upgrade_placeholder_pages(){
    if(get_option('gb_site_pages_v2_done')) return;
    $map=array(
      'auto-service'=>array('<h1>Auto Service</h1><p>Deze pagina wordt verder opgebouwd in Elementor.</p>','[garage_barnes_auto_service]'),
      'takeldienst'=>array('<h1>Takeldienst Barnes</h1><p>Deze pagina wordt verder opgebouwd in Elementor.</p>','[garage_barnes_takeldienst]'),
      'tweedehands'=>array('<h1>Tweedehandswagens</h1><p>Hier komt het aanbod uit Garage Barnes Vehicles.</p>','[garage_barnes_tweedehands]'),
      'over-ons'=>array('<h1>Over Garage Barnes</h1><p>Deze pagina wordt verder opgebouwd in Elementor.</p>','[garage_barnes_over_ons]'),
      'contact'=>array('<h1>Contact</h1><p>Deze pagina wordt verder opgebouwd in Elementor.</p>','[garage_barnes_contact]')
    );
    foreach($map as $slug=>$cfg){
      $page=get_page_by_path($slug,OBJECT,'page');
      if($page && trim($page->post_content)===trim($cfg[0])) wp_update_post(array('ID'=>$page->ID,'post_content'=>$cfg[1]));
    }
    update_option('gb_site_pages_v2_done',1,false);
}
add_action('init','gb_upgrade_placeholder_pages',30);
