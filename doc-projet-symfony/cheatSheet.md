## Serveur
```
symfony serve
symfony server:start
symfony server:stop
```
## Après clonage d'un repo
```
composer install
si dépendance JS: npm install
```
## GIT
```
git add .
git commit -m 'messge du commit'
git remote add origin https://repeoGit...   ajouter le remote
git remote remove origin    effacer repo
```

## Symfony

Après avoir configuré la connexion dans le fichier .env

```
# Rajouter les packages pour l'ORM

symfony composer req symfony/orm-pack
symfony composer req symfony/maker-bundle --dev
```
```
# Lancer la création de la BD

symfony console doctrine:database:create
```
```
# Création/update des entités

symfony console make:entity 
(valable pour créer une nouvelle ou rajouter de proprietes à une base existante)
```
```
# Créer une migration, la lancer

symfony console make:migration
symfony console doctrine:migration:migrate
```