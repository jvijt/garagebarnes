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
