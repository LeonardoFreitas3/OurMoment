<?php
/**
 * Legal pages.
 *
 * Creates Terms, Privacy and Returns as draft-quality starting points when the
 * theme is activated, and registers WordPress's privacy policy page.
 *
 * These texts are a starting point, NOT legal advice. Every [bracketed] field
 * must be filled in before going live. The personalized-goods exception to the
 * 14-day EU right of withdrawal is the most sensitive clause here and is worth
 * having reviewed.
 *
 * Pages are only ever created, never overwritten — once you edit them in the
 * admin, re-activating the theme will not touch your changes.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return array<string, array{title: string, content: string}>
 */
function ourmoment_legal_pages() {
    $email = '[contact@ourmoment.com]';

    $terms = <<<HTML
<!-- wp:paragraph -->
<p><em>Last updated: [date]</em></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>These terms govern your use of the OurMoment website and any purchase you make from it. By placing an order, you accept them.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Seller:</strong> [legal company name]<br><strong>Address:</strong> [address]<br><strong>Email:</strong> {$email}<br><strong>VAT / NIF:</strong> [number]</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Products</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We sell personalized gifts for couples — mugs, framed prints and canvas — printed to order by our production partners.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Product photographs and on-screen previews are representations. Colours may vary slightly between screens and the printed item, and between production batches. Frames and mugs may differ marginally in shade or grain.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Your content</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When you personalize a product you upload photographs and enter text. You confirm that:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>You own the rights to the content, or have permission to use it</li>
<li>The content does not infringe anyone's copyright, trademark, or right to their own image</li>
<li>The content is not unlawful, hateful, obscene or defamatory</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>You grant us a limited licence to reproduce your content solely to manufacture and deliver your order. We may refuse to print any design that breaches the above, and will refund you in full if we do.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Orders and prices</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Your order is an offer to buy. The contract forms when we send you an order confirmation email.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Prices are in euros and include VAT where applicable. Shipping is calculated at checkout. If a price is listed incorrectly through an obvious error, we may cancel the order and refund you rather than honour it. We may also refuse or cancel an order if the item is unavailable, if we suspect fraud, or if your content breaches these terms.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Payment</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Payments are handled by Stripe. We never see or store your card details.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Production and delivery</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Personalized items are made to order. Typical production takes [2–5] business days, and delivery within the EU takes a further [3–7] business days. These are estimates, not guarantees. Delays caused by carriers, customs or peak seasons are outside our control. Risk passes to you on delivery.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Cancellations and returns</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Because our products are personalized, the standard 14-day right of withdrawal does not apply to them. Our full policy, including your rights when an item arrives faulty, is set out on our <a href="/returns/">Returns &amp; Refunds</a> page, which forms part of these terms.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Our liability</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We do not exclude liability for death or personal injury caused by our negligence, for fraud, or for anything else that cannot lawfully be excluded. Otherwise, our liability for any order is limited to the amount you paid for it, and we are not liable for indirect or consequential loss. Nothing here affects your statutory rights as a consumer.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Intellectual property</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The OurMoment name, logo, site design and all original artwork remain our property. You may not reproduce them without written permission.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Governing law</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>These terms are governed by Portuguese law, and disputes fall under the jurisdiction of the Portuguese courts. As a consumer in the EU you may also use the European Commission's Online Dispute Resolution platform at <a href="https://ec.europa.eu/consumers/odr" rel="nofollow">ec.europa.eu/consumers/odr</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$email}</p>
<!-- /wp:paragraph -->
HTML;

    $privacy = <<<HTML
<!-- wp:paragraph -->
<p><em>Last updated: [date]</em></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This policy explains what personal data OurMoment collects, why we collect it, and what rights you have over it. We are the data controller.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Controller:</strong> [legal company name]<br><strong>Address:</strong> [address]<br><strong>Email:</strong> {$email}<br><strong>VAT / NIF:</strong> [number]</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What we collect</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>When you place an order:</strong> your name, email address, billing address, shipping address, phone number, and the contents of your order.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>When you personalize a product:</strong> the names, dates and text you enter, and any photographs you upload. These are personal data, and photographs of people may reveal information about others too. Only upload photographs you have the right to use.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>When you contact us:</strong> your name, email address and the content of your message.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>When you browse:</strong> your IP address, browser type, pages visited and cookie data.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We do not see or store your full payment card details. Payments are processed by Stripe, who receive them directly.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Why we collect it, and our legal basis</h2>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table"><table>
<thead><tr><th>Purpose</th><th>Legal basis</th></tr></thead>
<tbody>
<tr><td>Processing and delivering your order</td><td>Performance of a contract</td></tr>
<tr><td>Sending order and shipping confirmations</td><td>Performance of a contract</td></tr>
<tr><td>Sending your design to our print partner</td><td>Performance of a contract</td></tr>
<tr><td>Preventing fraud and abuse</td><td>Legitimate interest</td></tr>
<tr><td>Analytics and improving the site</td><td>Consent</td></tr>
<tr><td>Marketing emails</td><td>Consent, withdrawable at any time</td></tr>
<tr><td>Keeping accounting and tax records</td><td>Legal obligation</td></tr>
</tbody>
</table></figure>
<!-- /wp:table -->

<!-- wp:heading -->
<h2>Who we share it with</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Printify</strong> and its print providers — your name, shipping address and design files, so the item can be printed and shipped</li>
<li><strong>Customily</strong> — your personalization data and uploaded images, to generate the print file</li>
<li><strong>Stripe</strong> — payment processing</li>
<li><strong>[hosting provider]</strong> — website hosting</li>
<li><strong>[analytics provider, if used]</strong> — usage statistics, only with your consent</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Some processors may transfer data outside the EU. Where they do, the transfer is covered by Standard Contractual Clauses or an adequacy decision. We never sell your personal data.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>How long we keep it</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Order and invoice records:</strong> [10] years, as required by Portuguese tax law</li>
<li><strong>Uploaded photographs and design files:</strong> [90] days after your order ships, so we can reprint if something goes wrong, then deleted</li>
<li><strong>Contact form messages:</strong> [2] years</li>
<li><strong>Marketing consent:</strong> until you withdraw it</li>
<li><strong>Analytics data:</strong> [14] months</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Your rights</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Under the GDPR you have the right to access, rectify, erase, restrict or object to the processing of your personal data, to receive it in a portable format, and to withdraw consent where processing relies on it.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>To exercise any of these, email {$email}. We will respond within one month. If you believe we have mishandled your data, you may complain to the Portuguese supervisory authority, the Comissão Nacional de Proteção de Dados (CNPD), at <a href="https://www.cnpd.pt" rel="nofollow">cnpd.pt</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Cookies</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We use cookies that are strictly necessary for the site and cart to work, and — only with your consent — cookies for analytics. You can manage your choices at any time through the cookie banner.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Children</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our store is not directed at children under 16, and we do not knowingly collect their data.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Changes</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We may update this policy. The date at the top always tells you when it last changed.</p>
<!-- /wp:paragraph -->
HTML;

    $returns = <<<HTML
<!-- wp:paragraph -->
<p><em>Last updated: [date]</em></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Every OurMoment piece is made to order, personalized with your names, dates and photos. Because of this, our returns policy works a little differently from a standard shop — and we want to be upfront about that before you buy.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Personalized items</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Under EU consumer law, the 14-day right of withdrawal does not apply to goods made to your specification or clearly personalized. Once you approve your design and place your order, production begins and the item cannot be resold to anyone else. This means we cannot accept returns simply because you changed your mind about a personalized item.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Please double-check your preview before ordering:</strong> the spelling of names, the date, and the photo you uploaded. What you see in the preview is what we print.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>When we will replace or refund</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We will replace or fully refund your order, at no cost to you, if:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>The item arrives damaged or defective</li>
<li>The item is not what you ordered</li>
<li>The print is misaligned, faded, or contains a printing error on our side</li>
<li>Your order never arrives within [X] days of the estimated delivery date</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Contact us at {$email} within 14 days of delivery, including your order number, photographs clearly showing the problem, and a photograph of the shipping label if the packaging was damaged.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We do not usually ask you to ship the item back — for print-on-demand it is cheaper for everyone if you keep or dispose of the faulty piece.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Order changes and cancellations</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>You can change or cancel your order within [2] hours of placing it, before it enters production. Email us immediately at {$email} with your order number. After production starts we cannot cancel, because the item is already being printed with your personalization.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Refunds</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Approved refunds are issued to your original payment method within [5–10] business days. Depending on your bank, it may take a few more days to appear on your statement.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Non-personalized items</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you ordered an item with no personalization, you have the standard 14-day right of withdrawal from the day you receive it. Contact us and we will provide return instructions. The item must be unused and in its original packaging. Return shipping is at your cost unless the item was faulty.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Your statutory rights</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nothing in this policy affects your statutory rights under EU and Portuguese consumer law, including your rights regarding goods that are not as described, not fit for purpose, or not of satisfactory quality.</p>
<!-- /wp:paragraph -->
HTML;

    return [
        'terms'   => ['title' => 'Terms & Conditions', 'content' => $terms],
        'privacy' => ['title' => 'Privacy Policy',     'content' => $privacy],
        'returns' => ['title' => 'Returns & Refunds',  'content' => $returns],
    ];
}

/**
 * Create the legal pages on theme activation, and the three nav pages.
 * Existing pages are never modified.
 */
function ourmoment_create_pages() {
    foreach (ourmoment_legal_pages() as $slug => $page) {
        if (get_page_by_path($slug)) {
            continue;
        }

        $page_id = wp_insert_post([
            'post_title'     => $page['title'],
            'post_name'      => $slug,
            'post_content'   => $page['content'],
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ]);

        // Point WordPress's own privacy tooling at our privacy page
        if ($slug === 'privacy' && $page_id && !is_wp_error($page_id)) {
            update_option('wp_page_for_privacy_policy', $page_id);
        }
    }

    // Pages whose layout comes from page-{slug}.php; content stays empty.
    $template_pages = [
        'how-it-works' => 'How It Works',
        'about'        => 'About',
        'contact'      => 'Contact',
    ];

    foreach ($template_pages as $slug => $title) {
        if (get_page_by_path($slug)) {
            continue;
        }

        wp_insert_post([
            'post_title'     => $title,
            'post_name'      => $slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ]);
    }
}
add_action('after_switch_theme', 'ourmoment_create_pages');

/**
 * after_switch_theme only fires when the theme is activated, so an upgrade
 * over an already-active theme would never create the pages. Run once more
 * on the first admin request after the files change.
 */
add_action('admin_init', function () {
    if (get_option('ourmoment_pages_created') === '1') {
        return;
    }

    ourmoment_create_pages();
    update_option('ourmoment_pages_created', '1');
});

/**
 * The drafts ship with [bracketed] placeholders. Nag until they are gone,
 * because publishing a policy that says "[legal company name]" is worse
 * than publishing none at all.
 */
add_action('admin_notices', function () {
    if (!current_user_can('edit_pages')) {
        return;
    }

    $unfinished = [];
    foreach (array_keys(ourmoment_legal_pages()) as $slug) {
        $page = get_page_by_path($slug);
        if ($page && strpos($page->post_content, '[') !== false) {
            $unfinished[] = sprintf(
                '<a href="%s">%s</a>',
                esc_url(get_edit_post_link($page->ID)),
                esc_html($page->post_title)
            );
        }
    }

    if (!$unfinished) {
        return;
    }

    printf(
        '<div class="notice notice-warning"><p><strong>OurMoment:</strong> %s %s</p><p>%s</p></div>',
        esc_html__('These legal pages still contain placeholders in square brackets:', 'ourmoment'),
        wp_kses_post(implode(', ', $unfinished)),
        esc_html__('Replace them with your real company details before taking the store live. These texts are a starting point, not legal advice.', 'ourmoment')
    );
});
