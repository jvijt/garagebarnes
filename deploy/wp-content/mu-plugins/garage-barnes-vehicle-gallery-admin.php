<?php
/**
 * Garage Barnes - reliable vehicle gallery picker + 4:3 crop workflow.
 */
if (!defined('ABSPATH')) { exit; }

/**
 * Generate a consistent 4:3 vehicle image size for new uploads.
 */
add_action('after_setup_theme', function () {
    add_image_size('gb_vehicle_4x3', 1200, 900, true);
});

/**
 * Make sure the WordPress media modal is fully available on Barnes Vehicles edit screens.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php'), true)) { return; }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'gb_vehicle') { return; }
    wp_enqueue_media();
    wp_enqueue_script('jquery');
}, 100);

/**
 * Replace the fragile inline gallery behaviour with a delegated media picker.
 */
add_action('admin_footer-post.php', 'gb_vehicle_gallery_admin_script', 100);
add_action('admin_footer-post-new.php', 'gb_vehicle_gallery_admin_script', 100);
function gb_vehicle_gallery_admin_script() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'gb_vehicle') { return; }
    ?>
    <style id="gb-vehicle-gallery-admin-css">
      #gb_gallery_preview{display:grid!important;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px!important;margin:16px 0!important}
      .gb-gallery-admin-item{position:relative;border:1px solid #dcdcde;border-radius:5px;background:#fff;overflow:hidden}
      .gb-gallery-admin-thumb{aspect-ratio:4/3;background:#f0f0f1;overflow:hidden}
      .gb-gallery-admin-thumb img{display:block;width:100%!important;height:100%!important;object-fit:cover!important}
      .gb-gallery-admin-actions{display:flex;gap:6px;justify-content:space-between;align-items:center;padding:8px}
      .gb-gallery-admin-actions .button{font-size:12px;min-height:30px;line-height:28px;padding:0 8px}
      .gb-gallery-admin-remove{color:#b32d2e!important;border-color:#d63638!important}
      .gb-gallery-help{margin:12px 0 4px;padding:10px 12px;background:#f6f7f7;border-left:4px solid #5dc01d}
      .gb-gallery-count{font-weight:700;color:#50575e}
    </style>
    <script>
    jQuery(function($){
      var frame = null;
      var maxPhotos = 10;
      var $ids = $('#gb_gallery_ids');
      var $preview = $('#gb_gallery_preview');
      var $choose = $('#gb_gallery_choose');
      var $clear = $('#gb_gallery_clear');
      if (!$ids.length || !$choose.length) return;

      if (!$preview.prev('.gb-gallery-help').length) {
        $preview.before('<div class="gb-gallery-help"><strong>Fotogalerij 4:3</strong><br>Voeg hier maximaal <strong>10 extra voertuigfoto\'s</strong> toe. Je kunt in de mediabibliotheek meerdere foto\'s na elkaar aanklikken. Gebruik “Bijsnijden 4:3” als een foto handmatig moet worden uitgesneden. <span class="gb-gallery-count"></span></div>');
      }

      function currentIds(){
        return String($ids.val() || '').split(',').map(function(v){return parseInt(v,10);}).filter(Boolean).slice(0,maxPhotos);
      }

      function updateCount(){
        $('.gb-gallery-count').text('(' + currentIds().length + '/' + maxPhotos + ' geselecteerd)');
      }

      function cropUrl(id){
        return window.ajaxurl.replace('admin-ajax.php','post.php') + '?post=' + id + '&action=edit&gb_vehicle_crop=43';
      }

      function renderOne(att){
        var id = att.id;
        var sizes = att.sizes || {};
        var src = (sizes.thumbnail && sizes.thumbnail.url) ? sizes.thumbnail.url : att.url;
        return '<div class="gb-gallery-admin-item" data-id="'+id+'">' +
          '<div class="gb-gallery-admin-thumb"><img src="'+src+'" alt=""></div>' +
          '<div class="gb-gallery-admin-actions">' +
            '<a class="button" href="'+cropUrl(id)+'" target="_blank" rel="noopener">Bijsnijden 4:3</a>' +
            '<button type="button" class="button gb-gallery-admin-remove" data-id="'+id+'">Verwijder</button>' +
          '</div>' +
        '</div>';
      }

      function rebuildPreview(ids){
        $preview.empty();
        updateCount();
        if (!ids.length) return;
        ids.forEach(function(id){
          var attachment = wp.media.attachment(id);
          attachment.fetch().always(function(){
            $preview.append(renderOne(attachment.toJSON()));
          });
        });
      }

      rebuildPreview(currentIds());

      // Remove older direct handlers and replace with one reliable delegated handler.
      $choose.off('click');
      $(document).off('click.gbVehicleGallery', '#gb_gallery_choose');
      $(document).on('click.gbVehicleGallery', '#gb_gallery_choose', function(e){
        e.preventDefault();
        if (typeof wp === 'undefined' || !wp.media) {
          window.alert('De WordPress mediabibliotheek kon niet worden geladen. Vernieuw de pagina en probeer opnieuw.');
          return;
        }

        frame = wp.media({
          frame: 'select',
          state: 'library',
          title: 'Selecteer maximaal 10 extra voertuigfoto\'s',
          button: { text: 'Gebruik geselecteerde foto\'s' },
          library: { type: 'image' },
          multiple: 'add'
        });

        frame.on('open', function(){
          var selection = frame.state().get('selection');
          selection.reset();
          currentIds().forEach(function(id){
            var attachment = wp.media.attachment(id);
            attachment.fetch();
            selection.add(attachment);
          });

          selection.on('add', function(model){
            if (selection.length > maxPhotos) {
              selection.remove(model);
              window.alert('Je kunt maximaal ' + maxPhotos + ' extra foto\'s per voertuig selecteren.');
            }
          });
        });

        frame.on('select', function(){
          var selected = frame.state().get('selection').toJSON().slice(0,maxPhotos);
          var ids = selected.map(function(x){ return x.id; });
          $ids.val(ids.join(',')).trigger('change');
          $preview.html(selected.map(renderOne).join(''));
          updateCount();
        });

        frame.open();
      });

      $clear.off('click');
      $(document).off('click.gbVehicleGalleryClear', '#gb_gallery_clear');
      $(document).on('click.gbVehicleGalleryClear', '#gb_gallery_clear', function(e){
        e.preventDefault();
        $ids.val('').trigger('change');
        $preview.empty();
        updateCount();
      });

      $(document).on('click', '.gb-gallery-admin-remove', function(e){
        e.preventDefault();
        var removeId = parseInt($(this).data('id'),10);
        var ids = currentIds().filter(function(id){ return id !== removeId; });
        $ids.val(ids.join(',')).trigger('change');
        $(this).closest('.gb-gallery-admin-item').remove();
        updateCount();
      });
    });
    </script>
    <?php
}

/**
 * On the WordPress attachment editor, lock the crop ratio fields to 4:3 when opened
 * from a Barnes Vehicle gallery.
 */
add_action('admin_notices', function () {
    if (!isset($_GET['gb_vehicle_crop']) || $_GET['gb_vehicle_crop'] !== '43') { return; }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'attachment') { return; }
    echo '<div class="notice notice-info"><p><strong>Garage Barnes voertuigfoto:</strong> klik op <em>Afbeelding bewerken</em> en gebruik Bijsnijden. De beeldverhouding wordt vastgezet op <strong>4:3</strong>.</p></div>';
});

add_action('admin_footer-post.php', function () {
    if (!isset($_GET['gb_vehicle_crop']) || $_GET['gb_vehicle_crop'] !== '43') { return; }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'attachment') { return; }
    ?>
    <script>
    (function(){
      function lockRatio(){
        var x = document.querySelector('input[id*="crop-ratio-x"], input[name*="crop-ratio-x"]');
        var y = document.querySelector('input[id*="crop-ratio-y"], input[name*="crop-ratio-y"]');
        if (x && y) {
          x.value = '4'; y.value = '3';
          x.readOnly = true; y.readOnly = true;
          x.title = y.title = 'Vaste Garage Barnes beeldverhouding 4:3';
          return true;
        }
        return false;
      }
      if (!lockRatio()) {
        var obs = new MutationObserver(function(){ if(lockRatio()) obs.disconnect(); });
        obs.observe(document.body,{childList:true,subtree:true});
      }
    })();
    </script>
    <?php
}, 100);
