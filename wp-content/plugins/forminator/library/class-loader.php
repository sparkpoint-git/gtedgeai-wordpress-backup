<?php
/**
 * Forminator Loader
 *
 * @package Forminator
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Forminator_Loader
 */
class Forminator_Loader {

	/**
	 * Files
	 *
	 * @var array
	 */
	public $files = array();

	/**
	 * Forminator_Loader constructor.
	 */
	public function __construct() {}

	/**
	 * Retrieve data
	 *
	 * @since 1.0
	 * @since 1.7 add $requirements
	 *
	 * @param string $dir Directory name.
	 * @param array  $requirements Requirements.
	 *
	 * @return mixed
	 */
	public function load_files( $dir, $requirements = array() ) {
		$files = scandir( forminator_plugin_dir() . $dir );
		foreach ( $files as $file ) {
			if (
				in_array( $file, array( 'paypal.php', 'stripe.php', 'stripe-payment-element.php' ), true )
				&& forminator_payments_disabled()
			) {
				continue;
			}
			$path = forminator_plugin_dir() . $dir . '/' . $file;

			if ( $this->is_php( $file ) && is_file( $path ) ) {

				// check requirement.
				if ( ! empty( $requirements ) ) {
					if ( in_array( $file, array_keys( $requirements ), true ) ) {
						if ( ! $this->is_requirement_fulfilled( $requirements[ $file ] ) ) {
							continue;
						}
					}
				}
				// Get class name.
				$class_name = str_replace( '.php', '', $file );
				// Include file.
				include_once $path;

				// Init class.
				$object = $this->init( $class_name );

				$this->files[] = $object;
			}
		}

		return $this->files;
	}

	/**
	 * Check if PHP file
	 *
	 * @since 1.0
	 * @param string $file Filename.
	 *
	 * @return bool
	 */
	public function is_php( $file ) {
		$check = substr( $file, - 4 );
		if ( '.php' === $check ) {
			return true;
		}

		return false;
	}

	/**
	 * Normalize class name
	 *
	 * @since 1.0
	 * @param string $name Name.
	 *
	 * @return mixed|string
	 */
	public function normalize( $name ) {
		$name = str_replace( '-', '_', $name );
		$name = ucwords( $name );

		return $name;
	}

	/**
	 * Init class
	 *
	 * @since 1.0
	 * @param string $name Name.
	 *
	 * @return mixed
	 */
	private function init( $name ) {
		$class = 'Forminator_' . $this->normalize( $name );

		if ( class_exists( $class ) ) {
			$object = new $class();

			return $object;
		}
	}

	/**
	 * Check if requirement fulfilled by system
	 *
	 * @since 1.7
	 *
	 * @param array $requirement Requirement.
	 *
	 * @return bool
	 */
	private function is_requirement_fulfilled( $requirement ) {
		// check php version.
		if ( isset( $requirement['php'] ) ) {
			$version = $requirement['php'];
			if ( version_compare( PHP_VERSION, $version, 'lt' ) ) {
				return false;
			}
		}

		return true;
	}
}