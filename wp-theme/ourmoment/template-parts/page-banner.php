<?php
/**
 * Inner-page banner.
 * Pass a title/subtitle via get_template_part()'s $args.
 */
$om_title    = $args['title']    ?? get_the_title();
$om_subtitle = $args['subtitle'] ?? '';
?>
<header class="om-page-banner">
  <div class="om-container">
    <?php
    /**
     * The logo hung in a picture frame.
     *
     * A bare logo said nothing about what we sell. Framing it says it in one
     * glance, and reuses the frame the homepage category card already draws —
     * same wooden edge, same mat — so the pages read as one set.
     *
     * Built from the frame markup rather than an image so it inherits the
     * brand tokens and stays sharp at any density.
     */
    ?>
    <div class="om-banner-frame" aria-hidden="true">
      <div class="om-banner-frame-mat">
        <?php ourmoment_logo('om-banner-frame-logo'); ?>
      </div>
      <span class="om-banner-frame-hook"></span>
    </div>
    <h1><?php echo esc_html($om_title); ?></h1>
    <?php if ($om_subtitle) : ?>
      <p><?php echo esc_html($om_subtitle); ?></p>
    <?php endif; ?>
  </div>
</header>
