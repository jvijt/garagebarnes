<?php
/**
 * Garage Barnes - show featured images on homepage news cards.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('do_shortcode_tag', function ($output, $tag) {
    if ($tag !== 'garage_barnes_home' || !is_front_page()) { return $output; }

    $posts = get_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish',
    ));
    if (!$posts) { return $output; }

    $index = 0;
    $output = preg_replace_callback('/<article class="gb-news-card">/', function ($matches) use (&$index, $posts) {
        if (!isset($posts[$index])) { return $matches[0]; }
        $post = $posts[$index++];
        if (!has_post_thumbnail($post)) { return $matches[0]; }

        $image = get_the_post_thumbnail(
            $post,
            'large',
            array(
                'class' => 'gb-news-card-image',
                'loading' => 'lazy',
                'alt' => get_the_title($post),
            )
        );
        if (!$image) { return $matches[0]; }

        return $matches[0] . '<a class="gb-news-card-media" href="' . esc_url(get_permalink($post)) . '">' . $image . '</a>';
    }, $output);

    return $output;
}, 20, 2);

add_action('wp_head', function () {
    if (!is_front_page()) { return; }
    ?>
    <style id="gb-home-news-images-css">
      .gb-news-card{padding:0!important;overflow:hidden;display:flex;flex-direction:column}
      .gb-news-card-media{display:block;width:100%;aspect-ratio:16/9;overflow:hidden;background:#e9ece6}
      .gb-news-card-media:hover{text-decoration:none!important}
      .gb-news-card-image{display:block;width:100%!important;height:100%!important;object-fit:cover}
      .gb-news-card>span,.gb-news-card>h3,.gb-news-card>a:not(.gb-news-card-media),.gb-news-card>p{margin-left:30px;margin-right:30px}
      .gb-news-card>span{margin-top:28px}
      .gb-news-card>h3{margin-top:14px}
      .gb-news-card>a:not(.gb-news-card-media){margin-top:auto;margin-bottom:30px}
      .gb-news-placeholder{padding:30px!important}
      .gb-news-placeholder>span,.gb-news-placeholder>h3,.gb-news-placeholder>p{margin-left:0;margin-right:0}
      @media(max-width:700px){
        .gb-news-card>span,.gb-news-card>h3,.gb-news-card>a:not(.gb-news-card-media),.gb-news-card>p{margin-left:24px;margin-right:24px}
        .gb-news-card>a:not(.gb-news-card-media){margin-bottom:24px}
      }
    </style>
    <?php
}, 100);
