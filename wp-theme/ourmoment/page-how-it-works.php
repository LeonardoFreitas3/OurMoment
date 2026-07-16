<?php
/**
 * Page template for the "how-it-works" slug.
 */
get_header();
?>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'Como Funciona',
      'subtitle' => 'Da vossa foto até à porta, em três passos.',
  ]);

  get_template_part('template-parts/how-it-works', null, ['show_title' => false]);
  ?>

  <section class="om-page-cta om-fade">
    <div class="om-container">
      <h2>Prontos para criar algo que fica para sempre?</h2>
      <?php if (function_exists('wc_get_page_permalink')) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn">Começar a Personalizar</a>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
