# Test technique PHP

Créer un environnement docker simple contenant au minimum un container capable d'exécuter du code PHP

Créer un convertisseur [Sass](https://sass-lang.com/).

Ce dernier permettra de transformer le code ci-dessous en CSS.

```scss
.my-flowers {
  width: 30px;
  height: 30px;
  border-radius: 15px;

  .are {
    .beautiful 
    {
        color: #00f;
    }

    .ugly   {  
        color: #f00;
        width: 15px;
        
        &:hover {
            color: #0f0 ;
        }
    }
  }
}

.my-tailor {
    height: 120px ;

    .is-rich {
        content: '$';
    }

    .is-not-rich {
        content: '-1';
    }
}
```

vers

```css
.my-flowers {
  width: 30px;
  height: 30px;
  border-radius: 15px;
}
.my-flowers .are .beautiful {
  color: #00f;
}
.my-flowers .are .ugly {
  color: #f00;
  width: 15px;
}
.my-flowers .are .ugly:hover {
  color: #0f0;
}
.my-tailor {
  height: 120px;
}
.my-tailor .is-rich {
  content: '$';
}
.my-tailor .is-not-rich {
  content: '-1';
}
```

Consignes :

- Essayer de rester le plus bas niveau possible pour la conversion du texte. (càd ne pas utiliser une lib de parsing sass en php)
- Une interface grpahique n'est pas demandée: on devra pouvoir exécuter le code directement depuis le container Docker via le CLI PHP
- Norme PSR-4 obligatoire.


Nous sommes ouvert à toutes questions.

# Utilisation

Lancer le docker (dans le dossier infra)

```shell
docker-compose up -d --build
```

Pour lancer la conversion du fichier présent dans "/app/css_to_convert.scss" vers "/app/css_converted.css" (dans le dossier app)

```shell
docker container run --rm -v $(pwd):/app/ php:8.0.0RC4-fpm-alpine php /app/index.php
```