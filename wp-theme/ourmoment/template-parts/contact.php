<?php
/**
 * Contact section. Pass ['show_title' => false] when the page banner
 * already carries the heading.
 */
$om_show_title = $args['show_title'] ?? true;
?>
<section id="contact" class="om-contact">
  <div class="om-container">

    <?php if ($om_show_title) : ?>
      <h2 class="om-section-title om-fade">Contact</h2>
    <?php endif; ?>

    <?php
    /**
     * The brand mark in a frame, matching the one in the page banner.
     *
     * Replaces a heart-and-stem line drawing that read as a balloon rather
     * than a keepsake — and said nothing about what the shop makes.
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

    <p class="om-contact-intro om-fade">
      We'd love to hear from you. Fill out the form below, or reach us at
      <a href="mailto:contact@ourmoment.com">contact@ourmoment.com</a> and we'll get
      back to you as soon as we can.
    </p>

    <div class="om-contact-form om-fade">
      <?php $om_sent = isset($_GET['om_sent']) ? sanitize_key($_GET['om_sent']) : ''; ?>

      <?php if ($om_sent === 'ok') : ?>
        <p class="om-form-note om-form-ok">Message sent. We'll get back to you soon.</p>
      <?php elseif ($om_sent === 'error') : ?>
        <p class="om-form-note om-form-error">Something went wrong. Please check the fields and try again.</p>
      <?php endif; ?>

      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="om_contact">
        <?php wp_nonce_field('om_contact', 'om_contact_nonce'); ?>
        <input type="text" name="om_name" placeholder="Your name" required>
        <input type="email" name="om_email" placeholder="Your email" required>
        <textarea name="om_message" placeholder="Your message" required></textarea>
        <!-- honeypot: people don't see this field; bots fill it in -->
        <input type="text" name="om_website" tabindex="-1" autocomplete="off" aria-hidden="true" class="om-hp">
        <button type="submit" class="btn">Send message</button>
      </form>
    </div>

  </div>
</section>
