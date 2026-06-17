<?php
if (! defined('ABSPATH')) {
    exit;
}

$types = ['T-shirt', 'Sweat', 'Polo', 'Hoodie', 'Casquette', 'Autre'];
$fits = ['Unisexe', 'Homme', 'Femme'];

$render_product = static function (array $product, string|int $index) use ($types, $fits): void {
    $colors = [];
    foreach ((array) ($product['colors'] ?? []) as $color) {
        if (! is_array($color)) {
            continue;
        }
        $colors[] = ($color['name'] ?? 'Couleur') . ' | ' . ($color['hex'] ?? '#000000');
    }
    ?>
    <section class="sc-card sc-repeat-item" data-repeat-item>
        <input type="hidden" name="products[<?php echo esc_attr((string) $index); ?>][_delete]" value="0" data-delete-flag>
        <div class="sc-card-title">
            <div>
                <span class="sc-kicker">Produit</span>
                <h2><?php echo esc_html((string) ($product['name'] ?? 'Nouveau produit')); ?></h2>
            </div>
            <button type="button" class="button" data-remove-row>Supprimer</button>
        </div>

        <div class="sc-admin-grid">
            <label>Nom
                <input name="products[<?php echo esc_attr((string) $index); ?>][name]" value="<?php echo esc_attr((string) ($product['name'] ?? '')); ?>" required>
            </label>
            <label>Slug
                <input name="products[<?php echo esc_attr((string) $index); ?>][slug]" value="<?php echo esc_attr((string) ($product['slug'] ?? '')); ?>" placeholder="tshirt-noir">
            </label>
            <label>Type
                <select name="products[<?php echo esc_attr((string) $index); ?>][type]">
                    <?php foreach ($types as $type) : ?>
                        <option value="<?php echo esc_attr($type); ?>" <?php selected((string) ($product['type'] ?? 'T-shirt'), $type); ?>><?php echo esc_html($type); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Coupe
                <select name="products[<?php echo esc_attr((string) $index); ?>][fit]">
                    <?php foreach ($fits as $fit) : ?>
                        <option value="<?php echo esc_attr($fit); ?>" <?php selected((string) ($product['fit'] ?? 'Unisexe'), $fit); ?>><?php echo esc_html($fit); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Matière
                <input name="products[<?php echo esc_attr((string) $index); ?>][material]" value="<?php echo esc_attr((string) ($product['material'] ?? '')); ?>">
            </label>
            <label>Grammage
                <input name="products[<?php echo esc_attr((string) $index); ?>][weight]" value="<?php echo esc_attr((string) ($product['weight'] ?? '')); ?>">
            </label>
        </div>

        <label>Description
            <textarea name="products[<?php echo esc_attr((string) $index); ?>][description]"><?php echo esc_textarea((string) ($product['description'] ?? '')); ?></textarea>
        </label>

        <div class="sc-admin-grid">
            <label>Tailles
                <input name="products[<?php echo esc_attr((string) $index); ?>][sizes]" value="<?php echo esc_attr(implode(', ', (array) ($product['sizes'] ?? ['S', 'M', 'L', 'XL']))); ?>" placeholder="S, M, L, XL">
            </label>
            <label>Couleurs
                <textarea name="products[<?php echo esc_attr((string) $index); ?>][colors]" placeholder="Noir premium | #08090d"><?php echo esc_textarea(implode("\n", $colors)); ?></textarea>
            </label>
        </div>

        <div class="sc-switch-row">
            <label><input type="checkbox" name="products[<?php echo esc_attr((string) $index); ?>][active]" value="1" <?php checked(! empty($product['active'])); ?>> Actif sur le front</label>
            <label><input type="checkbox" name="products[<?php echo esc_attr((string) $index); ?>][default]" value="1" <?php checked(! empty($product['default'])); ?>> Produit par défaut</label>
        </div>
    </section>
    <?php
};

$blank = [
    'name' => '',
    'slug' => '',
    'active' => 1,
    'type' => 'T-shirt',
    'description' => '',
    'material' => '',
    'weight' => '',
    'fit' => 'Unisexe',
    'sizes' => ['S', 'M', 'L', 'XL'],
    'colors' => [['name' => 'Noir premium', 'hex' => '#08090d']],
    'default' => 0,
];
?>
<div class="wrap sc-admin">
    <div class="sc-admin-header">
        <div>
            <h1>Produits</h1>
            <p>Gérez les bases textiles, les tailles, les couleurs et le produit par défaut.</p>
        </div>
        <button type="button" class="button" data-add-row="products">Ajouter un produit</button>
    </div>

    <?php if (! empty($updated)) : ?>
        <div class="notice notice-success is-dismissible"><p>Produits enregistrés.</p></div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="shakass_save_products">
        <?php wp_nonce_field('shakass_save_products'); ?>

        <div data-repeat-list="products">
            <?php foreach ($products as $index => $product) : ?>
                <?php $render_product($product, $index); ?>
            <?php endforeach; ?>
        </div>

        <p class="submit">
            <button class="button button-primary">Enregistrer les produits</button>
        </p>
    </form>

    <template data-repeat-template="products">
        <?php $render_product($blank, '__INDEX__'); ?>
    </template>
</div>
