<?php
/**
 * Page template for the "contact" slug.
 */
get_header();
?>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'Contacto',
      'subtitle' => 'Adorávamos ouvir-te.',
  ]);

  get_template_part('template-parts/contact', null, ['show_title' => false]);
  ?>
</main>

<?php get_footer(); ?>
