#!/bin/bash

# Détection de l'OS
OS=$(uname -s 2>/dev/null || echo "Windows")

if [[ "$OS" == "Linux" || "$OS" == "Darwin" ]]; then
    # Pour Linux et macOS
    cd "$(dirname "$0")"  # Se place dans le dossier du script
elif [[ "$OS" == "Windows" || "$OS" == "MINGW"* || "$OS" == "CYGWIN"* ]]; then
    # Pour Windows (Git Bash, Cygwin, MSYS)
    cd "$(cd "$(dirname "$BASH_SOURCE")" && pwd)"
else
    echo "Système d'exploitation non reconnu : $OS"
    exit 1
fi

# Aller dans src et exécuter PHP
cd src
php loadDB.php
php -S localhost:8000
