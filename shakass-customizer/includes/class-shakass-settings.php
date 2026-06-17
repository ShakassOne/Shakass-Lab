<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Shakass_Settings
{
    public static function install_defaults(): void
    {
        add_option('shakass_products', shakass_default_products());
        add_option('shakass_mockups', shakass_default_mockups());
        add_option('shakass_pricing', shakass_default_pricing());
        add_option('shakass_settings', shakass_default_settings());
    }

    public static function sanitize_products(array $rows): array
    {
        $out = [];

        foreach ($rows as $row) {
            if (! is_array($row) || ! empty($row['_delete'])) {
                continue;
            }

            $name = sanitize_text_field((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $colors = [];
            foreach (preg_split('/\r\n|\r|\n/', (string) ($row['colors'] ?? '')) ?: [] as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }

                [$colorName, $hex] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '#000000');
                $colors[] = [
                    'name' => sanitize_text_field($colorName),
                    'hex'  => sanitize_hex_color($hex) ?: '#000000',
                ];
            }

            $rawSizes = $row['sizes'] ?? [];
            if (is_string($rawSizes)) {
                $rawSizes = preg_split('/[,;\r\n]+/', $rawSizes) ?: [];
            }
            $sizes = array_values(array_filter(array_map(
                static fn($size): string => sanitize_text_field((string) $size),
                (array) $rawSizes
            )));

            $slug = sanitize_title((string) ($row['slug'] ?? ''));
            $out[] = [
                'name'        => $name,
                'slug'        => $slug ?: sanitize_title($name),
                'active'      => empty($row['active']) ? 0 : 1,
                'type'        => sanitize_text_field((string) ($row['type'] ?? 'T-shirt')),
                'description' => sanitize_textarea_field((string) ($row['description'] ?? '')),
                'material'    => sanitize_text_field((string) ($row['material'] ?? '')),
                'weight'      => sanitize_text_field((string) ($row['weight'] ?? '')),
                'fit'         => sanitize_text_field((string) ($row['fit'] ?? 'Unisexe')),
                'sizes'       => $sizes ?: ['S', 'M', 'L', 'XL'],
                'colors'      => $colors ?: [['name' => 'Noir premium', 'hex' => '#08090d']],
                'default'     => empty($row['default']) ? 0 : 1,
            ];
        }

        if (! $out) {
            return shakass_default_products();
        }

        $hasDefault = false;
        foreach ($out as &$product) {
            if (! $hasDefault && ! empty($product['default'])) {
                $hasDefault = true;
                $product['default'] = 1;
                continue;
            }
            $product['default'] = 0;
        }
        unset($product);

        if (! $hasDefault) {
            $out[0]['default'] = 1;
        }

        return $out;
    }

    public static function sanitize_mockups(array $rows): array
    {
        $out = [];

        foreach ($rows as $row) {
            if (! is_array($row) || ! empty($row['_delete'])) {
                continue;
            }

            $name = sanitize_text_field((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $zone = static function (string $prefix) use ($row): array {
                return [
                    'x' => max(0, min(100, (float) ($row[$prefix . '_x'] ?? 25))),
                    'y' => max(0, min(100, (float) ($row[$prefix . '_y'] ?? 24))),
                    'w' => max(5, min(100, (float) ($row[$prefix . '_w'] ?? 50))),
                    'h' => max(5, min(100, (float) ($row[$prefix . '_h'] ?? 58))),
                ];
            };

            $out[] = [
                'product'     => sanitize_title((string) ($row['product'] ?? '')),
                'name'        => $name,
                'front_image' => esc_url_raw((string) ($row['front_image'] ?? '')),
                'back_image'  => esc_url_raw((string) ($row['back_image'] ?? '')),
                'color'       => sanitize_hex_color((string) ($row['color'] ?? '#08090d')) ?: '#08090d',
                'active'      => empty($row['active']) ? 0 : 1,
                'front_zone'  => $zone('front'),
                'back_zone'   => $zone('back'),
            ];
        }

        return $out ?: shakass_default_mockups();
    }

    public static function sanitize_pricing(array $data): array
    {
        $pricing = shakass_default_pricing();

        foreach ((array) ($data['base'] ?? []) as $key => $value) {
            $pricing['base'][sanitize_title((string) $key)] = max(0, (float) $value);
        }

        foreach (['A7', 'A6', 'A5', 'A4', 'A3'] as $format) {
            $pricing['formats'][$format] = max(0, (float) ($data['formats'][$format] ?? $pricing['formats'][$format]));
        }

        foreach (['text', 'image', 'qr'] as $key) {
            $pricing[$key] = max(0, (float) ($data[$key] ?? $pricing[$key]));
        }

        foreach (['1-9', '10-24', '25-49', '50+'] as $key) {
            $pricing['discounts'][$key] = max(0, min(100, (float) ($data['discounts'][$key] ?? $pricing['discounts'][$key])));
        }

        return $pricing;
    }

    public static function sanitize_settings(array $data): array
    {
        $tools = [];
        foreach (array_keys(shakass_active_tools()) as $tool) {
            $tools[$tool] = empty($data['tools'][$tool]) ? 0 : 1;
        }

        return [
            'email'           => sanitize_email((string) ($data['email'] ?? get_option('admin_email'))),
            'accent'          => sanitize_hex_color((string) ($data['accent'] ?? '#ff5a2c')) ?: '#ff5a2c',
            'dark_mode'       => empty($data['dark_mode']) ? 0 : 1,
            'cta'             => sanitize_text_field((string) ($data['cta'] ?? 'Envoyer ma demande')),
            'trust'           => sanitize_text_field((string) ($data['trust'] ?? '')),
            'default_product' => sanitize_title((string) ($data['default_product'] ?? '')),
            'tools'           => $tools,
        ];
    }
}
