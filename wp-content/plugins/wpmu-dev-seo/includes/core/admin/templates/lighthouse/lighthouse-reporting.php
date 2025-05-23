<?php
/**
 * Template: Lighthouse Reporting.
 *
 * @package SmartCrawl
 */

namespace SmartCrawl;

use SmartCrawl\Controllers\Cron;
use SmartCrawl\Lighthouse\Options;

$option_name             = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$toggle_field_name       = $option_name . '[lighthouse-cron-enable]';
$is_member               = ! empty( $_view['is_member'] );
$lighthouse_cron_enabled = Options::is_cron_enabled() && $is_member;
$lighthouse_recipients   = Options::email_recipients();
$cron                    = Cron::get();
$frequencies             = $cron->get_frequencies();
$lighthouse_frequency    = Options::reporting_frequency();
?>

<div>
	<p><?php esc_html_e( 'Enable scheduled SEO tests and get the customized results emailed directly to your inbox.', 'wds' ); ?></p>
</div>

<?php
if ( $lighthouse_cron_enabled ) {
	$this->render_view(
		'notice',
		array(
			'class'   => 'sui-notice-info wds-recipient-summary-notice',
			'message' => sprintf(
				/* translators: 1: List of frequency intervals, 2: Recipients */
				_n(
					'Automatic Lighthouse reports are enabled and sending %1$s to %2$d recipient.',
					'Automatic Lighthouse reports are enabled and sending %1$s to %2$d recipients.',
					count( $lighthouse_recipients ),
					'wds'
				),
				\smartcrawl_get_array_value( $frequencies, $lighthouse_frequency ),
				count( $lighthouse_recipients )
			),
		)
	);
} elseif ( $is_member ) {
	$this->render_view(
		'notice',
		array(
			'class'   => 'sui-notice-grey wds-recipient-summary-notice',
			'message' => esc_html__( 'Reporting is currently inactive. Activate it and choose your schedule below.', 'wds' ),
		)
	);
}
?>

<div class="sui-box-settings-row <?php echo $is_member ? '' : 'sui-disabled'; ?>">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Configure', 'wds' ); ?>
		</label>
		<span class="sui-description">
			<?php esc_html_e( 'Enable automated SEO reports for this website.', 'wds' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<?php
		$this->render_view(
			'toggle-item',
			array(
				'field_name'                 => $toggle_field_name,
				'field_id'                   => $toggle_field_name,
				'checked'                    => $lighthouse_cron_enabled,
				'item_label'                 => esc_html__( 'Send scheduled performance reports', 'wds' ),
				'sub_settings_template'      => 'lighthouse/lighthouse-recipients',
				'sub_settings_template_args' => array(
					'option_name'          => $option_name,
					'lighthouse_frequency' => $lighthouse_frequency,
					'email_recipients'     => $lighthouse_recipients,
				),
			)
		);
		?>
	</div>
</div>

<?php
if ( ! $is_member ) {
	$this->render_view(
		'mascot-message',
		array(
			'key'         => 'seo-checkup-upsell',
			'dismissible' => false,
			'message'     => sprintf(
				'%s <a target="_blank" class="sui-button sui-button-purple" href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_lighthouse_reporting_upsell_notice">%s</a>',
				esc_html__( 'Upgrade to Pro to unlock unlimited Lighthouse audits with automated scheduled reports to always stay on top of any issues.', 'wds' ),
				esc_html__( 'Unlock now with Pro', 'wds' )
			),
		)
	);
} else {
	?>
	<div class="sui-box-footer">
		<button class="sui-button sui-button-blue">
			<span class="sui-loading-text">
				<span class="sui-icon-save" aria-hidden="true"></span>
				<?php esc_html_e( 'Save Settings', 'wds' ); ?>
			</span>
			<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
		</button>
	</div>
<?php } ?>