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
    <svg class="om-page-banner-logo" viewBox="0 0 200 260" aria-hidden="true"><use href="#ourmoment-logo"/></svg>
    <h1><?php echo esc_html($om_title); ?></h1>
    <?php if ($om_subtitle) : ?>
      <p><?php echo esc_html($om_subtitle); ?></p>
    <?php endif; ?>
  </div>
</header>
