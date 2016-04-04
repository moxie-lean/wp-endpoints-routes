<?php namespace Lean\Endpoints;

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

		$site_url = home_url();

		// Create a route for each page.
		$pages_query = new \WP_Query([
			'post_type' => 'page',
			// @codingStandardsIgnoreStart
			// We need all pages, we really don't want to paginate this query.
			'posts_per_page' => -1,
			// codingStandardsIgnoreEnd
		]);

		while ( $pages_query->have_posts() ) {
			$pages_query->the_post();

			$page = $pages_query->post;

			$data[] = [
				'state' => $page->post_name,
				'url' => str_replace( $site_url, '', get_permalink( $page ) ),
				'template' => get_post_meta( $page->ID, '_wp_page_template', true ),
				'endpoint' => 'post',
				'params' => [
					'id' => $page->ID,
				],
			];
		}

		wp_reset_postdata();

		return $this->filter_data( $data );
	}
}
