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

<!-- Nav -->
<nav class="om-nav" id="om-nav">
  <div class="om-nav-inner">
    <button class="om-nav-toggle" aria-label="Menu" onclick="document.querySelector('.om-nav-links').classList.toggle('open')">
      <span></span><span></span><span></span>
    </button>
    <ul class="om-nav-links">
      <li><a href="#shop">Shop</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </div>
</nav>

<?php get_template_part('template-parts/hero'); ?>

<!-- Shop Section -->
<section id="shop" style="padding:clamp(3.5rem,7vw,6rem) 0;">
  <div class="container" style="max-width:1100px;margin:0 auto;padding:0 clamp(1.25rem,4vw,2.5rem);">
    <h2 class="om-section-title om-fade">Shop</h2>
    <?php
    if (class_exists('WooCommerce')) {
        echo do_shortcode('[products limit="3" columns="3" orderby="date" order="DESC"]');
    } else {
        echo '<p style="text-align:center;color:var(--text-soft);font-size:.85rem;">Products coming soon — install WooCommerce and Printify to add products.</p>';
    }
    ?>
    <div style="text-align:center;margin-top:2rem;" class="om-fade">
      <?php if (class_exists('WooCommerce')): ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn">Shop Now</a>
      <?php else: ?>
        <a href="#" class="btn">Shop Now</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/about'); ?>

<?php get_template_part('template-parts/contact'); ?>

<!-- Footer -->
<footer class="om-footer">
  <div style="max-width:1100px;margin:0 auto;padding:0 clamp(1.25rem,4vw,2.5rem);">
    <div class="om-footer-inner">
      <p>&copy; <?php echo date('Y'); ?> OurMoment. All rights reserved.</p>
      <div class="om-footer-social">
        <a href="#" aria-label="Instagram">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/>
          </svg>
        </a>
        <a href="#" aria-label="Pinterest">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"/><path d="M8 21 C9 16 10 13 11 10 C11 8 12 7 14 7 C16 7 17 9 16 12 C15 15 13 15 12 14"/>
          </svg>
        </a>
        <a href="#" aria-label="TikTok">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M9 12 L9 19 C9 21 11 22 13 21 C15 20 15 18 15 17 L15 3 C16 5 18 7 21 7"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
