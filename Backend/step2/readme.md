J'ai vu que les véhicules avaient une immatriculation ce qui change
l'implémentation du Fleet, de plus j'ai modifié les Model pour qu'il ai des ID
pour qu'ils puissent être persistés en base.

Ce que je ne comprends pas dans DDD c'est que dans les models du Domain on
rajoute des ID typique d'un SGBD alors que le Domain pure object n'en requière
pas (comme dans step1).

J'ai rajouter la class FleetCell pour conserver l'objet Vehicle dans Fleet.

J'ai utilisé sqlite pour la base de donnée pour ne pas avoir a géré un docker
postgres avec initialisation de base etc...

Je n'ai pas utilisé de bibliothèques externes.

Au cas ou ce n'est pas lançable en local

```sh
cat Dockerfile | docker build -t fulll:step2 -

docker run --volume=$PWD:/opt --rm -it fulll:step2 /bin/bash

composer install
php fleet.php create 1
```

Lancer les tests infra

```sh
./vendor/bin/behat --profile infra
```
