<?php
$om_show_title = $args['show_title'] ?? true;
$om_show_cta   = $args['show_cta']   ?? true;
?>
<section id="about" class="om-about">
  <div class="om-frame om-fade" style="aspect-ratio:3/4;">
    <svg viewBox="0 0 200 240" fill="none" style="width:60%;height:auto;flex-shrink:0;">
      <path d="M100 40 C85 25 62 22 52 38 C38 62 52 90 75 108 L100 130 L125 108 C148 90 162 62 148 38 C138 22 115 25 100 40Z" stroke="var(--text-soft)" stroke-width="1" fill="none"/>
      <path d="M80 115 C74 148 72 185 88 215 C94 226 100 230 100 230 C100 230 106 226 112 215 C128 185 126 148 120 115" stroke="var(--text-soft)" stroke-width="1" fill="none" stroke-linecap="round"/>
    </svg>
    <div class="om-frame-label">
      <span>Emily &amp; Jack</span>
      <small>07.08.2024</small>
    </div>
  </div>
  <div class="om-about-text om-fade">
    <?php if ($om_show_title) : ?>
      <h2 class="om-section-title" style="text-align:left;">Our Story</h2>
    <?php endif; ?>
    <p>At OurMoment, we believe in the power of meaningful, personalized gifts that celebrate love and connection.</p>
    <p>Founded with the goal of creating timeless keepsakes for couples, we fill each piece we create with thoughtfulness and care. Our mission is to help you capture your special moments and turn them into beautiful, lasting memories.</p>
    <?php if ($om_show_cta && function_exists('wc_get_page_permalink')) : ?>
      <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn" style="margin-top:1.2rem;">Shop Now</a>
    <?php endif; ?>
  </div>
</section>
