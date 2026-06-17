<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Shakass_Requests
{
    public function register(): void
    {
        add_action('init', [self::class, 'register_post_type']);
    }

    public static function register_post_type(): void
    {
        register_post_type('shakass_request', [
            'labels'          => [
                'name'          => 'Demandes Shakass',
                'singular_name' => 'Demande Shakass',
            ],
            'public'          => false,
            'show_ui'         => false,
            'show_in_rest'    => false,
            'supports'        => ['title'],
            'capability_type' => 'post',
        ]);
    }

    public static function create(array $data): int|WP_Error
    {
        $id = wp_insert_post([
            'post_type'   => 'shakass_request',
            'post_status' => 'private',
            'post_title'  => 'Demande - ' . sanitize_text_field((string) $data['name']) . ' - ' . current_time('mysql'),
        ], true);

        if (is_wp_error($id)) {
            return $id;
        }

        foreach ($data as $key => $value) {
            update_post_meta((int) $id, '_shakass_' . sanitize_key($key), $value);
        }
        update_post_meta((int) $id, '_shakass_status', 'new');

        return (int) $id;
    }

    public static function count_all(): int
    {
        $counts = wp_count_posts('shakass_request');
        return isset($counts->private) ? (int) $counts->private : 0;
    }

    /**
     * @return WP_Post[]
     */
    public static function get_all(int $limit = 100): array
    {
        return get_posts([
            'post_type'      => 'shakass_request',
            'post_status'    => 'private',
            'posts_per_page' => $limit,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
    }
}
