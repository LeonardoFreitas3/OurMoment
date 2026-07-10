<?php
/**
 * Page template for the "how-it-works" slug.
 */
get_header();
?>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'How It Works',
      'subtitle' => 'From your photo to their doorstep, in three steps.',
  ]);

  get_template_part('template-parts/how-it-works', null, ['show_title' => false]);
  ?>

  <section class="om-page-cta om-fade">
    <div class="om-container">
      <h2>Ready to make something they'll keep?</h2>
      <?php if (function_exists('wc_get_page_permalink')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn">Start Personalizing</a>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
