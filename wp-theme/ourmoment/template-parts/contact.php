<?php
/**
 * Contact section. Pass ['show_title' => false] when the page banner
 * already carries the heading.
 */
$om_show_title = $args['show_title'] ?? true;
?>
<section id="contact" class="om-contact">
  <div class="om-fade">
    <div class="om-contact-frame">
      <svg viewBox="0 0 200 240" fill="none">
        <path d="M100 40 C85 25 62 22 52 38 C38 62 52 90 75 108 L100 130 L125 108 C148 90 162 62 148 38 C138 22 115 25 100 40Z" stroke="var(--text-soft)" stroke-width="1" fill="none"/>
        <path d="M80 115 C74 148 72 185 88 215 C94 226 100 230 100 230 C100 230 106 226 112 215 C128 185 126 148 120 115" stroke="var(--text-soft)" stroke-width="1" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
  </div>
  <div class="om-fade">
    <?php if ($om_show_title) : ?>
      <h2 class="om-section-title" style="text-align:left;">Contact</h2>
    <?php endif; ?>
    <p>We would love to hear from you. Please fill out the form below or reach out to us at <a href="mailto:contact@ourmoment.com">contact@ourmoment.com</a> and we will respond as soon as possible.</p>
    <?php
    if (shortcode_exists('contact-form-7')) {
        echo do_shortcode('[contact-form-7 id="contact-form" title="Contact Form"]');
    } else {
        ?>
        <form method="post" action="#">
          <input type="text" name="name" placeholder="Your Name" required>
          <input type="email" name="email" placeholder="Your Email Address" required>
          <textarea name="message" placeholder="Your Message" required></textarea>
          <button type="submit" class="btn">Send Message</button>
        </form>
        <?php
    }
    ?>
  </div>
</section>
