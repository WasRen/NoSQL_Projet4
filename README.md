# NoSQL_Projet4

-------- POUR INITITALISER LES DONNEES -------

Télécharger la collection et l'importer dans POSTMAN
Executer les réquêtes à la suite :
Suppression de l'index movies
/DEL - DELETE MOVIES

Initialisation de l'index 
/PUT - INIT

Enregistrement du mapping Elastic Search
/PUT - MAPPING

Enregistrement des données
/PUT - BULK


---------------- REMARQUE ----------------
pour les dépendances : composer install

le fichier .env peut être adapté à notre server SQL et non au votre --> vérifier l'URL

le fichier src/Repository/PanierRepository ligne 31 et 32 contient les instructions
si vous rencontrez un problème à la première connexion d'un utilisateur



------------ FONCTIONNALITES ---------------

- Inscription et connexion utilisateur (mot de passse hashé,  restrictions des accès aux URL en mode non-connecté)
- Affichage du catalogue
- Recherche multi-critères 
- Gestion du panier (ajout et modification de la quantité possible, délai de 5min )
- Commande (création d'une commande, affichages de l'historiques des commandes




