<?php
/**
 * Template: Sitemap Advanced section.
 *
 * @package Smartcrwal
 */

namespace SmartCrawl;

$automatically_switched = empty( $automatically_switched ) ? false : $automatically_switched;
$total_post_count       = empty( $total_post_count ) ? 0 : $total_post_count;
$option_name            = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$regeneration_method    = \SmartCrawl\Sitemaps\Utils::get_regeneration_method();
$ping_google            = ! empty( $_view['options']['ping-google'] );
$ping_bing              = ! empty( $_view['options']['ping-bing'] );
?>

<?php $this->render_view( 'sitemap/sitemap-split-setting' ); ?>

<?php
$this->render_view(
	'toggle-group',
	array(
		'label'       => esc_html__( 'Include images', 'wds' ),
		'description' => esc_html__( 'If your posts contain imagery you would like others to be able to search, this setting will help Google Images index them correctly.', 'wds' ),
		'items'       => array(
			'sitemap-images' => array(
				'label'       => esc_html__( 'Include image items with the sitemap', 'wds' ),
				'description' => esc_html__( 'Note: When uploading attachments to posts, be sure to add titles and captions that clearly describe your images.', 'wds' ),
				'value'       => '1',
			),
		),
	)
);

?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Auto-notify search engines', 'wds' ); ?>
		</label>

		<span class="sui-description">
			<?php
			printf(
				/* translators: 1,2: strong tag, 3: plugin title */
				esc_html__( 'By default, %1$s%3$s%2$s will auto-notify Google and Bing whenever your sitemap changes. Alternatively, you can manually notify search engines.', 'wds' ),
				'<strong>',
				'</strong>',
				esc_html( \smartcrawl_get_plugin_title() )
			);
			?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<?php
		$this->render_view(
			'side-tabs',
			array(
				'id'    => 'wds-auto-notify-engines-tabs',
				'name'  => "{$option_name}[auto-notify-search-engines]",
				'value' => $ping_google && $ping_bing ? '1' : '',
				'tabs'  => array(
					array(
						'value' => '1',
						'label' => esc_html__( 'Automatic', 'wds' ),
					),
					array(
						'value'    => '',
						'label'    => esc_html__( 'Manual', 'wds' ),
						'template' => 'sitemap/sitemap-manually-notify-search-engines',
					),
				),
			)
		);
		?>
	</div>
</div>
<?php
$this->render_view(
	'toggle-group',
	array(
		'label'       => esc_html__( 'Style sitemap', 'wds' ),
		'description' => esc_html__( 'Adds some nice styling to your sitemap.', 'wds' ),
		'separator'   => true,
		'items'       => array(
			'sitemap-stylesheet' => array(
				'label'       => esc_html__( 'Include stylesheet with sitemap', 'wds' ),
				'description' => esc_html__( 'Note: This doesn’t affect your SEO and is purely visual.', 'wds' ),
				'value'       => '1',
			),
		),
	)
);
?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Automatic sitemap updates', 'wds' ); ?>
		</label>

		<span class="sui-description">
			<?php esc_html_e( 'By default, we will automatically update your sitemap but if you wish to update it manually, you can switch to manual mode, or if you wish to update it at regular intervals switch to scheduled mode.', 'wds' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<?php
		$this->render_view(
			'side-tabs',
			array(
				'id'    => 'wds-automatic-sitemap-updates-tabs',
				'name'  => "{$option_name}[sitemap-disable-automatic-regeneration]",
				'value' => $regeneration_method,
				'tabs'  => array(
					array(
						'value' => 'auto',
						'label' => esc_html__( 'Automatic', 'wds' ),
					),
					array(
						'value'    => 'manual',
						'label'    => esc_html__( 'Manual', 'wds' ),
						'template' => 'sitemap/sitemap-manual-update-button',
					),
					array(
						'value'    => 'scheduled',
						'label'    => esc_html__( 'Scheduled', 'wds' ),
						'template' => 'sitemap/sitemap-scheduled-update-options',
					),
				),
			)
		);
		?>
	</div>
</div>

<div id="wds-troubleshooting-sitemap-placeholder"></div>

<?php $this->render_view(
	'sitemap/sitemap-deactivate-button',
	array(
		'label_description'  => esc_html__( 'If you no longer wish to use the Sitemap generator, you can deactivate it.', 'wds' ),
		'button_description' => esc_html__( 'Note: Sitemaps are crucial for helping search engines index all of your content effectively. We highly recommend you have a valid sitemap.', 'wds' ),
	)
); ?>