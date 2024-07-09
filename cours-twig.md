---
theme: theme.json
author: Gaellan
date: June 19, 2024
paging: Page %d sur %d
---

# Le moteur de templates Twig

## Afficher une variable

## Les conditions

## Les boucles

## L'héritage

## Pour aller plus loin

---

# Afficher une variable avec Twig

Twig utilise une syntaxe que vous vous connaissez déjà : celle des `{{ }}`.

Habituellement quand vous voulez afficher une variable vous faites :

```html
<?php 
    $test = "Test";
?>

<h1><?php echo $test?></h1>

```

En Twig `<?php echo $test?>` est remplacé par `{{ }}` :

```html
<h1>{{ test }}</h1>
```

---

# Les conditions dans Twig

Une condition dans un fichier `.phtml` :

```html
<?php if($name)
{
?>
    <h1>Hello <?= $name; ?></h1>
<?php
}
else
{
    ?>
    <h1>Hello you</h1>
    <?php
}
```

en Twig :

```html
{% if name %}
   <h1>Hello {{ name }}</h1>
{% else %}
   <h1>Hello you</h1>
{% endif %}
```

---

# Les boucles dans Twig

Pour faire une boucle habituellement vous faites :

```html
<?php foreach($users as $user)
{
    ?>
    <li><?= $user; ?></li>
    <?php
}
```

en Twig : 

```html
{% for user in users %}
        <li>{{ user }}</li>
{% endfor %}
```

---

# L'héritage

Dans Twig les templates peuvent hériter les uns des autres. Imaginons que vous avez un template `layout.html.twig` :

```twig
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title> {% block title %}| Le nom de mon site{% endblock %}</title>
    </head>
    <body>
        {% block content %}
        {% endblock %}
    </body>
</html>
```

et un template `home.html.twig` :

```twig
{% extends "layout.html.twig" %}

{% block title %}
Ma page
{{ parent() }}
{% endblock %}

{% block content %}
    <h1>Accueil</h1>
{% endblock %}
```

`home` hérite de `layout` et complète son bloc `title` mais remplace son block `content`.