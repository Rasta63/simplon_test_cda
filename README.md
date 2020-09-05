# simplon_test_cda.

## Application developper avec le framework php symfony.

### Prérequis :

- [wamp avec php 7.4.9](https://www.wampserver.com/#download-wrapper)

- Configurer la ligne de commande PHP  [Sous windows](https://blog.emmanuelgautier.fr/configurer-la-ligne-de-commande-php-sous-windows/)

- [Symfony](https://symfony.com/download)

- [Composer](https://getcomposer.org/download/)

- [Postman](https://www.postman.com/downloads/)

Après les prérequis et avoir récupérer le dépôt, exécuter la commande suivante pour installer les dépendances :

```composer install```

Création de la base de donnés :

```php bin/console doctrine:database:create```
Bien s'assurer que le server wamp est bien lancé. 

Le lien de configuration de la base de donné se trouve dans le fichier .env, en temps normal je le mettrais dans un fichier .env.local et l’ajouter dans le gitignore pour ne pas donner des information sensible comme le nom de la base, le nom d’utilisateur et le mots de passe. 


Exécuter les migrations avec cette commande :  
```php bin/console doctrine:migrations:migrate```

Tester les routes avec les méthodes CRUD pour les utilisateurs, lancer le serveur symfony :
 ```symfony server:start```

Puis  lancez postman pour envoyer des requêtes http

les routes : 
/users , methodes (GET,POST)
/users/{id}, methodes (GET,PUT,DELETE)

Pensez à ajouter des utilisateurs avec la methodes POST avant de lancer les autres méthodes.

Exepmle : 

![alt text](https://github.com/Rasta63/simplon_test_cda/blob/master/CapturePostMan.PNG "Capture d'écran postman")
