<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function shakass_array_get(array $array, string $key, mixed $default = null): mixed
{
    return $array[$key] ?? $default;
}

function shakass_active_tools(): array
{
    return [
        'product' => 'Produit',
        'colors'  => 'Couleurs',
        'text'    => 'Texte',
        'upload'  => 'Image',
        'logo'    => 'Logo',
        'qr'      => 'QR Code',
        'layers'  => 'Calques',
        'request' => 'Demande',
    ];
}

function shakass_tool_icons(): array
{
    return [
        'product' => '▣',
        'colors'  => '●',
        'text'    => 'T',
        'upload'  => '↥',
        'logo'    => '◆',
        'qr'      => '▦',
        'layers'  => '☷',
        'request' => '✉',
    ];
}

function shakass_request_statuses(): array
{
    return [
        'new'        => 'Nouveau',
        'processing' => 'En cours',
        'done'       => 'Traité',
        'rejected'   => 'Refusé',
    ];
}

function shakass_default_products(): array
{
    return [
        [
            'name'        => 'T-shirt noir',
            'slug'        => 'tshirt-noir',
            'active'      => 1,
            'type'        => 'T-shirt',
            'description' => 'Base premium noire polyvalente pour marquage textile.',
            'material'    => 'Coton peigné',
            'weight'      => '180 g/m²',
            'fit'         => 'Unisexe',
            'sizes'       => ['S', 'M', 'L', 'XL'],
            'colors'      => [
                ['name' => 'Noir premium', 'hex' => '#08090d'],
                ['name' => 'Charbon', 'hex' => '#252933'],
                ['name' => 'Rouge Shakass', 'hex' => '#9b1717'],
            ],
            'default'     => 1,
        ],
        [
            'name'        => 'T-shirt blanc',
            'slug'        => 'tshirt-blanc',
            'active'      => 1,
            'type'        => 'T-shirt',
            'description' => 'Base claire pour visuels contrastés et couleurs fortes.',
            'material'    => 'Coton peigné',
            'weight'      => '180 g/m²',
            'fit'         => 'Unisexe',
            'sizes'       => ['S', 'M', 'L', 'XL'],
            'colors'      => [
                ['name' => 'Blanc optique', 'hex' => '#f7f4ee'],
                ['name' => 'Gris clair', 'hex' => '#d8dbe2'],
            ],
            'default'     => 0,
        ],
        [
            'name'        => 'Sweat noir',
            'slug'        => 'sweat-noir',
            'active'      => 1,
            'type'        => 'Sweat',
            'description' => 'Sweat confortable pour séries premium et équipes.',
            'material'    => 'Coton / polyester',
            'weight'      => '280 g/m²',
            'fit'         => 'Unisexe',
            'sizes'       => ['S', 'M', 'L', 'XL'],
            'colors'      => [
                ['name' => 'Noir premium', 'hex' => '#08090d'],
                ['name' => 'Gris carbone', 'hex' => '#747986'],
            ],
            'default'     => 0,
        ],
        [
            'name'        => 'Polo blanc',
            'slug'        => 'polo-blanc',
            'active'      => 1,
            'type'        => 'Polo',
            'description' => 'Polo blanc professionnel pour accueil, événementiel et staff.',
            'material'    => 'Piqué coton',
            'weight'      => '210 g/m²',
            'fit'         => 'Unisexe',
            'sizes'       => ['S', 'M', 'L', 'XL'],
            'colors'      => [
                ['name' => 'Blanc optique', 'hex' => '#f7f4ee'],
                ['name' => 'Bleu marine', 'hex' => '#111c35'],
            ],
            'default'     => 0,
        ],
    ];
}

function shakass_default_mockups(): array
{
    return [
        [
            'product'     => 'tshirt-noir',
            'name'        => 'T-shirt noir recto / verso',
            'front_image' => '',
            'back_image'  => '',
            'color'       => '#08090d',
            'active'      => 1,
            'front_zone'  => ['x' => 25, 'y' => 23, 'w' => 50, 'h' => 58],
            'back_zone'   => ['x' => 27, 'y' => 22, 'w' => 46, 'h' => 56],
        ],
        [
            'product'     => 'tshirt-blanc',
            'name'        => 'T-shirt blanc recto / verso',
            'front_image' => '',
            'back_image'  => '',
            'color'       => '#f7f4ee',
            'active'      => 1,
            'front_zone'  => ['x' => 25, 'y' => 23, 'w' => 50, 'h' => 58],
            'back_zone'   => ['x' => 27, 'y' => 22, 'w' => 46, 'h' => 56],
        ],
        [
            'product'     => 'sweat-noir',
            'name'        => 'Sweat noir recto / verso',
            'front_image' => '',
            'back_image'  => '',
            'color'       => '#08090d',
            'active'      => 1,
            'front_zone'  => ['x' => 26, 'y' => 25, 'w' => 48, 'h' => 54],
            'back_zone'   => ['x' => 28, 'y' => 23, 'w' => 44, 'h' => 54],
        ],
        [
            'product'     => 'polo-blanc',
            'name'        => 'Polo blanc recto / verso',
            'front_image' => '',
            'back_image'  => '',
            'color'       => '#f7f4ee',
            'active'      => 1,
            'front_zone'  => ['x' => 28, 'y' => 24, 'w' => 44, 'h' => 50],
            'back_zone'   => ['x' => 28, 'y' => 23, 'w' => 44, 'h' => 52],
        ],
    ];
}

function shakass_default_pricing(): array
{
    return [
        'base'      => [
            'tshirt-noir'  => 18,
            'tshirt-blanc' => 18,
            'sweat-noir'   => 34,
            'polo-blanc'   => 26,
        ],
        'formats'   => ['A7' => 3, 'A6' => 5, 'A5' => 8, 'A4' => 12, 'A3' => 18],
        'text'      => 4,
        'image'     => 9,
        'qr'        => 5,
        'discounts' => ['1-9' => 0, '10-24' => 5, '25-49' => 10, '50+' => 15],
    ];
}

function shakass_default_settings(): array
{
    return [
        'email'           => get_option('admin_email'),
        'accent'          => '#ff5a2c',
        'dark_mode'       => 1,
        'cta'             => 'Envoyer ma demande',
        'trust'           => 'Demande gratuite et sans engagement. Réponse rapide par Shakass Communication.',
        'default_product' => 'tshirt-noir',
        'tools'           => array_fill_keys(array_keys(shakass_active_tools()), 1),
    ];
}

function shakass_normalize_products(mixed $products): array
{
    $products = is_array($products) ? $products : [];
    $defaults = shakass_default_products();
    $normalized = [];

    foreach ($products as $index => $product) {
        if (! is_array($product)) {
            continue;
        }

        $fallback = $defaults[$index] ?? $defaults[0];
        $colors = [];
        foreach ((array) ($product['colors'] ?? $fallback['colors']) as $color) {
            if (! is_array($color)) {
                continue;
            }
            $hex = sanitize_hex_color((string) ($color['hex'] ?? ''));
            $colors[] = [
                'name' => sanitize_text_field((string) ($color['name'] ?? 'Couleur')),
                'hex'  => $hex ?: '#08090d',
            ];
        }

        $sizes = array_values(array_filter(array_map(
            static fn($size): string => sanitize_text_field((string) $size),
            (array) ($product['sizes'] ?? $fallback['sizes'])
        )));

        $name = sanitize_text_field((string) ($product['name'] ?? $fallback['name']));
        if ($name === '') {
            continue;
        }

        $slug = sanitize_title((string) ($product['slug'] ?? ''));

        $normalized[] = [
            'name'        => $name,
            'slug'        => $slug ?: sanitize_title($name),
            'active'      => empty($product['active']) ? 0 : 1,
            'type'        => sanitize_text_field((string) ($product['type'] ?? $fallback['type'])),
            'description' => sanitize_textarea_field((string) ($product['description'] ?? '')),
            'material'    => sanitize_text_field((string) ($product['material'] ?? '')),
            'weight'      => sanitize_text_field((string) ($product['weight'] ?? '')),
            'fit'         => sanitize_text_field((string) ($product['fit'] ?? 'Unisexe')),
            'sizes'       => $sizes ?: ['S', 'M', 'L', 'XL'],
            'colors'      => $colors ?: $fallback['colors'],
            'default'     => empty($product['default']) ? 0 : 1,
        ];
    }

    if (! $normalized) {
        $normalized = $defaults;
    }

    $hasDefault = false;
    foreach ($normalized as &$product) {
        if (! $hasDefault && ! empty($product['default'])) {
            $hasDefault = true;
            $product['default'] = 1;
            continue;
        }
        $product['default'] = 0;
    }
    unset($product);

    if (! $hasDefault) {
        $normalized[0]['default'] = 1;
    }

    return $normalized;
}

function shakass_normalize_mockups(mixed $mockups): array
{
    $mockups = is_array($mockups) ? $mockups : [];
    $defaults = shakass_default_mockups();
    $normalized = [];

    foreach ($mockups as $index => $mockup) {
        if (! is_array($mockup)) {
            continue;
        }

        $fallback = $defaults[$index] ?? $defaults[0];
        $zone = static function (array $source, array $fallbackZone): array {
            return [
                'x' => max(0, min(100, (float) ($source['x'] ?? $fallbackZone['x']))),
                'y' => max(0, min(100, (float) ($source['y'] ?? $fallbackZone['y']))),
                'w' => max(5, min(100, (float) ($source['w'] ?? $fallbackZone['w']))),
                'h' => max(5, min(100, (float) ($source['h'] ?? $fallbackZone['h']))),
            ];
        };

        $name = sanitize_text_field((string) ($mockup['name'] ?? $fallback['name']));
        if ($name === '') {
            continue;
        }

        $normalized[] = [
            'product'     => sanitize_title((string) ($mockup['product'] ?? $fallback['product'])),
            'name'        => $name,
            'front_image' => esc_url_raw((string) ($mockup['front_image'] ?? '')),
            'back_image'  => esc_url_raw((string) ($mockup['back_image'] ?? '')),
            'color'       => sanitize_hex_color((string) ($mockup['color'] ?? $fallback['color'])) ?: '#08090d',
            'active'      => empty($mockup['active']) ? 0 : 1,
            'front_zone'  => $zone((array) ($mockup['front_zone'] ?? []), $fallback['front_zone']),
            'back_zone'   => $zone((array) ($mockup['back_zone'] ?? []), $fallback['back_zone']),
        ];
    }

    return $normalized ?: $defaults;
}

function shakass_normalize_pricing(mixed $pricing): array
{
    $pricing = is_array($pricing) ? $pricing : [];
    $defaults = shakass_default_pricing();

    return [
        'base'      => array_replace($defaults['base'], array_map('floatval', (array) ($pricing['base'] ?? []))),
        'formats'   => array_replace($defaults['formats'], array_map('floatval', (array) ($pricing['formats'] ?? []))),
        'text'      => (float) ($pricing['text'] ?? $defaults['text']),
        'image'     => (float) ($pricing['image'] ?? $defaults['image']),
        'qr'        => (float) ($pricing['qr'] ?? $defaults['qr']),
        'discounts' => array_replace($defaults['discounts'], array_map('floatval', (array) ($pricing['discounts'] ?? []))),
    ];
}

function shakass_normalize_settings(mixed $settings): array
{
    $settings = is_array($settings) ? $settings : [];
    $defaults = shakass_default_settings();
    $tools = array_replace($defaults['tools'], array_intersect_key((array) ($settings['tools'] ?? []), $defaults['tools']));

    return [
        'email'           => sanitize_email((string) ($settings['email'] ?? $defaults['email'])),
        'accent'          => sanitize_hex_color((string) ($settings['accent'] ?? $defaults['accent'])) ?: $defaults['accent'],
        'dark_mode'       => empty($settings['dark_mode']) ? 0 : 1,
        'cta'             => sanitize_text_field((string) ($settings['cta'] ?? $defaults['cta'])),
        'trust'           => sanitize_text_field((string) ($settings['trust'] ?? $defaults['trust'])),
        'default_product' => sanitize_title((string) ($settings['default_product'] ?? $defaults['default_product'])),
        'tools'           => array_map(static fn($enabled): int => empty($enabled) ? 0 : 1, $tools),
    ];
}

function shakass_get_products(): array
{
    return shakass_normalize_products(get_option('shakass_products', shakass_default_products()));
}

function shakass_get_mockups(): array
{
    return shakass_normalize_mockups(get_option('shakass_mockups', shakass_default_mockups()));
}

function shakass_get_pricing(): array
{
    return shakass_normalize_pricing(get_option('shakass_pricing', shakass_default_pricing()));
}

function shakass_get_settings(): array
{
    return shakass_normalize_settings(get_option('shakass_settings', shakass_default_settings()));
}

function shakass_config_payload(): array
{
    $products = array_values(array_filter(shakass_get_products(), static fn(array $product): bool => ! empty($product['active'])));
    $mockups = array_values(array_filter(shakass_get_mockups(), static fn(array $mockup): bool => ! empty($mockup['active'])));

    if (! $products) {
        $products = shakass_default_products();
    }

    return [
        'products' => $products,
        'mockups'  => $mockups ?: shakass_default_mockups(),
        'pricing'  => shakass_get_pricing(),
        'settings' => shakass_get_settings(),
        'tools'    => shakass_active_tools(),
        'icons'    => shakass_tool_icons(),
    ];
}
