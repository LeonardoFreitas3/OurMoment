<?php
/**
 * Page template for the "faq" slug.
 */
get_header();

$om_faqs = [
    [
        'q' => 'How do I personalize my gift?',
        'a' => 'On any product page, add your names, your date, and upload your photo. You\'ll see a live preview of exactly how your piece will look before you buy — what you approve is what we print.',
    ],
    [
        'q' => 'What photo should I upload for the best result?',
        'a' => 'Upload the highest-resolution photo you have — clear, well-lit, and in focus. Small, blurry, or heavily filtered images (and screenshots) may print soft. The better the photo, the closer your piece comes to perfect.',
    ],
    [
        'q' => 'How long does it take to arrive?',
        'a' => 'Each item is made to order. Production typically takes a few business days, plus shipping time. You\'ll see estimated timings at checkout. Because our products are printed on demand, please order with a little time to spare for gifts with a deadline.',
    ],
    [
        'q' => 'Can I change or cancel my order?',
        'a' => 'You can change or cancel within a short window after ordering, before the item enters production. Email us right away with your order number. Once production starts, the item can\'t be changed because it\'s already being printed with your personalization.',
    ],
    [
        'q' => 'Can I return a personalized item?',
        'a' => 'Because every item is personalized just for you, personalized items are final sale and can\'t be returned or refunded for a change of mind or for information you entered. If the fault is ours — a damaged, defective, or wrong item — we\'ll make it right. See our Returns &amp; Refunds page for details.',
    ],
    [
        'q' => 'Is my payment secure?',
        'a' => 'Yes. Checkout is encrypted and payments are handled by Stripe. We never see or store your full card details.',
    ],
];
?>

<?php
/**
 * FAQPage structured data, built from the same $om_faqs array the page
 * renders — one source of truth, so the markup and the schema can never
 * drift apart. Search engines use this for rich results; assistants use it
 * because a question/answer pair is the shape they quote.
 *
 * Yoast emits its own graph separately; a standalone FAQPage block is valid
 * alongside it.
 */
$om_faq_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(function ($om_item) {
        return [
            '@type'          => 'Question',
            'name'           => wp_strip_all_tags($om_item['q']),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => wp_strip_all_tags($om_item['a']),
            ],
        ];
    }, $om_faqs),
];
?>
<script type="application/ld+json">
<?php echo wp_json_encode($om_faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main class="om-page">
  <?php
  get_template_part('template-parts/page-banner', null, [
      'title'    => 'FAQ',
      'subtitle' => 'Everything you need to know before you order.',
  ]);
  ?>

  <section class="om-faq">
    <div class="om-container">
      <?php foreach ($om_faqs as $i => $om_item) : ?>
        <details class="om-faq-item om-fade"<?php echo $i === 0 ? ' open' : ''; ?>>
          <summary>
            <span><?php echo esc_html($om_item['q']); ?></span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M6 9 L12 15 L18 9"/></svg>
          </summary>
          <div class="om-faq-answer"><p><?php echo wp_kses_post($om_item['a']); ?></p></div>
        </details>
      <?php endforeach; ?>

      <p class="om-faq-more">
        Still have a question? <a href="<?php echo esc_url(home_url('/contact/')); ?>">Get in touch</a>.
      </p>
    </div>
  </section>
</main>

<?php get_footer(); ?>
