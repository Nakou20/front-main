# CDS 49 – Chevrollier Driving School

Ce dépôt contient le code source de l’application CDS 49, développée pour l’auto-école Chevrollier Driving School.  
Il s’agit d’une solution applicative de gestion qui permet d’administrer les élèves, les moniteurs, les véhicules et les leçons.

⚠️ Nécessite PHP 8.4+

## Contenu du projet

- Le dump et les migrations de la base de données
- Le code de l’application web
- Une API REST utilisée par l’application mobile

---

## Utilisation

### Initialiser la base de données

```shell
php mvc db:migrate
```

### Créer un nouveau modèle

```shell
php mvc model:create NomDuModele
```

### Créer un nouveau controller

```shell
php mvc controller:create NomDuControler
```

### Lancer le projet

```shell
php mvc serve
```

-- 

## Développer en utilisant Docker

Si votre machine est équipée de Docker, vous pouvez lancer le site, l'API et la base de données en utilisant Docker Compose. 

```sh
docker compose -f docker-compose.local.yml up
```

Au premier démarrage de l'application, vous devrez initialiser la base de données avec les migrations :

```sh
docker compose -f docker-compose.local.yml exec front php mvc db:migrate
```

### Accéder à l'application

## Déployer sur Apache ou Docker

- [Déployer sur Apache](https://cours.brosseau.ovh/tp/ops/mini-mvc-sample/deployer-mini-mvc-sample.html)
- [Déployer avec Docker](https://cours.brosseau.ovh/tp/ops/mini-mvc-sample/mini-mvc-sample-docker.html)

---

Ce projet est réalisé à des fins pédagogiques. [Document associé disponible ici](https://cours.brosseau.ovh/tp/php/mvc/tp1.html)

---

**Note importante**, cette architecture est à but pédagogique seulement, si vous souhaitez réaliser un développement MVC je vous conseille fortement de partir sur une solution type Laravel.