<?php

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Shakass_Rest
{
    public function register(): void
    {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void
    {
        register_rest_route('shakass-customizer/v1', '/request', [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'receiveRequest'],
            'permission_callback' => static function (WP_REST_Request $request): bool {
                return wp_verify_nonce((string) $request->get_header('X-WP-Nonce'), 'wp_rest') !== false;
            },
            'args' => [
                'name' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
                'email' => ['type' => 'string', 'sanitize_callback' => 'sanitize_email'],
                'phone' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
                'quantity' => ['type' => 'integer', 'sanitize_callback' => 'absint'],
                'message' => ['type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'],
                'configuration' => ['type' => 'object'],
            ],
        ]);
    }

    public function receiveRequest(WP_REST_Request $request): WP_REST_Response
    {
        $payload = $request->get_params();

        return new WP_REST_Response([
            'success' => true,
            'message' => __('Structure de demande reçue. Le stockage complet sera branché dans une prochaine version.', 'shakass-customizer'),
            'data' => [
                'reference' => 'SC-' . gmdate('Ymd-His'),
                'preview' => $payload,
            ],
        ], 202);
    }
}
