
<?php
defined( 'ABSPATH' ) || exit;

/**
 * Register public REST route that proxies search queries to Cloudflare AutoRAG.
 */
add_action( 'rest_api_init', function () {
    register_rest_route(
        'autorag/v1',
        '/search',
        [
            'methods'             => 'POST',
            'callback'            => 'autorag_chatbot_search',
            'permission_callback' => '__return_true',
        ]
    );
} );

/**
 * Callback: forward the visitor query to Cloudflare and relay the JSON response.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function autorag_chatbot_search( WP_REST_Request $request ) {

    $options    = get_option( 'autorag_chatbot_settings', [] );
    $account_id = $options['account_id'] ?? '';
    $token      = $options['api_token'] ?? '';
    $rag_slug   = $options['rag_slug'] ?? '';

    if ( ! $account_id || ! $token || ! $rag_slug ) {
        return new WP_REST_Response(
            [ 'error' => __( 'Plugin is not fully configured.', 'autorag-chatbot' ) ],
            500
        );
    }

    $query = sanitize_textarea_field( $request->get_param( 'query' ) );

    if ( '' === $query ) {
        return new WP_REST_Response(
            [ 'error' => __( 'Query cannot be empty.', 'autorag-chatbot' ) ],
            400
        );
    }

    $url = "https://api.cloudflare.com/client/v4/accounts/{$account_id}/autorag/rags/{$rag_slug}/ai-search";

    $response = wp_remote_post(
        $url,
        [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$token}",
            ],
            'body'    => wp_json_encode( [ 'query' => $query ] ),
            'timeout' => 20,
        ]
    );

    if ( is_wp_error( $response ) ) {
        return new WP_REST_Response(
            [ 'error' => $response->get_error_message() ],
            500
        );
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    $code = wp_remote_retrieve_response_code( $response );

    return new WP_REST_Response( $body, $code );
}
