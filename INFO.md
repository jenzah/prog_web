
# Documentation du Projet
N'hésitez pas à ajouter les fichiers sur lesquels vous travaillez, avec une petite description.

## Dossier Principal
- **README.md**
    - Contient des informations sur le projet.
        - Description du projet, références et membres du groupe
        - Aperçu du projet, fonctions, limitations techniques
        - Configuration requise
        - Base de données et explications sur comment s'y connecter
        - Tutoriel Git à consulter pendant le travail

- **index.php**
    - La page d'accueil de l'application.
    - !! fonction recherche ne marche pas

- **config.php**
    - Définit les paramètres de connexion à la base de données

### Menu À propos
- **about.php**
    - La page "À propos", contient des informations sur l'"entreprise".

### Menu Propriétés
- **property.php**
    - Grille de toutes les propriétés
    - À faire :
        - WIP ajouter une fonction de recherche/filtrage
        - OK supprimer la barre latérale, avoir 3 colonnes à la place
        - OK vérifier si l'affichage de la date est automatique (calcule le temps et n'est pas simplement une entrée de texte)
    - Pour le moment, seul le titre est cliquable, peut-être envisager de rendre tout l'affichage cliquable (image, boîte, pas les éléments individuels)

- **propertydetail.php**
    - Détaille une seule propriété
    - À modifier :
        - le diaporama est automatique, y ajouter des boutons pour se déplacer entre les images
        - `À vendre` ressemble à un bouton, le rendre moins semblable à un bouton
        - le résumé de la propriété n'est pas nécessaire, définir plutôt correctement les caractéristiques
        - pas besoin de plans d'étage, les inclure dans les images
        - sur la barre latérale, au lieu du contenu actuel, mettre : informations sur l'agent, et 2 boutons, contacter l'agent (ouvre une nouvelle page avec eux), prendre RDV (va à la page RDV de cet agent)
    - images come from `admin/property`, image name is saved in the database

- **propertysearchresult.php**
    - Affiche les résultats de recherche de propriétés depuis la page d'accueil
    - Besoin de le fusionner avec `property.php` puis de le supprimer

- **stateproperty.php**
    - Pour le moment accessible depuis la page d'accueil (uniquement depuis là)
    - Affiche les propriétés d'un certain état
    - Besoin de clarifier cela, et peut-être fusionner avec `property.php` et `propertysearchresult.php` ou autre chose

### Menu
- **profile.php**
    - La page de profil utilisateur
    - Je ne suis pas sûr si elle a le même aspect pour tous les utilisateurs ou non
    - Pour le moment, elle contient :
        - un formulaire de feedback (que je ne pense pas nécessaire)
        - photo de profil
        - informations : nom, email, téléphone et rôle (nous n'avons pas besoin du rôle séparément - ajouter le rôle après le nom, par ex. `Jack London (admin)`)

### Connexion/déconnexion
- **login.php**
    - Page de connexion, demande email et mot de passe
    - Contient un lien vers `register.php`

- **logout.php**
    - Déconnecte l'utilisateur de la session actuelle
    - Redirige vers `login.php`

- **register.php**
    - Permet à l'utilisateur de s'inscrire
    - Contient un lien vers `login.php`
    - Doit pouvoir reconnaître si la personne qui se connecte est un client, un agent ou un administrateur

### Pages non utilisées (pour le moment)
- **contact.php**
    - Contient un formulaire de contact

- **tabletemplate.php**
    - Affiche toutes les propriétés ajoutées par un utilisateur (sur le compte utilisateur)
    - Je l'ai renommé, car il peut être utilisé comme modèle pour l'administrateur pour éditer toutes les tables (client, propriété, agent, administrateur)

- **addproperty.php**
    - Formulaire pour ajouter une propriété
    - Pourrait être utilisé par l'administrateur pour ajouter une propriété

- **addpropertydelete.php**
    - Supprime une propriété de la base de données
    - Il n'y a pas de page d'atterrissage, donc nous devrons vérifier si nous avons besoin d'une page HTML appropriée pour cela, ou si cela met simplement à jour la page où il est utilisé
    - Il est utilisé dans `tabletemplate.php`, qui était à l'origine une table avec toutes les propriétés d'un utilisateur. Je crois, mais peut être utilisé comme modèle pour toutes les tables (agents, clients) pour l'administrateur

- **addpropertyupdate.php**
    - Met à jour une propriété dans la base de données :
        - Se connecte à la base de données
        - etc --> la personne qui travaillera sur cette fonctionnalité devrait vérifier plus en détail ce qui se passe dans le HTML
    - Il est utilisé dans `tabletemplate.php`, comme ci-dessus

## Dossier Include
- **header.php**
    - Contient le code HTML pour l'en-tête supérieur et la ligne de menu

- **footer.php**
    - Contient le HTML pour le pied de page : informations et crédits


## Fichiers CSS
- **color.css**
    - Contient le thème de couleur pour le site web
    - Pour une raison, il y a des couleurs de thème définies pour les modes clair et sombre, mais je n'ai pas encore vu de bouton qui peut activer les fonctions. Je ne pense pas que nous ayons besoin d'ajouter cela, donc si tout le monde est d'accord, nous pouvons supprimer tout le code relatif.

- **style.css**
    - Contient tout le code css pour le style de la page web
    - La plupart des éléments utilisent le thème de couleur de `color.css`
        - Certains éléments ne le font pas, donc nous devons vérifier manuellement où ils sont, s'ils sont utilisés et les mettre à jour/supprimer
    - Je n'ai pas nettoyé ce fichier, donc au cas où nous aurions le temps, nous pourrions vouloir le faire. Je n'en vois pas l'intérêt pour le moment.

- Je n'ai pas encore vérifié le reste des fichiers css, ils semblent être générés par bootstrap, je laisserai les personnes qui s'occupent des fonctionnalités qu'ils fournissent voir s'ils sont nécessaires ou non.


## fonts/flaticon
Je ne sais pas exactement ce que fait ce dossier.
Le `flaticon.css` est appelé dans chaque fichier PHP qui se trouve dans le dossier principal.
Quand tout sera terminé, nous pourrions tester ce qui se passe si nous le supprimons de chaque fichier.