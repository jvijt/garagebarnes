<?php
/**
 * Garage Barnes - styled single news/article detail pages.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('ocean_display_related_posts', '__return_false', 99);
add_filter('ocean_display_post_next_prev', '__return_false', 99);

add_filter('the_content', function ($content) {
    if (is_admin() || !is_singular('post') || !in_the_loop() || !is_main_query()) return $content;

    $post_id = get_the_ID();
    $title = get_the_title($post_id);
    $date = get_the_date('d.m.Y', $post_id);
    $image = get_the_post_thumbnail($post_id, 'large', array('class'=>'gb-news-detail-image','loading'=>'eager'));
    $first_paragraph = '';
    $remaining = $content;

    if (preg_match('/<p\b[^>]*>.*?<\/p>/is', $content, $match)) {
        $first_paragraph = $match[0];
        $remaining = preg_replace('/<p\b[^>]*>.*?<\/p>/is', '', $content, 1);
    }
    if ($first_paragraph === '') $first_paragraph = '<p>' . esc_html(wp_trim_words(wp_strip_all_tags($content), 45, '…')) . '</p>';

    $prev = get_previous_post();
    $next = get_next_post();

    ob_start(); ?>
    <article class="gb-news-detail">
      <a class="gb-news-back" href="<?php echo esc_url(home_url('/')); ?>#nieuws">← Terug naar nieuws</a>
      <header class="gb-news-detail-header">
        <span class="gb-news-detail-kicker">Nieuws &amp; acties</span>
        <h1><?php echo esc_html($title); ?></h1>
      </header>

      <?php if ($image) : ?>
        <div class="gb-news-detail-intro">
          <div class="gb-news-detail-media"><?php echo $image; ?></div>
          <div class="gb-news-detail-lead"><div><span class="gb-news-detail-date"><?php echo esc_html($date); ?></span><?php echo wp_kses_post($first_paragraph); ?></div></div>
        </div>
      <?php else : ?>
        <div class="gb-news-detail-lead gb-news-detail-lead-full"><div><span class="gb-news-detail-date"><?php echo esc_html($date); ?></span><?php echo wp_kses_post($first_paragraph); ?></div></div>
      <?php endif; ?>

      <div class="gb-news-detail-body"><?php echo wp_kses_post($remaining); ?></div>

      <?php if ($prev || $next) : ?>
        <nav class="gb-news-detail-nav" aria-label="Vorige en volgende berichten">
          <div class="gb-news-nav-side gb-news-nav-prev"><?php if ($prev) : ?><a href="<?php echo esc_url(get_permalink($prev)); ?>"><span>← Vorig bericht</span><strong><?php echo esc_html(get_the_title($prev)); ?></strong></a><?php endif; ?></div>
          <div class="gb-news-nav-side gb-news-nav-next"><?php if ($next) : ?><a href="<?php echo esc_url(get_permalink($next)); ?>"><span>Volgend bericht →</span><strong><?php echo esc_html(get_the_title($next)); ?></strong></a><?php endif; ?></div>
        </nav>
      <?php endif; ?>
    </article>
    <?php return ob_get_clean();
}, 40);

add_action('wp_head', function () {
    if (!is_singular('post')) return; ?>
    <style id="gb-news-detail-css">
      body.single-post #main #content-wrap{max-width:none!important;width:100%!important;padding:0!important}
      body.single-post #primary{width:100%!important;max-width:none!important;float:none!important;padding:0!important;border:0!important}
      body.single-post #secondary,body.single-post .widget-area,body.single-post .sidebar-container{display:none!important}
      body.single-post .entry-header,body.single-post .single-post-title,body.single-post .thumbnail,body.single-post .post-tags,body.single-post .meta,body.single-post .post-pagination-wrap,body.single-post #related-posts,body.single-post .related-posts,body.single-post .oceanwp-related-posts{display:none!important}
      body.single-post .entry-content{margin:0!important;padding:0!important}
      .gb-news-detail{max-width:1180px;margin:0 auto;padding:64px 24px 90px;color:#252525}
      .gb-news-back{display:inline-block;margin:0 0 30px;color:#4f9f20!important;font-weight:700;text-decoration:none!important}
      .gb-news-back:hover{text-decoration:underline!important;text-underline-offset:3px}
      .gb-news-detail-header{max-width:900px;margin-bottom:36px}
      .gb-news-detail-kicker{display:block;margin-bottom:12px;color:#5dc01d;font-size:13px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}
      .gb-news-detail-header h1{margin:0;font-size:clamp(38px,5vw,66px);line-height:1.02;letter-spacing:-.04em;color:#202020}
      .gb-news-detail-intro{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(300px,.85fr);gap:38px;align-items:center;margin-bottom:34px}
      .gb-news-detail-media{overflow:hidden;border-radius:12px;background:#eef0eb;min-height:420px}
      .gb-news-detail-image{display:block;width:100%;height:100%;min-height:420px;object-fit:cover}
      .gb-news-detail-lead{padding:0!important;border:0!important;border-radius:0!important;background:transparent!important;font-size:18px!important;line-height:1.8!important;color:#444}
      .gb-news-detail-lead p{margin:0;color:#444;font-size:inherit!important;line-height:inherit!important;font-family:inherit!important;font-weight:inherit!important}
      .gb-news-detail-date{display:block;margin:0 0 14px;color:#5dc01d!important;font-size:15px;font-weight:800;letter-spacing:.03em}
      .gb-news-detail-lead-full{display:block;max-width:880px;margin:0 auto 34px}
      .gb-news-detail-body{max-width:880px;margin:0 auto;font-size:18px;line-height:1.8;color:#444}
      .gb-news-detail-body h2,.gb-news-detail-body h3{color:#202020;line-height:1.18}
      .gb-news-detail-body h2{margin:46px 0 16px;font-size:34px}.gb-news-detail-body h3{margin:36px 0 14px;font-size:26px}
      .gb-news-detail-body img{max-width:100%;height:auto;border-radius:10px}.gb-news-detail-body a{color:#4f9f20!important;font-weight:700}
      .gb-news-detail-nav{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:64px;padding-top:28px;border-top:1px solid #dfe3dc}
      .gb-news-nav-side a{display:flex;flex-direction:column;gap:7px;min-height:110px;padding:22px 24px;border:1px solid #dfe3dc;border-radius:10px;background:#fff;color:#202020!important;text-decoration:none!important;transition:border-color .2s ease,transform .2s ease}
      .gb-news-nav-side a:hover{border-color:#5dc01d;transform:translateY(-2px)}.gb-news-nav-side span{color:#5dc01d;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:.08em}.gb-news-nav-side strong{font-size:18px;line-height:1.3}.gb-news-nav-next{text-align:right}
      @media(max-width:800px){.gb-news-detail{padding:44px 18px 70px}.gb-news-detail-intro{grid-template-columns:1fr;gap:22px}.gb-news-detail-media,.gb-news-detail-image{min-height:0}.gb-news-detail-image{aspect-ratio:4/3}.gb-news-detail-lead{font-size:17px!important}.gb-news-detail-body{font-size:17px}.gb-news-detail-nav{grid-template-columns:1fr}.gb-news-nav-next{text-align:left}}
    </style>
    <?php
}, 100);
