<?php
/**
 * Page template for the "contact" slug.
 */
get_header();
?>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'Contact',
      'subtitle' => 'We would love to hear from you.',
  ]);

  get_template_part('template-parts/contact', null, ['show_title' => false]);
  ?>
</main>

<?php get_footer(); ?>
