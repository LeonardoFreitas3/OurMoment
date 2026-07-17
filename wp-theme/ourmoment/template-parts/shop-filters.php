<?php
/**
 * Shop filters sidebar — functional WooCommerce filters.
 * Sort, category navigation, and price range.
 */
defined('ABSPATH') || exit;
?>
<div class="om-filters">

  <?php if (function_exists('woocommerce_catalog_ordering')) : ?>
    <div class="om-filter-group">
      <h3>Sort by</h3>
      <?php woocommerce_catalog_ordering(); ?>
    </div>
  <?php endif; ?>

  <div class="om-filter-group">
    <h3>Categories</h3>
    <?php
    the_widget('WC_Widget_Product_Categories', [
        'title'              => '',
        'count'              => 1,
        'hierarchical'       => 1,
        'hide_empty'         => 1,
        'show_children_only' => 0,
        'dropdown'           => 0,
    ]);
    ?>
  </div>

  <div class="om-filter-group">
    <h3>Price</h3>
    <?php the_widget('WC_Widget_Price_Filter', ['title' => '']); ?>
  </div>

  <?php if (isset($_GET['min_price']) || isset($_GET['max_price']) || isset($_GET['product_cat'])) : ?>
    <a class="om-filter-clear" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Clear filters</a>
  <?php endif; ?>

</div>
