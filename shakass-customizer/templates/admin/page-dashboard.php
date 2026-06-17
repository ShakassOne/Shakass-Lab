<?php
if (! defined('ABSPATH')) {
    exit;
}

$active = count(array_filter($products, static fn(array $product): bool => ! empty($product['active'])));
$toolsEnabled = count(array_filter((array) ($settings['tools'] ?? [])));
$statuses = shakass_request_statuses();
?>
<div class="wrap sc-admin">
    <div class="sc-admin-header">
        <div>
            <h1>Shakass Customizer</h1>
            <p>Configurateur textile autonome via le shortcode <code>[shakass_customizer]</code>.</p>
        </div>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=shakass-customizer-settings')); ?>">Réglages</a>
    </div>

    <?php if (! empty($updated)) : ?>
        <div class="notice notice-success is-dismissible"><p>Modifications enregistrées.</p></div>
    <?php endif; ?>

    <div class="sc-admin-grid sc-dashboard-grid">
        <section class="sc-card">
            <span class="sc-kicker">Module</span>
            <strong>Actif</strong>
            <p>Version <?php echo esc_html(SHAKASS_CUSTOMIZER_VERSION); ?></p>
        </section>
        <section class="sc-card">
            <span class="sc-kicker">Demandes</span>
            <strong><?php echo esc_html((string) $requests_count); ?></strong>
            <p>Demandes reçues.</p>
        </section>
        <section class="sc-card">
            <span class="sc-kicker">Produits actifs</span>
            <strong><?php echo esc_html((string) $active); ?></strong>
            <p><?php echo esc_html((string) count($products)); ?> bases configurées.</p>
        </section>
        <section class="sc-card">
            <span class="sc-kicker">Outils front</span>
            <strong><?php echo esc_html((string) $toolsEnabled); ?></strong>
            <p>Outils activés dans le configurateur.</p>
        </section>
    </div>

    <div class="sc-admin-columns">
        <section class="sc-card">
            <h2>État de configuration</h2>
            <ul class="sc-check-list">
                <li><span>✓</span> Produits, couleurs et tailles stockés en options WordPress.</li>
                <li><span>✓</span> Mockups recto / verso et zones d’impression en pourcentage.</li>
                <li><span>✓</span> Endpoint REST sécurisé par nonce pour les demandes.</li>
                <li><span>✓</span> Shortcode autonome sans dépendance à un module e-commerce ou à un builder.</li>
            </ul>
        </section>

        <section class="sc-card">
            <h2>Dernières demandes</h2>
            <?php if (empty($recent_requests)) : ?>
                <p>Aucune demande reçue pour le moment.</p>
            <?php else : ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Produit</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_requests as $request) : ?>
                            <?php $status = get_post_meta($request->ID, '_shakass_status', true) ?: 'new'; ?>
                            <tr>
                                <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_name', true)); ?></td>
                                <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_product', true)); ?></td>
                                <td><?php echo esc_html($statuses[$status] ?? $statuses['new']); ?></td>
                                <td><a href="<?php echo esc_url(admin_url('admin.php?page=shakass-customizer-requests&request_id=' . $request->ID)); ?>">Voir</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</div>
