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
      <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
      <li><a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/#shop')); ?>">Shop</a></li>
      <li><a href="<?php echo esc_url(home_url('/#how-it-works')); ?>">How It Works</a></li>
      <li><a href="<?php echo esc_url(home_url('/#about')); ?>">About</a></li>
      <li><a href="<?php echo esc_url(home_url('/#contact')); ?>">Contact</a></li>
    </ul>

    <?php if (function_exists('wc_get_cart_url')) : ?>
      <a class="om-nav-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Cart">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path d="M6 8 L6 6 C6 3.8 8 2 12 2 C16 2 18 3.8 18 6 L18 8"/>
          <rect x="3" y="8" width="18" height="13" rx="1"/>
        </svg>
        <?php $count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0; ?>
        <span class="om-cart-count<?php echo $count ? ' has-items' : ''; ?>"><?php echo esc_html($count); ?></span>
      </a>
    <?php else : ?>
      <span class="om-nav-cart" aria-hidden="true"></span>
    <?php endif; ?>

  </div>
</nav>
