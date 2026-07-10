<?php
/**
 * Site footer. Legal links only render once their page exists, so the
 * footer never points at a 404 while the store is being set up.
 */
$om_legal = [
    'terms'   => 'Terms',
    'privacy' => 'Privacy',
    'returns' => 'Returns',
];

$om_links = [];
$om_has_privacy = false;

foreach ($om_legal as $om_slug => $om_label) {
    $om_page = get_page_by_path($om_slug);
    if ($om_page && $om_page->post_status === 'publish') {
        $om_links[] = ['url' => get_permalink($om_page), 'label' => $om_label];
        if ($om_slug === 'privacy') {
            $om_has_privacy = true;
        }
    }
}

// Fall back to WordPress's own privacy policy page if we have no /privacy/ page
if (!$om_has_privacy) {
    $om_privacy_id = (int) get_option('wp_page_for_privacy_policy');
    if ($om_privacy_id && get_post_status($om_privacy_id) === 'publish') {
        $om_links[] = ['url' => get_permalink($om_privacy_id), 'label' => 'Privacy'];
    }
}

if (function_exists('wc_get_page_permalink')) {
    $om_links[] = ['url' => wc_get_page_permalink('myaccount'), 'label' => 'My Account'];
}
?>
<footer class="om-footer">
  <div class="om-container">
    <div class="om-footer-inner">
      <p>&copy; <?php echo esc_html(date('Y')); ?> OurMoment. All rights reserved.</p>

      <?php if ($om_links) : ?>
        <nav class="om-footer-links" aria-label="Footer">
          <?php foreach ($om_links as $om_link) : ?>
            <a href="<?php echo esc_url($om_link['url']); ?>"><?php echo esc_html($om_link['label']); ?></a>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>

      <div class="om-footer-social">
        <a href="#" aria-label="Instagram">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/>
          </svg>
        </a>
        <a href="#" aria-label="Pinterest">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><path d="M8 21 C9 16 10 13 11 10 C11 8 12 7 14 7 C16 7 17 9 16 12 C15 15 13 15 12 14"/>
          </svg>
        </a>
        <a href="#" aria-label="TikTok">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path d="M9 12 L9 19 C9 21 11 22 13 21 C15 20 15 18 15 17 L15 3 C16 5 18 7 21 7"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</footer>
