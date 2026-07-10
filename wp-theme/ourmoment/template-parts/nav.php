<?php
/**
 * Brand navigation. Each item is its own page.
 */
$om_shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');

$om_items = [
    ['url' => home_url('/'),              'label' => 'Home',         'current' => is_front_page()],
    ['url' => $om_shop_url,               'label' => 'Shop',         'current' => function_exists('is_woocommerce') && (is_shop() || is_product_category() || is_product())],
    ['url' => home_url('/how-it-works/'), 'label' => 'How It Works', 'current' => is_page('how-it-works')],
    ['url' => home_url('/about/'),        'label' => 'About',        'current' => is_page('about')],
    ['url' => home_url('/contact/'),      'label' => 'Contact',      'current' => is_page('contact')],
];
?>
<nav class="om-nav" id="om-nav">
  <div class="om-nav-inner">

    <button class="om-nav-toggle" id="om-nav-toggle" aria-label="Menu" aria-expanded="false" aria-controls="om-nav-links">
      <span></span><span></span><span></span>
    </button>

    <a class="om-nav-brand" href="<?php echo esc_url(home_url('/')); ?>">
      <svg viewBox="0 0 200 260" aria-hidden="true"><use href="#ourmoment-logo"/></svg>
      <span>OurMoment</span>
    </a>

    <ul class="om-nav-links" id="om-nav-links">
      <?php foreach ($om_items as $om_item) : ?>
        <li>
          <a href="<?php echo esc_url($om_item['url']); ?>"<?php echo $om_item['current'] ? ' aria-current="page"' : ''; ?>>
            <?php echo esc_html($om_item['label']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php if (function_exists('wc_get_cart_url')) : ?>
      <a class="om-nav-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Cart">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path d="M6 8 L6 6 C6 3.8 8 2 12 2 C16 2 18 3.8 18 6 L18 8"/>
          <rect x="3" y="8" width="18" height="13" rx="1"/>
        </svg>
        <?php $om_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0; ?>
        <span class="om-cart-count<?php echo $om_count ? ' has-items' : ''; ?>"><?php echo esc_html($om_count); ?></span>
      </a>
    <?php else : ?>
      <span class="om-nav-cart" aria-hidden="true"></span>
    <?php endif; ?>

  </div>
</nav>
