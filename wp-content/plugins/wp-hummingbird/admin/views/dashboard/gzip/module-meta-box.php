<?php
/**
 * Caching meta box on dashboard page.
 *
 * @package Hummingbird
 *
 * @var array  $status           Array of results.
 * @var int    $inactive_types   Number of inactive types.
 * @var bool   $use_cdn          CDN status.
 * @var string $compression_type Compression type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p><?php esc_html_e( 'Gzip compresses your webpages and style sheets before sending them over to the browser.', 'wphb' ); ?></p>
<?php
if ( true === $use_cdn || 'br' === $compression_type ) {
	?>
	<div class="sui-notice sui-notice-success">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
				<p>
					<?php
					printf( /* translators: %1$s - opening <a> tag, %2$s - closing </a> tag */
						esc_html__( 'Brotli Compression is active and working well via CDN/Hosting, for enhanced performance in supported browsers. For browsers that don’t support Brotli, we’ll automatically use GZip instead. %1$sCheck browser support here%2$s.', 'wphb' ),
						'<a href="' . esc_url( 'https://caniuse.com/brotli' ) . '" onclick="wphbMixPanel.track(' . "'check_brotli_support'" . ')" target="_blank">',
						'</a>'
					)
					?>
				</p>
			</div>
		</div>
	</div>
	<?php
} elseif ( $inactive_types ) {
	$this->admin_notices->show_inline(
		sprintf( /* translators: %s: Number of inactive types */
			__( '%s of your compression types are inactive.', 'wphb' ),
			absint( $inactive_types )
		),
		'warning'
	);
} else {
	$this->admin_notices->show_inline( esc_html__( 'Gzip compression is currently active. Good job!', 'wphb' ) );
}
?>

<ul class="sui-list sui-no-margin-bottom">
	<li class="sui-list-header">
		<span><?php esc_html_e( 'File type', 'wphb' ); ?></span>
		<span><?php esc_html_e( 'Status', 'wphb' ); ?></span>
	</li>
	<?php
	foreach ( $status as $type => $result ) :
		$result_status       = __( 'Inactive', 'wphb' );
		$result_status_color = 'warning';
		if ( $result ) {
			$result_status       = __( 'Active', 'wphb' );
			$result_status_color = 'success';
		}
		?>
		<li>
			<span class="sui-list-label">
				<span class="wphb-filename-extension wphb-filename-extension-<?php echo esc_html( strtolower( $type ) ); ?>">
					<?php
					switch ( $type ) {
						case 'JavaScript':
							echo 'js';
							break;
						default:
							echo esc_html( strtolower( $type ) );
							break;
					}
					?>
				</span>
				<span class="wphb-filename-extension-label"><?php echo esc_html( $type ); ?></span>
			</span>

			<span class="sui-list-detail">
				<span class="sui-tag sui-tag-<?php echo esc_attr( $result_status_color ); ?>"                    					  data-tooltip="
														<?php
														printf( /* translators: %1$s: compressions status; %2$s: compression type */
															esc_html__( 'Gzip compression is %1$s for %2$s', 'wphb' ),
															esc_html( strtolower( $result_status ) ),
															esc_html( $type )
														);
														?>
						  ">
					<?php echo esc_html( $result_status ); ?>
				</span>
			</span>
		</li>
	<?php endforeach; ?>
</ul>