<?php
if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="sc-app" data-shakass-customizer>
    <header class="sc-topbar" aria-label="Topbar du configurateur Shakass">
        <div class="sc-brand" aria-label="Shakass Communication"><span class="sc-brand-word">SHAKA<span>SS</span></span><small>COMMUNICATION</small></div>
        <nav class="sc-breadcrumb" aria-label="Projet courant">Mes projets <span>›</span> Collection Été 2025 <span>›</span> <strong>T-shirt Streetwear</strong></nav>
        <div class="sc-actions"><button data-action="undo" aria-label="Annuler">↶</button><button data-action="redo" aria-label="Rétablir">↷</button><button data-action="save" class="sc-soft">▣ Enregistrer</button><button data-action="reset">↺ Réinitialiser</button></div>
    </header>

    <main class="sc-workspace">
        <aside class="sc-toolbar" aria-label="Outils principaux">
            <button class="is-active" data-tool="product"><span>♙</span>Produit</button>
            <button data-tool="colors"><span>◌</span>Couleurs</button>
            <button data-tool="text"><span>T</span>Texte</button>
            <button data-tool="upload"><span>⇧</span>Importer</button>
            <button data-tool="logo"><span>◇</span>Logo / SVG</button>
            <button data-tool="qr"><span>▦</span>QR Code</button>
            <button data-tool="layers"><span>▱</span>Calques</button>
            <button data-tool="request"><span>✉</span>Demande</button>
        </aside>

        <section class="sc-context-panel" aria-live="polite">
            <div class="sc-panel is-active" data-panel="product">
                <h2>Produit</h2><p>Base textile et finitions.</p>
                <label>Type produit<select data-summary="product"><option>T-shirt</option><option>Polo</option><option>Hoodie</option><option>Sweat</option></select></label>
                <div class="sc-grid"><label>Coupe<select><option>Unisexe</option><option>Homme</option><option>Femme</option></select></label><label>Taille<select data-summary="size"><option>M</option><option>XS</option><option>S</option><option>L</option><option>XL</option><option>XXL</option></select></label></div>
                <label>Matière<select><option>Coton peigné</option><option>Polyester</option><option>Mixte</option></select></label>
                <label>Grammage<select><option>180 g/m²</option><option>150 g/m²</option><option>220 g/m²</option></select></label>
                <div class="sc-premium-card"><strong>Qualité Premium</strong><span>Impression durable, rendu textile professionnel.</span></div>
            </div>

            <div class="sc-panel" data-panel="colors"><h2>Couleurs</h2><p>Couleur sélectionnée : <strong data-color-name>Noir premium</strong></p><div class="sc-swatches"><button class="is-active" data-shirt="#08090d" aria-label="Noir"></button><button data-shirt="#f5f2ea" aria-label="Blanc"></button><button data-shirt="#747986" aria-label="Gris"></button><button data-shirt="#111c35" aria-label="Bleu marine"></button><button data-shirt="#9b1717" aria-label="Rouge"></button></div></div>

            <div class="sc-panel" data-panel="text"><h2>Texte</h2><input type="text" data-text-input value="SHAKASS"><div class="sc-grid"><label>Police<select data-font><option>Inter</option><option>Montserrat</option><option>Arial</option><option>Georgia</option></select></label><label>Taille<input type="number" data-font-size value="42"></label></div><label>Couleur<input type="color" data-text-color value="#ffffff"></label><div class="sc-button-row"><button data-style="bold">Gras</button><button data-style="italic">Italique</button><button data-style="underline">Souligné</button></div><label>Alignement<select data-align><option>center</option><option>left</option><option>right</option></select></label><label>Effets<select data-effect><option>Aucun</option><option>Contour</option><option>Ombre</option><option>Néon</option></select></label><button class="sc-primary" data-add-text>Ajouter le texte</button><button data-apply-text>Appliquer</button></div>

            <div class="sc-panel" data-panel="upload"><h2>Importer</h2><div class="sc-dropzone" data-dropzone>Déposez PNG, JPG ou SVG ici<br><button data-pick-file>Choisir un fichier</button><input type="file" hidden accept="image/png,image/jpeg,image/svg+xml" data-file-input></div><div class="sc-mini-list" data-upload-list></div></div>
            <div class="sc-panel" data-panel="logo"><h2>Logo / SVG</h2><div class="sc-logo-grid"><button data-add-logo="♛">♛ Couronne</button><button data-add-logo="◆">◆ Emblème</button><button data-add-logo="SC">SC Monogramme</button></div><label>Recoloration future<input type="color" value="#ff5a2c"></label></div>
            <div class="sc-panel" data-panel="qr"><h2>QR Code</h2><input type="url" data-qr-url placeholder="https://shakass-communication.fr"><label>Style<select><option>Simple premium</option><option>Arrondi</option></select></label><button data-generate-qr>Générer</button><button class="sc-primary" data-add-qr>Ajouter ce QR Code</button></div>
            <div class="sc-panel" data-panel="layers"><h2>Calques</h2><div class="sc-layers" data-layers></div></div>
            <div class="sc-panel" data-panel="request"><h2>Demande</h2><form class="sc-form" data-request-form><input name="name" placeholder="Nom"><input name="email" type="email" placeholder="Email"><input name="phone" placeholder="Téléphone"><input name="quantity" type="number" value="25" min="1"><textarea name="message" placeholder="Votre message"></textarea><p>Demande gratuite et sans engagement</p><button class="sc-primary">Envoyer ma demande</button></form><output data-request-status></output></div>
        </section>

        <section class="sc-stage"><div class="sc-face-switch"><button class="is-active" data-side="front">Face</button><button data-side="back">Dos</button></div><div class="sc-shirt" data-shirt-mockup><div class="sc-shirt-neck"></div><div class="sc-print-zone"><div class="sc-floating-toolbar" data-floating-toolbar><button data-floating-copy title="Dupliquer">⧉</button><button data-floating-delete title="Supprimer">⌫</button><button title="Options">⋮</button></div><canvas id="shakass-canvas" width="360" height="430"></canvas></div></div><div class="sc-zoom"><button data-zoom="out">−</button><span data-zoom-label>100%</span><button data-zoom="in">+</button></div></section>

        <aside class="sc-summary"><h2>Récapitulatif</h2><div class="sc-product-card"><span class="sc-thumb"></span><div><strong data-summary-product>T-shirt</strong><small>Shakass Premium<br>180 g/m² · Unisexe</small></div></div><dl><dt>Couleur</dt><dd data-summary-color>Noir premium</dd><dt>Taille</dt><dd data-summary-size>M</dd><dt>Quantité</dt><dd data-summary-quantity>25 pièces</dd><dt>Technique</dt><dd>Impression textile premium</dd><dt>Prix estimatif HT</dt><dd class="sc-price">Sur devis</dd></dl><button class="sc-primary" data-open-request>✈ Envoyer ma demande</button><p class="sc-safe">▧ Demande gratuite et sans engagement</p></aside>
    </main>

    <nav class="sc-mobile-tools"><button data-tool="product">Produit</button><button data-tool="colors">Couleurs</button><button data-tool="text">Texte</button><button data-tool="upload">Importer</button><button data-tool="qr">QR Code</button><button data-tool="layers">Calques</button></nav>
    <div class="sc-mobile-sheet" data-mobile-sheet><button class="sc-sheet-close" data-close-sheet>×</button><div data-sheet-content></div></div>
</div>
