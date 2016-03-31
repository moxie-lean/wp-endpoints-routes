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
		$data = [];

		// Create a route for each page.
		$pages = get_pages();

		foreach( $pages as $page ) {
			$data[] = [
				'state' => $page->post_name,
				'url' => str_replace( site_url(), '', get_permalink( $page ) ),
				'template' => get_post_meta( $page->ID, '_wp_page_template', true ),
				'endpoint' => 'post',
				'params' => [
					'id' => $page->ID,
				]
			];
		}

		return $this->filter_data( $data );
	}
}
