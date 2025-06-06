<?php
/**
 * Performance module.
 *
 * @package Hummingbird
 */

namespace Hummingbird\Core\Modules;

use Hummingbird\Core\Module;
use Hummingbird\Core\Settings;
use Hummingbird\Core\Traits\Module as ModuleContract;
use Hummingbird\Core\Utils;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Performance
 */
class Performance extends Module {

	use ModuleContract;

	/**
	 * Initializes the module. Always executed even if the module is deactivated.
	 *
	 * Do not use __construct in subclasses, use init() instead
	 */
	public function init() {
		add_action( 'wphb_init_performance_scan', array( $this, 'on_init_performance_scan' ) );
	}

	/**
	 * Implement abstract parent method for clearing cache.
	 *
	 * @since 1.7.1
	 */
	public function clear_cache() {
		Settings::delete( 'wphb-last-report' );
		Settings::delete( 'wphb-stop-report' );
		delete_transient( 'wphb-doing-report' );
	}

	/**
	 * Initializes the Performance Scan
	 *
	 * @since 1.7.1 Removed static property.
	 */
	public function init_scan() {
		// Clear the cache.
		$this->clear_cache();

		// Start the test.
		self::set_doing_report();
		Utils::get_api()->performance->ping();

		// Clear dismissed report.
		if ( self::report_dismissed() ) {
			self::dismiss_report( false );
		}

		do_action( 'wphb_init_performance_scan' );
	}

	/**
	 * Do a cron scan.
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public static function cron_scan() {
		// Start the test.
		self::set_doing_report();
		$api    = Utils::get_api();
		$report = $api->performance->check();
		// Stop the test.
		self::set_doing_report( false );

		// Return the results.
		return $report;
	}

	/**
	 * Return the last Performance scan done data
	 *
	 * @return bool|mixed|WP_Error Data of the last scan or false of there's not such data
	 */
	public static function get_last_report() {
		$report = Settings::get( 'wphb-last-report' );
		self::move_audits_without_score( $report );

		return $report ? $report : false;
	}

	/**
	 * Check if WP Hummingbird is currently doing a Performance Scan
	 *
	 * @return false|int Timestamp when the report started, false if there's no report being executed
	 */
	public static function is_doing_report() {
		return (bool) Settings::get( 'wphb-stop-report' ) ? false : get_transient( 'wphb-doing-report' );
	}

	/**
	 * Start a new Performance Scan
	 *
	 * It sets the new status for the report
	 *
	 * @param bool $status If set to true, it will start a new Performance Report, otherwise it will stop the current one.
	 */
	public static function set_doing_report( $status = true ) {
		if ( ! $status ) {
			delete_transient( 'wphb-doing-report' );
			Settings::update( 'wphb-stop-report', true, false );
			return;
		}

		// Set time when we started the report.
		set_transient( 'wphb-doing-report', time(), 300 ); // save for 5 minutes.
		Settings::delete( 'wphb-stop-report' );
	}

	/**
	 * Get the latest report from server
	 *
	 * @return void
	 */
	public static function refresh_report() {
		self::set_doing_report( false );
		self::dismiss_report( false );
		$results = Utils::get_api()->performance->results();

		$skip_db = false;

		if ( is_wp_error( $results ) ) {
			$error_message = $results->get_error_message();

			if ( 200 === $results->get_error_code() && 'Performance Results not found' === $error_message ) {
				$skip_db = true;
				$message = __( 'Whoops, looks like we were unable to grab your test results this time round. Please wait a few moments and try again...', 'wphb' );
			} else {
				$message = __( "The performance test didn't return any results. This could be because you're on a local website (which we can't access) or something went wrong trying to connect with the testing API. Give it another go and if the problem persists, please open a ticket with our support team.", 'wphb' );
			}

			// It's an actual error.
			$results = new WP_Error( 'performance-error', $message, array( 'details' => $error_message ) );
		}

		/**
		 * Fires after getting the latest report.
		 *
		 * @since 1.9.2
		 *
		 * @see https://developers.google.com/speed/docs/insights/v4/reference/pagespeedapi/runpagespeed
		 *
		 * @param object $report  Object with report data.
		 */
		if ( isset( $results->data ) && ! is_wp_error( $results->data ) ) {
			do_action( 'wphb_get_performance_report', $results->data );
		}

		if ( $skip_db ) {
			return;
		}

		// Only save reports from Performance module.
		Settings::update( 'wphb-last-report', $results, false );
	}

	/**
	 * Check if time enough has passed to make another test ( 5 minutes )
	 *
	 * @param bool|wp_error|array|object $last_report  Last report.
	 *
	 * @return bool|integer True if a new test is available or the time in minutes remaining for next test
	 */
	public static function can_run_test( $last_report = false ) {
		if ( ! $last_report || is_wp_error( $last_report ) ) {
			$last_report = self::get_last_report();
		}

		$current_gmt_time = time();
		if ( $last_report && ! is_wp_error( $last_report ) ) {
			$data_time = $last_report->data->time;
			if ( ( $data_time + 300 ) < $current_gmt_time ) {
				return true;
			} else {
				$remaining = ceil( ( ( $data_time + 300 ) - $current_gmt_time ) / 60 );
				return absint( $remaining );
			}
		}

		return true;
	}

	/**
	 * Set last report dismissed status to true
	 *
	 * @since 1.8
	 *
	 * @param bool $dismiss  Enable or disable dismissed report status.
	 *
	 * @return bool
	 */
	public static function dismiss_report( $dismiss ) {
		Settings::update_setting( 'dismissed', (bool) $dismiss, 'performance' );

		if ( (bool) $dismiss ) {
			// Ignore report in the Hub.
			$api     = Utils::get_api();
			$results = $api->performance->ignore();

			if ( is_wp_error( $results ) ) {
				return $results->get_error_message();
			}
		}

		return true;
	}

	/**
	 * Return whether the last report was dismissed
	 *
	 * @since 1.8
	 *
	 * @param bool|wp_error|array|object $last_report  Last report.

	 * @return bool True if user dismissed report or false of there's no site option
	 */
	public static function report_dismissed( $last_report = false ) {
		if ( Settings::get_setting( 'dismissed', 'performance' ) ) {
			return true;
		}

		if ( ! $last_report || is_wp_error( $last_report ) ) {
			$last_report = self::get_last_report();
		}

		if ( isset( $last_report->data->ignored ) && $last_report->data->ignored ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the class for a specific type. Used to select proper icons and styles.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $score  Score value.
	 * @param string $type   Type of item. Accepts: score, icon.
	 *
	 * @return string
	 */
	public static function get_impact_class( $score = 0, $type = 'score' ) {
		if ( ! in_array( $type, array( 'score', 'icon' ), true ) ) {
			return '';
		}

		// Make this method universal - either use 1 as a 100%, or 100 as 100%.
		if ( 0 < $score && 1 >= $score && is_float( $score ) ) {
			$score = $score * 100;
		}

		$impact_score_class = 'error';
		$impact_icon_class  = 'warning-alert';

		if ( 90 <= (int) $score ) {
			$impact_score_class = 'success';
			$impact_icon_class  = 'check-tick';
		} elseif ( 50 <= (int) $score ) {
			$impact_score_class = 'warning';
			$impact_icon_class  = 'warning-alert';
		}

		return 'score' === $type ? $impact_score_class : $impact_icon_class;
	}

	/**
	 * Get the lowest score from a list of audits and return the appropriate class.
	 *
	 * @since 2.0.0
	 *
	 * @param array $audits  Array with audits.
	 *
	 * @return string
	 */
	public static function get_audits_class( $audits ) {
		if ( is_null( $audits ) ) {
			return 'warning';
		}

		$lowest_score = 1;

		foreach ( $audits as $audit ) {
			if ( ! isset( $audit->score ) ) {
				continue;
			}

			if ( $lowest_score > $audit->score ) {
				$lowest_score = $audit->score;
			}
		}

		return self::get_impact_class( $lowest_score );
	}

	/**
	 * Triggered when a performance scan is initialized
	 */
	public function on_init_performance_scan() {
		if ( Utils::is_member() ) {
			return;
		}

		$options            = $this->get_options();
		$options['reports'] = false;

		$this->update_options( $options );
	}

	/**
	 * Return audits mapped to metrics that they affect.
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */
	public static function get_maps() {
		return array(
			'fcp' => array(
				'server-response-time',
				'render-blocking-resources',
				'redirects',
				'critical-request-chains',
				'uses-text-compression',
				'uses-rel-preconnect',
				'uses-rel-preload',
				'font-display',
				'unminified-javascript',
				'unminified-css',
				'unused-css-rules',
			),
			'lcp' => array(
				'server-response-time',
				'render-blocking-resources',
				'redirects',
				'critical-request-chains',
				'uses-text-compression',
				'uses-rel-preconnect',
				'uses-rel-preload',
				'font-display',
				'unminified-javascript',
				'unminified-css',
				'unused-css-rules',
				'largest-contentful-paint-element',
				'preload-lcp-image',
				'unused-javascript',
				'efficient-animated-content',
				'total-byte-weight',
			),
			'tbt' => array(
				'long-tasks',
				'third-party-summary',
				'third-party-facades',
				'bootup-time',
				'mainthread-work-breakdown',
				'dom-size',
				'duplicated-javascript',
				'legacy-javascript',
			),
			'cls' => array(
				'layout-shift-elements',
				'non-composited-animations',
				'unsized-images',
			),
		);
	}

	/**
	 * Return relevant metric IDs based on audit ID.
	 *
	 * @since 3.1.0
	 *
	 * @param string $audit  Audit ID.
	 *
	 * @return string  Metric IDs, separated with a space.
	 */
	public static function get_relevant_metrics( $audit ) {
		$metrics = array();

		foreach ( self::get_maps() as $metric => $audits ) {
			if ( ! in_array( $audit, $audits, true ) ) {
				continue;
			}

			$metrics[] = $metric;
		}

		return implode( ' ', $metrics );
	}

	/**
	 * Get audits for MP.
	 *
	 * @since 3.9.0
	 *
	 * @return array
	 */
	public static function get_audits_for_mp() {
		$audits = array(
			'opportunities' => array(
				'mobile'  => self::get_audits_from_performance_test( 'mobile', 'opportunities' ),
				'desktop' => self::get_audits_from_performance_test( 'desktop', 'opportunities' ),
			),
			'diagnostics'   => array(
				'mobile'  => self::get_audits_from_performance_test( 'mobile', 'diagnostics' ),
				'desktop' => self::get_audits_from_performance_test( 'desktop', 'diagnostics' ),
			),
			'passed'        => array(
				'mobile'  => self::get_audits_from_performance_test( 'mobile', 'passed' ),
				'desktop' => self::get_audits_from_performance_test( 'desktop', 'passed' ),
			),
		);

		return $audits;
	}

	/**
	 * Get audits from the performance test.
	 *
	 * @since 3.9.0
	 *
	 * @param string $device     Device type.
	 * @param string $audit_type Audit type.
	 *
	 * @return string
	 */
	public static function get_audits_from_performance_test( $device = 'desktop', $audit_type = 'opportunities' ) {
		$last_report = self::get_last_report();
		$get_audits  = isset( $last_report->data->$device->audits->$audit_type ) ? $last_report->data->$device->audits->$audit_type : array();

		$performance_audit = array();
		if ( ! empty( $get_audits ) ) {
			foreach ( $get_audits as $key => $value ) {
				$performance_audit[] = $key;
			}
		}

		return ! empty( $performance_audit ) ? $performance_audit : 'na';
	}

	/** Get performance report error message if any.
	 *
	 * @since 3.9.0
	 *
	 * @param object $report Performance report.
	 *
	 * @return bool
	 */
	public static function get_performance_report_error( $report ) {
		$error_details = '';
		// Is that a report with errors?
		if ( is_wp_error( $report ) ) {
			$error_details = $report->get_error_data();
		} elseif ( $report && is_null( $report->data->desktop->metrics ) ) {
			$error_details = "The performance test didn't return any results";
		}

		return $error_details;
	}

	/**
	 * Get LCP submetrics for MP.
	 *
	 * @since 3.9.0
	 *
	 * @return array
	 */
	public static function get_lcp_submetrics_for_mp() {
		$lcp_submetrics = array(
			'lcp_ttfb'         => array(
				'mobile'  => self::get_lcp_submetrics( 'mobile', 'TTFB' ),
				'desktop' => self::get_lcp_submetrics( 'desktop', 'TTFB' ),
			),
			'lcp_load_delay'   => array(
				'mobile'  => self::get_lcp_submetrics( 'mobile', 'Load Delay' ),
				'desktop' => self::get_lcp_submetrics( 'desktop', 'Load Delay' ),
			),
			'lcp_render_delay' => array(
				'mobile'  => self::get_lcp_submetrics( 'mobile', 'Render Delay' ),
				'desktop' => self::get_lcp_submetrics( 'desktop', 'Render Delay' ),
			),
			'lcp_load_time'    => array(
				'mobile'  => self::get_lcp_submetrics( 'mobile', 'Load Time' ),
				'desktop' => self::get_lcp_submetrics( 'desktop', 'Load Time' ),
			),
			'lcp_element'      => array(
				'mobile'  => self::get_lcp_submetrics( 'mobile', 'passed', true ),
				'desktop' => self::get_lcp_submetrics( 'desktop', 'passed', true ),
			),
		);

		return $lcp_submetrics;
	}

	/**
	 * Get the LCP submetrics.
	 *
	 * @since 3.9.0
	 *
	 * @param string $device      Device type.
	 * @param string $phase       Phase.
	 * @param bool   $lcp_element LCP element.
	 *
	 * @return string
	 */
	public static function get_lcp_submetrics( $device = 'desktop', $phase = 'TTFB', $lcp_element = false ) {
		$report      = self::get_last_report();
		$lcp         = 'largest-contentful-paint-element';
		$submetrics  = '';
		$lcp_snippet = '';

		if ( ! empty( $report->data->$device->audits->opportunities->$lcp->details->items[1]->items ) ) {
			$submetrics  = $report->data->$device->audits->opportunities->$lcp->details->items[1]->items;
			$lcp_snippet = $report->data->$device->audits->opportunities->$lcp->details->items[0]->items[0]->node;
		} elseif ( ! empty( $report->data->$device->audits->diagnostics->$lcp->details->items[1]->items ) ) {
			$submetrics  = $report->data->$device->audits->diagnostics->$lcp->details->items[1]->items;
			$lcp_snippet = $report->data->$device->audits->diagnostics->$lcp->details->items[0]->items[0]->node;
		} elseif ( ! empty( $report->data->$device->audits->passed->$lcp->details->items[1]->items ) ) {
			$submetrics  = $report->data->$device->audits->passed->$lcp->details->items[1]->items;
			$lcp_snippet = $report->data->$device->audits->passed->$lcp->details->items[0]->items[0]->node;
		}

		if ( true === $lcp_element ) {
			return ! empty( $lcp_snippet->nodeLabel ) && ! empty( $lcp_snippet->snippet ) ? $lcp_snippet->nodeLabel . $lcp_snippet->snippet : 'na';
		}

		if ( empty( $submetrics ) ) {
			return 'na';
		}

		foreach ( $submetrics as $submetric ) {
			if ( $phase === $submetric->phase ) {
				return $submetric->timing;
			}
		}

		return 'na';
	}

	/**
	 * Returns the core web vitals status.
	 *
	 * @since 3.10.0
	 *
	 * @param string $device Device type.
	 *
	 * @return bool
	 */
	public static function core_web_vitals_status( $device = 'desktop' ) {
		$report     = self::get_last_report();
		$field_data = isset( $report->data->{$device}->field_data ) ? $report->data->{$device}->field_data : null;

		if ( empty( $field_data ) ) {
			return 'na';
		}

		// Get the relevant metrics.
		$lcp = ! empty( $field_data->LARGEST_CONTENTFUL_PAINT_MS->percentile ) ? $field_data->LARGEST_CONTENTFUL_PAINT_MS->percentile : null;
		$inp = ! empty( $field_data->INTERACTION_TO_NEXT_PAINT->percentile ) ? $field_data->INTERACTION_TO_NEXT_PAINT->percentile : null;
		$cls = ! empty( $field_data->CUMULATIVE_LAYOUT_SHIFT_SCORE->percentile ) ? $field_data->CUMULATIVE_LAYOUT_SHIFT_SCORE->percentile / 100 : null;

		// Thresholds for passing Core Web Vitals.
		$lcp_pass = null === $lcp ? true : $lcp <= 2500;
		$inp_pass = null === $inp ? true : $inp <= 200;
		$cls_pass = null === $cls ? true : $cls <= 0.1;

		// Return true if all metrics pass, false otherwise.
		return $lcp_pass && $inp_pass && $cls_pass ? 'pass' : 'fail';
	}

	/**
	 * Move audits without a score to the `passed` object.
	 *
	 * @since 3.13.0
	 *
	 * @param object $psi_response PSI response.
	 */
	public static function move_audits_without_score( &$psi_response ) {
		if ( ! isset( $psi_response->data ) ) {
			return;
		}

		foreach ( array( 'desktop', 'mobile' ) as $platform ) {
			if ( ! isset( $psi_response->data->$platform->audits ) ) {
				continue;
			}

			$audits = $psi_response->data->$platform->audits;

			if ( ! isset( $audits->passed ) ) {
				$audits->passed = new \stdClass();
			}

			foreach ( array( 'diagnostics', 'opportunities' ) as $audit_type ) {
				if ( isset( $audits->$audit_type ) ) {
					foreach ( $audits->$audit_type as $key => $audit ) {
						if ( ! isset( $audit->score ) ) {
							$audits->passed->$key = $audit;
							unset( $audits->$audit_type->$key );
						}
					}
				}
			}
		}
	}
}