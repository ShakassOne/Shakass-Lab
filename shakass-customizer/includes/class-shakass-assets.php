<?php

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Shakass_Assets
{
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
    }

    public function registerAssets(): void
    {
        wp_register_style(
            'shakass-customizer',
            SHAKASS_CUSTOMIZER_URL . 'assets/css/configurator.css',
            [],
            SHAKASS_CUSTOMIZER_VERSION
        );

        wp_register_script(
            'fabric',
            'https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js',
            [],
            '5.3.0',
            true
        );

        wp_register_script(
            'shakass-customizer',
            SHAKASS_CUSTOMIZER_URL . 'assets/js/configurator.js',
            ['fabric'],
            SHAKASS_CUSTOMIZER_VERSION,
            true
        );
    }

    public static function enqueue(): void
    {
        wp_enqueue_style('shakass-customizer');
        wp_enqueue_script('fabric');
        wp_enqueue_script('shakass-customizer');
        wp_localize_script('shakass-customizer', 'ShakassCustomizer', [
            'restUrl' => esc_url_raw(rest_url('shakass-customizer/v1/request')),
            'nonce' => wp_create_nonce('wp_rest'),
            'version' => SHAKASS_CUSTOMIZER_VERSION,
        ]);
    }
}
