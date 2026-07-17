<?php
/**
 * Legal pages (US-oriented drafts).
 *
 * Creates Terms, Privacy and Returns as draft-quality starting points and
 * registers WordPress's privacy policy page.
 *
 * These texts are a STARTING POINT, NOT legal advice. Every [bracketed] field
 * must be filled in before going live. This store is operated from Portugal
 * and sells into the United States — that cross-border setup (US sales-tax
 * nexus, which entity/jurisdiction governs, CCPA and other state privacy laws,
 * plus GDPR for any EU visitors) is exactly the kind of thing that needs a
 * professional review. The "all sales final for personalized items" clause is
 * the most important one to get right.
 *
 * Pages are only ever created, never overwritten — once you edit them in the
 * admin, re-activating the theme will not touch your changes. If the old
 * EU-oriented pages already exist, delete them so these regenerate.
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
<p>These Terms of Service ("Terms") govern your use of the OurMoment website and any purchase you make from it. By placing an order, you agree to these Terms.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Seller:</strong> [legal business name]<br><strong>Address:</strong> [business address]<br><strong>Email:</strong> {$email}</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Our products</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We sell personalized gifts for couples — mugs, framed prints, and canvas — printed to order by our production partners. Product photos and on-screen previews are representations. Colors may vary slightly between screens and the printed item, and between production batches.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Your content and personalization</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When you personalize a product you upload photos and enter text. You represent and warrant that:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>You own the rights to the content, or have permission to use it;</li>
<li>The content does not infringe anyone's copyright, trademark, or right of publicity/privacy;</li>
<li>The content is not unlawful, hateful, obscene, or defamatory.</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>You grant us a limited license to reproduce your content solely to manufacture and deliver your order. We may refuse to print any design that violates the above and will refund you in full if we do. You agree to indemnify and hold us harmless from any claim arising out of content you submit.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Orders and pricing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Your order is an offer to buy. A contract is formed when we send you an order confirmation email. Prices are in US dollars. Applicable sales tax is calculated at checkout where required. If a price is listed incorrectly due to an obvious error, we may cancel the order and refund you rather than honor it. We may also refuse or cancel an order if the item is unavailable, if we suspect fraud, or if your content violates these Terms.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Payment</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Payments are processed by Stripe. We never see or store your full card details.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Production and shipping</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Personalized items are made to order. Typical production takes [X–X] business days, and US delivery takes a further [X–X] business days. These are estimates, not guarantees; delays caused by carriers or peak seasons are outside our control. Title and risk of loss pass to you upon delivery to the carrier.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Cancellations and returns</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Because our products are personalized, all sales of personalized items are final. Your rights when an item arrives damaged, defective, or incorrect are set out on our <a href="/returns/">Returns &amp; Refunds</a> page, which is part of these Terms.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Disclaimers</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Except as expressly stated, the website and products are provided "as is" and "as available," without warranties of any kind, to the fullest extent permitted by law. Some states do not allow the exclusion of certain warranties, so some of these exclusions may not apply to you.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Limitation of liability</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>To the fullest extent permitted by law, our total liability for any order is limited to the amount you paid for it, and we are not liable for indirect, incidental, or consequential damages. Nothing here limits liability that cannot be limited under applicable law.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Intellectual property</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The OurMoment name, logo, site design, and all original artwork are our property. You may not reproduce them without written permission.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Governing law and disputes</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>These Terms are governed by the laws of [State/jurisdiction], without regard to conflict-of-law rules, except where your local consumer-protection laws provide rights that cannot be waived. Before filing any claim, you agree to first contact us at {$email} so we can try to resolve it informally.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Changes</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We may update these Terms. The version in effect is the one published when you place your order.</p>
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
<p>This Privacy Policy explains what personal information OurMoment collects, why we collect it, and the choices you have.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Business:</strong> [legal business name]<br><strong>Address:</strong> [business address]<br><strong>Email:</strong> {$email}</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Information we collect</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>When you place an order:</strong> your name, email, billing and shipping address, phone number, and order contents.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>When you personalize a product:</strong> the names, dates, and text you enter, and any photos you upload. Only upload photos you have the right to use.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>When you contact us:</strong> your name, email, and the content of your message.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>When you browse:</strong> IP address, browser type, pages visited, and cookie data.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We do not see or store your full payment card details. Payments are processed by Stripe, who receive them directly.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>How we use your information</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>To process, produce, and deliver your order;</li>
<li>To send order and shipping confirmations;</li>
<li>To provide customer support;</li>
<li>To prevent fraud and abuse;</li>
<li>To improve our site (analytics), and — only if you opt in — to send marketing emails;</li>
<li>To meet legal and tax obligations.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>How we share it</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Printify</strong> and its print providers — your name, shipping address, and design files, to print and ship your item;</li>
<li><strong>Customily</strong> — your personalization data and uploaded images, to generate the print file;</li>
<li><strong>Stripe</strong> — payment processing;</li>
<li><strong>[hosting provider]</strong> — website hosting;</li>
<li><strong>[analytics provider, if used]</strong> — usage statistics, subject to your cookie choices.</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p><strong>We do not sell your personal information</strong>, and we do not share it for cross-context behavioral advertising as those terms are defined under California law.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Cookies</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We use cookies that are necessary for the site and cart to work, and — with your consent — cookies for analytics. You can manage your choices through the cookie banner at any time.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>How long we keep it</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Order and invoice records:</strong> as required by applicable tax law;</li>
<li><strong>Uploaded photos and design files:</strong> [90] days after your order ships, then deleted;</li>
<li><strong>Contact messages:</strong> [2] years;</li>
<li><strong>Marketing consent:</strong> until you withdraw it.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Your privacy rights</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>California residents (CCPA/CPRA).</strong> You have the right to know what personal information we collect, to request access to or deletion of it, to correct inaccurate information, and to not be discriminated against for exercising these rights. As stated above, we do not sell or share your personal information. To make a request, email {$email}; we will verify and respond as required by law.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Other US states.</strong> Residents of states with comprehensive privacy laws (such as Virginia, Colorado, Connecticut, and others) may have similar rights to access, correct, or delete their information. Contact us at the email above to exercise them.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>EU/UK visitors.</strong> If you are in the EU or UK, you may have additional rights under the GDPR/UK GDPR, including access, rectification, erasure, and portability. Email us to exercise them.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Children</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our store is not directed to children under 13, and we do not knowingly collect their personal information.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Security and changes</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We use reasonable measures to protect your information, though no method of transmission is completely secure. We may update this policy; the "last updated" date above always tells you when it last changed.</p>
<!-- /wp:paragraph -->
HTML;

    $returns = <<<HTML
<!-- wp:paragraph -->
<p><em>Last updated: [date]</em></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Every OurMoment piece is made to order, personalized with your names, dates, and photos. Because of that, our returns policy works a little differently from a standard shop — and we want to be upfront about it before you buy.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Personalized items are final sale</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Because each item is personalized just for you, it can't be resold to anyone else. For that reason, <strong>all sales of personalized items are final</strong> and we can't accept returns or exchanges for a change of mind.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Please double-check your preview before ordering:</strong> the spelling of names, the date, and the photo you uploaded. What you see in the live preview is what we print.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>When we'll replace or refund</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We'll replace or fully refund your order, at no cost to you, if:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>The item arrives damaged or defective;</li>
<li>You received the wrong item;</li>
<li>The print is misaligned, faded, or has a printing error on our side;</li>
<li>Your order never arrives within [X] days of the estimated delivery date.</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Contact us at {$email} within [30] days of delivery, and include your order number and clear photos of the problem (and of the shipping label if the packaging was damaged). We usually don't ask you to ship the item back.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Order changes and cancellations</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>You can change or cancel your order within [2] hours of placing it, before it enters production. Email us right away at {$email} with your order number. Once production starts, we can't cancel, because the item is already being printed with your personalization.</p>
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
<p>If you ordered an item with no personalization, you may return it within [30] days of delivery if it is unused and in its original packaging. Contact us for instructions. Return shipping is at your cost unless the item was faulty.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[legal business name]<br>[business address]<br>{$email}</p>
<!-- /wp:paragraph -->
HTML;

    return [
        'terms'   => ['title' => 'Terms of Service', 'content' => $terms],
        'privacy' => ['title' => 'Privacy Policy',   'content' => $privacy],
        'returns' => ['title' => 'Returns & Refunds', 'content' => $returns],
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
 * because publishing a policy that says "[legal business name]" is worse
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
        esc_html__('Replace them with your real business details before taking the store live. These are starting points, not legal advice — a US/cross-border review is strongly recommended.', 'ourmoment')
    );
});
