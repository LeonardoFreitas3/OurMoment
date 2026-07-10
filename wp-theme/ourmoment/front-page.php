<?php
/**
 * OurMoment — Custom Front Page (bypasses Astra's default layout)
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/nav'); ?>

<?php get_template_part('template-parts/hero'); ?>

<?php get_template_part('template-parts/categories'); ?>

<?php get_template_part('template-parts/how-it-works'); ?>

<!-- Shop Section -->
<section id="shop" class="om-shop">
  <div class="om-container">
    <h2 class="om-section-title om-fade">New Arrivals</h2>
    <div class="om-fade">
      <?php
      if (class_exists('WooCommerce')) {
          echo do_shortcode('[products limit="4" columns="4" orderby="date" order="DESC" visibility="visible"]');
      } else {
          echo '<p class="om-shop-empty">Products coming soon.</p>';
      }
      ?>
    </div>
    <?php if (class_exists('WooCommerce')) : ?>
      <div class="om-shop-cta om-fade">
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn">Shop All</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php get_template_part('template-parts/about'); ?>

<?php get_template_part('template-parts/testimonials'); ?>

<?php get_template_part('template-parts/contact'); ?>

<?php get_template_part('template-parts/site-footer'); ?>

<?php wp_footer(); ?>
</body>
</html>
