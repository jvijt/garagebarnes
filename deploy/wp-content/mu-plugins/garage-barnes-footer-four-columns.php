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
      .gb-footer-grid .gb-footer-address-link,
      .gb-footer-grid .gb-footer-address-link:link,
      .gb-footer-grid .gb-footer-address-link:visited,
      .gb-footer-grid .gb-footer-address-link:hover,
      .gb-footer-grid .gb-footer-address-link:focus,
      .gb-footer-grid .gb-footer-address-link:active,
      .gb-footer-grid .gb-footer-phone-link,
      .gb-footer-grid .gb-footer-phone-link:link,
      .gb-footer-grid .gb-footer-phone-link:visited,
      .gb-footer-grid .gb-footer-phone-link:hover,
      .gb-footer-grid .gb-footer-phone-link:focus,
      .gb-footer-grid .gb-footer-phone-link:active{color:#fff!important}
      .gb-footer-grid .gb-footer-address-link{display:flex;flex-direction:column;gap:7px;text-decoration:none!important}
      .gb-footer-grid .gb-footer-address-link:hover{text-decoration:underline!important;text-underline-offset:3px}
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
        var addressLines=address.querySelectorAll('strong');
        if(addressLines.length){
          var mapsLink=document.createElement('a');
          mapsLink.className='gb-footer-address-link';
          mapsLink.href='https://www.google.com/maps/search/?api=1&query=Zonneke+4%2C+9220+Hamme%2C+Belgium';
          mapsLink.target='_blank';
          mapsLink.rel='noopener noreferrer';
          addressLines.forEach(function(line){ mapsLink.appendChild(line); });
          var label=address.querySelector('.gb-footer-label');
          if(label){ label.insertAdjacentElement('afterend',mapsLink); }
          else{ address.prepend(mapsLink); }
        }

        var phoneLabel=phone.querySelector('.gb-footer-label');
        if(phoneLabel) phoneLabel.textContent='Telefoon';
        Array.from(phone.querySelectorAll('a')).forEach(function(link){ link.classList.add('gb-footer-phone-link'); });
        while(phone.firstChild){ address.appendChild(phone.firstChild); }
        phone.remove();
      }
    });
    </script>
    <?php
}, 100);
