<?php

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Shakass_Admin
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
    }

    public function menu(): void
    {
        add_menu_page('Shakass Customizer', 'Shakass Customizer', 'manage_options', 'shakass-customizer', [$this, 'requestsPage'], 'dashicons-art', 58);
        add_submenu_page('shakass-customizer', 'Demandes', 'Demandes', 'manage_options', 'shakass-customizer', [$this, 'requestsPage']);
        add_submenu_page('shakass-customizer', 'Réglages', 'Réglages', 'manage_options', 'shakass-customizer-settings', [$this, 'settingsPage']);
    }

    public function requestsPage(): void
    {
        echo '<div class="wrap"><h1>Demandes Shakass Customizer</h1><p>La structure de réception des demandes est prête. Le stockage persistant sera ajouté après validation de l’interface.</p></div>';
    }

    public function settingsPage(): void
    {
        echo '<div class="wrap"><h1>Réglages Shakass Customizer</h1><p>Version ' . esc_html(SHAKASS_CUSTOMIZER_VERSION) . ' — shortcode : <code>[shakass_customizer]</code></p></div>';
    }
}
