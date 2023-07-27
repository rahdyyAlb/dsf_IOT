# BARCODE PRO 

![logo.png](assets%2Fimages%2Flogo.png)

## Mise en place du projet 

### Prérequis

- PHP 8.2
- node & yarn
- symfony cli
- composer 2

### Installation en local

- Premièrement cloner le projet
- Executer les commandes

- copier le `.env` en `.env.local`
- `composer install`
- `yarn install` 
- `yarn encore dev-server`
- `symfony serve -d` Pour lancer le serveur web
- `symfony console doctrine:migration:migrate` Vous permettra d'initialiser la BD
- `php bin/console hautelook:fixtures:load ` Sera nécessaire pour créer des données factives
- Le projet devrait être accessible via `127.0.0.1`
- Un utilisateur admin est créer par les fixtures avec les identifiants suivant -> admin@user.fr:password

### Création d'une base de données locale (Utilisation du projet sans docker)

- Connectez-vous à votre base de données `mysql -u root -p`
- Décommentez la ligne 27 de votre .env et y mettre `DATABASE_URL="mysql://root:root@127.0.0.1:8889/BARCODE_PRO"` (Changez éventuellement le "root:root" si les identifiants de votre base de données sont différents)
- executez la commande suivante `symfony console doctrine:database:create`
- `symfony console doctrine:migration:migrate` 

