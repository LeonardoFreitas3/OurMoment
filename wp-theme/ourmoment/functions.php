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
    wp_enqueue_style('ourmoment-style', get_stylesheet_uri(), ['astra-parent'], '1.23.0');
    wp_enqueue_script('ourmoment-js', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '1.23.0', true);
});

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});

// Disable Astra's "scroll to top" button.
add_filter('astra_scroll_to_top_enable', '__return_false');

/**
 * Replace Astra's header/footer with the brand nav/footer on ALL pages
 * (front page renders its own inside front-page.php).
 *
 * remove_action() on the named callbacks is not enough: with Astra's Header
 * Footer Builder active the copyright bar is hooked by a different callback,
 * so it survived and rendered a second footer under ours. Clear the hooks
 * outright, then attach ours.
 */
add_action('wp', function () {
    if (is_front_page()) {
        return;
    }
    remove_all_actions('astra_header');
    remove_all_actions('astra_footer');
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
    if (is_page(['about', 'contact', 'how-it-works', 'faq'])) {
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
 * Native contact form handler.
 *
 * The form posts to admin-post.php; we validate a nonce, drop honeypot
 * submissions, sanitise, email the site admin, and redirect back with a
 * status flag. Removes the Contact Form 7 dependency — the themed form just
 * works. Delivery still depends on the site being able to send mail; set up
 * an SMTP plugin so wp_mail actually reaches the inbox.
 */
function om_handle_contact() {
    $redirect = wp_get_referer() ?: home_url('/contact/');

    // Honeypot — a filled hidden field means a bot; pretend success silently.
    if (!empty($_POST['om_website'])) {
        wp_safe_redirect(add_query_arg('om_sent', 'ok', $redirect) . '#contact');
        exit;
    }

    $ok = isset($_POST['om_contact_nonce'])
        && wp_verify_nonce($_POST['om_contact_nonce'], 'om_contact');

    $name    = sanitize_text_field(wp_unslash($_POST['om_name'] ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['om_email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['om_message'] ?? ''));

    if (!$ok || $name === '' || !is_email($email) || $message === '') {
        wp_safe_redirect(add_query_arg('om_sent', 'error', $redirect) . '#contact');
        exit;
    }

    $to      = get_option('admin_email');
    $subject = sprintf('New contact from %s — OurMoment', $name);
    $body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
    $headers = ['Reply-To: ' . $name . ' <' . $email . '>'];

    wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('om_sent', 'ok', $redirect) . '#contact');
    exit;
}
add_action('admin_post_om_contact', 'om_handle_contact');
add_action('admin_post_nopriv_om_contact', 'om_handle_contact');

/**
 * Trust block under the add-to-cart button on product pages.
 *
 * Honest, POD-compatible signals only: the live preview ("what you see is
 * what you get") answers the rival's pixelated-render complaints, and none
 * of it promises delivery speed or support the store can't control.
 * Written in pt-PT as the source; translate to EN via TranslatePress.
 */
add_action('woocommerce_after_add_to_cart_form', function () {
    ?>
    <ul class="om-trust">
      <li class="om-trust-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path d="M2 12 C4 7 8 4 12 4 C16 4 20 7 22 12 C20 17 16 20 12 20 C8 20 4 17 2 12Z"/>
          <circle cx="12" cy="12" r="3.2"/>
        </svg>
        <div>
          <strong>What you see is what you get</strong>
          <span>Personalize it and see the final result before you buy. No surprises.</span>
        </div>
      </li>
      <li class="om-trust-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path d="M12 21 C12 21 4 15.5 4 9.5 C4 6.5 6.2 4.5 8.7 5.3 C10.2 5.8 11.4 7 12 8 C12.6 7 13.8 5.8 15.3 5.3 C17.8 4.5 20 6.5 20 9.5 C20 15.5 12 21 12 21Z"/>
        </svg>
        <div>
          <strong>Made just for you</strong>
          <span>Every piece carries your names, dates, and photos.</span>
        </div>
      </li>
      <li class="om-trust-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <rect x="4" y="10" width="16" height="11" rx="1.5"/>
          <path d="M8 10 L8 7 C8 4.8 9.8 3 12 3 C14.2 3 16 4.8 16 7 L16 10"/>
        </svg>
        <div>
          <strong>Secure checkout</strong>
          <span>Encrypted checkout, with the payment methods you already trust.</span>
        </div>
      </li>
    </ul>
    <?php
});

/**
 * Image-quality tip on the product page, below the trust block. Personalized
 * prints are only as sharp as the photo you upload, so tell people up front.
 */
add_action('woocommerce_after_add_to_cart_form', function () {
    ?>
    <div class="om-imgtip">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
        <rect x="3" y="5" width="18" height="14" rx="2"/>
        <circle cx="9" cy="10" r="2"/>
        <path d="M3 17 L9 12 L13 15 L17 11 L21 15"/>
      </svg>
      <div>
        <strong>For the sharpest print</strong>
        <span>Upload a <strong>JPG or PNG</strong> at the highest resolution you have — ideally <strong>1500&nbsp;&times;&nbsp;1500&nbsp;px or larger</strong> (around 2&ndash;5&nbsp;MB). Make sure it's clear, well-lit, and in focus. Avoid screenshots, zoomed-in phone crops, and heavily filtered photos — they can print soft or pixelated.</span>
      </div>
    </div>
    <?php
}, 20);

/**
 * The "Select options" loop button on personalized (variable) products reads
 * better as "Personalize" for this store.
 */
add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) {
    if ($product && $product->is_type('variable')) {
        return __('Personalize', 'ourmoment');
    }
    return $text;
}, 10, 2);

/**
 * Trim Printify's keyword-stuffed product titles for display.
 *
 * Printify imports titles like "Romantic Heart Outline Mug — Couple Silhouette
 * Coffee Cup"; everything after the em/en dash is SEO filler. Show only the
 * part before it on the storefront (shop loop, single product, cart,
 * breadcrumb) while leaving the stored post_title — and therefore the slug,
 * the admin list, and Yoast's SEO title — untouched, so the search keywords
 * are kept where they belong.
 *
 * ponytail: display-only, so it never touches the DB and is fully reversible.
 */
add_filter('the_title', function ($title, $post_id = 0) {
    if (is_admin() || !$post_id || get_post_type($post_id) !== 'product') {
        return $title;
    }
    // Split on an em or en dash padded by spaces — Printify's separator.
    $clean = preg_split('/\s+[—–]\s+/u', $title, 2)[0];
    return trim($clean) !== '' ? trim($clean) : $title;
}, 10, 2);

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
