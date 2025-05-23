<?php
/**
 * Functions and template tags dedicated to attendees in Ticket Commerce.
 *
 * @since 5.1.9
 */

use TEC\Tickets\Commerce\Models\Attendee_Model;
use \TEC\Tickets\Commerce\Attendee;

/**
 * Fetches and returns a decorated post object representing an attendee.
 *
 * @since 5.1.9
 *
 * @param null|int|WP_Post $attendee               The attendee ID or post object or `null` to use the global one.
 * @param string|null      $output                 The required return type. One of `OBJECT`, `ARRAY_A`, or `ARRAY_N`, which
 *                                                 correspond to a WP_Post object, an associative array, or a numeric array,
 *                                                 respectively. Defaults to `OBJECT`.
 * @param string           $filter                 Type of filter to apply.
 * @param bool             $force                  Whether to force a re-fetch ignoring cached results or not.
 *
 * @return array|mixed|void|WP_Post|null    The Order post object or array, `null` if not found.
 */
function tec_tc_get_attendee( $attendee = null, $output = OBJECT, $filter = 'raw', $force = false ) {
	/**
	 * Filters the attendee result before any logic applies.
	 *
	 * Returning a non `null` value here will short-circuit the function and return the value.
	 * Note: this value will not be cached and the caching of this value is a duty left to the filtering function.
	 *
	 * @since 5.1.9
	 *
	 * @param mixed       $return      The attendee object to return.
	 * @param mixed       $attendee    The attendee object to fetch.
	 * @param string|null $output      The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which
	 *                                 correspond to a `WP_Post` object, an associative array, or a numeric array,
	 *                                 respectively. Defaults to `OBJECT`.
	 * @param string      $filter      Type of filter to apply.
	 */
	$return = apply_filters( 'tec_tickets_commerce_get_attendee_before', null, $attendee, $output, $filter );

	if ( null !== $return ) {
		return $return;
	}

	$post = false;

	/** @var Tribe__Cache $cache */
	$cache = tribe( 'cache' );

	$cache_post = get_post( $attendee );

	if ( empty( $cache_post ) || ! Attendee::is_valid( $cache_post ) ) {
		return null;
	}

	$key_fields = [
		$cache_post->ID,
		$cache_post->post_modified,
		// Use the `post_password` field as we show/hide some information depending on that.
		$cache_post->post_password,
		// We must include options on cache key, because options influence the hydrated data on the Order object.
		wp_json_encode( Tribe__Settings_Manager::get_options() ),
		wp_json_encode( [
			get_option( 'start_of_week' ),
			get_option( 'timezone_string' ),
			get_option( 'gmt_offset' )
		] ),
		$output,
		$filter,
	];

	$cache_key = 'tec_tc_get_attendee_' . md5( wp_json_encode( $key_fields ) );

	if ( ! $force ) {
		$post = $cache->get( $cache_key, Tribe__Cache_Listener::TRIGGER_SAVE_POST );
	}

	if ( false === $post ) {
		$post = Attendee_Model::from_post( $attendee )->to_post( OBJECT, $filter );

		if ( empty( $post ) ) {
			return null;
		}

		/**
		 * Filters the attendee post object before caching it and returning it.
		 *
		 * Note: this value will be cached; as such this filter might not run on each request.
		 * If you need to filter the output value on each call of this function then use the `tec_tickets_commerce_get_attendee_before`
		 * filter.
		 *
		 * @since 5.1.9
		 *
		 * @param WP_Post $post   The attendee post object, decorated with a set of custom properties.
		 * @param string  $output The output format to use.
		 * @param string  $filter The filter, or context of the fetch.
		 */
		$post = apply_filters( 'tec_tickets_commerce_get_attendee', $post, $output, $filter );

		// Dont try to reset cache when forcing.
		if ( ! $force ) {
			$cache->set( $cache_key, $post, WEEK_IN_SECONDS, Tribe__Cache_Listener::TRIGGER_SAVE_POST );
		}
	}

	/**
	 * Filters the attendee result after the attendee has been built from the function.
	 *
	 * Note: this value will not be cached and the caching of this value is a duty left to the filtering function.
	 *
	 * @since 5.1.9
	 *
	 * @param WP_Post     $post        The attendee post object to filter and return.
	 * @param int|WP_Post $attendee    The attendee object to fetch.
	 * @param string|null $output      The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which
	 *                                 correspond to a `WP_Post` object, an associative array, or a numeric array,
	 *                                 respectively. Defaults to `OBJECT`.
	 * @param string      $filter      Type of filter to apply.
	 */
	$post = apply_filters( 'tec_tickets_commerce_get_attendee_after', $post, $attendee, $output, $filter );

	$attendee_id       = is_object( $attendee ) ? $attendee->ID : $post->ID;
	$provider          = tribe_tickets_get_ticket_provider( $attendee_id );
	$post->provider    = $provider->class_name;
	$post->attendee_id = $attendee_id;

	if ( ! property_exists( $post, 'product_id' ) ) {
		$post->product_id = get_post_meta( $attendee_id, TEC\Tickets\Commerce\Module::ATTENDEE_PRODUCT_KEY, true );
	}

	if ( OBJECT !== $output ) {
		$post = ARRAY_A === $output ? (array) $post : array_values( (array) $post );
	}

	return $post;
}
