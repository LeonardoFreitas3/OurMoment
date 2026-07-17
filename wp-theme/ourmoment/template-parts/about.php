<?php
$om_show_title = $args['show_title'] ?? true;
$om_show_cta   = $args['show_cta']   ?? true;
?>
<section id="about" class="om-about">
  <div class="om-container">

    <?php if ($om_show_title) : ?>
      <h2 class="om-section-title om-fade">Our Story</h2>
    <?php endif; ?>

    <div class="om-frame om-about-frame om-fade">
      <svg viewBox="0 0 200 240" fill="none">
        <path d="M100 40 C85 25 62 22 52 38 C38 62 52 90 75 108 L100 130 L125 108 C148 90 162 62 148 38 C138 22 115 25 100 40Z" stroke="var(--text-soft)" stroke-width="1" fill="none"/>
        <path d="M80 115 C74 148 72 185 88 215 C94 226 100 230 100 230 C100 230 106 226 112 215 C128 185 126 148 120 115" stroke="var(--text-soft)" stroke-width="1" fill="none" stroke-linecap="round"/>
      </svg>
      <div class="om-frame-label">
        <span>Emily &amp; Jack</span>
        <small>07.08.2024</small>
      </div>
    </div>

    <div class="om-about-text om-fade">
      <p>At OurMoment, we believe in the power of meaningful, personalized gifts that celebrate the love and connection between two people.</p>
      <p>We were born to create timeless keepsakes for couples, and we pour the same care into every piece. Our mission is to help you capture your special moments and turn them into beautiful, lasting memories.</p>
      <?php if ($om_show_cta && function_exists('wc_get_page_permalink')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn">Shop Now</a>
      <?php endif; ?>
    </div>

  </div>
</section>
