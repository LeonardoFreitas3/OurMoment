<?php
/**
 * Page template for the "about" slug.
 */
get_header();
?>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'Our Story',
      'subtitle' => 'Meaningful gifts, made for the moments that matter.',
  ]);

  get_template_part('template-parts/about', null, ['show_title' => false]);
  get_template_part('template-parts/testimonials');
  ?>
</main>

<?php get_footer(); ?>
