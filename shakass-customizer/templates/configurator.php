<?php
if (! defined('ABSPATH')) {
    exit;
}

$cfg = shakass_config_payload();
$settings = $cfg['settings'];
$tools = shakass_active_tools();
$icons = shakass_tool_icons();
?>
<div
    class="sc-app"
    data-shakass-customizer
    style="--sc-accent: <?php echo esc_attr($settings['accent']); ?>;"
>
    <header class="sc-topbar" aria-label="<?php echo esc_attr__('Configurateur textile Shakass Communication', 'shakass-customizer'); ?>">
        <div class="sc-brand" aria-label="Shakass Communication">
            <span class="sc-brand-mark">SC</span>
            <span class="sc-brand-copy">
                <strong>Shakass</strong>
                <small>Communication</small>
            </span>
        </div>

        <div class="sc-project-line">
            <span data-project-title>Projet textile</span>
            <strong data-summary-compact>Configuration en cours</strong>
        </div>

        <div class="sc-actions" aria-label="<?php echo esc_attr__('Actions du configurateur', 'shakass-customizer'); ?>">
            <button type="button" class="sc-icon-btn" data-action="undo" title="<?php echo esc_attr__('Annuler', 'shakass-customizer'); ?>" aria-label="<?php echo esc_attr__('Annuler', 'shakass-customizer'); ?>">↶</button>
            <button type="button" class="sc-icon-btn" data-action="redo" title="<?php echo esc_attr__('Rétablir', 'shakass-customizer'); ?>" aria-label="<?php echo esc_attr__('Rétablir', 'shakass-customizer'); ?>">↷</button>
            <button type="button" class="sc-icon-btn" data-action="save" title="<?php echo esc_attr__('Enregistrer localement', 'shakass-customizer'); ?>" aria-label="<?php echo esc_attr__('Enregistrer localement', 'shakass-customizer'); ?>">▣</button>
            <button type="button" class="sc-icon-btn" data-action="reset" title="<?php echo esc_attr__('Réinitialiser', 'shakass-customizer'); ?>" aria-label="<?php echo esc_attr__('Réinitialiser', 'shakass-customizer'); ?>">↺</button>
        </div>
    </header>

    <main class="sc-workspace">
        <aside class="sc-toolbar" aria-label="<?php echo esc_attr__('Outils', 'shakass-customizer'); ?>">
            <?php foreach ($tools as $key => $label) : ?>
                <?php if (empty($settings['tools'][$key])) { continue; } ?>
                <button type="button" class="<?php echo $key === 'product' ? 'is-active' : ''; ?>" data-tool="<?php echo esc_attr($key); ?>" title="<?php echo esc_attr($label); ?>">
                    <span aria-hidden="true"><?php echo esc_html($icons[$key] ?? '•'); ?></span>
                    <em><?php echo esc_html($label); ?></em>
                </button>
            <?php endforeach; ?>
        </aside>

        <section class="sc-context-panel" data-context-panel aria-label="<?php echo esc_attr__('Options', 'shakass-customizer'); ?>">
            <button type="button" class="sc-sheet-close" data-close-panel aria-label="<?php echo esc_attr__('Fermer les options', 'shakass-customizer'); ?>">×</button>

            <div class="sc-panel is-active" data-panel="product">
                <div class="sc-panel-head">
                    <span>01</span>
                    <h2>Produit</h2>
                </div>
                <label>Base textile
                    <select data-product-select></select>
                </label>
                <div class="sc-control-grid">
                    <label>Taille
                        <select data-size-select></select>
                    </label>
                    <label>Quantité
                        <input type="number" min="1" step="1" value="25" data-quantity>
                    </label>
                </div>
                <div class="sc-info-card" data-product-description></div>
            </div>

            <div class="sc-panel" data-panel="colors">
                <div class="sc-panel-head">
                    <span>02</span>
                    <h2>Couleurs</h2>
                </div>
                <p class="sc-muted">Couleur sélectionnée : <strong data-color-name></strong></p>
                <div class="sc-swatches" data-swatches></div>
            </div>

            <div class="sc-panel" data-panel="text">
                <div class="sc-panel-head">
                    <span>03</span>
                    <h2>Texte</h2>
                </div>
                <label>Contenu
                    <input data-text-input value="SHAKASS" maxlength="80">
                </label>
                <div class="sc-control-grid">
                    <label>Taille
                        <input type="number" min="10" max="180" step="1" data-font-size value="42">
                    </label>
                    <label>Couleur
                        <input type="color" data-text-color value="#ffffff">
                    </label>
                </div>
                <div class="sc-segmented" role="group" aria-label="<?php echo esc_attr__('Alignement du texte', 'shakass-customizer'); ?>">
                    <button type="button" data-text-align="left">G</button>
                    <button type="button" class="is-active" data-text-align="center">C</button>
                    <button type="button" data-text-align="right">D</button>
                </div>
                <div class="sc-button-stack">
                    <button type="button" class="sc-primary" data-add-text>Ajouter le texte</button>
                    <button type="button" data-apply-text>Appliquer au calque sélectionné</button>
                </div>
            </div>

            <div class="sc-panel" data-panel="upload">
                <div class="sc-panel-head">
                    <span>04</span>
                    <h2>Image</h2>
                </div>
                <div class="sc-dropzone">
                    <strong>PNG ou JPG</strong>
                    <span>Le fichier est ajouté au visuel en cours.</span>
                    <button type="button" data-pick-file>Choisir une image</button>
                    <input type="file" hidden accept="image/png,image/jpeg" data-file-input>
                </div>
                <div class="sc-mini-list" data-upload-list></div>
            </div>

            <div class="sc-panel" data-panel="logo">
                <div class="sc-panel-head">
                    <span>05</span>
                    <h2>Logo</h2>
                </div>
                <div class="sc-logo-grid">
                    <button type="button" data-add-logo="SC">Monogramme SC</button>
                    <button type="button" data-add-logo="SHAKASS">Signature Shakass</button>
                    <button type="button" data-add-logo="◆">Emblème</button>
                </div>
            </div>

            <div class="sc-panel" data-panel="qr">
                <div class="sc-panel-head">
                    <span>06</span>
                    <h2>QR Code</h2>
                </div>
                <label>URL à encoder
                    <input type="url" data-qr-url placeholder="https://shakass-communication.fr">
                </label>
                <div class="sc-button-stack">
                    <button type="button" data-generate-qr>Préparer l’aperçu</button>
                    <button type="button" class="sc-primary" data-add-qr>Ajouter au textile</button>
                </div>
                <div class="sc-qr-preview" data-qr-preview aria-hidden="true"></div>
            </div>

            <div class="sc-panel" data-panel="layers">
                <div class="sc-panel-head">
                    <span>07</span>
                    <h2>Calques</h2>
                </div>
                <div class="sc-layers" data-layers></div>
            </div>

            <div class="sc-panel" data-panel="request">
                <div class="sc-panel-head">
                    <span>08</span>
                    <h2>Demande</h2>
                </div>
                <form class="sc-form" data-request-form>
                    <input name="name" required placeholder="Nom">
                    <input name="email" required type="email" placeholder="Email">
                    <input name="phone" placeholder="Téléphone">
                    <textarea name="message" placeholder="Précisions, délai, technique souhaitée"></textarea>
                    <p><?php echo esc_html($settings['trust']); ?></p>
                    <button type="submit" class="sc-primary"><?php echo esc_html($settings['cta']); ?></button>
                </form>
                <output data-request-status></output>
            </div>
        </section>

        <section class="sc-stage" aria-label="<?php echo esc_attr__('Aperçu textile', 'shakass-customizer'); ?>">
            <div class="sc-face-switch" role="group" aria-label="<?php echo esc_attr__('Face du textile', 'shakass-customizer'); ?>">
                <button type="button" class="is-active" data-side="front">Face</button>
                <button type="button" data-side="back">Dos</button>
            </div>

            <div class="sc-stage-inner" data-stage-inner>
                <div class="sc-shirt" data-shirt-mockup>
                    <div class="sc-shirt-sleeve sc-shirt-sleeve-left"></div>
                    <div class="sc-shirt-sleeve sc-shirt-sleeve-right"></div>
                    <div class="sc-shirt-neck"></div>
                    <div class="sc-shirt-shadow"></div>
                    <div class="sc-print-zone" data-print-zone>
                        <div class="sc-canvas-empty" data-empty-state>Ajoutez un texte, une image ou un QR code</div>
                        <div class="sc-floating-toolbar" data-floating-toolbar>
                            <button type="button" data-duplicate-active title="<?php echo esc_attr__('Dupliquer', 'shakass-customizer'); ?>">⧉</button>
                            <button type="button" data-delete-active title="<?php echo esc_attr__('Supprimer', 'shakass-customizer'); ?>">⌫</button>
                        </div>
                        <canvas id="shakass-canvas" width="360" height="430"></canvas>
                    </div>
                </div>
            </div>

            <div class="sc-zoom" aria-label="<?php echo esc_attr__('Zoom', 'shakass-customizer'); ?>">
                <button type="button" data-zoom="out">−</button>
                <span data-zoom-label>100%</span>
                <button type="button" data-zoom="in">+</button>
            </div>
        </section>

        <aside class="sc-summary" aria-label="<?php echo esc_attr__('Récapitulatif', 'shakass-customizer'); ?>">
            <div class="sc-summary-head">
                <h2>Récapitulatif</h2>
                <span data-layer-count>0 calque</span>
            </div>
            <div class="sc-product-card">
                <span class="sc-thumb" data-summary-thumb></span>
                <div>
                    <strong data-summary-product></strong>
                    <small data-summary-meta></small>
                </div>
            </div>
            <dl>
                <dt>Couleur</dt>
                <dd data-summary-color></dd>
                <dt>Taille</dt>
                <dd data-summary-size></dd>
                <dt>Quantité</dt>
                <dd data-summary-quantity></dd>
                <dt>Face active</dt>
                <dd data-summary-side>Face</dd>
                <dt>Estimation HT</dt>
                <dd class="sc-price" data-summary-price>Sur devis</dd>
            </dl>
            <button type="button" class="sc-primary sc-cta" data-open-request><?php echo esc_html($settings['cta']); ?></button>
            <p class="sc-safe"><?php echo esc_html($settings['trust']); ?></p>
        </aside>
    </main>

    <nav class="sc-mobile-tools" aria-label="<?php echo esc_attr__('Outils mobiles', 'shakass-customizer'); ?>">
        <?php foreach ($tools as $key => $label) : ?>
            <?php if (empty($settings['tools'][$key])) { continue; } ?>
            <button type="button" data-tool="<?php echo esc_attr($key); ?>">
                <span aria-hidden="true"><?php echo esc_html($icons[$key] ?? '•'); ?></span>
                <?php echo esc_html($label); ?>
            </button>
        <?php endforeach; ?>
    </nav>
</div>
