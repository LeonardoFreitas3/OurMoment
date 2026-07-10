<?php
/**
 * OurMoment Child Theme
 */

require_once get_stylesheet_directory() . '/inc/legal-pages.php';

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('astra-parent', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'ourmoment-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;1,400&display=swap',
        [],
        null
    );
    wp_enqueue_style('ourmoment-style', get_stylesheet_uri(), ['astra-parent'], '1.7.0');
    wp_enqueue_script('ourmoment-js', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '1.7.0', true);
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

/**
 * Print the brand logo. Decorative by default — pass an $alt only where the
 * logo is the sole content of a link, so screen readers get a destination.
 */
function ourmoment_logo($class = '', $alt = '') {
    printf(
        '<img src="%s" class="%s" alt="%s" width="288" height="288" %s>',
        esc_url(get_stylesheet_directory_uri() . '/assets/img/logo.png'),
        esc_attr($class),
        esc_attr($alt),
        $alt ? '' : 'aria-hidden="true"'
    );
}
