<?php
/**
 * Garage Barnes - footer 4-column layout.
 */
if (!defined('ABSPATH')) { exit; }

add_action('wp_footer', function () {
    if (is_admin()) { return; }
    ?>
    <style id="gb-footer-four-columns-css">
      .gb-footer-grid{grid-template-columns:1.35fr 1fr 1.15fr 1.25fr!important}
      @media(max-width:1000px){.gb-footer-grid{grid-template-columns:repeat(2,1fr)!important}}
      @media(max-width:700px){.gb-footer-grid{grid-template-columns:1fr!important}}
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
      var grid=document.querySelector('.gb-footer-grid');
      if(!grid) return;
      var cols=Array.from(grid.children);
      if(cols.length<5) return;

      var address=cols[1];
      var phone=cols[2];
      if(address && phone){
        var phoneLabel=phone.querySelector('.gb-footer-label');
        if(phoneLabel) phoneLabel.textContent='Telefoon';
        while(phone.firstChild){ address.appendChild(phone.firstChild); }
        phone.remove();
      }
    });
    </script>
    <?php
}, 100);
