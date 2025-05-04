<?php

namespace FileBird\Controller\Import;

use FileBird\Admin\Settings;
use FileBird\Classes\Helpers;

defined( 'ABSPATH' ) || exit;
class ImportController {
    const IMPORT_ENDPOINT = 'import';

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

        $this->add(
            'happyfiles',
            array(
				'name'     => 'HappyFiles',
				'author'   => 'Codeer',
				'taxonomy' => 'happyfiles_category',
			)
        );
        $this->add(
            'enhanced',
            array(
				'name'     => 'Enhanced Media Library',
				'author'   => 'wpUXsolutions',
				'taxonomy' => 'media_category',
			)
        );
        $this->add(
            'wpmf',
            array(
				'name'     => 'WP Media folder',
				'author'   => 'Joomunited',
				'taxonomy' => 'wpmf-category',
			)
        );
        $this->add(
            'premio',
            array(
				'name'     => 'Folders',
				'author'   => 'Premio',
				'taxonomy' => 'media_folder',
			)
        );
        $this->add(
            'wf',
            array(
				'name'     => 'Wicked Folders',
				'author'   => 'Wicked Plugins',
				'taxonomy' => 'wf_attachment_folders',
			)
        );
        $this->add(
            'feml',
            array(
				'name'     => 'WP Media Folders',
				'author'   => 'Damien Barrère',
				'taxonomy' => 'feml-folder',
			)
        );
        $this->add(
            'wpmlf',
            array(
                'name'   => 'WordPress Media Library Folders',
                'author' => 'Max Foundry',
            )
        );
        $this->add(
            'mla',
            array(
                'name'     => 'Media Library Assistant',
                'author'   => 'David Lingren',
                'taxonomy' => 'attachment_category',
            )
        );

        $this->add(
            'realmedia',
            array(
				'name'   => 'WP Real Media Library',
				'author' => 'devowl.io GmbH',
			)
        );

        $this->add(
            'mediamatic',
            array(
                'name'     => 'Mediamatic',
                'author'   => 'Plugincraft',
                'taxonomy' => 'mediamatic_wpfolder',
            )
        );
    }

    public function add( $prefix, $attributes ) {
        new DataImport( $prefix, $attributes );
    }

    public function get_all() {
        return DataImport::get();
    }

    public static function get_all_plugins_import() {
        $data_import         = DataImport::get();
        $total_folder_import = 0;
		foreach ( $data_import as $prefix => $data ) {
			$data_import[ $prefix ]->counter     = self::get_counters( $prefix );
            $data_import[ $prefix ]->completed   = get_option( 'njt_fb_updated_from_' . $data->prefix ) === '1';
            $data_import[ $prefix ]->noThanks    = get_option( "njt_fb_{$data->prefix}_no_thanks" ) === '1';
            $data_import[ $prefix ]->description = sprintf( esc_html__( 'We found you have %1$s categories you created from %2$s plugin. Would you like to import it to %3$s?', 'filebird' ), '<strong>(' . esc_html( $data_import[ $prefix ]->counter ) . ')</strong>', '<strong>' . esc_html( $data->name ) . '</strong>', '<strong>FileBird</strong>' );

            $total_folder_import += $data_import[ $prefix ]->counter;
		}
        return (object) array(
			'plugins'             => array_filter(
                $data_import,
                function( $data ) {
                    return $data->counter !== 0;
				}
            ),
			'total_folder_import' => $total_folder_import,
		);
    }

    public static function get_notice_import( $screen ) {
        if ( $screen !== 'upload.php' && $screen !== Settings::getInstance()->getSettingHookSuffix() ) {
            return (object) array( 'plugins' => new \stdClass() );
        }

        return self::get_all_plugins_import();
    }

    public function register_rest_routes() {
        register_rest_route(
			NJFB_REST_URL,
			self::IMPORT_ENDPOINT . '/get-folders/(?P<prefix>[a-zA-Z]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_folders' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

        register_rest_route(
			NJFB_REST_URL,
			self::IMPORT_ENDPOINT . '/get-attachments/(?P<prefix>[a-zA-Z]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_attachments' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

        register_rest_route(
            NJFB_REST_URL,
            'get-backup-files',
            array(
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_backup_files' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );

        register_rest_route(
            NJFB_REST_URL,
            'download-backup',
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'download_backup' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );
        register_rest_route(
            NJFB_REST_URL,
            'backup-now',
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'backup_now' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );
        register_rest_route(
			NJFB_REST_URL,
			self::IMPORT_ENDPOINT . '/run/(?P<prefix>[a-zA-Z]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'run_import' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);
    }

    public function permission_callback() {
		return current_user_can( 'upload_files' );
    }

    public static function get_counters( $prefix ) {
        $import_method = ImportFactory::getImportMethod( $prefix );
        $data          = DataImport::get( $prefix );

        return $import_method->get_counters( $data );
    }

    public function get_folders( $request ) {
		$prefix = sanitize_key( $request->get_param( 'prefix' ) );

        $import_method = ImportFactory::getImportMethod( $prefix );
        $data          = DataImport::get( $prefix );

        return $import_method->get_folders( $data );
    }

    public function get_attachments( $request ) {
        $prefix = sanitize_key( $request->get_param( 'prefix' ) );

        $import_method = ImportFactory::getImportMethod( $prefix );
        $data          = DataImport::get( $prefix );

        return $import_method->get_attachments( $data );
    }

    public function get_backup_files( $request ) {
        global $wpdb;
        $query = "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'filebird_backup_%' ORDER BY option_id DESC";
        $backup_files = $wpdb->get_col( $query );

        $formatted_backup_files = array_map(
            function( $filename ) {
                $id = substr( $filename, 16, 19 );

                $str_time = preg_replace('/^([^_]*)_([^_]*)_([^_]*)_/', '$1-$2-$3 ', $id, 1);
                $str_time = preg_replace('/_/', ':', $str_time, 2);
                $str_time = str_replace('_', ' ', $str_time);
                $timestamp = strtotime($str_time);
                $format_from_wp_settings = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                $formatted_time = wp_date( $format_from_wp_settings, $timestamp );

                return array(
                    'id' => $id,
                    'name' => $formatted_time,
                );
            },
            $backup_files
        );
        wp_send_json_success( array( 'backup_files' => $formatted_backup_files ) );
    }
    public function download_backup( $request ) {
       
        $id = sanitize_key( $request->get_param( 'id' ) );
        $filename = 'filebird_backup_' . $id;
        $folders = get_option( $filename, array() );
        if( is_array( $folders ) && empty( $folders ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'There are no folders in this backup file.', 'filebird' ) ) );
        }
        if ( ! $folders ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Backup file not found', 'filebird' ) ) );
        }

        wp_send_json_success( array( 'filename' => 'filebird_' . $id . '.csv', 'folders' => $folders ) );
    }

    public function backup_now( $request ) {
        $backup = Helpers::backupFileBird();
        wp_send_json_success( array( $backup ) );
    }
    public function run_import( $request ) {
        $prefix = sanitize_key( $request->get_param( 'prefix' ) );

        $import_method = ImportFactory::getImportMethod( $prefix );
        $data          = DataImport::get( $prefix );

        return $import_method->run( $data );
    }
}