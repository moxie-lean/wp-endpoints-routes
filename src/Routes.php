<?php namespace Leean\Endpoints;

use Leean\AbstractEndpoint;

/**
 * Class to provide activation point for our endpoint.
 */
class Routes extends AbstractEndpoint {

	/**
	 * Endpoint path
	 *
	 * @Override
	 * @var String
	 */
	protected $endpoint = '/routes';

	/**
	 * Get the data.
	 *
	 * @Override
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return array|\WP_Error
	 */
	public function endpoint_callback( \WP_REST_Request $request ) {
		$date = [];

		return $this->filter_data( $data );
	}
}
