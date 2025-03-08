# Omnes Immobilier - Plateforme Web Immobilière

## À propos du Projet

Ce projet est une plateforme web immobilière développée pour la communauté Omnes Education.
La plateforme permet aux utilisateurs de parcourir les propriétés disponibles, de prendre rendez-vous avec des agents immobiliers et de communiquer avec eux par différents canaux (texte, audio, vidéo ou e-mail).

*Ce projet est dérivé de : https://github.com/suraj25809/Real-Estate-Php*

*Développé par:*
- *Wynona Wendy BELINGA OWONO*
- *Ashley OHNONA*
- *Kawtar BENAICHA*
- *Jennifer ZAHORA*
-salut les if

## Aperçu du Projet

La plateforme permet aux clients de :

- Voir les annonces de toutes les propriétés immobilières à vendre
- Sélectionner un agent immobilier associé à une propriété
- Consulter les informations de l'agent (CV, coordonnées, emploi du temps hebdomadaire)
- Planifier des rendez-vous pour visiter des propriétés
- Recevoir des confirmations de rendez-vous
- Communiquer avec les agents disponibles par messagerie, audio, vidéo ou e-mail

Le site est géré par une équipe administrative qui peut ajouter de nouvelles propriétés, des agents immobiliers et gérer les emplois du temps des agents.


## Fonctionnalités Principales

### Types d'Utilisateurs
1. **Administrateur**
   - Ajouter/supprimer des propriétés et des agents immobiliers
   - Gérer les propriétés (ajouter des nouvelles, retirer celles vendues)
   - Créer des profils d'agents avec ID, nom, photos, spécialisation
   - Définir la disponibilité hebdomadaire des agents
   - (Créer et gérer les informations de CV des agents)

2. **Agent Immobilier**
   - Communiquer avec les clients (e-mail, texte, audio, vidéo)
   - Accéder à leur profil et calendrier de rendez-vous
   - Voir les consultations actuelles et à venir

3. **Client**
   - Parcourir les propriétés disponibles
   - Vérifier la disponibilité des agents
   - Planifier des rendez-vous avec les agents
   - Annuler des rendez-vous
   - Effectuer des paiements pour les services
   - Créer un nouveau compte si nécessaire
   - (Consulter l'historique des rendez-vous)


### Fonctionnalités Détaillées

1. **Page d'Accueil**
   - Message de bienvenue
   - Section "Événement de la semaine"
   - Carrousel de propriétés
   - Bulletin immobilier
   - Coordonnées et localisation Google Maps

2. **Tout Parcourir**
   - Catégories : Résidentiel, Commercial, Terrain, Appartements à louer
   - Informations détaillées sur les propriétés (photos, descriptions)
   - Informations sur l'agent associé

3. **Recherche**
   - Recherche par nom d'agent
   - Recherche par numéro de propriété
   - Recherche par ville/commune

4. **Rendez-vous**
   - Voir les rendez-vous confirmés
   - Voir les détails des rendez-vous (date, heure, adresse)
   - Annuler des rendez-vous

5. **Votre Compte**
   - Gestion du profil utilisateur
   - Consulter l'historique des consultations
   - Gérer les informations de paiement

(6. **Détails des Propriétés**
   - Photos/vidéos de la propriété
   - Description (pièces, dimensions, étage, balcon, parking)
   - Emplacement)

7. **Profils des Agents**
   - Photo et coordonnées
   - Calendrier de disponibilité hebdomadaire
   - CV et expérience
   - Options de communication

8. **Planification de Rendez-vous**
   - Voir les créneaux disponibles de l'agent
   - Réserver des rendez-vous
   - Recevoir une confirmation

9. **Communication**
   - Chat texte
   - Audio
   - Vidéo
   - E-mail

10. **Traitement des Paiements**
    - Plusieurs méthodes de paiement (cartes de crédit, PayPal)
    - Confirmation de paiement


## Exigences Techniques

- Front-end : HTML, CSS, JavaScript, jQuery, Bootstrap
- Back-end : PHP, 

- Architecture client-serveur
- Configuration du serveur de base de données
- Contrôle de version Git


(## Fonctionnalités Avancées (Optionnelles)

- Chèques-cadeaux comme option de paiement
- Offres spéciales pour les fêtes
- Cartes de réduction (10-20% sur le premier loyer)
- Indicateurs de statut des agents en direct
- Visites virtuelles des propriétés)

## Considérations de Sécurité

- Authentification des utilisateurs
- Traitement sécurisé des paiements
- Protection des données privées

Ce projet sera développé comme une application client-serveur complète, avec les informations sur les propriétés, les utilisateurs, les agents et les administrateurs stockées en toute sécurité sur le serveur.

---

# Requirements

    PHP >= 7.3;
    PDO PHP Extension;
    GD PHP extension
    MySQL >= 5.7;


# Configuration de la Base de Données

Nom de la base de données: `omnes_immobilier`

1. **Démarrer le serveur MySQL**
   - Lancez le serveur MySQL via le panneau de contrôle XAMPP/WAMP
   
2. **Créer la base de données**
   - Ouvrez phpMyAdmin (http://localhost/phpmyadmin)
   - Créez une nouvelle base de données nommée `omnes_immobilier`
   
3. **Importer la base de données**
   - Sélectionnez la base de données `omnes_immobilier` dans phpMyAdmin
   - Importer le fichier `omnes_immobilier.sql`

4. **Configurer la connexion**
   - Ouvrez le fichier `config.php`
   - Modifiez les paramètres de connexion selon la configuration locale :
     ```php
     $con = mysqli_connect("localhost", "root", "", "omnes_immobilier");
     ```
     > Note: "localhost" est généralement le nom du serveur pour le développement local, "root" est l'utilisateur par défaut, le mot de passe est souvent vide, et "omnes_immobilier" est le nom de la base de données.


# Tutoriel de Base Git

## Authentification à GitHub

### Méthode recommandée : Authentification SSH (plus sécurisée)
Plus d'informations dans la [documentation officielle GitHub sur SSH](https://docs.github.com/fr/authentication/connecting-to-github-with-ssh).

### Alternative : GitHub Desktop
GitHub Desktop est une solution simple :

1. Téléchargez et installez [GitHub Desktop](https://desktop.github.com/)
2. Connectez-vous à votre compte GitHub
3. Clonez le dépôt via l'interface
4. Toutes les opérations Git peuvent être effectuées via l'application

GitHub Desktop gère automatiquement l'authentification pour vous.


## Configuration Initiale
### Cloner le Dépôt
Pour créer une copie locale du dépôt sur votre machine :
```bash
git clone https://github.com/jenzah/prog_web.git
cd nom-du-depot
```


## Gestion des Branches
### Afficher Toutes les Branches
```bash
git branch -a
```

### Créer une Nouvelle Branche
```bash
git checkout -b feature/votre-nouvelle-fonctionnalite
```
Cela crée une nouvelle branche et y bascule immédiatement.

### Basculer Entre les Branches
```bash
git checkout nom-de-branche
```


## Workflow
### Obtenir les Dernières Modifications
Avant de commencer à travailler, récupérez toujours les dernières modifications :
```bash
git pull
```

### Vérifier le Statut
Pour voir quels fichiers ont été modifiés :
```bash
git status
```

### Préparer les Modifications
Pour ajouter des fichiers modifiés à votre prochain commit :
```bash
git add fichier.php          # Ajouter un fichier spécifique
git add dossier/             # Ajouter tous les fichiers d'un dossier
git add .                    # Ajouter tous les fichiers modifiés
```

### Valider les Modifications
Enregistrez vos modifications préparées avec un message descriptif :
```bash
git commit -m "Votre message de commit descriptif"
```

### Pousser les Modifications
Téléchargez vos commits vers le dépôt distant :
```bash
git push
```

Pour une nouvelle branche qui n'a jamais été poussée auparavant :
```bash
git push -u origin feature/votre-nouvelle-fonctionnalite
```

## Opérations Avancées
### Fusionner les Modifications
Pour fusionner une autre branche dans votre branche actuelle :
```bash
git merge nom-de-branche
```