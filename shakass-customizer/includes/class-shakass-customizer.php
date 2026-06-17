<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

require_once SHAKASS_CUSTOMIZER_PATH . 'includes/helpers.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-settings.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-requests.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-assets.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-shortcode.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-rest.php';
require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-admin.php';

final class Shakass_Customizer
{
    private static ?self $instance = null;

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public static function activate(): void
    {
        Shakass_Settings::install_defaults();
        Shakass_Requests::register_post_type();
        flush_rewrite_rules();
    }

    public function boot(): void
    {
        (new Shakass_Requests())->register();
        (new Shakass_Assets())->register();
        (new Shakass_Shortcode())->register();
        (new Shakass_Rest())->register();

        if (is_admin()) {
            (new Shakass_Admin())->register();
        }
    }
}
