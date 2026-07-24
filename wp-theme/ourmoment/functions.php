<?php
/**
 * OurMoment Child Theme
 */

require_once get_stylesheet_directory() . '/inc/legal-pages.php';

/**
 * Delivery estimates, quoted to customers on product pages.
 *
 * Built from Printful's published figures plus the step Printful does not
 * see: Customily takes ~30 minutes to render the print file, and the order
 * then waits for a manual confirmation before production starts. One
 * business day is budgeted for that.
 *
 *   order handling   1 business day   (Customily render + our confirmation)
 *   production       2-5              (Printful, same in every facility)
 *   shipping US      3-4              (Printful domestic standard)
 *   shipping EU      3-7              (within-EU, from Barcelona or Riga)
 *
 * Rounded outward, never inward: a gift that arrives after the anniversary
 * is a refund and a one-star review, so the number a buyer reads has to be
 * one we beat, not one we hope to hit.
 *
 * Deliberately NOT in the Product schema yet — Google renders shippingDetails
 * straight into search results, and these are still derived from published
 * averages rather than measured. Move them into the schema once the sample
 * order has been placed and timed, and correct them here if it lands wide.
 */
define('OM_DELIVERY_US', '6–10 business days');
define('OM_DELIVERY_EU', '6–13 business days');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('astra-parent', get_template_directory_uri() . '/style.css');

    /**
     * Brand fonts, served from this server rather than fonts.googleapis.com.
     *
     * Loading them from Google sent every visitor's IP address to Google
     * before they had consented to anything — the practice German courts
     * have already ruled on, and this store targets Germany. Self-hosting
     * removes the transfer entirely, so there is nothing to consent to.
     *
     * It is also faster: no DNS lookup, TLS handshake and connection to a
     * third-party origin in front of the first paint.
     *
     * assets/css/fonts.css is generated — see the header inside it.
     */
    wp_enqueue_style(
        'ourmoment-fonts',
        get_stylesheet_directory_uri() . '/assets/css/fonts.css',
        [],
        '1.44.0'
    );
    wp_enqueue_style('ourmoment-style', get_stylesheet_uri(), ['astra-parent'], '1.44.0');
    wp_enqueue_script('ourmoment-js', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '1.44.0', true);
});

/**
 * Drop the dns-prefetch hint for fonts.googleapis.com.
 *
 * WordPress adds it whenever a stylesheet is registered against that host.
 * Now that the fonts are local, the hint resolves a domain the page never
 * contacts — dead weight, and a lingering signal that we still talk to
 * Google when we do not.
 */
add_filter('wp_resource_hints', function ($urls, $relation) {
    if ($relation !== 'dns-prefetch') {
        return $urls;
    }
    return array_values(array_filter($urls, function ($url) {
        $host = is_array($url) ? ($url['href'] ?? '') : $url;
        return strpos($host, 'fonts.googleapis.com') === false
            && strpos($host, 'fonts.gstatic.com') === false;
    }));
}, 10, 2);

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
          <rect x="1" y="6" width="14" height="11" rx="1.5"/>
          <path d="M15 10 L19 10 L22 14 L22 17 L15 17 Z"/>
          <circle cx="6" cy="19" r="2"/>
          <circle cx="18" cy="19" r="2"/>
        </svg>
        <div>
          <strong>Made to order &middot; arrives in <?php echo esc_html(OM_DELIVERY_US); ?></strong>
          <span>Printed and shipped from the facility closest to you — in the
          US for US orders, in Europe for European ones, so it never sits in
          customs. Europe: <?php echo esc_html(OM_DELIVERY_EU); ?>. You'll see
          your shipping cost at checkout.</span>
        </div>
      </li>
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
 * Show variable products as "From <lowest price>" instead of a range.
 *
 * A personalized pillow spanning 16.00 to 45.00 rendered as "16,00 € – 45,00 €",
 * which reads as two prices to compare rather than one to act on, and buries
 * the entry point that actually gets the click. The upper bound is still
 * visible once a size is chosen, which is the moment it means anything.
 *
 * wc_format_price_range() hands us $from and $to either as raw numbers or as
 * already-formatted strings depending on the caller, so both are handled.
 */
add_filter('woocommerce_format_price_range', function ($price, $from, $to) {
    $min = is_numeric($from) ? wc_price($from) : $from;
    return sprintf(
        '<span class="om-price-from">%s</span> %s',
        esc_html__('From', 'ourmoment'),
        $min
    );
}, 10, 3);

// Hide the SKU / category / tags meta block on the product page.
add_action('init', function () {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    // Ordering is rendered in the shop sidebar instead of the top bar.
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
});

/**
 * The price-range filter needs WooCommerce's slider script; enqueue it on
 * product archives, where our sidebar renders the price widget.
 *
 * The slider also kept showing $ after a visitor switched to EUR. WooCommerce
 * localises woocommerce_price_slider_params once, capturing whichever currency
 * was active at that moment, and the multi-currency switch happens too late to
 * be seen. Rather than guess at which internal filter still exists — the old
 * woocommerce_price_slider_params filter is long gone — patch the object the
 * script actually reads, right after it is printed.
 *
 * wp_add_inline_script(..., 'after') lands between the localised data and the
 * slider's own jQuery-ready init, so the corrected symbol is in place before
 * anything formats a label.
 *
 * Only the formatting is corrected. The bounds come from the _price meta in
 * store currency, so they hold while the manual rate is 1.00; a real exchange
 * rate would need the amounts converted too.
 */
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_shop') || !(is_shop() || is_product_category() || is_product_tag())) {
        return;
    }

    wp_enqueue_script('wc-price-slider');

    if (!function_exists('get_woocommerce_currency_symbol')) {
        return;
    }

    $format = str_replace(
        ['%1$s', '%2$s'],
        ['%s', '%v'],
        get_woocommerce_price_format()
    );

    wp_add_inline_script(
        'wc-price-slider',
        sprintf(
            'if (typeof woocommerce_price_slider_params !== "undefined") {'
          . 'woocommerce_price_slider_params.currency_format_symbol = %s;'
          . 'woocommerce_price_slider_params.currency_format = %s;'
          . 'woocommerce_price_slider_params.currency_format_decimal_sep = %s;'
          . 'woocommerce_price_slider_params.currency_format_thousand_sep = %s;'
          . '}',
            wp_json_encode(get_woocommerce_currency_symbol()),
            wp_json_encode($format),
            wp_json_encode(wc_get_price_decimal_separator()),
            wp_json_encode(wc_get_price_thousand_separator())
        ),
        'after'
    );
}, 20);

/**
 * Force key storefront strings to English regardless of the WordPress site
 * language (belt-and-suspenders until Site Language is set to English).
 */
add_filter('woocommerce_product_single_add_to_cart_text', function () {
    return __('Add to Cart', 'ourmoment');
});
add_filter('woocommerce_dropdown_variation_attribute_options_args', function ($args) {
    $args['show_option_none'] = 'Choose an option';
    return $args;
});

// Force the My Account navigation labels to English.
add_filter('woocommerce_account_menu_items', function ($items) {
    $labels = [
        'dashboard'       => 'Dashboard',
        'orders'          => 'Orders',
        'downloads'       => 'Downloads',
        'edit-address'    => 'Addresses',
        'edit-account'    => 'Account Details',
        'customer-logout' => 'Log Out',
    ];
    foreach ($labels as $key => $label) {
        if (isset($items[$key])) {
            $items[$key] = $label;
        }
    }
    return $items;
});

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
 * Point crawlers at the sitemap from robots.txt.
 *
 * WordPress serves a virtual robots.txt and neither core nor Yoast were
 * advertising the sitemap on this install, so search engines and AI crawlers
 * had to discover every URL by following links. One line fixes that.
 *
 * Only runs while robots.txt is virtual: dropping a real file in the web root
 * bypasses this filter entirely, so add the line there if that ever happens.
 */
add_filter('robots_txt', function ($output) {
    if (strpos($output, 'Sitemap:') !== false) {
        return $output;
    }
    return rtrim($output) . "\n\nSitemap: " . esc_url(home_url('/sitemap_index.xml')) . "\n";
});

/**
 * Serve /llms.txt — a plain-text summary of the store for AI assistants,
 * modelled on the emerging llms.txt convention.
 *
 * ponytail: speculative. The convention is young and no major crawler has
 * committed to reading it, but it is ~20 lines and costs nothing to serve.
 * Delete this block if it is still unread a year from now. The facts here
 * must stay true — an assistant that quotes a stale delivery estimate does
 * more damage than one that never quotes us at all.
 */
add_action('template_redirect', function () {
    if (untrailingslashit($_SERVER['REQUEST_URI'] ?? '') !== '/llms.txt') {
        return;
    }

    $name = get_bloginfo('name');
    $desc = get_bloginfo('description');

    // WordPress has already resolved this request to a 404 by the time
    // template_redirect fires, and serving the body without correcting the
    // status makes crawlers discard it. Claim the request explicitly.
    global $wp_query;
    if ($wp_query instanceof WP_Query) {
        $wp_query->is_404 = false;
    }
    status_header(200);

    header('Content-Type: text/plain; charset=utf-8');
    header('X-Robots-Tag: noindex');

    echo "# {$name}\n\n";
    echo "> {$desc}\n\n";
    echo "{$name} sells personalized keepsakes for couples: framed prints, "
       . "blankets, mugs and wall art customised with your names, your date "
       . "and your own photo.\n\n";
    echo "## How ordering works\n\n";
    echo "- Choose a product, upload a photo, and add names and a date.\n";
    echo "- A live preview shows the finished design before you pay.\n";
    echo "- Every item is made to order, then shipped.\n";
    echo "- Personalized items are final sale, except where the fault is ours.\n\n";
    echo "## Pages\n\n";
    foreach ([
        '/'             => 'Home',
        '/shop/'        => 'All products',
        '/how-it-works/' => 'How personalization works',
        '/faq/'         => 'Frequently asked questions',
        '/about/'       => 'About the brand',
        '/contact/'     => 'Contact',
        '/returns/'     => 'Returns and refunds',
    ] as $path => $label) {
        echo "- [{$label}](" . home_url($path) . ")\n";
    }
    exit;
});

/**
 * Fill the two Product schema gaps Search Console flags that we can answer
 * truthfully today.
 *
 * WooCommerce core emits this graph, not Yoast, so this is the WooCommerce
 * filter rather than wpseo_schema_product.
 *
 * - brand: satisfies the "no global identifier" warning. We have no GTIN —
 *   print-on-demand blanks are not retail SKUs — but the brand is us.
 * - validFrom: the date the product (and therefore its price) went live.
 *
 * Deliberately NOT added: shippingDetails and hasMerchantReturnPolicy. Both
 * describe commercial facts we do not have yet — the print provider is still
 * undecided, so shipping cost and delivery time are unknown. Google surfaces
 * those values directly in search results, so a wrong number is a broken
 * promise to a customer, not a markup warning. Add them once the provider is
 * chosen and the numbers are real.
 */
add_filter('woocommerce_structured_data_product', function ($markup, $product) {
    if (!is_array($markup) || !is_object($product)) {
        return $markup;
    }

    if (empty($markup['brand'])) {
        $markup['brand'] = [
            '@type' => 'Brand',
            'name'  => get_bloginfo('name'),
        ];
    }

    $created = $product->get_date_created();
    if ($created && !empty($markup['offers']) && is_array($markup['offers'])) {
        foreach ($markup['offers'] as $i => $offer) {
            if (is_array($offer) && empty($offer['validFrom'])) {
                $markup['offers'][$i]['validFrom'] = $created->date('c');
            }
        }
    }

    return $markup;
}, 10, 2);

/**
 * Line art for a homepage category card, picked by a keyword in the category
 * name. The catalogue is small and the names are ours, so a lookup beats
 * asking anyone to draw or upload artwork per category.
 *
 * Lives here rather than in the template: a function declared inside an
 * included file fatals the second time that file is included.
 *
 * Anything unmatched falls back to a gift box, which reads as "a present"
 * whatever the category turns out to hold.
 */
function ourmoment_category_art($name)
{
    $n = strtolower($name);
    $art = [
        'mug' => '<path d="M45 60 L45 145 C45 155 55 162 68 162 L112 162 C125 162 135 155 135 145 L135 60 Z" stroke="var(--text-soft)" stroke-width="1.5" stroke-linejoin="round"/>'
              . '<path d="M135 75 C155 75 165 85 165 100 C165 115 155 125 135 125" stroke="var(--text-soft)" stroke-width="1.5"/>'
              . '<path d="M90 95 C85 88 76 88 74 95 C71 103 78 110 90 119 C102 110 109 103 106 95 C104 88 95 88 90 95Z" stroke="var(--accent)" stroke-width="1.5"/>',

        'frame' => '<rect x="40" y="20" width="120" height="160" stroke="var(--frame-wood)" stroke-width="5"/>'
                 . '<rect x="52" y="32" width="96" height="136" stroke="var(--text-soft)" stroke-width=".8" opacity=".3"/>'
                 . '<path d="M100 70 C92 60 78 58 72 68 C64 82 74 98 88 108 L100 118 L112 108 C126 98 136 82 128 68 C122 58 108 60 100 70Z" stroke="var(--text-soft)" stroke-width="1.2"/>',

        'pillow' => '<path d="M42 62 C38 58 38 52 44 50 L156 50 C162 52 162 58 158 62 L158 138 C162 142 162 148 156 150 L44 150 C38 148 38 142 42 138 Z" stroke="var(--text-soft)" stroke-width="1.5" stroke-linejoin="round"/>'
                  . '<path d="M100 82 C94 74 82 73 78 82 C73 92 82 104 100 116 C118 104 127 92 122 82 C118 73 106 74 100 82Z" stroke="var(--accent)" stroke-width="1.4"/>',

        'puzzle' => '<path d="M50 55 L95 55 L95 70 C95 76 105 76 105 70 L105 55 L150 55 L150 100 L135 100 C129 100 129 110 135 110 L150 110 L150 145 L50 145 Z" stroke="var(--text-soft)" stroke-width="1.5" stroke-linejoin="round"/>'
                  . '<path d="M100 105 C96 100 88 99 85 105 C82 111 88 119 100 127 C112 119 118 111 115 105 C112 99 104 100 100 105Z" stroke="var(--accent)" stroke-width="1.3"/>',

        'candle' => '<rect x="70" y="85" width="60" height="75" rx="4" stroke="var(--text-soft)" stroke-width="1.5"/>'
                  . '<path d="M100 78 C94 68 100 58 100 48 C108 58 112 68 108 78 C106 82 102 82 100 78Z" stroke="var(--accent)" stroke-width="1.5"/>'
                  . '<path d="M78 108 L122 108" stroke="var(--text-soft)" stroke-width=".8" opacity=".4"/>',

        'ornament' => '<circle cx="100" cy="112" r="42" stroke="var(--text-soft)" stroke-width="1.5"/>'
                    . '<path d="M92 70 L92 60 C92 54 108 54 108 60 L108 70" stroke="var(--text-soft)" stroke-width="1.5"/>'
                    . '<path d="M100 98 C96 92 88 91 85 97 C82 104 89 112 100 122 C111 112 118 104 115 97 C112 91 104 92 100 98Z" stroke="var(--accent)" stroke-width="1.3"/>',

        'blanket' => '<path d="M40 60 C60 52 80 68 100 60 C120 52 140 68 160 60 L160 145 C140 153 120 137 100 145 C80 153 60 137 40 145 Z" stroke="var(--text-soft)" stroke-width="1.5" stroke-linejoin="round"/>'
                   . '<path d="M100 88 C95 81 85 80 82 88 C78 96 86 106 100 116 C114 106 122 96 118 88 C115 80 105 81 100 88Z" stroke="var(--accent)" stroke-width="1.3"/>',
    ];

    foreach ($art as $key => $svg) {
        if (strpos($n, $key) !== false) {
            return $svg;
        }
    }
    // Wall art rarely carries the word "frame"; catch the rest of that family.
    foreach (['poster', 'canvas', 'print', 'wall'] as $key) {
        if (strpos($n, $key) !== false) {
            return $art['frame'];
        }
    }
    return '<rect x="52" y="88" width="96" height="70" stroke="var(--text-soft)" stroke-width="1.5"/>'
         . '<path d="M52 108 L148 108 M100 88 L100 158" stroke="var(--text-soft)" stroke-width="1.5"/>'
         . '<path d="M100 88 C88 88 78 80 82 71 C86 63 98 68 100 88 C102 68 114 63 118 71 C122 80 112 88 100 88Z" stroke="var(--accent)" stroke-width="1.4"/>';
}

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
