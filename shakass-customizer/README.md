# Shakass Customizer

Version : **1.3.0**

Plugin WordPress autonome pour Shakass Communication. Il ajoute un configurateur textile premium via le shortcode :

```text
[shakass_customizer]
```

## Fonctionnalités V1

- Interface front dark premium responsive : topbar, barre outils verticale, panneau contextuel, scène textile, switch Face / Dos, récapitulatif et CTA.
- Version mobile dédiée : mockup visible en haut, outils horizontaux en bas et panneau d’options en bottom sheet.
- Données front alimentées par l’administration : produits actifs, couleurs, tailles, mockups, zones d’impression, tarifs, CTA et outils activés.
- Edition graphique avec Fabric.js : texte, image PNG/JPG, logo rapide, QR préparé, déplacement, rotation, redimensionnement, duplication, suppression, undo/redo et calques.
- Administration WordPress : Dashboard, Produits, Mockups, Tarifs, Demandes et Réglages.
- Demandes client sauvegardées dans le CPT privé `shakass_request`, avec statut et endpoint REST sécurisé par nonce.
- Données par défaut créées à l’activation : T-shirt noir, T-shirt blanc, Sweat noir, Polo blanc, tailles S/M/L/XL, zones recto/verso et tarif simple.

## Installation et test

1. Copier `shakass-customizer` dans `wp-content/plugins/`.
2. Activer **Shakass Customizer**.
3. Vérifier le menu **Shakass Customizer** dans l’administration.
4. Créer une page contenant `[shakass_customizer]`.
5. Tester produit, couleur, taille, Face / Dos, texte, upload image, calques, QR et envoi de demande.

## Limites restantes

- Le QR code front est un aperçu préparé, pas encore un QR ISO final scannable.
- Fabric.js est chargé depuis CDN pour cette V1 ; une production verrouillée devrait le vendoriser dans le plugin.
- Les mockups image recto / verso sont supportés par l’admin ; sans image, le front utilise le mockup CSS premium.
- Les estimations tarifaires sont volontairement simples et doivent être validées commercialement avant devis final.
