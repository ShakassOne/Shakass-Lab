<?php
if (! defined('ABSPATH')) {
    exit;
}

$statuses = shakass_request_statuses();
?>
<div class="wrap sc-admin">
    <div class="sc-admin-header">
        <div>
            <h1>Demandes</h1>
            <p>Suivez les configurations envoyées depuis le shortcode.</p>
        </div>
    </div>

    <?php if (! empty($updated)) : ?>
        <div class="notice notice-success is-dismissible"><p>Demande mise à jour.</p></div>
    <?php endif; ?>

    <?php if ($detail) : ?>
        <?php
        $currentStatus = get_post_meta($detail->ID, '_shakass_status', true) ?: 'new';
        $preview = (string) get_post_meta($detail->ID, '_shakass_preview', true);
        $fields = [
            'name'            => 'Nom',
            'email'           => 'Email',
            'phone'           => 'Téléphone',
            'product'         => 'Produit',
            'color'           => 'Couleur',
            'size'            => 'Taille',
            'side'            => 'Face active',
            'quantity'        => 'Quantité',
            'estimated_price' => 'Prix estimatif HT',
            'message'         => 'Message',
            'created_at'      => 'Créée le',
        ];
        ?>
        <section class="sc-card sc-request-detail">
            <div class="sc-card-title">
                <div>
                    <span class="sc-kicker">Détail</span>
                    <h2>Demande #<?php echo esc_html((string) $detail->ID); ?></h2>
                </div>
                <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=shakass-customizer-requests')); ?>">Retour à la liste</a>
            </div>

            <div class="sc-admin-columns">
                <div>
                    <dl class="sc-detail-list">
                        <?php foreach ($fields as $key => $label) : ?>
                            <?php $value = get_post_meta($detail->ID, '_shakass_' . $key, true); ?>
                            <dt><?php echo esc_html($label); ?></dt>
                            <dd><?php echo esc_html((string) $value); ?><?php echo $key === 'estimated_price' && $value !== '' ? ' €' : ''; ?></dd>
                        <?php endforeach; ?>
                    </dl>

                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="sc-status-form">
                        <input type="hidden" name="action" value="shakass_update_request">
                        <input type="hidden" name="request_id" value="<?php echo esc_attr((string) $detail->ID); ?>">
                        <?php wp_nonce_field('shakass_update_request'); ?>
                        <label>Statut
                            <select name="status">
                                <?php foreach ($statuses as $key => $label) : ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php selected($currentStatus, $key); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <button class="button button-primary">Mettre à jour</button>
                    </form>
                </div>

                <div>
                    <?php if ($preview !== '') : ?>
                        <figure class="sc-preview">
                            <img src="<?php echo esc_attr($preview); ?>" alt="Aperçu de la demande">
                        </figure>
                    <?php endif; ?>
                    <label>Configuration JSON
                        <textarea readonly><?php echo esc_textarea((string) get_post_meta($detail->ID, '_shakass_configuration', true)); ?></textarea>
                    </label>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="sc-card">
        <div class="sc-card-title">
            <div>
                <span class="sc-kicker">Suivi</span>
                <h2>Liste des demandes</h2>
            </div>
        </div>

        <?php if (empty($requests)) : ?>
            <p>Aucune demande reçue pour le moment.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix estimatif</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request) : ?>
                        <?php $status = get_post_meta($request->ID, '_shakass_status', true) ?: 'new'; ?>
                        <tr>
                            <td><?php echo esc_html(get_the_date('', $request)); ?></td>
                            <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_name', true)); ?></td>
                            <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_email', true)); ?></td>
                            <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_product', true)); ?></td>
                            <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_quantity', true)); ?></td>
                            <td><?php echo esc_html((string) get_post_meta($request->ID, '_shakass_estimated_price', true)); ?> €</td>
                            <td><span class="sc-status sc-status-<?php echo esc_attr($status); ?>"><?php echo esc_html($statuses[$status] ?? $statuses['new']); ?></span></td>
                            <td><a class="button" href="<?php echo esc_url(admin_url('admin.php?page=shakass-customizer-requests&request_id=' . $request->ID)); ?>">Voir détail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
