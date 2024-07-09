--- 
theme: theme.json
author: Gaellan
date: Nov 28, 2023
paging: Page %d sur %d
---

# Initiation à Sass

Sass (Syntactically Awesome Stylesheets) est un pre-processeur de CSS, c'est à dire que vous écrivez dans la syntaxe de Sass, vous exécutez une commande dans le terminal, et cette commande transforme votre code Sass en code CSS.

## Préparer ses dossiers pour Sass

Dans vos projets, vous devriez toujours avoir un dossier `assets`. Dans le dossier `assets`, créez un dossier `styles`. Dans le dossier `styles`, créez deux dossiers `sass` et `css`.

Dans votre fichier html, mettez le lien de votre fichier CSS comme ceci :

```html
<link rel="stylesheet" href="assets/styles/css/style.css" />
```

Ne touchez pas à votre dossier `assets/styles/css`.

Dans votre dossier `assets/styles/sass`, créez un fichier `style.scss` et un fichier `_vars.scss` (attention le `_` au début du nom de fichier est important).

## Compiler Sass

Ouvrez une fenêtre de terminal et rendez-vous dans le dossier de votre projet / exercice.

Lorsque vous y êtes, saisissez la commande : 

```sh
sass -w assets/styles/sass:assets/styles/css
```

Cela demande à Sass de surveiller toutes les modifications que vous faites dans vos fichiers `.scss` et de les compiler en `.css`.

---

# Les bases de Sass : imbriquer les propriétés

Un des aspects magiques de Sass c'est de nous permettre d'imbriquer nos propriétés :

Voici du code CSS classique :

```css
main article section {
    background-color : blue;
    color: red;
}
```

en Sass ça donne ça :

```scss
main {
    article {
        section {
            background-color:blue;
            color: red;
        }
    }
}
```

Cela peut permettre facilement de différencier par exemple, un `<ul>` qui est dans votre `<nav>`, ou bien ailleurs dans le `<body>` : 

```scss
body {
    nav {
        ul {
            background-color: blue;
        }
    }

    ul {
        background-color:red;
    }
}
```

Ici la `<ul>` de la `<nav>` aura un fond bleu, toutes les autres auront un fond rouge.

---

# Initiation à Sass : l'opérateur &

Pour aller un peu plus loin, on utilise l'opérateur & pour désigner le sélecteur qui était dans l'imbrication d'au dessus :

```scss
nav {
    ul {
        list-style:none;

        li {
            text-decoration:none;

            &.active {
                text-decoration:underline;
            }
        }
    }
}
```

équivaut à écrire :

```scss
nav {

    ul {
        list-style:none;

        li {
            text-decoration:none;
        }

        li.active {
            text-decoration:underline;
        }
    }
}
```

Et cela peut fonctionner avec tous types de sélecteurs : `:first-of-type`, `>`, `type=['submit']`, ...

---

# Initiation à Sass : les variables

La dernière chose que nous allons voir dans cette initiation c'est la déclaration et l'utilisation des variables.

Pour déclarer une variable :

```scss
$lightGrey: #dddddd;
```

Pour l'utiliser :

```scss
p {
	color: $lightGrey;
}
```

---

# Initiation à Sass : importer des fichiers

Vous pouvez avoir des sous-fichiers mais leurs noms doivent commencer par un underscore `_`. Et vous devez les importer dans votre `style.scss` en les appelant par leur nom sans underscore.

Par exemple pour importer le fichier `_vars.scss` où vous aurez déclaré vos variables :

```scss
@import "vars";
```

Vous pouvez importer un fichier css directement dans votre Sass, cela peut être très utile pour importer les fichiers d'une librairie ou utiliser des feuilles toutes faites comme `reset.css` ou `normalize.css`.


Importez-les de préférence au début du fichier, comme ça tout le code suivant peut en bénéficier !

