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
      <?php ourmoment_logo('', 'OurMoment — Home'); ?>
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

    <div class="om-nav-actions">
      <?php
      /**
       * Currency switcher, from WooPayments Multi-Currency.
       *
       * The function only exists once multi-currency is active, so its
       * absence is the guard — the nav simply renders without it on a
       * single-currency store, and if WooPayments is ever removed nothing
       * fatals. Symbols on, flags off: flags imply a country, and a euro
       * price is not a country.
       */
      if (function_exists('wc_get_currency_switcher_markup')) :
          ?>
          <div class="om-nav-currency">
            <?php echo wc_get_currency_switcher_markup(['symbol' => true, 'flag' => false]); ?>
          </div>
          <?php
      endif;
      ?>

      <?php if (function_exists('wc_get_page_permalink')) : ?>
        <?php
        $om_logged_in  = is_user_logged_in();
        $om_account_url = wc_get_page_permalink('myaccount');
        ?>
        <a class="om-nav-icon" href="<?php echo esc_url($om_account_url); ?>"
           aria-label="<?php echo $om_logged_in ? 'My Account' : 'Log in'; ?>"
           title="<?php echo $om_logged_in ? 'My Account' : 'Log in'; ?>">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 21 C4 16.5 7.5 14 12 14 C16.5 14 20 16.5 20 21"/>
          </svg>
          <?php if ($om_logged_in) : ?><span class="om-nav-dot" aria-hidden="true"></span><?php endif; ?>
        </a>

        <a class="om-nav-icon om-nav-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Cart" title="Cart">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path d="M6 8 L6 6 C6 3.8 8 2 12 2 C16 2 18 3.8 18 6 L18 8"/>
            <rect x="3" y="8" width="18" height="13" rx="1"/>
          </svg>
          <?php $om_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0; ?>
          <span class="om-cart-count<?php echo $om_count ? ' has-items' : ''; ?>"><?php echo esc_html($om_count); ?></span>
        </a>
      <?php endif; ?>
    </div>

  </div>
</nav>
