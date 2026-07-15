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
      We would love to hear from you. Fill out the form below, or reach us at
      <a href="mailto:contact@ourmoment.com">contact@ourmoment.com</a> and we will
      respond as soon as possible.
    </p>

    <div class="om-contact-form om-fade">
      <?php $om_sent = isset($_GET['om_sent']) ? sanitize_key($_GET['om_sent']) : ''; ?>

      <?php if ($om_sent === 'ok') : ?>
        <p class="om-form-note om-form-ok">Mensagem enviada. Respondemos assim que pudermos.</p>
      <?php elseif ($om_sent === 'error') : ?>
        <p class="om-form-note om-form-error">Algo correu mal. Confirma os campos e tenta de novo.</p>
      <?php endif; ?>

      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="om_contact">
        <?php wp_nonce_field('om_contact', 'om_contact_nonce'); ?>
        <input type="text" name="om_name" placeholder="O teu nome" required>
        <input type="email" name="om_email" placeholder="O teu email" required>
        <textarea name="om_message" placeholder="A tua mensagem" required></textarea>
        <!-- honeypot: os humanos não veem este campo; os bots preenchem-no -->
        <input type="text" name="om_website" tabindex="-1" autocomplete="off" aria-hidden="true" class="om-hp">
        <button type="submit" class="btn">Enviar mensagem</button>
      </form>
    </div>

  </div>
</section>
