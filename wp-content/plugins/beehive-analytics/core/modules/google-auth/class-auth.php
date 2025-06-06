<?php
/**
 * The Google auth class.
 *
 * @link    http://wpmudev.com
 * @since   3.2.0
 *
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Modules\Google_Auth
 */

namespace Beehive\Core\Modules\Google_Auth;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

use Beehive\Google\Client;
use Beehive\Monolog\Logger;
use Beehive\Core\Helpers\Cache;
use Beehive\Core\Helpers\General;
use Beehive\GuzzleHttp\Middleware;
use Beehive\GuzzleHttp\HandlerStack;
use Beehive\Core\Helpers\Permission;
use Beehive\Core\Utils\Abstracts\Base;
use Beehive\GuzzleHttp\MessageFormatter;
use Beehive\Google\Service\PeopleService;
use Beehive\Monolog\Formatter\LineFormatter;
use Beehive\Psr\Http\Message\RequestInterface;
use Beehive\Monolog\Handler\RotatingFileHandler;

/**
 * Class Auth
 *
 * @package Beehive\Core\Modules\Google_Auth
 */
class Auth extends Base {

	/**
	 * Google client instance.
	 *
	 * @since 3.2.0
	 *
	 * @var Client
	 */
	private $client;

	/**
	 * Initialize all sub classes.
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function init() {
		// Init child class.
		Actions::instance()->init();

		// Views.
		Views\Admin::instance()->init();

		// Register endpoints.
		Endpoints\Auth::instance();
	}

	/**
	 * Getter method for Client instance.
	 *
	 * It will always return the existing client instance.
	 * If you need new instance set $new param as true.
	 *
	 * @param bool $new To get new instance.
	 *
	 * @since 3.2.0
	 *
	 * @return Client
	 */
	public function client( $new = false ) {
		// Make sure the autoloader is ready.
		General::vendor_autoload();

		// If requested for new instance.
		if ( $new || ! $this->client instanceof Client ) {
			// Set new instance.
			$this->client = new Client();
			// Check if the plugin is in dev mode.
			if ( defined( 'BEEHIVE_DEV_MODE' ) && BEEHIVE_DEV_MODE ) {
				$this->setup_dev_mode();
			}

			// Set our application name.
			$this->client->setApplicationName( General::plugin_name() );
		}

		return $this->client;
	}

	/**
	 * Setup the Google Client instance.
	 *
	 * Setup the access token, client id and client secret to
	 * the current Google Client instance.
	 * Setup the access type as `offline`.
	 * Setup the scope to Analytics Readonly mode.
	 *
	 * @param bool        $network Network flag.
	 * @param bool|string $client  Client ID.
	 * @param bool|string $secret  Client secret.
	 * @param bool|string $token   Access token.
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function setup( $network = false, $client = false, $secret = false, $token = false ) {
		$helper = Helper::instance();

		// Set client id.
		$helper->set_client( $client, $network );

		// Set client secret.
		$helper->set_secret( $secret, $network );

		// Set access token.
		$helper->set_token( $token, $network );

		// Offline access.
		$this->client()->setAccessType( 'offline' );

		// Always show consent screen.
		$this->client()->setPrompt( 'consent' );

		// Ask for Approval prompt.
		$this->client()->setApprovalPrompt( 'force' );

		// Incremental auth.
		$this->client()->setIncludeGrantedScopes( true );

		// Set redirect url.
		$helper->set_redirect_url();

		/**
		 * Filter hook to add or remove Google auth scopes.
		 *
		 * See all Google oauth scopes here: https://developers.google.com/identity/protocols/googlescopes
		 *
		 * @param array $scopes Default required scopes.
		 *
		 * @since 3.2.0
		 */
		$scopes = (array) apply_filters( 'beehive_google_auth_scopes', array() );

		// These are always required.
		$required_scopes = array(
			PeopleService::USERINFO_PROFILE,
			PeopleService::USERINFO_EMAIL,
		);

		// Merge all scopes.
		$scopes = array_unique( array_merge( $required_scopes, $scopes ) );

		// Set scopes for the Auth request.
		$this->client()->addScope( $scopes );

		/**
		 * Action hook to execute after setting up Google client.
		 *
		 * @param bool        $network Network flag.
		 * @param bool|string $client  Client ID.
		 * @param bool|string $secret  Client secret.
		 * @param bool|string $token   Access token.
		 *
		 * @since 3.2.0
		 */
		do_action( 'beehive_google_setup', $network, $client, $secret, $token );
	}

	/**
	 * Setup the Google Client instance using default API keys.
	 *
	 * This is not a recommended method. But if user would like to
	 * connect with Google without API keys, we could let them using
	 * the default hardcoded API keys.
	 * If client ID is specified, we will use that client ID and it's
	 * client secret pair.
	 *
	 * @param bool   $network   Network flag.
	 * @param string $client_id Client ID.
	 *
	 * @since 3.2.0
	 * @since 3.3.0 Added client ID param.
	 *
	 * @return void
	 */
	public function setup_default( $network = false, $client_id = '' ) {
		$credential = array(
			'client_id'     => '',
			'client_secret' => '',
		);

		// Get default credentials.
		if ( empty( $client_id ) ) {
			$credential = $this->get_default_credential( $network );
		} else {
			$default_creds = Data::instance()->credentials();
			// Check if the client id exist in default list.
			if ( isset( $default_creds[ $client_id ] ) ) {
				$credential = array(
					'client_id'     => $client_id,
					'client_secret' => $default_creds[ $client_id ]['secret'],
				);
			}
		}

		/**
		 * Filter to change default client ID.
		 *
		 * @param string $default_client_id Default client ID.
		 * @param bool   $network           Network flag.
		 *
		 * @since 3.0.0
		 */
		$default_client_id = apply_filters( 'beehive_google_default_client_id', $credential['client_id'], $network );

		/**
		 * Filter to change default client secret.
		 *
		 * @param string $default_client_secret Default client secret.
		 * @param bool   $network               Network flag.
		 *
		 * @since 3.0.0
		 */
		$default_client_secret = apply_filters( 'beehive_google_default_client_secret', $credential['client_secret'], $network );

		// Setup using default credentials.
		$this->setup( $network, $default_client_id, $default_client_secret );

		/**
		 * Filter hook to change default redirect url.
		 *
		 * This url is the Google Access code prompt screen url.
		 *
		 * @since 3.2.0
		 */
		$url = apply_filters( 'beehive_google_default_redirect_url', General::get_api_url( 'v1/intermediate-auth/google' ) );

		// Override redirect url.
		Helper::instance()->set_redirect_url( $url );

		// Set new access token if existing one is expired.
		if ( $this->client->isAccessTokenExpired() ) {
			if ( empty( $this->client->getRefreshToken() ) ) {
				// Logout so users will have to login again.
				$this->logout();
			} else {
				// Fetch new access token using refresh token.
				$token = $this->client->fetchAccessTokenWithRefreshToken( $this->client->getRefreshToken() );
				// If a valid token found.
				if ( isset( $token['access_token'], $token['refresh_token'] ) ) {
					// We don't need scope. It may get blocked by WAFs.
					if ( isset( $token['scope'] ) ) {
						unset( $token['scope'] );
					}

					// Update it so Google will not make an additional HTTP request every time to get new token.
					beehive_analytics()->settings->update( 'access_token', wp_json_encode( $token ), 'google_login' );
				}
			}
		}

		/**
		 * Action hook to execute after setting up Google client using default credentials.
		 *
		 * @param bool $network Network flag.
		 *
		 * @since 3.2.0
		 */
		do_action( 'beehive_google_setup_default', $network );
	}

	/**
	 * Get the default API credentials to load balance.
	 *
	 * We use multiple API keys to load balance the request
	 * limit set by Google. If user is already logged in, get
	 * the keys from the db. Otherwise get a random pair.
	 * Before 3.3.0, we had only one API key pair. So it will take
	 * some time to eventually
	 *
	 * @param bool $network Network flag.
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */
	public function get_default_credential( $network = false ) {
		// Check if already logged in.
		$loggedin = Helper::instance()->is_logged_in( $network );

		if ( $loggedin ) {
			// Get the pair from the db.
			$credentials = array(
				'client_id'     => beehive_analytics()->settings->get( 'client_id', 'google_login', $network, '' ),
				'client_secret' => beehive_analytics()->settings->get( 'client_secret', 'google_login', $network, '' ),
			);

			// Backward compatibility for 3.2.6 and below.
			if (
				/**
				 * Filter to allow/disallow fallback to old default credentials.
				 *
				 * @since 3.4.1
				 *
				 * @param bool $fallback Should fallback (default true).
				 */
				apply_filters( 'beehive_google_auth_fallback_old_credentials', true )
				// If no credentials found after login.
				&& ( empty( $credentials['client_id'] ) || empty( $credentials['client_secret'] ) )
			) {
				$credentials = array(
					'client_id'     => '640050123521-r5bp4142nh6dkh8bn0e6sn3pv852v3fm.apps.googleusercontent.com',
					'client_secret' => 'wWEelqN4DvE2DJjUPp-4KSka',
				);
			}
		}

		// Get random credentials if not set.
		if ( empty( $credentials['client_id'] ) || empty( $credentials['client_secret'] ) ) {
			$credentials = $this->get_random_creds();
		}

		/**
		 * Filter to modify default credentials before processing.
		 *
		 * @since 3.2.7
		 *
		 * @param array $credentials Client ID and Client secret.
		 */
		return apply_filters( 'beehive_google_auth_get_default_credential', $credentials );
	}

	/**
	 * Logout current Google authentication code.
	 *
	 * Logging out will not remove API credentials.
	 *
	 * @param bool $network Network flag.
	 *
	 * @since 3.2.0
	 *
	 * @return bool
	 */
	public function logout( $network = false ) {
		// Make sure the current user has permission.
		if ( ! Permission::user_can( 'settings', $network ) ) {
			return false;
		}

		// Remove Google login data.
		$logout = beehive_analytics()->settings->update_group( array(), 'google_login', $network );

		// Remove the account id and tracking id.
		if ( $logout ) {
			beehive_analytics()->settings->update( 'stream', '', 'google', $network );
			beehive_analytics()->settings->update( 'api_error', false, 'google', $network );
			beehive_analytics()->settings->update( 'account_id', '', 'google', $network );
			beehive_analytics()->settings->update( 'auto_track_ga4', '', 'misc', $network );
		}

		Cache::delete_transient( 'google_streams_v3400', $network );

		// Refresh caches.
		Cache::refresh_transient( $network );
		Cache::refresh_cache();

		/**
		 * Hook to execute after logout.
		 *
		 * @param bool $success Is success or fail?.
		 *
		 * @since 3.2.0
		 */
		do_action( 'beehive_google_auth_logout', $logout );

		return $logout;
	}

	/**
	 * Get a random credential pair for authentication.
	 *
	 * @since 3.2.7
	 *
	 * @return array
	 */
	private function get_random_creds() {
		// Get the available pair of default keys.
		$default_creds = Data::instance()->credentials();

		// Take only client IDs.
		$client_ids = array_keys( $default_creds );

		$keys = array();

		// Prepare for the random selection.
		foreach ( $client_ids as $key => $client_id ) {
			if ( isset( $default_creds[ $client_id ]['weight'] ) ) {
				for ( $i = 0; $i < $default_creds[ $client_id ]['weight']; $i++ ) {
					$keys[] = $key;
				}
			}
		}

		// Get random client ID.
		$random_client = $client_ids[ $keys[ wp_rand( 0, count( $keys ) - 1 ) ] ];

		// Set the client id and secret of random pair.
		$credentials = array(
			'client_id'     => $random_client,
			'client_secret' => $default_creds[ $random_client ]['secret'],
		);

		/**
		 * Filter to modify random credentials before processing.
		 *
		 * @param array $credentials Client ID and Client secret.
		 *
		 * @since 3.2.7
		 */
		return apply_filters( 'beehive_google_auth_get_random_creds', $credentials );
	}

	/**
	 * Sets up the development mode for the application.
	 *
	 * This method configures the HTTP client with optional features such as:
	 * - Proxy settings.
	 * - Mock API server middleware for testing.
	 * - API call logging middleware for debugging.
	 *
	 * Development mode configurations:
	 * - Proxy settings: Defined via the `BEEHIVE_PROXY_URL` constant.
	 * - Mock server: Defined via the `BEEHIVE_MOCK_HOST` constant.
	 * - API logging: Enabled via the `BEEHIVE_DEBUG_API` constant. Logs are stored in
	 *   the `wp-content/uploads/beehive/api-calls.log` file.
	 *
	 * Middleware:
	 * - Modifies the request URI for mock server testing.
	 * - Logs HTTP requests with a timestamp and request details.
	 *
	 * @return void
	 */
	public function setup_dev_mode(): void {
		$config = array();

		if ( defined( 'BEEHIVE_PROXY_URL' ) && BEEHIVE_PROXY_URL ) {
			$config['proxy']  = BEEHIVE_PROXY_URL;
			$config['verify'] = false;
		}

		$handler_stack = HandlerStack::create();

		// Setup mock API server middleware if defined.
		if ( defined( 'BEEHIVE_MOCK_HOST' ) && BEEHIVE_MOCK_HOST ) {
			$mock_base_uri = Middleware::mapRequest(
				function ( RequestInterface $request ) {
					return $request->withUri(
						$request->getUri()
						->withHost( wp_parse_url( BEEHIVE_MOCK_HOST, PHP_URL_HOST ) )
						->withPort( wp_parse_url( BEEHIVE_MOCK_HOST, PHP_URL_PORT ) )
						->withScheme( wp_parse_url( BEEHIVE_MOCK_HOST, PHP_URL_SCHEME ) )
					);
				}
			);
			$handler_stack->push( $mock_base_uri );
		}

		// Setup API call logging middleware if debugging is enabled.
		if ( defined( 'BEEHIVE_DEBUG_API' ) && BEEHIVE_DEBUG_API ) {
			$stream = new RotatingFileHandler( WP_CONTENT_DIR . '/uploads/beehive/api-calls.log' );
			$stream->setFormatter( new LineFormatter( '%message%' . PHP_EOL, 'd-m-Y h:i' ) );

			$logger = new Logger( 'api-logger' );
			$logger->pushHandler( $stream );

			$request_logger = Middleware::log( $logger, new MessageFormatter( '{method} {target}' ) );
			$handler_stack->push( $request_logger );
		}

		$config['handler'] = $handler_stack;

		// Configure and set the HTTP client.
		$http_client = new \Beehive\GuzzleHttp\Client( $config );
		$this->client->setHttpClient( $http_client );
	}
}