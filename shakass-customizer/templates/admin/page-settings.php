<?php
if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap sc-admin">
    <div class="sc-admin-header">
        <div>
            <h1>Réglages</h1>
            <p>Paramètres globaux du configurateur front et de l’envoi des demandes.</p>
        </div>
    </div>

    <?php if (! empty($updated)) : ?>
        <div class="notice notice-success is-dismissible"><p>Réglages enregistrés.</p></div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="shakass_save_settings">
        <?php wp_nonce_field('shakass_save_settings'); ?>

        <section class="sc-card">
            <div class="sc-card-title">
                <div>
                    <span class="sc-kicker">Général</span>
                    <h2>Identité et demande</h2>
                </div>
            </div>

            <div class="sc-admin-grid">
                <label>Email destinataire
                    <input type="email" name="settings[email]" value="<?php echo esc_attr((string) $settings['email']); ?>">
                </label>
                <label>Texte du CTA
                    <input name="settings[cta]" value="<?php echo esc_attr((string) $settings['cta']); ?>">
                </label>
                <label>Message rassurant
                    <input name="settings[trust]" value="<?php echo esc_attr((string) $settings['trust']); ?>">
                </label>
                <label>Produit par défaut
                    <select name="settings[default_product]">
                        <?php foreach ($products as $product) : ?>
                            <option value="<?php echo esc_attr((string) $product['slug']); ?>" <?php selected((string) $settings['default_product'], (string) $product['slug']); ?>>
                                <?php echo esc_html((string) $product['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Couleur accent
                    <input type="color" name="settings[accent]" value="<?php echo esc_attr((string) $settings['accent']); ?>">
                </label>
                <label class="sc-inline-check">
                    <input type="checkbox" name="settings[dark_mode]" value="1" <?php checked(! empty($settings['dark_mode'])); ?>>
                    Interface dark premium
                </label>
            </div>
        </section>

        <section class="sc-card">
            <div class="sc-card-title">
                <div>
                    <span class="sc-kicker">Front</span>
                    <h2>Outils affichés</h2>
                </div>
            </div>
            <div class="sc-tool-grid">
                <?php foreach (shakass_active_tools() as $key => $label) : ?>
                    <label>
                        <input type="checkbox" name="settings[tools][<?php echo esc_attr($key); ?>]" value="1" <?php checked(! empty($settings['tools'][$key])); ?>>
                        <span><?php echo esc_html($label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </section>

        <p class="submit">
            <button class="button button-primary">Enregistrer les réglages</button>
        </p>
    </form>
</div>
