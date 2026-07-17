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

    <div class="om-contact-frame om-fade">
      <svg viewBox="0 0 200 240" fill="none">
        <path d="M100 40 C85 25 62 22 52 38 C38 62 52 90 75 108 L100 130 L125 108 C148 90 162 62 148 38 C138 22 115 25 100 40Z" stroke="var(--text-soft)" stroke-width="1" fill="none"/>
        <path d="M80 115 C74 148 72 185 88 215 C94 226 100 230 100 230 C100 230 106 226 112 215 C128 185 126 148 120 115" stroke="var(--text-soft)" stroke-width="1" fill="none" stroke-linecap="round"/>
      </svg>
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
