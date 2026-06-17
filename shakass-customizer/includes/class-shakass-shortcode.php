<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Shakass_Shortcode
{
    public function register(): void
    {
        add_shortcode('shakass_customizer', [$this, 'render']);
    }

    public function render(): string
    {
        Shakass_Assets::enqueue();

        ob_start();
        include SHAKASS_CUSTOMIZER_PATH . 'templates/configurator.php';
        return (string) ob_get_clean();
    }
}
