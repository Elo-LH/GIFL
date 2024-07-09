# Mini-Projet Twig

## Étape 0 : Mise en place

Faites les étapes du cours sur la mise en place d'un projet Twig. Pour la BDD utilisez celle du blog secu.


## Étape 1 : Créer une homepage

### Les templates

Commencez par créer deux templates :

#### Le layout

`layout.html.twig` qui contient la structure de votre page (DOCTYPE, head, appels au CSS) et qui définit 3 blocs dans son body :

- header
- main
- footer

#### La home

`home.html.twig` hérite de layout et surcharge le bloc `main` pour y afficher un `<h1>` qui dit "Page d'accueil".

### Le controller

Créez un `BlogController` qui hérite de votre `AbstractController`.

Il a une méthode `home` qui appelle sa méthode render en lui passant `"home.html.twig"` et un tableau vide.

### Le Router

Créez une classe Router dans votre dossier `services` avec la méthode `handleRequest` habituelle. Si `$_GET["route"]` n'existe pas il doit appeler la méthode `home` du `BlogController`.

Instanciez un Router dans votre index.php et appelez sa méthode `handleRequest` en lui passant `$_GET`.


## Étape 2 : afficher les posts sur la homepage

### Le modèle Post

Créez une classe Post dans vos `models` qui correspond à votre table `posts`, ne vous occupez pas des auteurs et des dates.

### PostManager

Créez une classe PostManager qui hérite de votre AbstractManager.

Ajoutez lui une méthode `findAll` qui retourne un tableau contenant tous les `Post` de la base de données.

### Le controlleur

Faites appel à la méthode `findAll` du `PostManager` dans votre méthode `home` du `BlogController` et passez le tableau des posts dans le tableau vide de votre appel à la méthode render.

### Le template

Dans `home.html.twig` utilisez une boucle pour afficher pour chaque `Post` son titre et son excerpt.


## Étape 3 : le header

Dans votre template `home.html.twig`, surchargez le bloc header. Dedans vous allez définir une nav qui contient des liens vers chacune des catégories du blog.

### le Modèle Category

Créez un modèle `Category` qui correspond à la table `categories` de votre base de données.

### CategoryManager

Créez une classe `CategoryManager` qui hérite de votre `AbstractManager`. Ajoutez lui une méthode `findAll`qui retourne un tableau de toutes les catégories contenus dans la base de données.

### BlogController

Dans la méthode `home` de votre `BlogController`, récupérez la liste des catégories et passez-la au template.

### home.html.twig

Utilisez la liste des catégories pour dynamiser votre navigation.




