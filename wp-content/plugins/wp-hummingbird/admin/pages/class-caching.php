<?php
/**
 * Caching pages: page caching, browser caching, gravatar caching, rss caching, settings for page caching.
 *
 * @package Hummingbird
 *
 * @since 1.9.0  Refactored to run admin page actions in order (first - register_meta_boxes, second - on_load, etc).
 */

namespace Hummingbird\Admin\Pages;

use Hummingbird\Admin\Page;
use Hummingbird\Core\Integration\Opcache;
use Hummingbird\Core\Module_Server;
use Hummingbird\Core\Modules\Caching\Preload;
use Hummingbird\Core\Modules\Cloudflare;
use Hummingbird\Core\Modules\Page_Cache;
use Hummingbird\Core\Settings;
use Hummingbird\Core\Utils;
use Hummingbird\Core\Modules\Caching\Fast_CGI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Caching
 *
 * @property array tabs
 */
class Caching extends Page {

	use \Hummingbird\Core\Traits\WPConfig;

	/**
	 * Number of issues.
	 *
	 * If Cloudflare is enabled will calculate number of issues for it, if not - number of local issues.
	 *
	 * @since 1.5.3
	 * @var   int $issues  Default 0.
	 */
	private $issues = 0;

	/**
	 * Register meta boxes for the page.
	 */
	public function register_meta_boxes() {
		/**
		 * PAGE CACHING META BOXES.
		 */
		$caching_callback = false;
		if ( ( is_multisite() && is_network_admin() ) || ! is_multisite() ) {
			/**
			 * SUMMARY META BOX
			 */
			$this->add_meta_box(
				'summary',
				null,
				array( $this, 'caching_summary' ),
				null,
				null,
				'main',
				array(
					'box_class'         => 'sui-box sui-summary ' . Utils::get_whitelabel_class(),
					'box_content_class' => false,
				)
			);

			// Main site.
			$caching_callback = true;
		} elseif ( is_super_admin() || 'blog-admins' === Settings::get_setting( 'enabled', 'page_cache' ) ) {
			// Sub sites.
			$caching_callback = true;
		}

		/**
		 * PAGE CACHE META BOXES
		 */
		if ( Utils::get_api()->hosting->has_fast_cgi_header() ) {
			if ( ( is_multisite() && is_network_admin() ) || ! is_multisite() ) {
				$this->add_meta_box(
					'caching/page/fast-cgi',
					__( 'Page Caching', 'wphb' ),
					array( $this, 'page_caching_fast_cgi_metabox' ),
					array( $this, 'page_caching_metabox_header' ),
					function () {
						Fast_CGI::is_fast_cgi_supported() ? $this->view( 'caching/meta-box-footer', array() ) : null;
					},
					'page_cache'
				);
			} else {
				$this->add_meta_box(
					'caching/page/fastcgi-subsite',
					__( 'Page Caching', 'wphb' ),
					null,
					array( $this, 'page_caching_metabox_header' ),
					null,
					'page_cache'
				);
			}
		} elseif ( Utils::get_module( 'page_cache' )->is_active() ) {
			$footer = ( is_multisite() && is_network_admin() ) || ! is_multisite();
			$this->add_meta_box(
				'caching/page-caching',
				__( 'Page Caching', 'wphb' ),
				array( $this, 'page_caching_metabox' ),
				array( $this, 'page_caching_metabox_header' ),
				$footer ? array( $this, 'page_caching_metabox_footer' ) : null,
				'page_cache'
			);
		} elseif ( $caching_callback ) {
			$this->add_meta_box(
				'caching/page-caching-disabled',
				__( 'Page Caching', 'wphb' ),
				array( $this, 'page_caching_disabled_metabox' ),
				null,
				null,
				'page_cache',
				array( 'box_content_class' => 'sui-box sui-message' )
			);
		}

		/**
		 * INTEGRATION META BOXES.
		 *
		 * @since 2.5.0
		 */
		$this->add_meta_box(
			'integrations',
			__( 'Integrations', 'wphb' ),
			array( $this, 'integrations_metabox' ),
			null,
			null,
			'integrations'
		);

		// Do not continue on subsites.
		if ( is_multisite() && ! is_network_admin() ) {
			return;
		}

		/**
		 * GRAVATAR CACHING META BOXES.
		 */
		if ( Utils::get_module( 'gravatar' )->is_active() ) {
			$this->add_meta_box(
				'caching/gravatar',
				__( 'Gravatar Caching', 'wphb' ),
				array( $this, 'caching_gravatar_metabox' ),
				null,
				null,
				'gravatar'
			);
		} else {
			$this->add_meta_box(
				'gravatar-disabled',
				__( 'Gravatar Caching', 'wphb' ),
				array( $this, 'caching_gravatar_disabled_metabox' ),
				null,
				null,
				'gravatar',
				array( 'box_content_class' => 'sui-box sui-message' )
			);
		}

		/**
		 * RSS CACHING META BOXES.
		 */
		$this->add_meta_box(
			Utils::get_module( 'rss' )->is_active() ? 'caching/rss' : 'caching/rss-disabled',
			__( 'RSS Caching', 'wphb' ),
			array( $this, 'caching_rss_metabox' ),
			null,
			function () {
				$this->view( 'caching/meta-box-footer', array() );
			},
			'rss'
		);

		/**
		 * SETTINGS META BOX
		 */
		$this->add_meta_box(
			'caching/other-settings',
			__( 'Settings', 'wphb' ),
			array( $this, 'settings_metabox' ),
			null,
			function () {
				$this->view( 'caching/meta-box-footer', array() );
			},
			'settings'
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook  Hook from where the call is made.
	 */
	public function enqueue_scripts( $hook ) {
		parent::enqueue_scripts( $hook );

		// Only for integrations and browser caching.
		if ( ! in_array( $this->get_current_tab(), array( 'caching', 'integrations' ), true ) ) {
			return;
		}

		$cloudflare = Utils::get_module( 'cloudflare' )->is_connected() && Utils::get_module( 'cloudflare' )->is_zone_selected();
		if ( 'caching' === $this->get_current_tab() && is_multisite() && ! is_main_site() && ! $cloudflare ) {
			return;
		}

		// Load styles.
		if ( file_exists( WPHB_DIR_PATH . 'admin/assets/css/wphb-react-' . $this->get_current_tab() . '.min.css' ) ) {
			wp_enqueue_style(
				'wphb-styles-' . $this->get_current_tab(),
				WPHB_DIR_URL . 'admin/assets/css/wphb-react-' . $this->get_current_tab() . '.min.css',
				array(),
				WPHB_VERSION
			);
		}

		// Load scripts.
		if ( file_exists( WPHB_DIR_PATH . 'admin/assets/js/wphb-react-' . $this->get_current_tab() . '.min.js' ) ) {
			wp_enqueue_script(
				'wphb-react-' . $this->get_current_tab(),
				WPHB_DIR_URL . 'admin/assets/js/wphb-react-' . $this->get_current_tab() . '.min.js',
				array( 'wp-i18n', 'lodash', 'wphb-react-lib' ),
				WPHB_VERSION,
				true
			);
		}

		// Common settings.
		$settings = array(
			'links'  => array(
				'wphbDirUrl' => WPHB_DIR_URL,
			),
			'nonces' => array(
				'HBFetchNonce' => wp_create_nonce( 'wphb-fetch' ),
			),
		);

		if ( 'caching' === $this->get_current_tab() ) {
			$settings = array_merge_recursive(
				$settings,
				array(
					'isMember' => Utils::is_member(),
					'links'    => array(
						'support' => array(
							'chat'  => Utils::get_link( 'chat' ),
							'forum' => Utils::get_link( 'support' ),
						),
					),
					'module'   => array(
						'isWhiteLabeled'   => apply_filters( 'wpmudev_branding_hide_branding', false ),
						'htaccessWritable' => Module_Server::is_htaccess_writable(),
						'htaccessWritten'  => Module_Server::is_htaccess_written( 'caching' ),
						'cacheTypes'       => Utils::get_module( 'caching' )->get_types(),
						'recommended'      => Utils::get_module( 'caching' )->get_recommended_caching_values(),
						'detectedServer'   => Module_Server::get_server_type(),
						'frequencies'      => \Hummingbird\Core\Modules\Caching::get_frequencies(),
						'frequenciesCF'    => Cloudflare::get_frequencies(),
						'snippets'         => array(
							'apache' => Module_Server::get_code_snippet( 'caching', 'apache' ),
							'nginx'  => Module_Server::get_code_snippet( 'caching', 'nginx' ),
							'iis'    => Module_Server::get_code_snippet( 'caching', 'iis' ),
						),
					),
				)
			);
		}

		if ( 'integrations' === $this->get_current_tab() ) {
			$options     = Utils::get_module( 'cloudflare' )->get_options();
			$expiry      = Utils::get_module( 'cloudflare' )->get_caching_expiration();
			$frequencies = Cloudflare::get_frequencies();

			$settings = array_merge_recursive(
				$settings,
				array(
					'modify' => ( is_multisite() && ( is_network_admin() || ! is_main_site() ) ) || ! is_multisite(),
					'links'  => array(
						'caching' => Utils::get_admin_menu_url( 'caching' ) . '&view=caching#wphb-box-caching-settings',
					),
					'module' => array(
						'cloudflare' => array(
							'accountId' => $options['account_id'],
							'connected' => Utils::get_module( 'cloudflare' )->is_connected(),
							'dnsSet'    => Utils::get_module( 'cloudflare' )->has_cloudflare(),
							'expiry'    => $expiry,
							'human'     => $frequencies[ $expiry ],
							'zone'      => $options['zone'],
							'zoneName'  => $options['zone_name'],
						),
						'apo'        => array(
							'enabled'   => Utils::get_module( 'cloudflare' )->is_apo_enabled(),
							'purchased' => $options['apo_paid'],
							'settings'  => $options['apo'],
						),
					),
				)
			);
		}

		wp_localize_script( 'wphb-react-' . $this->get_current_tab(), 'wphbReact', $settings );

		wp_add_inline_script(
			'wphb-react-' . $this->get_current_tab(),
			'wp.i18n.setLocaleData( ' . wp_json_encode( Utils::get_locale_data() ) . ', "wphb" );',
			'before'
		);
	}

	/**
	 * Function triggered when the page is loaded before render any content.
	 *
	 * @since 1.7.0
	 * @since 1.9.0  Moved here from init().
	 */
	public function on_load() {
		$this->tabs = array(
			'page_cache'   => __( 'Page Caching', 'wphb' ),
			'caching'      => __( 'Browser Caching', 'wphb' ),
			'gravatar'     => __( 'Gravatar Caching', 'wphb' ),
			'rss'          => __( 'RSS Caching', 'wphb' ),
			'integrations' => __( 'Integrations', 'wphb' ),
			'settings'     => __( 'Settings', 'wphb' ),
		);

		// We need to update the status on all pages, for the menu icons to function properly.
		$this->update_cache_status();

		// Remove modules that are not used on subsites in a network.
		if ( is_multisite() && ! is_network_admin() ) {
			if ( ! Settings::get_setting( 'enabled', 'page_cache' ) && ! Fast_CGI::is_fast_cgi_enabled() ) {
				unset( $this->tabs['page_cache'] );
			}

			$cloudflare_is_setup = Utils::get_module( 'cloudflare' )->is_connected() && Utils::get_module( 'cloudflare' )->is_zone_selected();
			if ( ! $cloudflare_is_setup ) {
				unset( $this->tabs['caching'] );
			}

			unset( $this->tabs['gravatar'] );
			unset( $this->tabs['rss'] );
			unset( $this->tabs['settings'] );
		}
	}

	/**
	 * Execute an action for specified module.
	 *
	 * Action will execute if:
	 * - Both action and module vars are defined;
	 * - Action is available as a methods in a selected module.
	 *
	 * Used actions: enable, disable, disconnect.
	 * Supported modules: page_cache, caching, cloudflare, gravatar, rss.
	 *
	 * @since 1.9.0  Moved here from on_load().
	 */
	public function trigger_load_action() {
		parent::trigger_load_action();

		if ( ! isset( $_GET['action'] ) || ! isset( $_GET['module'] ) ) { // Input var ok.
			return;
		}

		check_admin_referer( 'wphb-caching-actions' );
		$action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); // Input var ok.
		$module = sanitize_text_field( wp_unslash( $_GET['module'] ) ); // Input var ok.

		// If unsupported module - exit.
		$mod = Utils::get_module( $module );

		// Allow only supported actions.
		if ( ! $mod || ! in_array( $action, array( 'enable', 'disable', 'disconnect' ), true ) ) {
			return;
		}

		if ( method_exists( $mod, $action ) ) {
			call_user_func( array( $mod, $action ) );
		}

		$redirect_url = add_query_arg( array( 'view' => $module ), Utils::get_admin_menu_url( 'caching' ) );
		wp_safe_redirect( $redirect_url );
	}

	/**
	 * Hooks for caching pages.
	 *
	 * @since 1.9.0
	 */
	public function add_screen_hooks() {
		parent::add_screen_hooks();

		// Icons in the submenu.
		add_filter( 'wphb_admin_after_tab_' . $this->get_slug(), array( $this, 'after_tab' ) );

		// Redis notice text.
		add_filter( 'wphb_update_notice_text', array( $this, 'redis_notice_update_text' ) );
	}

	/**
	 * Init browser cache settings.
	 *
	 * @since 1.8.1
	 */
	private function update_cache_status() {
		/**
		 * Check Cloudflare status.
		 *
		 * If Cloudflare is active, we store the values of CLoudFlare caching settings to the report variable.
		 * Else - we store the local setting in the report variable. That way we don't have to query and check
		 * later on what report to show to the user.
		 */
		if ( Utils::get_module( 'cloudflare' )->is_connected() && Utils::get_module( 'cloudflare' )->is_zone_selected() ) {
			$options = Settings::get_settings( 'caching' );
			$expires = array(
				'CSS'        => $options['expiry_css'],
				'JavaScript' => $options['expiry_javascript'],
				'Media'      => $options['expiry_media'],
				'Images'     => $options['expiry_images'],
			);

			$expiration = Utils::get_module( 'cloudflare' )->get_caching_expiration();
			// Fill the report with values from Cloudflare.
			$report = array_fill_keys( array_keys( $expires ), $expiration );
			// Get number of issues.
			if ( YEAR_IN_SECONDS > $expiration ) {
				$this->issues = count( $report ) + 1; // One additional issue for Cloudflare.
			}
			return;
		}

		// Get the latest local report.
		$report = Utils::get_module( 'caching' )->get_analysis_data();

		// Get number of issues.
		$this->issues = Utils::get_number_of_issues( 'caching', $report );
	}

	/**
	 * We need to insert an extra label to the tabs sometimes
	 *
	 * @param string $tab Current tab.
	 */
	public function after_tab( $tab ) {
		if ( 'caching' === $tab ) {
			if ( 0 !== $this->issues ) {
				echo '<span class="sui-tag sui-tag-warning">' . absint( $this->issues ) . '</span>';
				return;
			}

			echo '<span class="sui-icon-check-tick sui-success" aria-hidden="true"></span>';
			return;
		}

		// Available modules.
		if ( ! in_array( $tab, array( 'gravatar', 'page_cache', 'rss' ), true ) ) {
			return;
		}

		$module = Utils::get_module( $tab );

		if ( ( $module->is_active() && ( ! isset( $module->error ) || ! is_wp_error( $module->error ) ) ) || ( 'page_cache' === $tab && Fast_CGI::is_fast_cgi_enabled() ) ) {
			echo '<span class="sui-icon-check-tick sui-success" aria-hidden="true"></span>';
		} elseif ( isset( $module->error ) && is_wp_error( $module->error ) ) {
			echo '<span class="sui-icon-warning-alert sui-warning" aria-hidden="true"></span>';
		}
	}

	/**
	 * *************************
	 * CACHING SUMMARY
	 *
	 * @since 1.9.1
	 ***************************/

	/**
	 * Caching summary meta box.
	 */
	public function caching_summary() {
		$preloader = new Preload();

		$this->view(
			'caching/summary-meta-box',
			array(
				'pc_active'       => Utils::get_module( 'page_cache' )->is_active(),
				'cached'          => Settings::get_setting( 'pages_cached', 'page_cache' ),
				'issues'          => $this->issues,
				'gravatar'        => Utils::get_module( 'gravatar' )->is_active(),
				'rss'             => Settings::get_setting( 'duration', 'rss' ),
				'preload_running' => $preloader->is_process_running(),
				'preload_active'  => Settings::get_setting( 'preload', 'page_cache' ),
				'options'         => Utils::get_module( 'page_cache' )->get_options(),
				'has_fastcgi'     => Utils::get_api()->hosting->has_fast_cgi_header(),
			)
		);
	}

	/**
	 * *************************
	 * PAGE CACHING
	 *
	 * @since 1.7.0
	 ***************************/

	/**
	 * Disabled page caching meta box.
	 */
	public function page_caching_disabled_metabox() {
		$this->view(
			'caching/page/disabled-meta-box',
			array(
				'activate_url'          => wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'enable',
							'module' => 'page_cache',
						)
					),
					'wphb-caching-actions'
				),
				'is_fast_cgi_supported' => Fast_CGI::is_fast_cgi_supported(),
				'is_homepage_preload'   => Utils::is_homepage_preload_enabled() ? 'enabled' : 'disabled',
			)
		);
	}

	/**
	 * Page caching fastCGI meta box.
	 */
	public function page_caching_fast_cgi_metabox() {
		$this->view(
			'caching/page/fast-cgi-meta-box',
			array(
				'fast_cgi_settings'     => Fast_CGI::wphb_fast_cgi_data( true ),
				'is_fast_cgi_supported' => Fast_CGI::is_fast_cgi_supported(),
				'options'               => Utils::get_module( 'page_cache' )->get_options(),
				'settings'              => Utils::get_module( 'page_cache' )->get_settings(),
			)
		);
	}

	/**
	 * Page caching meta box.
	 */
	public function page_caching_metabox() {
		$module  = Utils::get_module( 'page_cache' );
		$options = $module->get_options();

		$common_args = array(
			'error'          => $module->error,
			'deactivate_url' => wp_nonce_url(
				add_query_arg(
					array(
						'action' => 'disable',
						'module' => 'page_cache',
					)
				),
				'wphb-caching-actions'
			),
			'minify_active'  => Utils::get_module( 'minify' )->is_active(),
			'cdn_active'     => Utils::get_module( 'minify' )->get_cdn_status(),
		);

		if ( ( is_multisite() && is_network_admin() ) || ! is_multisite() ) {
			$custom_post_types = array();
			$settings          = $module->get_settings();
			if ( isset( $settings['custom_post_types'] ) ) {
				$custom_post_types = $settings['custom_post_types'];
			}
			$settings['custom_post_types'] = $custom_post_types;

			$log = WP_CONTENT_DIR . '/wphb-logs/page-caching-log.php';
			if ( ! file_exists( $log ) ) {
				$log = false;
			} else {
				$log = content_url() . '/wphb-logs/page-caching-log.php';
			}

			$opcache = Opcache::get_instance();

			$gzip = Utils::get_module( 'gzip' )->get_analysis_data();

			$args = array(
				'settings'           => $settings,
				'clear_interval'     => Utils::format_interval_hours( $settings['clear_interval']['interval'] ),
				'options'            => $options,
				'admins_can_disable' => 'blog-admins' === $options['enabled'],
				'blog_is_frontpage'  => 'posts' === get_option( 'show_on_front' ) && ! is_multisite(),
				'opcache_enabled'    => $opcache->is_enabled(),
				'pages'              => Page_Cache::get_page_types(),
				'can_compress'       => ! isset( $gzip['HTML'] ) || ! $gzip['HTML'],
				'custom_post_types'  => get_post_types(
					array(
						'public'   => true,
						'_builtin' => false,
					),
					'objects'
				),
				'logs_link'          => $log,
				'download_url'       => wp_nonce_url(
					add_query_arg(
						array(
							'logs'   => 'download',
							'module' => $module->get_slug(),
						)
					),
					'wphb-log-action'
				),
			);

			$this->view( 'caching/page/meta-box', wp_parse_args( $args, $common_args ) );
		} else {
			$args = array(
				'can_deactivate' => 'blog-admins' === $options['enabled'],
			);

			$this->view( 'caching/page/subsite-meta-box', wp_parse_args( $args, $common_args ) );
		}
	}

	/**
	 * Page caching header meta box.
	 *
	 * @since 2.7.1
	 */
	public function page_caching_metabox_header() {
		$args = array(
			'title'                 => Utils::get_cache_page_title(),
			'has_fastcgi'           => Utils::get_api()->hosting->has_fast_cgi_header(),
			'is_fast_cgi_supported' => Fast_CGI::is_fast_cgi_supported(),
			'is_subsite'            => Utils::is_subsite(),
		);

		$this->view( 'caching/page/meta-box-header', $args );
	}

	/**
	 * Page caching footer meta box.
	 *
	 * @since 2.7.1
	 */
	public function page_caching_metabox_footer() {
		$this->view( 'caching/page/meta-box-footer', array() );
	}

	/**
	 * *************************
	 * GRAVATAR CACHING
	 *
	 * @since 1.5.0
	 ***************************/

	/**
	 * Disabled Gravatar caching meta box.
	 *
	 * @since 1.5.3
	 */
	public function caching_gravatar_disabled_metabox() {
		$this->view(
			'caching/gravatar/disabled-meta-box',
			array(
				'activate_url' => wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'enable',
							'module' => 'gravatar',
						)
					),
					'wphb-caching-actions'
				),
			)
		);
	}

	/**
	 * Gravatar meta box.
	 */
	public function caching_gravatar_metabox() {
		$module = Utils::get_module( 'gravatar' );

		$this->view(
			'caching/gravatar/meta-box',
			array(
				'module_active'  => $module->is_active(),
				'error'          => $module->error,
				'deactivate_url' => wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'disable',
							'module' => 'gravatar',
						)
					),
					'wphb-caching-actions'
				),
			)
		);
	}

	/**
	 * *************************
	 * RSS CACHING
	 *
	 * @since 1.8
	 ***************************/

	/**
	 * Display Rss caching meta box.
	 */
	public function caching_rss_metabox() {
		$active = Utils::get_module( 'rss' )->is_active();

		$args = array(
			'url' => wp_nonce_url(
				add_query_arg(
					array(
						'action' => $active ? 'disable' : 'enable',
						'module' => 'rss',
					)
				),
				'wphb-caching-actions'
			),
		);

		$meta_box = 'caching/rss/disabled-meta-box';
		if ( $active ) {
			$meta_box         = 'caching/rss/meta-box';
			$args['duration'] = Settings::get_setting( 'duration', 'rss' );
		}

		$this->view( $meta_box, $args );
	}

	/**
	 * *************************
	 * INTEGRATIONS
	 *
	 * @since 2.5.0
	 ***************************/

	/**
	 * Display integrations meta box.
	 */
	public function integrations_metabox() {
		$redis_vars = Utils::get_module( 'redis' )->get_status_related_vars();

		$this->view(
			'caching/integrations/meta-box',
			array(
				'apo_purchased'         => Settings::get_setting( 'apo_paid', 'cloudflare' ),
				'cf_is_connected'       => Utils::get_module( 'cloudflare' )->is_connected(),
				'has_cloudflare'        => Utils::get_module( 'cloudflare' )->has_cloudflare(),
				'redis_connected'       => $redis_vars['redis_connected'],
				'redis_enabled'         => $redis_vars['redis_enabled'],
				'is_redis_object_cache' => $redis_vars['is_redis_object_cache'],
				'disable_redis'         => $redis_vars['disable_redis'],
				'error'                 => $redis_vars['connection_error'],
			)
		);
	}

	/**
	 * Adjust Redis notice text (update/save changes) according to design.
	 *
	 * @param string $text  Current notice text.
	 *
	 * @return string
	 */
	public function redis_notice_update_text( $text ) {
		$updated = filter_input( INPUT_GET, 'updated', FILTER_UNSAFE_RAW );

		if ( 0 === strpos( $updated, 'redis' ) ) {
			return Utils::get_module( 'redis' )->get_update_notice( $updated );
		}

		return $text;
	}

	/**
	 * *************************
	 * SETTINGS
	 *
	 * @since 1.8.1
	 ***************************/

	/**
	 * Display settings meta box.
	 */
	public function settings_metabox() {
		$detection = Settings::get_setting( 'detection', 'page_cache' );
		$this->view( 'caching/settings/meta-box', compact( 'detection' ) );
	}

	/**
	 * Overwrites parent class render_header method.
	 */
	public function render_header() {
		add_action( 'wphb_sui_header_sui_actions_right', array( $this, 'add_header_actions' ) );

		parent::render_header();
	}

	/**
	 * Add clear cache button to the header.
	 *
	 * @since 3.9.0
	 */
	public function add_header_actions() {
		if ( ! Utils::get_api()->hosting->has_fast_cgi_header() && ! Utils::get_module( 'page_cache' )->is_active() ) {
			return;
		}

		$view_tab = sanitize_text_field( filter_input( INPUT_GET, 'view', FILTER_UNSAFE_RAW ) );
		if ( ! empty( $view_tab ) && 'page_cache' !== $view_tab ) {
			return;
		}
		?>
		<button type="button" class="sui-button sui-tooltip sui-tooltip-bottom-right sui-tooltip-constrained" id="wphb-clear-cache" data-module="page_cache" data-tooltip="<?php esc_attr_e( 'Clear all page cache', 'wphb' ); ?>" aria-live="polite">
			<!-- Default State Content -->
			<span class="sui-button-text-default">
				<?php esc_html_e( 'Clear cache', 'wphb' ); ?>
			</span>

			<!-- Loading State Content -->
			<span class="sui-button-text-onload">
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				<?php esc_html_e( 'Clearing cache', 'wphb' ); ?>
			</span>
		</button>
		<?php
	}
}