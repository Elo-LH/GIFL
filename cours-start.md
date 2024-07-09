---
theme: theme.json
author: Gaellan
date: June 19, 2024
paging: Page %d sur %d
---

# Démarrer un projet PHP complet

## Installer les librairies avec composer

## Autoload des classes

## Préparer le .env

## Le .gitignore

## AbstractController

## AbstractManager

---

# Installer les librairies avec composer

## dotenv

```sh
composer require vlucas/phpdotenv
```

## Twig

```sh
composer require "twig/twig:^3.0"
```

## Var Dumper

```sh
composer require --dev symfony/var-dumper
```

---

# Autoload des classes

Créez votre architecture de dossiers habituelle :

- assets
- controllers
- managers
- models
- services
- templates

Le dossier vendor a déjà été créé par composer.

Nous allons ensuite modifier le `composer.json` pour qu'il se charge d'autoloader nos classes, modifiez le pour qu'il soit équivalent à :

```json
{
    "autoload": {
        "classmap": [
            "controllers/",
            "managers/",
            "models/",
            "services/"
        ]
    },
    "require": {
        "vlucas/phpdotenv": "^5.6",
        "twig/twig": "^3.0"
    },
    "require-dev": {
        "symfony/var-dumper": "^7.0"
    }
}
```

Ensuite à chaque fois que vous ajouterez une classe dans l'un des dossiers :

```sh
composer dump-autoload
```

pour ajouter cette classe à l'autoload.

---

# Préparez le .env

Créez un fichier `.env.example` à la racine de votre projet, et mettez y le contenu suivant :

```txt
# Database info
DB_NAME="databasename"
DB_USER="username"
DB_PASSWORD="password"
DB_CHARSET="utf8"
DB_HOST="db.3wa.io"
```

ensuite faites une copie de ce fichier que vous appeleez `.env` et ajoutez-y les bonnes informations de connexion à votre base de données.

Créez ensuite un fichier `index.php` à la racine de votre projet et mettez-y le contenu suivant :

```php
<?php

// charge l'autoload de composer
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
```

---

# Le .gitignore

Créez un fichier `.gitignore` à la racine de votre projet et mette y le contenu suivant :

```txt
vendor/
.env
composer.lock
```

Si vous avez créé un repository pour votre projet faites les commandes suivantes :

```sh
git add .gitignore
git commit -m "add gitignore"
git push
```

cela vous évitera de versionner les dossiers et fichiers qui ne devraient pas être en ligne.

---

# AbstractController

Voici le contenu de votre AbstractController qui initialise et appelle Twig :

```php
abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader,[
            'debug' => true,
        ]);

        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->twig = $twig;
    }

    protected function render(string $template, array $data) : void
    {
        echo $this->twig->render($template, $data);
    }
}
```

---

# AbstractManager

Voici le contenu de votre AbstractManager qui se connecte à la base de données avec les informations de votre fichier `.env` :

```php
abstract class AbstractManager
{
    protected PDO $db;

    public function __construct()
    {
        $connexion = "mysql:host=".$_ENV["DB_HOST"].";port=3306;charset=".$_ENV["DB_CHARSET"].";dbname=".$_ENV["DB_NAME"];
        $this->db = new PDO(
            $connexion,
            $_ENV["DB_USER"],
            $_ENV["DB_PASSWORD"]
        );
    }
}
```