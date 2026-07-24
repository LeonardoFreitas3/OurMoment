<?php
$om_show_title = $args['show_title'] ?? true;
$om_show_cta   = $args['show_cta']   ?? true;
?>
<section id="about" class="om-about">
  <div class="om-container">

    <?php if ($om_show_title) : ?>
      <h2 class="om-section-title om-fade">Our Story</h2>
    <?php endif; ?>

    <?php
    /**
     * A framed piece with a sample couple's names under it — the product as a
     * customer would receive it.
     *
     * The brand mark stands in for the photo. The drawing it replaces was a
     * heart on a long stem that read as a balloon, which sold nothing.
     */
    ?>
    <div class="om-frame om-about-frame om-fade">
      <?php ourmoment_logo('om-about-frame-logo'); ?>
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
