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
        register_rest_route('shakass-customizer/v1', '/config', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => static fn(): WP_REST_Response => new WP_REST_Response(shakass_config_payload()),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('shakass-customizer/v1', '/request', [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'receiveRequest'],
            'permission_callback' => static fn(WP_REST_Request $request): bool => wp_verify_nonce((string) $request->get_header('X-WP-Nonce'), 'wp_rest') !== false,
        ]);
    }

    public function receiveRequest(WP_REST_Request $request): WP_REST_Response
    {
        $payload = $request->get_json_params();
        if (! is_array($payload)) {
            $payload = $request->get_params();
        }

        $email = sanitize_email((string) ($payload['email'] ?? ''));
        $name = sanitize_text_field((string) ($payload['name'] ?? ''));

        if ($name === '' || ! is_email($email)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Nom et email valides obligatoires.',
            ], 400);
        }

        $quantity = max(1, absint($payload['quantity'] ?? 1));
        $configuration = wp_json_encode($payload['configuration'] ?? [], JSON_UNESCAPED_UNICODE);

        $data = [
            'name'            => $name,
            'email'           => $email,
            'phone'           => sanitize_text_field((string) ($payload['phone'] ?? '')),
            'product'         => sanitize_text_field((string) ($payload['product'] ?? '')),
            'color'           => sanitize_text_field((string) ($payload['color'] ?? '')),
            'size'            => sanitize_text_field((string) ($payload['size'] ?? '')),
            'side'            => sanitize_text_field((string) ($payload['side'] ?? 'front')),
            'quantity'        => $quantity,
            'estimated_price' => max(0, (float) ($payload['estimated_price'] ?? 0)),
            'message'         => sanitize_textarea_field((string) ($payload['message'] ?? '')),
            'configuration'   => $configuration ?: '{}',
            'preview'         => $this->sanitizePreview((string) ($payload['preview'] ?? '')),
            'created_at'      => current_time('mysql'),
        ];

        $id = Shakass_Requests::create($data);
        if (is_wp_error($id)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Impossible de sauvegarder la demande.',
            ], 500);
        }

        $settings = shakass_get_settings();
        if (! empty($settings['email'])) {
            $url = admin_url('admin.php?page=shakass-customizer-requests&request_id=' . (int) $id);
            wp_mail(
                $settings['email'],
                'Nouvelle demande Shakass Customizer #' . (int) $id,
                "Une nouvelle demande est disponible dans l'administration.\n\nClient: {$name}\nEmail: {$email}\nProduit: {$data['product']}\nQuantité: {$quantity}\n\n{$url}"
            );
        }

        return new WP_REST_Response([
            'success'   => true,
            'message'   => 'Demande envoyée et sauvegardée.',
            'id'        => (int) $id,
            'reference' => 'SC-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT),
        ], 201);
    }

    private function sanitizePreview(string $preview): string
    {
        if (! str_starts_with($preview, 'data:image/png;base64,')) {
            return '';
        }

        return strlen($preview) <= 700000 ? $preview : '';
    }
}
