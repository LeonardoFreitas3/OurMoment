<?php
/**
 * Inner-page banner.
 * Pass a title/subtitle via get_template_part()'s $args.
 *
 * No mark above the heading: it repeated the one already in the nav a few
 * pixels higher, and pushed the page's actual subject below the fold on
 * short screens. The heading is what the visitor came to read, so it goes
 * first.
 */
$om_title    = $args['title']    ?? get_the_title();
$om_subtitle = $args['subtitle'] ?? '';
?>
<header class="om-page-banner">
  <div class="om-container">
    <h1><?php echo esc_html($om_title); ?></h1>
    <?php if ($om_subtitle) : ?>
      <p><?php echo esc_html($om_subtitle); ?></p>
    <?php endif; ?>
  </div>
</header>
