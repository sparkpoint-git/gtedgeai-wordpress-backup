<?php
/**
 * Smush upsell notice.
 *
 * @since 3.1.2
 * @package Hummingbird
 */

use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="sui-upsell-notice sui-padding sui-padding-top--hidden sui-padding-bottom__desktop--hidden">
	<div class="sui-upsell-notice__content">
		<div class="sui-notice sui-notice-purple">
			<div class="sui-notice-content">
				<div class="sui-notice-message">
					<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
					<p><?php esc_html_e( 'Did you know WP Smush Pro delivers up to 2x better compression, allows you to smush your originals and removes any bulk smushing limits?', 'wphb' ); ?></p>
					<p><a class="sui-button sui-button-purple" target="_blank" href="<?php echo esc_url( Utils::get_link( 'smush-plugin', 'hummingbird_dash_smush_upsell_link' ) ); ?>" onclick="window.wphbMixPanel.trackHBUpsell( 'smush_upsell', 'dash_widget_upgrade', 'cta_clicked', this.href, 'hb_smush_upsell' );">
						<?php esc_html_e( 'UPGRADE TO PRO', 'wphb' ); ?>
					</a></p>
				</div>
			</div>
		</div>
	</div>
</div>