<?php
$om_img = get_stylesheet_directory_uri() . '/assets/img';
?>
<section class="om-hero">
  <picture class="om-hero-photo">
    <source
      type="image/webp"
      srcset="<?php echo esc_url("$om_img/banner-700.webp"); ?> 700w,
              <?php echo esc_url("$om_img/banner-1042.webp"); ?> 1042w"
      sizes="100vw">
    <img
      src="<?php echo esc_url("$om_img/banner-1042.jpg"); ?>"
      srcset="<?php echo esc_url("$om_img/banner-700.jpg"); ?> 700w,
              <?php echo esc_url("$om_img/banner-1042.jpg"); ?> 1042w"
      sizes="100vw"
      alt=""
      width="1054" height="1492"
      fetchpriority="high" decoding="async">
  </picture>

  <div class="om-hero-scrim" aria-hidden="true"></div>

  <div class="om-hero-content">
    <?php ourmoment_logo('om-hero-logo'); ?>
    <h1>OurMoment</h1>
    <p class="om-hero-tagline">Presentes com Significado para Casais</p>
    <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '#shop'); ?>" class="btn">Ver Loja</a>
  </div>
</section>
