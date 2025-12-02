#!/bin/bash

echo "Ce script va supprimer la base de données et la recréer."
symfony console doctrine:database:drop --force
symfony console doctrine:database:create

rm migrations/Ve*
symfony console make:migration --no-interaction
symfony console doctrine:migrations:migrate --no-interaction
symfony console doctrine:fixtures:load --no-interaction