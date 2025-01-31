# MEDIATHEQUE - SAPET Alan

## Idée :
Site web d'une médiathèque, qui souhaite gérer la prise de rendez-vous pour emprunter et rendre des articles par ses clients.

## Fonctionnalités :
- Connexion/Déconnexion
- Restriction d'accès pour utilisateurs non connectés
- Système de panier
- Création de rendez-vous
- Gestion automatique de rappel de rendu des articles possédés
- Prise de rdv sur 7 jours uniquement pour ne pas monopoliser un article
- Consulter ses précédents rendez-vous

## Fonctionnalités que l'on pourrait ajouter pour améliorer :
- Gestion du stock et de la rupture de stock
- Poser un rendez-vous seulement pour rendre des articles
- Pouvoir consulter facilement ses articles encore en possession
- Modifier/Supprimer un rendez-vous à venir

## Fonctionnalités qui auraient pu être ajoutées, mais ne correspondent pas au type de projet :
- Création de compte => La médiathèque veut s'assurer de connaître sa clientèle, et préfère qu'ils viennent en boutique pour s'inscrire, et éviter au maximum les scalpers (individus qui se précipitent sur un produit convoité pour ensuite le vendre/le prêter à prix fort).
- Modification de compte => Dans le même sens, il est mieux que la médiathèque ait le contrôle sur les comptes des clients.

## Points techniques réalisés
- Application Full-Stack Symfony : Front-End en Twig, et Symfony pour le Back-End
- Système de connexion : Utilisation de l'authentification par user de Symfony
- Système de réservation et de récupération de la date/heure courante
- Système de panier : Utilisation des services
