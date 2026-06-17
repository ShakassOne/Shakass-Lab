<?php

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-assets.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-shortcode.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-rest.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-admin.php';

final class Shakass_Customizer
{
    private static ?self $instance = null;

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function boot(): void
    {
        (new Shakass_Assets())->register();
        (new Shakass_Shortcode())->register();
        (new Shakass_Rest())->register();

        if (is_admin()) {
            (new Shakass_Admin())->register();
        }
    }
}
