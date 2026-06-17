<?php
if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap sc-admin">
    <div class="sc-admin-header">
        <div>
            <h1>Tarifs</h1>
            <p>Définissez une estimation simple pour guider la demande client.</p>
        </div>
    </div>

    <?php if (! empty($updated)) : ?>
        <div class="notice notice-success is-dismissible"><p>Tarifs enregistrés.</p></div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="shakass_save_pricing">
        <?php wp_nonce_field('shakass_save_pricing'); ?>

        <section class="sc-card">
            <div class="sc-card-title">
                <div>
                    <span class="sc-kicker">Bases</span>
                    <h2>Prix de base par produit</h2>
                </div>
            </div>
            <div class="sc-admin-grid">
                <?php foreach ($products as $product) : ?>
                    <label><?php echo esc_html((string) $product['name']); ?>
                        <input type="number" min="0" step="0.01" name="pricing[base][<?php echo esc_attr((string) $product['slug']); ?>]" value="<?php echo esc_attr((string) ($pricing['base'][$product['slug']] ?? 0)); ?>">
                    </label>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="sc-card">
            <div class="sc-card-title">
                <div>
                    <span class="sc-kicker">Options</span>
                    <h2>Formats et éléments</h2>
                </div>
            </div>
            <div class="sc-admin-grid">
                <?php foreach (['A7', 'A6', 'A5', 'A4', 'A3'] as $format) : ?>
                    <label>Format <?php echo esc_html($format); ?>
                        <input type="number" min="0" step="0.01" name="pricing[formats][<?php echo esc_attr($format); ?>]" value="<?php echo esc_attr((string) ($pricing['formats'][$format] ?? 0)); ?>">
                    </label>
                <?php endforeach; ?>
                <label>Coût texte
                    <input name="pricing[text]" type="number" min="0" step="0.01" value="<?php echo esc_attr((string) ($pricing['text'] ?? 0)); ?>">
                </label>
                <label>Coût logo / image
                    <input name="pricing[image]" type="number" min="0" step="0.01" value="<?php echo esc_attr((string) ($pricing['image'] ?? 0)); ?>">
                </label>
                <label>Coût QR code
                    <input name="pricing[qr]" type="number" min="0" step="0.01" value="<?php echo esc_attr((string) ($pricing['qr'] ?? 0)); ?>">
                </label>
            </div>
        </section>

        <section class="sc-card">
            <div class="sc-card-title">
                <div>
                    <span class="sc-kicker">Quantités</span>
                    <h2>Remises quantité (%)</h2>
                </div>
            </div>
            <div class="sc-admin-grid">
                <?php foreach (['1-9', '10-24', '25-49', '50+'] as $range) : ?>
                    <label><?php echo esc_html($range); ?>
                        <input name="pricing[discounts][<?php echo esc_attr($range); ?>]" type="number" min="0" max="100" step="0.01" value="<?php echo esc_attr((string) ($pricing['discounts'][$range] ?? 0)); ?>">
                    </label>
                <?php endforeach; ?>
            </div>
        </section>

        <p class="submit">
            <button class="button button-primary">Enregistrer les tarifs</button>
        </p>
    </form>
</div>
