<?php
/**
 * OurMoment Child Theme
 */

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('astra-parent', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'ourmoment-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;1,400&display=swap',
        [],
        null
    );
    wp_enqueue_style('ourmoment-style', get_stylesheet_uri(), ['astra-parent'], '1.4.0');
    wp_enqueue_script('ourmoment-js', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '1.4.0', true);
});

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});

/**
 * Replace Astra's header/footer with the brand nav/footer on ALL pages
 * (front page renders its own inside front-page.php).
 */
add_action('wp', function () {
    if (is_front_page()) {
        return;
    }
    remove_action('astra_header', 'astra_header_markup');
    remove_action('astra_footer', 'astra_footer_markup');
    add_action('astra_header', function () {
        get_template_part('template-parts/nav');
    });
    add_action('astra_footer', function () {
        get_template_part('template-parts/site-footer');
    });
});

/**
 * Our page templates lay out their own full-bleed sections, so mark them
 * and let the CSS unwrap Astra's fixed-width content container.
 */
add_filter('body_class', function ($classes) {
    if (is_page(['about', 'contact', 'how-it-works'])) {
        $classes[] = 'om-fullwidth';
    }
    return $classes;
});

// Refresh the nav cart count when products are added via AJAX
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $fragments['.om-cart-count'] = '<span class="om-cart-count' . ($count ? ' has-items' : '') . '">' . esc_html($count) . '</span>';
    return $fragments;
});

// Brand logo SVG symbol, available on every page
add_action('wp_head', function () {
    ?>
    <svg xmlns="http://www.w3.org/2000/svg" style="display:none">
      <defs>
        <symbol id="ourmoment-logo" viewBox="0 0 200 260">
          <path d="M62 52 C56 42 50 38 48 30 C46 24 50 18 56 16 C60 14 64 18 66 24 C68 30 66 38 65 42" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M65 42 C70 36 76 32 80 38 C84 44 82 52 78 58 C74 64 68 68 64 74" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M108 30 C114 24 122 22 128 28 C134 34 134 44 130 52 C126 60 120 66 118 72" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M64 74 C56 82 36 96 28 118 C20 140 28 168 48 188 C62 202 80 216 96 230" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M118 72 C128 80 150 94 160 118 C170 142 162 168 142 188 C128 202 110 216 96 230" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M96 230 C94 238 90 246 92 252 C94 258 100 258 102 252 C104 246 100 238 96 230Z" stroke="currentColor" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M78 120 C72 108 80 96 90 102 C96 106 96 114 92 122 C88 130 80 136 78 120Z" stroke="currentColor" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M112 120 C118 108 110 96 100 102 C94 106 94 114 98 122 C102 130 110 136 112 120Z" stroke="currentColor" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </symbol>
      </defs>
    </svg>
    <?php
});
