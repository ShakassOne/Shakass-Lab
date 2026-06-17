# Shakass Customizer

Version : **1.2.0**

Plugin WordPress autonome pour Shakass Communication. Il ajoute un configurateur textile premium via le shortcode :

```text
[shakass_customizer]
```

## Fonctionnalités V1 solide

- Administration WordPress complète : Dashboard, Produits, Mockups, Tarifs, Demandes et Réglages.
- Données configurables stockées dans des options WordPress propres pour produits, mockups, tarifs et réglages.
- Demandes client sauvegardées dans le CPT interne `shakass_request` avec statuts.
- Endpoint REST de configuration et endpoint REST d’envoi de demande sécurisés par nonce pour l’envoi.
- Front dynamique alimenté par les données admin : produits actifs, tailles, couleurs, zones d’impression, tarifs, CTA et outils activés.
- Interface front dark premium responsive avec topbar, toolbar, panneau contextuel, canvas central, face/dos, récapitulatif et bottom UI mobile.
- Édition Fabric.js : ajout texte, import PNG/JPG, logos placeholders, QR placeholder, sélection, déplacement, rotation, redimensionnement, duplication, suppression et calques non dupliqués.
- Estimation tarifaire simple basée sur produit, quantité, éléments ajoutés et remises.

## Installation et test

1. Copier `shakass-customizer` dans `wp-content/plugins/`.
2. Activer **Shakass Customizer**.
3. Vérifier le menu **Shakass Customizer** dans l’administration.
4. Créer une page contenant `[shakass_customizer]`.
5. Tester un ajout de texte, un upload PNG/JPG, le switch Face/Dos et l’envoi d’une demande.

## Données par défaut

À l’activation, le plugin crée : T-shirt noir, T-shirt blanc, Sweat noir, Polo blanc, mockups par défaut, zones recto/verso et tarifs simples.

## Limites restantes

- Les champs produits/mockups sont volontairement simples pour cette V1 ; l’ajout dynamique de lignes sans rechargement peut être amélioré.
- Le QR Code est un placeholder visuel côté canvas, prêt à être remplacé par une vraie librairie QR.
- Les images mockups réelles sont supportées côté données mais le rendu V1 utilise encore majoritairement le mockup CSS textile.
- Fabric.js est chargé via CDN ; une version production stricte devrait le vendoriser.
- Le clipping strict de la zone d’impression reste à renforcer ; la zone est visible et dimensionnée par les mockups.
