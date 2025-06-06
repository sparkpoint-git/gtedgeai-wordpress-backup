<?php
/**
 * Upsell Hummingbird meta box for Free users.
 *
 * @since 2.0.1
 * @package Hummingbird
 */

use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<p>
	<?php esc_html_e( 'Get our full WordPress performance optimization suite with Hummingbird Pro and additional benefits of WPMU DEV membership.', 'wphb' ); ?>
</p>

<ol class="sui-upsell-list">
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Enhanced file minification with CDN', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Delay JavaScript Execution', 'wphb' ); ?><span class="sui-tag sui-tag-beta" style="background-color: #1abc9c"><?php esc_html_e( 'New', 'wphb' ); ?></span></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Prioritize Critical CSS for more page speed ', 'wphb' ); ?><span class="sui-tag sui-tag-beta" style="background-color: #1abc9c"><?php esc_html_e( 'New', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Smush Pro for the best image optimization', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Instant site health alerts and notifications', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'White label automated reporting', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Premium WordPress plugins', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Manage unlimited WordPress sites', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( '24/7 live WordPress support', 'wphb' ); ?></li>
	<li><span class="sui-icon-check sui-md" aria-hidden="true"></span> <?php esc_html_e( 'Zero risk, 30-day money-back guarantee', 'wphb' ); ?></li>
</ol>

<br>

<a href="<?php echo esc_url( Utils::get_link( 'plugin', 'hummingbird_dashboard_upsellwidget_button' ) ); ?>" onclick="window.wphbMixPanel.trackHBUpsell( 'pro_general', 'dash_widget', 'cta_clicked', this.href, 'hb_pro_upsell' );" class="sui-button sui-button-purple" target="_blank">
	<?php esc_html_e( 'UNLOCK NOW WITH PRO', 'wphb' ); ?>
</a>