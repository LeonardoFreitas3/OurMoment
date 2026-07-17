<?php
/**
 * Site footer.
 *
 * Legal links only render once their page is published, and shop links are
 * pulled from the real product categories, so the footer never points at a
 * 404 while the store is still being set up.
 */

$om_has_woo = function_exists('wc_get_page_permalink');
$om_shop_url = $om_has_woo ? wc_get_page_permalink('shop') : home_url('/shop/');

// ── Legal links, only if published ────────────────────────────────
$om_legal_slugs = [
    'terms'   => 'Terms of Service',
    'privacy' => 'Privacy Policy',
    'returns' => 'Returns & Refunds',
];

$om_legal = [];
$om_has_privacy = false;
foreach ($om_legal_slugs as $om_slug => $om_label) {
    $om_page = get_page_by_path($om_slug);
    if ($om_page && $om_page->post_status === 'publish') {
        $om_legal[] = ['url' => get_permalink($om_page), 'label' => $om_label];
        if ($om_slug === 'privacy') {
            $om_has_privacy = true;
        }
    }
}
if (!$om_has_privacy) {
    $om_privacy_id = (int) get_option('wp_page_for_privacy_policy');
    if ($om_privacy_id && get_post_status($om_privacy_id) === 'publish') {
        $om_legal[] = ['url' => get_permalink($om_privacy_id), 'label' => 'Privacy Policy'];
    }
}

// ── Shop links from real product categories ───────────────────────
$om_cats = [];
if ($om_has_woo) {
    $om_terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 4,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ]);
    if (!is_wp_error($om_terms)) {
        foreach ($om_terms as $om_term) {
            $om_cats[] = ['url' => get_term_link($om_term), 'label' => $om_term->name];
        }
    }
}
?>
<footer class="om-footer">
  <div class="om-container">

    <div class="om-footer-grid">

      <div class="om-footer-brand">
        <?php ourmoment_logo('om-footer-logo'); ?>
        <p class="om-footer-tagline">Meaningful gifts for couples.<br>Made to order, made to keep.</p>
        <div class="om-footer-social">
          <a href="#" aria-label="Instagram">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/>
            </svg>
          </a>
          <a href="#" aria-label="Pinterest">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <circle cx="12" cy="12" r="10"/><path d="M8 21 C9 16 10 13 11 10 C11 8 12 7 14 7 C16 7 17 9 16 12 C15 15 13 15 12 14"/>
            </svg>
          </a>
          <a href="#" aria-label="TikTok">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <path d="M9 12 L9 19 C9 21 11 22 13 21 C15 20 15 18 15 17 L15 3 C16 5 18 7 21 7"/>
            </svg>
          </a>
        </div>
      </div>

      <nav class="om-footer-col" aria-label="Shop">
        <h3>Shop</h3>
        <ul>
          <li><a href="<?php echo esc_url($om_shop_url); ?>">All Products</a></li>
          <?php foreach ($om_cats as $om_cat) : ?>
            <li><a href="<?php echo esc_url($om_cat['url']); ?>"><?php echo esc_html($om_cat['label']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <nav class="om-footer-col" aria-label="Help">
        <h3>Help</h3>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/how-it-works/')); ?>">How It Works</a></li>
          <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a></li>
          <?php if ($om_has_woo) : ?>
            <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">My Account</a></li>
            <li><a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>">Cart</a></li>
          <?php endif; ?>
        </ul>
      </nav>

      <nav class="om-footer-col" aria-label="Company">
        <h3>Company</h3>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/about/')); ?>">Our Story</a></li>
          <?php foreach ($om_legal as $om_link) : ?>
            <li><a href="<?php echo esc_url($om_link['url']); ?>"><?php echo esc_html($om_link['label']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

    </div>

    <?php
    // Newsletter only appears once a mailing plugin is installed — an input
    // that posts nowhere is worse than no input at all.
    $om_newsletter = '';
    if (shortcode_exists('mailpoet_form')) {
        $om_newsletter = do_shortcode('[mailpoet_form id="1"]');
    } elseif (shortcode_exists('klaviyo_form')) {
        $om_newsletter = do_shortcode('[klaviyo_form]');
    }
    ?>
    <?php if ($om_newsletter) : ?>
      <div class="om-footer-newsletter">
        <h3>Join the list</h3>
        <p>Ideas, new pieces, and 10% off your first order.</p>
        <?php echo $om_newsletter; // phpcs:ignore WordPress.Security.EscapeOutput ?>
      </div>
    <?php endif; ?>

    <div class="om-footer-bottom">
      <p>&copy; <?php echo esc_html(date('Y')); ?> OurMoment. All rights reserved.</p>

      <div class="om-footer-pay">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
          <rect x="4" y="9" width="16" height="12" rx="1.5"/>
          <path d="M8 9 L8 6.5 C8 5 9.3 4 11 4 L13 4 C14.7 4 16 5 16 6.5 L16 9"/>
        </svg>
        <span class="om-pay-label">Secure payments</span>
        <ul aria-label="Accepted payment methods">
          <li>Visa</li>
          <li>Mastercard</li>
          <li>Apple&nbsp;Pay</li>
          <li>Google&nbsp;Pay</li>
        </ul>
      </div>
    </div>

  </div>
</footer>
