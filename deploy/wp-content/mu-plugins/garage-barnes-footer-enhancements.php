<?php
/**
 * Garage Barnes footer enhancements.
 */
if (!defined('ABSPATH')) { exit; }

function gb_footer_enhancements_assets() {
    if (is_admin()) { return; }
    ?>
    <style id="gb-footer-enhancements-css">
      .gb-footer-brand .gb-network-note{display:none!important}
      .gb-footer-brand .gb-network-logo{width:180px!important;max-width:100%!important;max-height:78px!important;margin-top:12px!important}
      .gb-footer-brand .gb-network-link{display:inline-flex;align-self:flex-start}
      .gb-footer-brand .gb-footer-services{margin:14px 0 8px;line-height:1.65;color:#bcbcbc}
      .gb-footer-brand .gb-footer-services a{color:#bcbcbc!important;text-decoration:none!important}
      .gb-footer-brand .gb-footer-services a:hover{color:#fff!important}
      .gb-footer-bottom .gb-footer-facebook{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;margin-left:12px;border:1px solid rgba(255,255,255,.28);border-radius:50%;color:#fff!important;text-decoration:none!important;vertical-align:middle;transition:background .2s ease,border-color .2s ease}
      .gb-footer-bottom .gb-footer-facebook:hover{background:#5dc01d;border-color:#5dc01d}
      .gb-footer-bottom .gb-footer-facebook svg{width:14px;height:14px;display:block;fill:currentColor}
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
      var brand=document.querySelector('.gb-footer-brand');
      if(!brand) return;

      var note=brand.querySelector('.gb-network-note');
      if(note) note.remove();

      var networkLogo=brand.querySelector('.gb-network-logo');
      if(networkLogo && !networkLogo.closest('.gb-network-link')){
        var link=document.createElement('a');
        link.className='gb-network-link';
        link.href='https://www.123autoservice.be/';
        link.target='_blank';
        link.rel='noopener noreferrer';
        networkLogo.parentNode.insertBefore(link,networkLogo);
        link.appendChild(networkLogo);
      }

      var bottom=document.querySelector('.gb-footer-bottom');
      if(bottom && !bottom.querySelector('.gb-footer-facebook')){
        var left=bottom.querySelector('span');
        if(left){
          left.insertAdjacentHTML('beforeend','<a class="gb-footer-facebook" href="https://www.facebook.com/GarageBarnes" target="_blank" rel="noopener noreferrer" aria-label="Garage Barnes op Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-9h3l.45-3.5H13.5V7.26c0-1.01.28-1.7 1.73-1.7H17V2.43c-.31-.04-1.38-.13-2.63-.13-2.6 0-4.37 1.58-4.37 4.5v2.7H7V13h3v9h3.5z"/></svg></a>');
        }
      }

      var intro=brand.querySelector('p');
      if(intro){
        intro.className='gb-footer-services';
        intro.innerHTML=''
          + '<a href="/auto-service/">Garage</a>, '
          + '<a href="/auto-service/">onderhoud</a>, '
          + '<a href="/auto-service/">herstellingen</a>, '
          + '<a href="/tweedehands/">tweedehandswagens</a> en '
          + '<a href="/takeldienst/">takeldienst</a> vanuit Hamme.';
      }
    });
    </script>
    <?php
}
add_action('wp_footer','gb_footer_enhancements_assets',99);
