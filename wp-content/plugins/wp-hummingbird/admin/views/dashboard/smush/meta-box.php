<?php
/**
 * Smush meta box on dashboard page.
 *
 * @package Hummingbird
 *
 * @var string $activate_pro_url  URL to activate Pro version.
 * @var string $activate_url      URL to activate Free version.
 * @var bool   $can_activate      Can the user activate Smush.
 * @var bool   $is_active         Activation status.
 * @var bool   $is_installed      Installation status.
 * @var bool   $is_pro            Pro status.
 * @var array  $smush_data        Smush data.
 */

use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="<?php echo ! Utils::is_member() || ! $is_pro ? 'sui-box-body' : ''; ?>">
	<p class="sui-margin-bottom">
		<?php esc_html_e( 'Automatically compress and optimize your images with our super popular Smush plugin.', 'wphb' ); ?>
	</p>

	<!-- No plugin is installed -->
	<?php if ( ! $is_installed ) : ?>
		<a href="<?php echo esc_url( Utils::get_link( 'smush' ) ); ?>" class="sui-button sui-button-blue" id="smush-install" onclick="window.WPHB_Admin.dashboard.trackSmushUpsell( event, this, 'dash_widget_install' );">
			<?php
			if ( Utils::is_member() ) {
				esc_html_e( 'Install Smush Pro', 'wphb' );
			} else {
				esc_html_e( 'Install Smush', 'wphb' );
			}
			?>
		</a>

	<!-- Plugin is installed but not active -->
	<?php elseif ( ! $is_active && $can_activate ) : ?>
		<?php
		$this->admin_notices->show_inline(
			esc_html__( 'WP Smush is installed but not activated! Activate and set up now to reduce page load time.', 'wphb' ),
			'warning'
		);
		?>
		<?php if ( $is_pro ) : ?>
			<a href="<?php echo esc_url( $activate_url ); ?>" class="sui-button sui-button-blue" id="smush-activate" onclick="window.WPHB_Admin.dashboard.trackSmushUpsell( event, this, 'dash_widget_activate' );">
				<?php esc_html_e( 'Activate Smush Pro', 'wphb' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( $activate_url ); ?>" class="sui-button sui-button-blue" id="smush-activate" onclick="window.WPHB_Admin.dashboard.trackSmushUpsell( event, this, 'dash_widget_activate' );">
				<?php esc_html_e( 'Activate Smush', 'wphb' ); ?>
			</a>
		<?php endif; ?>

	<!-- Plugin is installed and active -->
	<?php elseif ( $is_active ) : ?>
		<?php
		if ( 0 === $smush_data['bytes'] || 0 === $smush_data['percent'] ) {
			$this->admin_notices->show_inline(
				esc_html__( 'WP Smush is installed but no images have been smushed yet. Get in there and smush away!', 'wphb' )
			);
		} else {
			$this->admin_notices->show_inline(
				sprintf( /* translators: %1$s - Smush total saved space, %2$s - Smush total saved percentage */
					esc_html__( "WP Smush is installed. So far you've saved %1\$s of space. That's a total savings of %2\$s. Nice one!", 'wphb' ),
					$smush_data['human'],
					number_format_i18n( $smush_data['percent'], 2 ) . '%'
				)
			);
		}
		?>
	<?php endif; ?>
</div>

<?php
if ( ( Utils::is_member() && $is_active && ! $is_pro ) || ( ! Utils::is_member() && ! $is_pro ) ) {
	$this->view( 'dashboard/smush/meta-box-upsell' );
}
?>