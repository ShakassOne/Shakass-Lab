<?php
if (! defined('ABSPATH')) {
    exit;
}

$firstProduct = $products[0]['slug'] ?? 'tshirt-noir';

$render_mockup = static function (array $mockup, string|int $index) use ($products): void {
    $frontZone = (array) ($mockup['front_zone'] ?? ['x' => 25, 'y' => 24, 'w' => 50, 'h' => 58]);
    $backZone = (array) ($mockup['back_zone'] ?? ['x' => 27, 'y' => 22, 'w' => 46, 'h' => 56]);
    ?>
    <section class="sc-card sc-repeat-item" data-repeat-item>
        <input type="hidden" name="mockups[<?php echo esc_attr((string) $index); ?>][_delete]" value="0" data-delete-flag>
        <div class="sc-card-title">
            <div>
                <span class="sc-kicker">Mockup</span>
                <h2><?php echo esc_html((string) ($mockup['name'] ?? 'Nouveau mockup')); ?></h2>
            </div>
            <button type="button" class="button" data-remove-row>Supprimer</button>
        </div>

        <div class="sc-admin-grid">
            <label>Produit
                <select name="mockups[<?php echo esc_attr((string) $index); ?>][product]">
                    <?php foreach ($products as $product) : ?>
                        <option value="<?php echo esc_attr((string) $product['slug']); ?>" <?php selected((string) ($mockup['product'] ?? ''), (string) $product['slug']); ?>>
                            <?php echo esc_html((string) $product['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Nom
                <input name="mockups[<?php echo esc_attr((string) $index); ?>][name]" value="<?php echo esc_attr((string) ($mockup['name'] ?? '')); ?>" required>
            </label>
            <label>Couleur textile
                <input type="color" name="mockups[<?php echo esc_attr((string) $index); ?>][color]" value="<?php echo esc_attr((string) ($mockup['color'] ?? '#08090d')); ?>">
            </label>
            <label class="sc-inline-check">
                <input type="checkbox" name="mockups[<?php echo esc_attr((string) $index); ?>][active]" value="1" <?php checked(! empty($mockup['active'])); ?>>
                Actif
            </label>
        </div>

        <div class="sc-admin-grid">
            <label>Image recto
                <span class="sc-media-field">
                    <input name="mockups[<?php echo esc_attr((string) $index); ?>][front_image]" value="<?php echo esc_attr((string) ($mockup['front_image'] ?? '')); ?>" data-media-input>
                    <button type="button" class="button" data-media-pick>Choisir</button>
                </span>
            </label>
            <label>Image verso
                <span class="sc-media-field">
                    <input name="mockups[<?php echo esc_attr((string) $index); ?>][back_image]" value="<?php echo esc_attr((string) ($mockup['back_image'] ?? '')); ?>" data-media-input>
                    <button type="button" class="button" data-media-pick>Choisir</button>
                </span>
            </label>
        </div>

        <div class="sc-admin-grid">
            <fieldset>
                <legend>Zone recto (%)</legend>
                <?php foreach (['x', 'y', 'w', 'h'] as $key) : ?>
                    <label><?php echo esc_html(strtoupper($key)); ?>
                        <input type="number" min="0" max="100" step="0.1" name="mockups[<?php echo esc_attr((string) $index); ?>][front_<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr((string) ($frontZone[$key] ?? '')); ?>">
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <fieldset>
                <legend>Zone verso (%)</legend>
                <?php foreach (['x', 'y', 'w', 'h'] as $key) : ?>
                    <label><?php echo esc_html(strtoupper($key)); ?>
                        <input type="number" min="0" max="100" step="0.1" name="mockups[<?php echo esc_attr((string) $index); ?>][back_<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr((string) ($backZone[$key] ?? '')); ?>">
                    </label>
                <?php endforeach; ?>
            </fieldset>
        </div>
    </section>
    <?php
};

$blank = [
    'product' => $firstProduct,
    'name' => '',
    'front_image' => '',
    'back_image' => '',
    'color' => '#08090d',
    'active' => 1,
    'front_zone' => ['x' => 25, 'y' => 24, 'w' => 50, 'h' => 58],
    'back_zone' => ['x' => 27, 'y' => 22, 'w' => 46, 'h' => 56],
];
?>
<div class="wrap sc-admin">
    <div class="sc-admin-header">
        <div>
            <h1>Mockups</h1>
            <p>Associez les visuels recto / verso et les zones d’impression en pourcentage.</p>
        </div>
        <button type="button" class="button" data-add-row="mockups">Ajouter un mockup</button>
    </div>

    <?php if (! empty($updated)) : ?>
        <div class="notice notice-success is-dismissible"><p>Mockups enregistrés.</p></div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="shakass_save_mockups">
        <?php wp_nonce_field('shakass_save_mockups'); ?>

        <div data-repeat-list="mockups">
            <?php foreach ($mockups as $index => $mockup) : ?>
                <?php $render_mockup($mockup, $index); ?>
            <?php endforeach; ?>
        </div>

        <p class="submit">
            <button class="button button-primary">Enregistrer les mockups</button>
        </p>
    </form>

    <template data-repeat-template="mockups">
        <?php $render_mockup($blank, '__INDEX__'); ?>
    </template>
</div>
