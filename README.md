Ce script à pour but de générer un fichier JSON sur un serveur avec la liste des subscribers d'une chaîne Twitch.

Pré-requis
========= 
- PHP >= 7.0
- Composer


Installation
============
 Se rendre dans le dossier du projet pour installer les dépendences :
 ```bash
 composer install
 ```

Copier le fichier ".env.default" en ".env".

Se connecter sur le site : https://dev.twitch.tv/ et créer une nouvelle application.
Copier l'identifiant client généré dans le .env à la ligne `TWITCH_CLIENT_ID` ainsi que le secret à la ligne `TWITCH_SECRET`

Il faut maintenant génerer un token d'accès via cette URL : https://twitchtokengenerator.com/ en sélectionnant les scopes suivant :
1. channel_read
2. channel_subscriptions

Après avoir généré ces codes, renseigner simplement le token refresh dans le .env.

Execution
==========
Executer simplement le fichier app.php :
```
php /var/www/scripts/twitch-subs/app.php
```