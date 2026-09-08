<?php
/**
 * Garage Barnes - editorial single news/article detail pages.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('ocean_display_related_posts', '__return_false', 99);
add_filter('ocean_display_post_next_prev', '__return_false', 99);

add_filter('the_content', function ($content) {
    if (is_admin() || !is_singular('post') || !in_the_loop() || !is_main_query()) return $content;

    $post_id = get_the_ID();
    $title   = get_the_title($post_id);
    $date    = get_the_date('d.m.Y', $post_id);
    $image   = get_the_post_thumbnail($post_id, 'large', array(
        'class'   => 'gb-news-detail-image',
        'loading' => 'eager',
    ));
    $prev = get_previous_post();
    $next = get_next_post();

    ob_start(); ?>
    <article class="gb-news-detail">
      <div class="gb-news-detail-toolbar">
        <a class="gb-news-back" href="<?php echo esc_url(home_url('/')); ?>#nieuws">← Terug naar nieuws</a>
        <span class="gb-news-detail-kicker">Nieuws &amp; acties</span>
      </div>

      <header class="gb-news-detail-header">
        <h1><?php echo esc_html($title); ?></h1>
        <div class="gb-news-detail-meta">
          <span class="gb-news-detail-date"><?php echo esc_html($date); ?></span>
          <span class="gb-news-detail-dot" aria-hidden="true"></span>
          <span>Garage Barnes</span>
        </div>
      </header>

      <?php if ($image) : ?>
        <figure class="gb-news-detail-media"><?php echo $image; ?></figure>
      <?php endif; ?>

      <div class="gb-news-detail-body"><?php echo wp_kses_post($content); ?></div>

      <?php if ($prev || $next) : ?>
        <nav class="gb-news-detail-nav" aria-label="Vorige en volgende berichten">
          <div class="gb-news-nav-side gb-news-nav-prev">
            <?php if ($prev) : ?>
              <a href="<?php echo esc_url(get_permalink($prev)); ?>">
                <span>← Vorig bericht</span>
                <strong><?php echo esc_html(get_the_title($prev)); ?></strong>
              </a>
            <?php endif; ?>
          </div>
          <div class="gb-news-nav-side gb-news-nav-next">
            <?php if ($next) : ?>
              <a href="<?php echo esc_url(get_permalink($next)); ?>">
                <span>Volgend bericht →</span>
                <strong><?php echo esc_html(get_the_title($next)); ?></strong>
              </a>
            <?php endif; ?>
          </div>
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

      .gb-news-detail{max-width:1180px;margin:0 auto;padding:58px 24px 92px;color:#242424}
      .gb-news-detail-toolbar{display:flex;align-items:center;justify-content:space-between;gap:24px;margin-bottom:46px;padding-bottom:18px;border-bottom:1px solid #e3e6e0}
      .gb-news-back{color:#4f9f20!important;font-size:14px;font-weight:700;text-decoration:none!important}
      .gb-news-back:hover{text-decoration:underline!important;text-underline-offset:3px}
      .gb-news-detail-kicker{color:#5dc01d;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}

      .gb-news-detail-header{max-width:960px;margin:0 auto 38px;text-align:center}
      .gb-news-detail-header h1{margin:0;font-size:clamp(42px,5.8vw,72px);line-height:1.02;letter-spacing:-.045em;color:#202020}
      .gb-news-detail-meta{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:22px;color:#777;font-size:14px}
      .gb-news-detail-date{color:#4f9f20;font-weight:800}
      .gb-news-detail-dot{width:4px;height:4px;border-radius:50%;background:#b7bcb3}

      .gb-news-detail-media{margin:0 0 52px;overflow:hidden;border-radius:14px;background:#eef0eb}
      .gb-news-detail-image{display:block;width:100%;height:auto;aspect-ratio:16/8.5;object-fit:cover}

      .gb-news-detail-body{max-width:820px;margin:0 auto;font-size:18px;line-height:1.82;color:#444}
      .gb-news-detail-body>p:first-child{margin-top:0;font-size:20px;line-height:1.75;color:#333}
      .gb-news-detail-body p{margin:0 0 24px}
      .gb-news-detail-body h2,.gb-news-detail-body h3{color:#202020;line-height:1.18;letter-spacing:-.02em}
      .gb-news-detail-body h2{margin:48px 0 18px;font-size:34px}
      .gb-news-detail-body h3{margin:38px 0 15px;font-size:26px}
      .gb-news-detail-body ul,.gb-news-detail-body ol{margin:0 0 26px;padding-left:24px}
      .gb-news-detail-body li{margin-bottom:8px}
      .gb-news-detail-body blockquote{margin:36px 0;padding:2px 0 2px 24px;border-left:4px solid #5dc01d;color:#333;font-size:21px;line-height:1.6}
      .gb-news-detail-body img{max-width:100%;height:auto;border-radius:10px}
      .gb-news-detail-body a{color:#4f9f20!important;font-weight:700}

      .gb-news-detail-nav{display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:960px;margin:70px auto 0;padding-top:28px;border-top:1px solid #dfe3dc}
      .gb-news-nav-side a{display:flex;flex-direction:column;gap:8px;min-height:116px;padding:24px 26px;border:1px solid #dfe3dc;border-radius:10px;background:#fff;color:#202020!important;text-decoration:none!important;transition:border-color .2s ease,transform .2s ease,box-shadow .2s ease}
      .gb-news-nav-side a:hover{border-color:#5dc01d;transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.04)}
      .gb-news-nav-side span{color:#5dc01d;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.09em}
      .gb-news-nav-side strong{font-size:18px;line-height:1.35}
      .gb-news-nav-next{text-align:right}

      @media(max-width:800px){
        .gb-news-detail{padding:38px 18px 68px}
        .gb-news-detail-toolbar{margin-bottom:34px;padding-bottom:14px}
        .gb-news-detail-kicker{font-size:11px;letter-spacing:.1em}
        .gb-news-detail-header{margin-bottom:28px;text-align:left}
        .gb-news-detail-header h1{font-size:clamp(36px,11vw,50px)}
        .gb-news-detail-meta{justify-content:flex-start;margin-top:16px}
        .gb-news-detail-media{margin-bottom:34px;border-radius:10px}
        .gb-news-detail-image{aspect-ratio:4/3}
        .gb-news-detail-body{font-size:17px}
        .gb-news-detail-body>p:first-child{font-size:18px}
        .gb-news-detail-nav{grid-template-columns:1fr;margin-top:54px}
        .gb-news-nav-next{text-align:left}
      }
    </style>
    <?php
}, 100);
