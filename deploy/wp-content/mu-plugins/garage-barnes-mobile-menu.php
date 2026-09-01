<?php
/**
 * Garage Barnes mobile navigation enhancement.
 */
if (!defined('ABSPATH')) { exit; }

function gb_mobile_menu_assets() {
    if (is_admin()) { return; }
    ?>
    <style id="gb-mobile-menu-css">
      .gb-mobile-menu-toggle{display:none}
      @media(max-width:900px){
        .gb-site-header{position:relative}
        .gb-header-inner{position:relative;display:grid!important;grid-template-columns:1fr auto;align-items:center;gap:12px!important;padding:10px 0!important;min-height:76px!important}
        .gb-garage-logo-link{grid-column:1;grid-row:1}
        .gb-garage-logo{width:155px!important;max-height:58px!important}
        .gb-mobile-menu-toggle{grid-column:2;grid-row:1;display:inline-flex;align-items:center;justify-content:center;width:46px;height:46px;padding:0;border:1px solid #d9ddd5;border-radius:4px;background:#fff;color:#202020;cursor:pointer}
        .gb-mobile-menu-toggle span,.gb-mobile-menu-toggle span:before,.gb-mobile-menu-toggle span:after{display:block;width:22px;height:2px;background:currentColor;transition:transform .2s ease,opacity .2s ease}
        .gb-mobile-menu-toggle span{position:relative}
        .gb-mobile-menu-toggle span:before,.gb-mobile-menu-toggle span:after{content:"";position:absolute;left:0}
        .gb-mobile-menu-toggle span:before{top:-7px}.gb-mobile-menu-toggle span:after{top:7px}
        .gb-mobile-menu-toggle[aria-expanded="true"] span{background:transparent}
        .gb-mobile-menu-toggle[aria-expanded="true"] span:before{top:0;transform:rotate(45deg)}
        .gb-mobile-menu-toggle[aria-expanded="true"] span:after{top:0;transform:rotate(-45deg)}
        .gb-main-nav,.gb-header-actions{display:none!important}
        .gb-site-header.gb-mobile-open .gb-main-nav{grid-column:1/-1;grid-row:2;display:flex!important;flex-direction:column;align-items:stretch;width:100%;margin:4px 0 0!important;padding:8px 0 4px!important;gap:0!important;overflow:visible!important;white-space:normal!important;border-top:1px solid #eceee9}
        .gb-site-header.gb-mobile-open .gb-main-nav a{display:block;padding:13px 4px;border-bottom:1px solid #eceee9;font-size:15px}
        .gb-site-header.gb-mobile-open .gb-header-actions{grid-column:1/-1;grid-row:3;display:flex!important;align-items:center;justify-content:space-between;margin:0!important;padding:8px 0 4px;gap:12px}
        .gb-site-header.gb-mobile-open .gb-towing-logo-link{display:flex!important}
        .gb-site-header.gb-mobile-open .gb-towing-logo{width:125px!important;max-height:48px!important}
        .gb-site-header.gb-mobile-open .gb-header-actions .gb-button{margin-left:auto;min-height:42px;padding:0 14px;font-size:12px}
      }
      @media(max-width:520px){
        .gb-topbar-links{gap:12px!important;flex-wrap:wrap}
        .gb-site-header.gb-mobile-open .gb-header-actions{align-items:flex-start;flex-direction:column}
        .gb-site-header.gb-mobile-open .gb-header-actions .gb-button{margin-left:0}
      }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
      var header=document.querySelector('.gb-site-header');
      var inner=header ? header.querySelector('.gb-header-inner') : null;
      var nav=header ? header.querySelector('.gb-main-nav') : null;
      if(!header || !inner || !nav || inner.querySelector('.gb-mobile-menu-toggle')) return;
      var button=document.createElement('button');
      button.type='button';
      button.className='gb-mobile-menu-toggle';
      button.setAttribute('aria-expanded','false');
      button.setAttribute('aria-controls','gb-main-navigation');
      button.setAttribute('aria-label','Menu openen');
      button.innerHTML='<span></span>';
      nav.id='gb-main-navigation';
      inner.insertBefore(button, nav);
      button.addEventListener('click',function(){
        var open=header.classList.toggle('gb-mobile-open');
        button.setAttribute('aria-expanded',open?'true':'false');
        button.setAttribute('aria-label',open?'Menu sluiten':'Menu openen');
      });
      nav.querySelectorAll('a').forEach(function(link){link.addEventListener('click',function(){header.classList.remove('gb-mobile-open');button.setAttribute('aria-expanded','false');button.setAttribute('aria-label','Menu openen');});});
      window.addEventListener('resize',function(){if(window.innerWidth>900){header.classList.remove('gb-mobile-open');button.setAttribute('aria-expanded','false');}});
    });
    </script>
    <?php
}
add_action('wp_head','gb_mobile_menu_assets',99);
