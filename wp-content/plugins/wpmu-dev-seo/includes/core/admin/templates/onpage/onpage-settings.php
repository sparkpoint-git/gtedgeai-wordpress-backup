<?php
/**
 * Template: Onpage Settings.
 *
 * @package Smartcrwal
 */

namespace SmartCrawl;

$active_tab                = empty( $active_tab ) ? '' : $active_tab;
$front_page                = empty( $front_page ) ? null : $front_page;
$front_page_notice         = empty( $front_page_notice ) ? '' : $front_page_notice;
$show_static_home_settings = empty( $show_static_home_settings ) ? false : $show_static_home_settings;
$onpage_enabled            = Settings::get_setting( 'onpage' );
?>

<?php $this->render_view( 'before-page-container' ); ?>

<div id="container" class="<?php \smartcrawl_wrap_class( 'wds-page-onpage' ); ?>">
	<?php
	$this->render_view(
		'page-header',
		array(
			'title'                 => esc_html__( 'Title & Meta', 'wds' ),
			'documentation_chapter' => 'title-meta',
			'extra_actions'         => 'onpage/onpage-header-actions',
			'utm_campaign'          => 'smartcrawl_title-meta_docs',
		)
	);
	?>

	<?php $this->render_view( 'floating-notices' ); ?>

	<?php
	$this->render_view(
		'modal',
		array(
			'id'            => 'wds-supported-macros-modal',
			'title'         => esc_html__( 'Supported Macros', 'wds' ),
			'body_template' => 'onpage/onpage-macros-modal',
		)
	);
	?>

	<?php
	if ( ! $onpage_enabled ) {
		$this->render_view( 'onpage/onpage-disabled' );
	} else {
		?>
		<div class="wds-vertical-tabs-container sui-row-with-sidenav" id="page-title-meta-tabs">
			<?php
			$this->render_view(
				'onpage/onpage-sidenav',
				array(
					'active_tab'                => $active_tab,
					'show_static_home_settings' => $show_static_home_settings,
				)
			);

			$this->render_view(
				'vertical-tab',
				array(
					'tab_id'       => 'tab_static_homepage',
					'tab_name'     => esc_html__( 'Homepage', 'wds' ),
					'is_active'    => 'tab_static_homepage' === $active_tab,
					'button_text'  => false,
					'tab_sections' => array(
						array(
							'section_description' => esc_html__( 'Customize your homepage title, description and meta options.', 'wds' ),
							'section_type'        => 'static-homepage',
							'section_template'    => 'onpage/onpage-static-homepage',
							'section_args'        => array(
								'front_page'        => $front_page,
								'front_page_notice' => $front_page_notice,
							),
						),
					),
				)
			);
			?>

			<form action='<?php echo esc_attr( $_view['action_url'] ); ?>' method='post' class="wds-form">
				<?php $this->settings_fields( $_view['option_name'] ); ?>

				<input
					type="hidden"
					name='<?php echo esc_attr( $_view['option_name'] ); ?>[<?php echo esc_attr( $_view['slug'] ); ?>-setup]'
					value="1"
				>
				<?php
				/*
				 * Homepage tab
				 */
				$this->render_view(
					'vertical-tab',
					array(
						'tab_id'       => 'tab_homepage',
						'tab_name'     => esc_html__( 'Homepage', 'wds' ),
						'is_active'    => 'tab_homepage' === $active_tab,
						'button_text'  => false,
						'tab_sections' => array(
							array(
								'section_description' => esc_html__( 'Customize your homepage title, description and meta options.', 'wds' ),
								'section_type'        => 'home',
								'section_template'    => 'onpage/onpage-list-homepage',
								'section_args'        => array(
									'meta_robots_main_blog_archive' => $meta_robots_main_blog_archive,
								),
							),
						),
					)
				);

				/*
				 * Post types tab
				 */
				// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
				$post_type_sections = array();
				foreach ( get_post_types( array( 'public' => true ) ) as $post_type ) {
					if ( in_array( $post_type, array( 'revision', 'nav_menu_item' ), true ) ) {
						continue;
					}

					$wp_post_type_object = get_post_type_object( $post_type );

					$post_type_sections[] = array(
						'section_title'       => $wp_post_type_object->labels->name,
						/* translators: %s: Singular post type name */
						'section_description' => sprintf( esc_html__( 'Customize your %s title, description and meta options.', 'wds' ), strtolower( $wp_post_type_object->labels->singular_name ) ),
						'section_type'        => $post_type,
						'section_template'    => 'onpage/onpage-section-post-type',
						'section_args'        => array(
							'post_type'        => $post_type,
							'post_type_object' => $wp_post_type_object,
							'post_type_robots' => ( ! empty( $post_robots[ $post_type ] ) ? $post_robots[ $post_type ] : array() ),
						),
					);
				}

				$this->render_view(
					'vertical-tab',
					array(
						'tab_id'       => 'tab_post_types',
						'tab_name'     => esc_html__( 'Post Types', 'wds' ),
						'is_active'    => 'tab_post_types' === $active_tab,
						'tab_sections' => $post_type_sections,
					)
				);

				/*
				 * Other taxonomies
				 */
				$taxonomy_sections = array();
				$taxonomies        = array_merge(
					array( 'post_tag', 'category' ),
					get_taxonomies(
						array(
							'_builtin' => false,
							'public'   => true,
						)
					)
				);
				foreach ( $taxonomies as $taxonomy_name ) {
					$wp_taxonomy               = get_taxonomy( $taxonomy_name );
					$meta_robots_taxonomy_name = 'meta_robots_' . str_replace( '-', '_', $wp_taxonomy->name );
					$taxonomy_sections[]       = array(
						'section_title'       => $wp_taxonomy->label,
						/* translators: %s: Taxonomy label */
						'section_description' => sprintf( esc_html__( 'Customize the title, description and meta options for %s.', 'wds' ), strtolower( $wp_taxonomy->label ) ),
						'section_type'        => $wp_taxonomy->name,
						'section_template'    => 'onpage/onpage-section-taxonomy',
						'section_args'        => array(
							'taxonomy'    => $wp_taxonomy,
							'meta_robots' => $$meta_robots_taxonomy_name,
						),
					);
				}

				$this->render_view(
					'vertical-tab',
					array(
						'tab_id'       => 'tab_taxonomies',
						'tab_name'     => esc_html__( 'Taxonomies', 'wds' ),
						'is_active'    => 'tab_taxonomies' === $active_tab,
						'tab_sections' => $taxonomy_sections,
					)
				);

				$archive_sections = array(
					// Author archive.
					array(
						'section_title'          => esc_html__( 'Author Archive', 'wds' ),
						'section_description'    => esc_html__( 'If you are the only author of your website content Google may see your author archives as duplicate content to your Blog Homepage. If this is the case we recommend disabling author archives.', 'wds' ),
						'section_type'           => 'author',
						'section_template'       => 'onpage/onpage-section-author-archive',
						'section_enabled_option' => 'enable-author-archive',
						'section_toggle_tooltip' => esc_html__( 'Enable/Disable author archives depending on whether you require them or not', 'wds' ),
						'section_args'           => array(
							'meta_robots_author' => $meta_robots_author,
						),
					),
					// Date archive.
					array(
						'section_title'          => esc_html__( 'Date Archive', 'wds' ),
						'section_description'    => esc_html__( 'Google may see your date archives as duplicate content to your Blog Homepage. For this reason we recommend disabling date archives.', 'wds' ),
						'section_type'           => 'date',
						'section_template'       => 'onpage/onpage-section-date-archive',
						'section_enabled_option' => 'enable-date-archive',
						'section_toggle_tooltip' => esc_html__( 'Enable/Disable date archives depending on whether you require them or not', 'wds' ),
						'section_args'           => array(
							'meta_robots_date' => $meta_robots_date,
						),
					),
					// Search page.
					array(
						'section_title'       => esc_html__( 'Search Page', 'wds' ),
						'section_description' => esc_html__( 'Customize your search page title, description and meta options.', 'wds' ),
						'section_type'        => 'search',
						'section_template'    => 'onpage/onpage-section-search',
						'section_args'        => array(
							'meta_robots_search' => $meta_robots_search,
						),
					),
					// 404 page.
					array(
						'section_title'       => esc_html__( '404 Page', 'wds' ),
						'section_description' => esc_html__( 'Customize your 404 page title, description and meta options.', 'wds' ),
						'section_type'        => '404',
						'section_template'    => 'onpage/onpage-section-404',
						'section_args'        => array(),
					),
				);

				/**
				 * Post types.
				 *
				 * @var array $archive_post_types
				 */
				foreach ( $archive_post_types as $archive_post_type => $archive_post_type_label ) {

					$archive_sections[] = array(
						'section_title'       => $archive_post_type_label . esc_html__( ' Archive', 'wds' ),
						'section_description' => sprintf(
							/* translators: %s: Archive post type label */
							esc_html__( 'Customize title, description and meta for the archive page of custom post type %s.', 'wds' ),
							strtolower( $archive_post_type_label )
						),
						'section_type'        => $archive_post_type,
						'section_template'    => 'onpage/onpage-section-post-type-archive',
						'section_args'        => array(
							'archive_post_type'        => $archive_post_type,
							'archive_post_type_label'  => $archive_post_type_label,
							'archive_post_type_robots' => ( ! empty( $archive_post_type_robots[ $archive_post_type ] ) ? $archive_post_type_robots[ $archive_post_type ] : array() ),
						),
					);
				}

				/*
				 * Archives
				 */
				$this->render_view(
					'vertical-tab',
					array(
						'tab_id'       => 'tab_archives',
						'tab_name'     => esc_html__( 'Archives', 'wds' ),
						'is_active'    => 'tab_archives' === $active_tab,
						'tab_sections' => $archive_sections,
					)
				);

				// Makes sure is_plugin_active_for_network function is existing.
				if ( ! function_exists( '\is_plugin_active_for_network' ) ) {
					require_once ABSPATH . '/wp-admin/includes/plugin.php';
				}

				// Is BuddyPress active.
				$buddypress_active = is_plugin_active_for_network( 'buddypress/bp-loader.php' )
					? is_main_site()
					: is_plugin_active( 'buddypress/bp-loader.php' );

				if ( $buddypress_active ) {
					$buddypress_sections = array();

					if ( function_exists( '\groups_get_groups' ) ) {
						$buddypress_sections[] = array(
							'section_title'       => esc_html__( 'BuddyPress Groups', 'wds' ),
							'section_description' => esc_html__( 'Customize your BuddyPress group title, description and meta options.', 'wds' ),
							'section_type'        => 'bp-group',
							'section_template'    => 'onpage/onpage-section-buddypress-groups',
							'section_args'        => array(
								'meta_robots_bp_groups' => $meta_robots_bp_groups,
							),
						);
					}

					$buddypress_sections[] = array(
						'section_title'       => esc_html__( 'BuddyPress Profile', 'wds' ),
						'section_description' => esc_html__( 'Customize your BuddyPress profile title, description and meta options.', 'wds' ),
						'section_type'        => 'bp-profile',
						'section_template'    => 'onpage/onpage-section-buddypress-profile',
						'section_args'        => array(
							'meta_robots_bp_profile' => $meta_robots_bp_profile,
						),
					);

					$this->render_view(
						'vertical-tab',
						array(
							'tab_id'       => 'tab_buddypress',
							'tab_name'     => esc_html__( 'BuddyPress', 'wds' ),
							'is_active'    => 'tab_buddypress' === $active_tab,
							'tab_sections' => $buddypress_sections,
						)
					);
				}
				?>

				<?php
				$this->render_view(
					'vertical-tab',
					array(
						'tab_id'       => 'tab_settings',
						'tab_name'     => esc_html__( 'Settings', 'wds' ),
						'is_active'    => 'tab_settings' === $active_tab,
						'tab_sections' => array(
							array(
								'section_type'     => '',
								'section_template' => 'onpage/onpage-section-settings',
								'section_args'     => array(
									'separators' => $separators,
								),
							),
						),
					)
				);
				?>
			</form>

		</div><!-- end page-title-meta-tabs -->
	<?php } ?>
	<?php $this->render_view( 'footer' ); ?>
</div><!-- end wds-page-onpage -->