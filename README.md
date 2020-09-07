# simplon_test_cda.

## Application developper avec le framework php symfony.

### Prérequis :

- [wamp avec php 7.4.9](https://www.wampserver.com/#download-wrapper)

- Configurer la ligne de commande PHP  [Sous windows](https://blog.emmanuelgautier.fr/configurer-la-ligne-de-commande-php-sous-windows/)

- [Symfony](https://symfony.com/download)

- [Composer](https://getcomposer.org/download/)

- [Postman](https://www.postman.com/downloads/)

### Installation des dépendances :

```composer install```

### Création de la base de donnés :

```php bin/console doctrine:database:create```

Bien s'assurer que le server wamp est bien lancé. 

Le lien de configuration de la base de donné se trouve dans le fichier .env, en temps normal je le mettrais dans un fichier .env.local et l’ajouter dans le gitignore pour ne pas donner des information sensible comme le nom de la base, le nom d’utilisateur et le mots de passe. 


- Exécuter les migrations avec cette commande :  
```php bin/console doctrine:migrations:migrate```

### Test des routes avec les méthodes CRUD sur les utilisateurs

- Execute du serveur symfony :
 ```symfony server:start```

- Lancez postman pour envoyer des requêtes http à l'app ainsi tester les urls

### Les routes : 
 - /users , methodes (GET,POST)
 - /users/{id}, methodes (GET,PUT,DELETE)

Ajout d'utilisateurs avec la methodes POST, avant de lancer les autres méthodes.

Exemple :

![alt text](https://github.com/Rasta63/simplon_test_cda/blob/master/CapturePostMan.PNG "Capture d'écran postman")
