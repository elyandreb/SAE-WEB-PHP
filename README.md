# SAE-WEB-PHP

### Lien du projet
https://github.com/elyandreb/SAE-WEB-PHP

Groupe 23A :

## Composition de l'équipe
- **Lenny VERGEROLLE** (Chef de projet)
- Loris GRANDCHAMP
- Elyandre BURET
- Valentin HUN

## Fonctionnalités
### Visiteur 
- Connexion/inscription
- Recherche des restaurants
- Filtre des restaurants par type de cuisine, type de restaurant, note
- Visualisation des restaurants
### Visiteur connecté
NB : Le visiteur connecté a les fonctionnalités d'un visiteur
- Filtre par préférence, favoris
- Modification du profil (Données de l'utilisateur dont les préférences)
- Accès aux restaurants favoris 
- Accès aux avis
- Ajout d'avis, de favoris
- Suppression d'avis, de favoris
- Modification d'avis, de favoris
### Admin 
NB : L'admin a les fonctionnalités d'un visiteur
- Suppression d'avis
- Modification d'avis

## Installation de l'application

### Prérequis
- PHP 7.4 ou supérieur
- Composer 2.0 ou supérieur

### Étapes d'installation

1. **Cloner le dépôt** :
```bash
git clone https://github.com/elyandreb/SAE-WEB-PHP
```

2. **Si ce n'est pas fait installez Composer** :

#### Pour Windows

1. Téléchargez et exécutez l'installateur Windows depuis [getcomposer.org/download](https://getcomposer.org/download/)
2. Suivez les instructions de l'assistant d'installation
3. L'installateur ajoutera automatiquement Composer à votre PATH

Ou via PowerShell :
```powershell
# Téléchargez l'installateur
Invoke-WebRequest -Uri https://getcomposer.org/installer -OutFile composer-setup.php

# Installez Composer globalement
php composer-setup.php --install-dir=C:\ProgramData\ComposerSetup\bin --filename=composer

# Nettoyez
Remove-Item composer-setup.php
```

#### Pour Linux/macOS

```bash
# Téléchargez l'installateur
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Vérifiez l'installateur (optionnel mais recommandé)
HASH=$(curl -sS https://composer.github.io/installer.sig)
php -r "if (hash_file('sha384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

# Installez Composer globalement
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Nettoyez
rm composer-setup.php
```

3. **Installation des dépendances du projet**

Une fois Composer installé, mettez-vous à la racine du projet et tapez la commande ci-dessous pour installer les dépendances du projet (notamment PHPUnit)

```bash
composer install
```

## Lancement des tests
### Pour lancer tous les tests

#### Sur Windows
```bash
vendor\bin\phpunit --testdox src/tests
```
#### Sur Linux/macOS
```bash
./vendor/bin/phpunit --testdox src/tests
```

### Pour lancer un test spécifique

#### Sur Windows
```bash
vendor\bin\phpunit --testdox src/tests/<nom_du_test>
```
#### Sur Linux/macOS
```bash
./vendor/bin/phpunit --testdox src/tests/<nom_du_test>
```

## Lancer l'application

### Avec start.sh

#### Sur Windows
Depuis la racine du projet
```bash
.\start.sh
```

#### Sur Linux
Depuis la racine du projet
```bash
sed -i -e 's/\r$//' start.sh
```
```bash
bash start.sh
```

### Sans utiliser start.sh
1. Déplacez-vous dans le dossier src
```bash
cd src
```

2. Initialisez la base de données (avant le premier lancement)
```bash
php loadDB.php
```

3. Lancer l'application
```sh
php -S localhost:8000
```
## Structure du projet

```
.
├── README.md
├── restaurant.db
├── src
│   ├── classes
│   │   ├── autoloader
│   │   │   └── autoload.php
│   │   ├── controller
│   │   │   ├── ControllerAvis.php
│   │   │   ├── ControllerCuisine.php
│   │   │   ├── ControllerFavoris.php
│   │   │   ├── ControllerLogin.php
│   │   │   ├── ControllerPreferences.php
│   │   │   ├── ControllerProfil.php
│   │   │   ├── ControllerRegister.php
│   │   │   └── ControllerRestaurant.php
│   │   ├── model
│   │   │   ├── CritiqueModel.php
│   │   │   ├── FavoriModel.php
│   │   │   ├── Model_bd.php
│   │   │   ├── RestaurantModel.php
│   │   │   ├── TypeCuisineModel.php
│   │   │   └── UserModel.php
│   │   └── provider
│   │       └── Provider.php
│   ├── config.php
│   ├── data
│   │   └── restaurants_orleans.json
│   ├── index.php
│   ├── loadDB.php
│   ├── restaurant.db
│   ├── static
│   │   ├── css
│   │   │   ├── avis.css
│   │   │   ├── bouton_filtre.css
│   │   │   ├── connexion.css
│   │   │   ├── edit_profil.css
│   │   │   ├── favoris.css
│   │   │   ├── header.css
│   │   │   ├── inscription.css
│   │   │   ├── preferences.css
│   │   │   ├── profil.css
│   │   │   ├── restaurant.css
│   │   │   └── style.css
│   │   ├── img
│   │   │   ├── coeur.svg
│   │   │   ├── coeur_vide.svg
│   │   │   ├── logo.svg
│   │   │   ├── restaurant.svg
│   │   │   ├── star.svg
│   │   │   └── user.svg
│   │   └── js
│   │       ├── favoris.js
│   │       └── header.js
│   ├── tests
│   │   ├── test_critique.php
│   │   ├── test_restaurant.php
│   │   ├── test_type_cuisine.php
│   │   └── test_utilisateur.php
│   └── views
│       ├── add_avis.php
│       ├── edit_avis.php
│       ├── edit_profil.php
│       ├── gerer_avis.php
│       ├── header.php
│       ├── les_avis.php
│       ├── les_favoris.php
│       ├── les_restaurants.php
│       ├── login_form.php
│       ├── preferences_form.php
│       ├── profil.php
│       └── register_form.php
├── start.sh
└── test.sh
```

