<?php
/**
 * Advanced tools admin page.
 *
 * @since 1.8
 * @package Hummingbird
 */

namespace Hummingbird\Admin\Pages;

use Hummingbird\Admin\Page;
use Hummingbird\Core\Modules\Advanced as Advanced_Module;
use Hummingbird\Core\Modules\Caching\Preload;
use Hummingbird\Core\Modules\Minify\Minify_Group;
use Hummingbird\Core\Settings;
use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Advanced_Tools
 *
 * @package Hummingbird\Admin\Pages
 */
class Advanced extends Page {

	use \Hummingbird\Core\Traits\Smush;

	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {
		// Init the tabs.
		$this->tabs = array(
			'main'   => __( 'General', 'wphb' ),
			'db'     => __( 'Database Cleanup', 'wphb' ),
			'lazy'   => __( 'Lazy Load', 'wphb' ),
			'system' => __( 'System Information', 'wphb' ),
			'health' => __( 'Plugin Health', 'wphb' ),
		);
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		/**
		 * General meta box.
		 */
		$this->add_meta_box( 'advanced/general', __( 'General', 'wphb' ), array( $this, 'advanced_general_metabox' ) );

		/**
		 * Database cleanup meta boxes.
		 */
		$this->add_meta_box(
			'advanced/db',
			__( 'Database Cleanup', 'wphb' ),
			array( $this, 'advanced_db_metabox' ),
			null,
			null,
			'db',
			array(
				'box_footer_class' => Utils::is_member() ? 'sui-box-footer' : 'sui-box-footer wphb-db-cleanup-no-membership',
			)
		);

		/**
		 * Lazy load meta boxes.
		 *
		 * @since 2.5.0
		 */
		$this->add_meta_box(
			'advanced/lazy',
			__( 'Lazy Load', 'wphb' ),
			array( $this, 'advanced_lazy_metabox' ),
			null,
			function() {
				$this->view( 'advanced/general-meta-box-footer' );
			},
			'lazy'
		);

		/**
		 * System information meta box.
		 */
		$this->add_meta_box(
			'advanced/system-info',
			__( 'System Information', 'wphb' ),
			array( $this, 'system_info_metabox' ),
			null,
			null,
			'system'
		);

		/**
		 * Plugin health meta box.
		 *
		 * @since 2.7.0
		 */
		$this->add_meta_box(
			'advanced/site-health',
			__( 'Plugin Health', 'wphb' ),
			array( $this, 'site_health_metabox' ),
			null,
			null,
			'health'
		);
	}

	/**
	 * *************************
	 * Advanced General page meta boxes.
	 ***************************/

	/**
	 * Advanced general meta box.
	 */
	public function advanced_general_metabox() {
		$options = Settings::get_settings( 'advanced' );

		$prefetch = '';
		foreach ( $options['prefetch'] as $url ) {
			$prefetch .= $url . "\r\n";
		}

		$preconnect = '';
		foreach ( $options['preconnect'] as $url ) {
			$preconnect .= $url . "\r\n";
		}

		$query_string = $options['query_string'];
		$remove_emoji = $options['emoji'];

		if ( ( $options['query_strings_global'] || $options['emoji_global'] ) && is_multisite() && ! is_network_admin() ) {
			$network_options = get_blog_option( get_main_site_id(), 'wphb_settings' );

			// See if we need to fetch the network value for query strings option.
			if ( $options['query_strings_global'] && isset( $network_options['advanced'] ) && isset( $network_options['advanced']['query_string'] ) ) {
				$query_string = $network_options['advanced']['query_string'];
				add_filter( 'wphb_query_strings_disabled', '__return_true' );
			}

			// See if we need to fetch the network value for emoji option.
			if ( $options['emoji_global'] && isset( $network_options['advanced'] ) && isset( $network_options['advanced']['emoji'] ) ) {
				$remove_emoji = $network_options['advanced']['emoji'];
				add_filter( 'wphb_emojis_disabled', '__return_true' );
			}
		}

		$this->view(
			'advanced/general-meta-box',
			array(
				'woo_active'           => class_exists( 'woocommerce' ),
				'woo_link'             => self_admin_url( 'admin.php?page=wc-settings&tab=products' ),
				'query_stings'         => $query_string,
				'query_strings_global' => $options['query_strings_global'],
				'cart_fragments'       => $options['cart_fragments'],
				'emoji'                => $remove_emoji,
				'post_revisions'       => $options['post_revisions'],
				'viewport_meta'        => $options['viewport_meta'],
				'emoji_global'         => $options['emoji_global'],
				'prefetch'             => trim( $prefetch ),
				'preconnect'           => trim( $preconnect ),
			)
		);
	}

	/**
	 * *************************
	 * Advanced Database cleanup page meta boxes.
	 ***************************/

	/**
	 * Database cleanup meta box.
	 */
	public function advanced_db_metabox() {
		$fields = Advanced_Module::get_db_fields();
		$data   = Advanced_Module::get_db_count();

		foreach ( $fields as $type => $field ) {
			$fields[ $type ]['value'] = $data->$type;
		}

		$this->view( 'advanced/db-meta-box', compact( 'fields' ) );
	}

	/**
	 * *************************
	 * System Information page meta boxes.
	 ***************************/

	/**
	 * System Information meta box.
	 */
	public function system_info_metabox() {
		$this->view(
			'advanced/system-info-meta-box',
			array(
				'system_info' => array(
					'php'    => Advanced_Module::get_php_info(),
					'db'     => Advanced_Module::get_db_info(),
					'wp'     => Advanced_Module::get_wp_info(),
					'server' => Advanced_Module::get_server_info(),
				),
			)
		);
	}

	/**
	 * *************************
	 * Lazy load page meta boxes.
	 *
	 * @since 2.5.0
	 ***************************/

	/**
	 * Lazy load meta box.
	 *
	 * @since 2.5.0
	 */
	public function advanced_lazy_metabox() {
		$options = Settings::get_settings( 'advanced' );

		$this->view(
			'advanced/lazy-load-meta-box',
			array(
				'is_enabled'                      => $options['lazy_load']['enabled'],
				'method'                          => $options['lazy_load']['method'],
				'button'                          => $options['lazy_load']['button'],
				'preload'                         => isset( $options['lazy_load']['preload'] ) ? $options['lazy_load']['preload'] : false,
				'threshold'                       => $options['lazy_load']['threshold'],
				'smush_activate_url'              => $this->smush_activation_url(),
				'activate_smush_lazy_load_url'    => self_admin_url( 'admin.php?page=smush&view=lazy_load' ),
				'is_smush_lazy_load_configurable' => $this->is_lazy_load_configurable(),
				'is_smush_active'                 => $this->is_smush_enabled(),
				'is_smush_installed'              => $this->is_smush_installed(),
				'is_smush_pro'                    => $this->is_smush_pro,
				'smush_lazy_load'                 => $this->is_lazy_load_enabled(),
			)
		);
	}

	/**
	 * *************************
	 * Plugin health page meta boxes.
	 *
	 * @since 2.7.0
	 ***************************/

	/**
	 * Plugin health meta box.
	 *
	 * @since 2.7.0
	 */
	public function site_health_metabox() {
		$preloader = new Preload();
		$this->view(
			'advanced/site-health-meta-box',
			array(
				'minify_groups'  => Minify_Group::get_minify_groups(),
				'orphaned_metas' => Utils::get_module( 'advanced' )->get_orphaned_ao_complex(),
				'preloading'     => Settings::get_setting( 'preload', 'page_cache' ) || $preloader->is_process_running(),
				'queue_size'     => $preloader->get_queue_size(),
			)
		);
	}

}