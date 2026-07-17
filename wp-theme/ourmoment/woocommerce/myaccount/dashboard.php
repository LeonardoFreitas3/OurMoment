<?php
/**
 * My Account dashboard — OurMoment override.
 *
 * Replaces the default welcome text with a simple, English, user-friendly
 * tile layout linking to the account sections. Same information, cleaner UX.
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

$om_user = wp_get_current_user();

$om_tiles = [
    [
        'url'   => wc_get_account_endpoint_url('orders'),
        'label' => 'Orders',
        'desc'  => 'Track and review your past orders.',
        'icon'  => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 9h6M9 13h6M9 17h3"/>',
    ],
    [
        'url'   => wc_get_account_endpoint_url('edit-address'),
        'label' => 'Addresses',
        'desc'  => 'Manage your shipping and billing details.',
        'icon'  => '<path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
    ],
    [
        'url'   => wc_get_account_endpoint_url('edit-account'),
        'label' => 'Account Details',
        'desc'  => 'Update your name, email, and password.',
        'icon'  => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.5 3.5-7 8-7s8 2.5 8 7"/>',
    ],
    [
        'url'   => wc_logout_url(),
        'label' => 'Log Out',
        'desc'  => 'Sign out of your account.',
        'icon'  => '<path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3"/><path d="M10 17l-5-5 5-5M5 12h11"/>',
    ],
];
?>

<div class="om-account-dash">
  <p class="om-account-hi">
    Hi <strong><?php echo esc_html($om_user->display_name); ?></strong>. Welcome back.
  </p>

  <div class="om-account-tiles">
    <?php foreach ($om_tiles as $om_tile) : ?>
      <a class="om-account-tile" href="<?php echo esc_url($om_tile['url']); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><?php echo $om_tile['icon']; // phpcs:ignore ?></svg>
        <span class="om-tile-label"><?php echo esc_html($om_tile['label']); ?></span>
        <span class="om-tile-desc"><?php echo esc_html($om_tile['desc']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php
/**
 * My Account dashboard hook — kept so extensions can still inject content.
 */
do_action('woocommerce_account_dashboard');
