# SAE-WEB-PHP

Groupe 23A :

Loris GRANDCHAMP, Elyandre BURET, Valentin HUN, Lenny VERGEROLLE

Chef de groupe : Lenny VERGEROLLE 

# Projet PHP Restaurants


## Fonctionnalités


## Utilisation

Le script utilise les classes et espaces de noms suivants :


## Lancer l'application

```sh
sh start.sh
```

## Pour installer Composer 

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') ==='dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
composer require --dev phpunit/phpunit

# Pour lancer les tests
## a la racine 

src/tests/

vendor/bin/phpunit src/tests/*test* 

vendor/bin/phpunit src/tests/*test* --testdox --debug