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
    <h2 class="om-section-title om-fade">Bestsellers</h2>
    <?php
    if (class_exists('WooCommerce')) {
        echo do_shortcode('[products limit="3" columns="3" orderby="popularity"]');
    } else {
        echo '<p style="text-align:center;color:var(--text-soft);font-size:.85rem;">Products coming soon.</p>';
    }
    ?>
    <div style="text-align:center;margin-top:2rem;" class="om-fade">
      <?php if (class_exists('WooCommerce')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn">Shop All</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/about'); ?>

<?php get_template_part('template-parts/testimonials'); ?>

<?php get_template_part('template-parts/contact'); ?>

<?php get_template_part('template-parts/site-footer'); ?>

<?php wp_footer(); ?>
</body>
</html>
