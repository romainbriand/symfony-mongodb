# Restomap

Une petite application Symfony / MongoDB pour gérer une cartographie simple de restaurant.

## Installation

- Cloner le dépôt
- Installer les dépendances avec ``composer install``
- Charger les fixtures avec ``php bin/console doctrine:mongodb:fixtures:load``

## Composants utilisés
- [DoctrineMongoDBBundle](https://symfony.com/doc/current/bundles/DoctrineMongoDBBundle/index.html)
- [DoctrineFixturesBundle](https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html)
- [Faker](https://github.com/fzaninotto/Faker)

## Configuration

Pour information, voici quelques points importants dans la configuration.

### Environnement 

Dans le fichier ``.env`` à la racine, les variables d'environnement suivantes sont renseignées :
- `MONGODB_URL=mongodb://localhost:27017` chaîne de connexion au serveur MongoDB
- `MONGODB_DB=symfony` nom de la base MongoDB
- `REDIS_URL=redis://localhost:6379` chaîne de connexion à Redis pour le cache
- `GOOGLE_MAPS_API_KEY` Clé d'API Google Maps pour la page d'accueil

### Service

Dans le fichier ``config/services.yaml``, on a fait en sorte de configurer le ParamConverter de Doctrine pour fonctionner avec l'ODM plutôt que l'ORM (par défaut) :

```
Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter:
        arguments:
            - "@doctrine_mongodb"
        tags:
            - { name: request.param_converter }
```

## Lectures complémentaires :

- [Configuring the cache with framework bundle](https://symfony.com/doc/current/cache.html#configuring-cache-with-frameworkbundle)
- [Doctrine Param Converter](https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter)