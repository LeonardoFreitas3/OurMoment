<?php
/**
 * Shop archive — OurMoment override.
 *
 * Two-column layout: a functional filters sidebar on the left, products on
 * the right. The catalog-ordering dropdown is moved into the sidebar (see
 * functions.php, which removes it from the default top bar).
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

get_header('shop');

do_action('woocommerce_before_main_content');
?>

<?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
  <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
<?php endif; ?>

<?php do_action('woocommerce_archive_description'); ?>

<div class="om-shop-layout">

  <aside class="om-shop-sidebar">
    <button class="om-filters-toggle" type="button" aria-expanded="false" onclick="this.closest('.om-shop-sidebar').classList.toggle('open'); this.setAttribute('aria-expanded', this.closest('.om-shop-sidebar').classList.contains('open'));">
      Filters
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
    </button>
    <div class="om-shop-sidebar-inner">
      <?php get_template_part('template-parts/shop-filters'); ?>
    </div>
  </aside>

  <div class="om-shop-main">
    <?php do_action('woocommerce_before_shop_loop'); // notices + result count (ordering moved to sidebar) ?>

    <?php if (woocommerce_product_loop()) : ?>

      <?php woocommerce_product_loop_start(); ?>

      <?php if (wc_get_loop_prop('total')) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php do_action('woocommerce_shop_loop'); ?>
          <?php wc_get_template_part('content', 'product'); ?>
        <?php endwhile; ?>
      <?php endif; ?>

      <?php woocommerce_product_loop_end(); ?>

      <?php do_action('woocommerce_after_shop_loop'); ?>

    <?php else : ?>

      <?php do_action('woocommerce_no_products_found'); ?>

    <?php endif; ?>
  </div>

</div>

<?php
do_action('woocommerce_after_main_content');

get_footer('shop');
