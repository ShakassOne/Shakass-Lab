# Shakass Customizer

Version : **1.0.0**

Plugin WordPress autonome pour Shakass Communication. Il ajoute un configurateur textile premium via le shortcode :

```text
[shakass_customizer]
```

## Installation

1. Copier le dossier `shakass-customizer` dans `wp-content/plugins/`.
2. Activer **Shakass Customizer** depuis l’administration WordPress.
3. Créer ou modifier une page WordPress.
4. Ajouter le shortcode `[shakass_customizer]` dans le contenu de la page.

## Fonctionnalités V1

- Interface sombre premium, responsive desktop et mobile.
- Topbar, barre d’outils multi-niveaux et panneaux contextuels.
- Mockup textile CSS/SVG sans image externe cassée.
- Zone d’impression visible avec canvas Fabric.js.
- Exemple de design préchargé : `SHAKASS`, `COMMUNICATION`, symbole couronne.
- Ajout et édition simple de texte.
- Upload image préparé côté interface.
- Gestion visuelle des calques : sélectionner, dupliquer, supprimer.
- Switch Face / Dos côté interface.
- Récapitulatif et formulaire de demande.
- Menu admin WordPress : `Shakass Customizer`, `Demandes`, `Réglages`.
- Endpoint REST sécurisé par nonce pour recevoir la structure des futures demandes.

## Dépendances

Fabric.js est chargé via CDN en V1 :

```text
https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js
```

Ce choix permet de valider rapidement l’interface premium. Pour une version production plus stricte, il est recommandé de vendoriser Fabric.js dans `assets/vendor/` afin de supprimer la dépendance réseau.

## Limites restantes

- Le stockage persistant des demandes n’est pas encore branché.
- L’upload ajoute l’aperçu de fichier dans l’interface mais ne téléverse pas encore vers la médiathèque WordPress.
- Le QR Code est représenté par un placeholder graphique en V1.
- Le switch Face / Dos est prêt côté UI mais ne conserve pas encore deux états de canvas séparés.
- Les prix sont affichés en mode `Sur devis`.

## Prochaines étapes recommandées

1. Valider la direction visuelle desktop et mobile.
2. Ajouter le stockage des demandes dans un custom post type ou une table dédiée.
3. Brancher l’upload WordPress sécurisé.
4. Ajouter la génération réelle de QR Code.
5. Séparer les états Face / Dos.
6. Ajouter des mockups textile réels lorsque les visuels officiels sont disponibles.
7. Vendoriser Fabric.js pour limiter les dépendances externes.
