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
     * The same framed mark the Contact page shows, so the two story pages
     * open on the same image.
     */
    ?>
    <div class="om-contact-frame om-fade">
      <div class="om-banner-frame om-banner-frame--lg" aria-hidden="true">
        <div class="om-banner-frame-mat">
          <?php ourmoment_logo('om-banner-frame-logo'); ?>
        </div>
        <span class="om-banner-frame-hook"></span>
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
