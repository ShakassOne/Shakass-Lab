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
        add_action('admin_post_shakass_save_products', [$this, 'saveProducts']);
        add_action('admin_post_shakass_save_mockups', [$this, 'saveMockups']);
        add_action('admin_post_shakass_save_pricing', [$this, 'savePricing']);
        add_action('admin_post_shakass_save_settings', [$this, 'saveSettings']);
        add_action('admin_post_shakass_update_request', [$this, 'updateRequest']);
    }

    public function menu(): void
    {
        add_menu_page(
            'Shakass Customizer',
            'Shakass Customizer',
            'manage_options',
            'shakass-customizer',
            [$this, 'dashboard'],
            'dashicons-art',
            58
        );

        add_submenu_page('shakass-customizer', 'Dashboard', 'Dashboard', 'manage_options', 'shakass-customizer', [$this, 'dashboard']);
        add_submenu_page('shakass-customizer', 'Produits', 'Produits', 'manage_options', 'shakass-customizer-products', [$this, 'products']);
        add_submenu_page('shakass-customizer', 'Mockups', 'Mockups', 'manage_options', 'shakass-customizer-mockups', [$this, 'mockups']);
        add_submenu_page('shakass-customizer', 'Tarifs', 'Tarifs', 'manage_options', 'shakass-customizer-pricing', [$this, 'pricing']);
        add_submenu_page('shakass-customizer', 'Demandes', 'Demandes', 'manage_options', 'shakass-customizer-requests', [$this, 'requests']);
        add_submenu_page('shakass-customizer', 'Réglages', 'Réglages', 'manage_options', 'shakass-customizer-settings', [$this, 'settings']);
    }

    private function view(string $file, array $vars = []): void
    {
        if (! current_user_can('manage_options')) {
            wp_die(esc_html__('Accès refusé.', 'shakass-customizer'));
        }

        $updated = ! empty($_GET['updated']);
        extract($vars, EXTR_SKIP);
        include SHAKASS_CUSTOMIZER_PATH . 'templates/admin/' . $file . '.php';
    }

    private function ensureCanManage(string $nonceAction): void
    {
        if (! current_user_can('manage_options')) {
            wp_die(esc_html__('Accès refusé.', 'shakass-customizer'));
        }

        check_admin_referer($nonceAction);
    }

    public function dashboard(): void
    {
        $products = shakass_get_products();
        $requests = Shakass_Requests::get_all(5);

        $this->view('page-dashboard', [
            'products'        => $products,
            'requests_count'  => Shakass_Requests::count_all(),
            'recent_requests' => $requests,
            'settings'        => shakass_get_settings(),
        ]);
    }

    public function products(): void
    {
        $this->view('page-products', ['products' => shakass_get_products()]);
    }

    public function mockups(): void
    {
        $this->view('page-mockups', [
            'mockups'  => shakass_get_mockups(),
            'products' => shakass_get_products(),
        ]);
    }

    public function pricing(): void
    {
        $this->view('page-pricing', [
            'pricing'  => shakass_get_pricing(),
            'products' => shakass_get_products(),
        ]);
    }

    public function requests(): void
    {
        $id = absint($_GET['request_id'] ?? 0);
        $detail = $id ? get_post($id) : null;
        if ($detail && $detail->post_type !== 'shakass_request') {
            $detail = null;
        }

        $this->view('page-requests', [
            'requests' => Shakass_Requests::get_all(100),
            'detail'   => $detail,
        ]);
    }

    public function settings(): void
    {
        $this->view('page-settings', [
            'settings' => shakass_get_settings(),
            'products' => shakass_get_products(),
        ]);
    }

    public function saveProducts(): void
    {
        $this->ensureCanManage('shakass_save_products');
        update_option('shakass_products', Shakass_Settings::sanitize_products((array) ($_POST['products'] ?? [])));
        wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-products&updated=1'));
        exit;
    }

    public function saveMockups(): void
    {
        $this->ensureCanManage('shakass_save_mockups');
        update_option('shakass_mockups', Shakass_Settings::sanitize_mockups((array) ($_POST['mockups'] ?? [])));
        wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-mockups&updated=1'));
        exit;
    }

    public function savePricing(): void
    {
        $this->ensureCanManage('shakass_save_pricing');
        update_option('shakass_pricing', Shakass_Settings::sanitize_pricing((array) ($_POST['pricing'] ?? [])));
        wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-pricing&updated=1'));
        exit;
    }

    public function saveSettings(): void
    {
        $this->ensureCanManage('shakass_save_settings');
        update_option('shakass_settings', Shakass_Settings::sanitize_settings((array) ($_POST['settings'] ?? [])));
        wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-settings&updated=1'));
        exit;
    }

    public function updateRequest(): void
    {
        $this->ensureCanManage('shakass_update_request');

        $id = absint($_POST['request_id'] ?? 0);
        $status = sanitize_key((string) ($_POST['status'] ?? 'new'));

        if ($id && array_key_exists($status, shakass_request_statuses())) {
            update_post_meta($id, '_shakass_status', $status);
        }

        wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-requests&request_id=' . $id . '&updated=1'));
        exit;
    }
}
