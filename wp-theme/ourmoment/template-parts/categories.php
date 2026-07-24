<?php
/**
 * Homepage category grid.
 *
 * Reads the store's real product categories rather than hardcoding cards, so
 * the section follows the catalogue instead of drifting from it. Each card
 * links to its own archive and shows how many products sit in it.
 *
 * Falls back to the two hand-written cards while every product still sits in
 * a single catch-all category — two cards with real copy sell better than one
 * card saying "New Arrivals".
 *
 * Artwork comes from ourmoment_category_art() in functions.php.
 */

$om_shop  = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '#shop';
$om_cards = [];

if (function_exists('wc_get_page_permalink')) {
    $om_terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 6,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'exclude'    => [(int) get_option('default_product_cat')],
    ]);

    // One category means the catalogue is not organised yet; the fallback
    // below says more than a lone "New Arrivals" card would.
    if (!is_wp_error($om_terms) && count($om_terms) > 1) {
        foreach ($om_terms as $om_term) {
            $om_link = get_term_link($om_term);
            if (is_wp_error($om_link)) {
                continue;
            }
            $om_cards[] = [
                'url'   => $om_link,
                'title' => $om_term->name,
                'desc'  => $om_term->description
                    ?: sprintf(
                        /* translators: %s: category name, lowercased */
                        __('Personalized %s, made with your names, your date and your photo.', 'ourmoment'),
                        strtolower($om_term->name)
                    ),
                'count' => (int) $om_term->count,
                'art'   => ourmoment_category_art($om_term->name),
            ];
        }
    }
}

if (!$om_cards) {
    $om_cards = [
        [
            'url'   => $om_shop,
            'title' => __('Personalized Mugs', 'ourmoment'),
            'desc'  => __('Your names, your date, your photo — on the mug you reach for every morning.', 'ourmoment'),
            'count' => 0,
            'art'   => ourmoment_category_art('mug'),
        ],
        [
            'url'   => $om_shop,
            'title' => __('Personalized Wall Art', 'ourmoment'),
            'desc'  => __('Framed prints and canvas made from your photos and your story.', 'ourmoment'),
            'count' => 0,
            'art'   => ourmoment_category_art('frame'),
        ],
    ];
}
?>

<section class="om-categories">
  <div class="om-container">
    <h2 class="om-section-title om-fade"><?php esc_html_e('What We Create', 'ourmoment'); ?></h2>
    <p class="om-section-lead om-fade">
      <?php esc_html_e('Every piece is made to order and printed close to you — in Europe for European orders, in the United States for US ones.', 'ourmoment'); ?>
    </p>

    <div class="om-categories-grid<?php echo count($om_cards) > 2 ? ' om-categories-grid--many' : ''; ?>">
      <?php foreach ($om_cards as $om_card) : ?>
        <a href="<?php echo esc_url($om_card['url']); ?>" class="om-category-card om-fade">
          <div class="om-category-visual">
            <svg viewBox="0 0 200 200" fill="none" aria-hidden="true">
              <?php echo $om_card['art']; // phpcs:ignore WordPress.Security.EscapeOutput — static markup from the lookup ?>
            </svg>
          </div>
          <h3><?php echo esc_html($om_card['title']); ?></h3>
          <p><?php echo esc_html($om_card['desc']); ?></p>
          <span class="om-category-link">
            <?php esc_html_e('Shop', 'ourmoment'); ?>
            <?php if ($om_card['count']) : ?>
              <span class="om-category-count"><?php echo esc_html($om_card['count']); ?></span>
            <?php endif; ?>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
