<?php
/**
 * Page template for the "about" slug.
 */
get_header();
?>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'A Nossa História',
      'subtitle' => 'Presentes com significado, feitos para os momentos que contam.',
  ]);

  get_template_part('template-parts/about', null, ['show_title' => false]);
  get_template_part('template-parts/testimonials');
  ?>
</main>

<?php get_footer(); ?>
