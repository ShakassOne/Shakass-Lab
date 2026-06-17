<?php
declare(strict_types=1);
if (! defined('ABSPATH')) { exit; }
final class Shakass_Admin {
 public function register(): void { add_action('admin_menu', [$this,'menu']); add_action('admin_post_shakass_save_products', [$this,'saveProducts']); add_action('admin_post_shakass_save_mockups', [$this,'saveMockups']); add_action('admin_post_shakass_save_pricing', [$this,'savePricing']); add_action('admin_post_shakass_save_settings', [$this,'saveSettings']); add_action('admin_post_shakass_update_request', [$this,'updateRequest']); }
 public function menu(): void { add_menu_page('Shakass Customizer','Shakass Customizer','manage_options','shakass-customizer',[$this,'dashboard'],'dashicons-art',58); foreach ([['Produits','products'],['Mockups','mockups'],['Tarifs','pricing'],['Demandes','requests'],['Réglages','settings']] as $p) add_submenu_page('shakass-customizer',$p[0],$p[0],'manage_options','shakass-customizer-'.$p[1],[$this,$p[1]]); }
 private function view(string $file, array $vars=[]): void { if (!current_user_can('manage_options')) wp_die('Accès refusé.'); extract($vars); include SHAKASS_CUSTOMIZER_PATH.'templates/admin/'.$file.'.php'; }
 public function dashboard(): void { $this->view('page-dashboard',['products'=>get_option('shakass_products', shakass_default_products()),'requests'=>Shakass_Requests::count_all()]); }
 public function products(): void { $this->view('page-products',['products'=>get_option('shakass_products', shakass_default_products())]); }
 public function mockups(): void { $this->view('page-mockups',['mockups'=>get_option('shakass_mockups', shakass_default_mockups()),'products'=>get_option('shakass_products', shakass_default_products())]); }
 public function pricing(): void { $this->view('page-pricing',['pricing'=>get_option('shakass_pricing', shakass_default_pricing()),'products'=>get_option('shakass_products', shakass_default_products())]); }
 public function requests(): void { $id=absint($_GET['request_id'] ?? 0); $this->view('page-requests',['requests'=>Shakass_Requests::get_all(),'detail'=>$id ? get_post($id) : null]); }
 public function settings(): void { $this->view('page-settings',['settings'=>get_option('shakass_settings', shakass_default_settings()),'products'=>get_option('shakass_products', shakass_default_products())]); }
 public function saveProducts(): void { check_admin_referer('shakass_save_products'); update_option('shakass_products', Shakass_Settings::sanitize_products($_POST['products'] ?? [])); wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-products&updated=1')); exit; }
 public function saveMockups(): void { check_admin_referer('shakass_save_mockups'); update_option('shakass_mockups', Shakass_Settings::sanitize_mockups($_POST['mockups'] ?? [])); wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-mockups&updated=1')); exit; }
 public function savePricing(): void { check_admin_referer('shakass_save_pricing'); update_option('shakass_pricing', Shakass_Settings::sanitize_pricing($_POST['pricing'] ?? [])); wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-pricing&updated=1')); exit; }
 public function saveSettings(): void { check_admin_referer('shakass_save_settings'); update_option('shakass_settings', Shakass_Settings::sanitize_settings($_POST['settings'] ?? [])); wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-settings&updated=1')); exit; }
 public function updateRequest(): void { check_admin_referer('shakass_update_request'); $id=absint($_POST['request_id'] ?? 0); $status=sanitize_key($_POST['status'] ?? 'new'); if ($id && array_key_exists($status, shakass_request_statuses())) update_post_meta($id, '_shakass_status', $status); wp_safe_redirect(admin_url('admin.php?page=shakass-customizer-requests&request_id='.$id.'&updated=1')); exit; }
}
