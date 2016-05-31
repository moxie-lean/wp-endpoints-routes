<?php namespace Lean\Endpoints;

use Lean\AbstractEndpoint;

/**
 * Class to provide activation point for our endpoint.
 */
class Routes extends AbstractEndpoint {

	const FILTER_BLOG_PARAMS = 'ln_endpoints_route_blog_params';

	const FILTER_SINGLE_POST_ROUTE = 'ln_endpoints_route_single_post_route';

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

		$page_on_front = get_option( 'page_on_front' );

		$blog_page = get_option( 'page_for_posts' );

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

			if ( $page_on_front && $blog_page && (int) $blog_page === $page->ID ) {
				continue;
			}

			$url = str_replace( $site_url, '', get_permalink( $page ) );

			$data[] = [
				'state' => $page->post_name,
				'url' => '/' === $url ? $url : rtrim( $url, '/' ),
				'template' => get_post_meta( $page->ID, '_wp_page_template', true ),
				'endpoint' => get_rest_url( null, 'wp/v2/pages' ),
				'params' => [
					'include' => $page->ID,
				],
			];
		}

		wp_reset_postdata();

		return $this->filter_data( array_merge( $data, self::get_blog_routes() ) );
	}

	/**
	 * Create routes for the blog page and single posts if active..
	 *
	 * @return array
	 */
	private function get_blog_routes() {
		$data = [];

		$blog_url = false;

		$site_url = home_url();

		$page_on_front = get_option( 'page_on_front' );

		$blog_page = get_option( 'page_for_posts' );

		if ( ! $page_on_front ) {
			$blog_url = '/';
		} elseif ( $blog_page ) {
			$blog_url = rtrim( str_replace( $site_url, '', get_permalink( $blog_page ) ), '/' );
		}

		if ( $blog_url ) {
			$data[] = [
				'state' => 'blogIndex',
				'url' => $blog_url,
				'template' => 'blog',
				'endpoint' => get_rest_url( null, 'wp/v2/posts' ),
				'params' => apply_filters( self::FILTER_BLOG_PARAMS, [] ),
			];
		}

		// Create routes for single blog posts if active.
		$single_post_url = apply_filters( self::FILTER_SINGLE_POST_ROUTE, '/' === $blog_url ? '/blog' : $blog_url );

		if ( $single_post_url ) {
			$data[] = [
				'state' => 'blogPost',
				'url' => $single_post_url . '/:slug',
				'template' => 'blog-single',
				'endpoint' => get_rest_url( null, 'wp/v2/posts' ),
			];
		}

		return $data;
	}
}
