#!/bin/bash

# Vérifie si Composer est déjà installé
if ! command -v composer &> /dev/null
then
    echo "Composer non trouvé. Installation de Composer..."

    # Télécharge l'installateur de Composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

    # Vérifie le SHA-384 de l'installateur
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installateur vérifié'; } else { echo 'Installateur corrompu'; unlink('composer-setup.php'); } echo PHP_EOL;"

    # Exécute l'installateur
    php composer-setup.php

    # Supprime l'installateur
    php -r "unlink('composer-setup.php');"
else
    echo "Composer est déjà installé."
fi

# Ajoute PHPUnit comme dépendance de développement
composer require --dev phpunit/phpunit

# Exécute les tests

vendor/bin/phpunit src/tests/*test* 