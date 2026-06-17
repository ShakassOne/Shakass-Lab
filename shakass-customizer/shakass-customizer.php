<?php
/**
 * Plugin Name: Shakass Customizer
 * Plugin URI: https://shakass-communication.fr/
 * Description: Configurateur textile premium pour Shakass Communication, disponible via le shortcode [shakass_customizer].
 * Version: 1.1.0
 * Requires PHP: 8.0
 * Author: Shakass Communication
 * Text Domain: shakass-customizer
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

define('SHAKASS_CUSTOMIZER_VERSION', '1.1.0');
define('SHAKASS_CUSTOMIZER_FILE', __FILE__);
define('SHAKASS_CUSTOMIZER_PATH', plugin_dir_path(__FILE__));
define('SHAKASS_CUSTOMIZER_URL', plugin_dir_url(__FILE__));

require_once SHAKASS_CUSTOMIZER_PATH . 'includes/class-shakass-customizer.php';

add_action('plugins_loaded', static function (): void {
    Shakass_Customizer::instance()->boot();
});
