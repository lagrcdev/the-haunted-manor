# The Haunted Manor

Site de réservation d'un hôtel fictif où chaque chambre est habitée par un fantôme. C'est un projet réalisé pour le titre professionnel Développeur Web et Web Mobile. L'hôtel, ses chambres, ses coordonnées et ses photos sont inventés. Le site utilise une API en PHP 8.3, les bases de données MySQL et MongoDB, et React pour l'affichage.

Booking website for a fictional hotel where every room is haunted. It is a project built for the French Web Developer certification. The hotel, its rooms, its contact details and its photos are made up. The site uses a PHP 8.3 API, the MySQL and MongoDB databases, and React for the display.

## Ce que fait le site

- Voir la liste des chambres et le détail de chacune (fantôme, histoire, niveau de frayeur, prix par nuit).
- Créer un compte, se connecter, se déconnecter.
- Réserver une chambre sur une période, voir ses réservations et les annuler.
- Laisser un avis dans le livre d'or et supprimer ses propres avis.
- Espace administrateur : voir et annuler les réservations, ajouter, modifier et supprimer des chambres.
- Pages de mentions légales et de politique de confidentialité.
- Site conçu d'abord pour le téléphone (mobile first), avec un menu hamburger, puis adapté aux grands écrans.

## Technologies

FRONT-END

- HTML, CSS et JavaScript
- React 19, React Router
- Vite

BACK-END

- PHP 8.3 orienté objet
- API en JSON
- Requêtes préparées avec PDO

### Bases de données

- MySQL 8.4 : comptes, chambres et réservations
- MongoDB 7 : livre d'or

### Environnement de travail

- Docker : PHP avec Apache, MySQL, MongoDB, phpMyAdmin

## Installer et lancer le projet

Il faut Docker Desktop, Node.js et Git.

1. Récupérer le projet :

   git clone https://github.com/lagrcdev/the-haunted-manor.git
   cd the-haunted-manor

2. Créer le fichier `.env` à partir du modèle, puis y mettre ses propres mots de passe (ce fichier n'est jamais envoyé sur GitHub) :

   cp .env.example .env

   `DB_USER` et `DB_PASSWORD` doivent correspondre à `MYSQL_USER` et `MYSQL_PASSWORD`.

3. Lancer le back-end et les bases de données :

   docker compose up -d --build

   Au premier démarrage, MySQL crée les tables et ajoute les trois chambres. L'API répond sur http://localhost:8080 et phpMyAdmin sur http://localhost:8081.

4. Lancer le front-end :

   cd frontend
   npm install
   npm run dev

   Le site est alors sur http://localhost:5173. Vite renvoie les adresses `/api` et `/uploads` vers le port 8080.

### Créer un compte administrateur

Il n'existe pas d'inscription pour un administrateur. On s'inscrit comme un client, puis on change le rôle dans phpMyAdmin (table `utilisateur`, colonne `role`, valeur `admin`) ou en SQL :

   UPDATE utilisateur SET role = 'admin' WHERE email = 'votre@email.fr';

## Sécurité

- Mots de passe enregistrés sous forme de hash (`password_hash`).
- Requêtes SQL préparées, sorties échappées côté React.
- Jeton CSRF exigé pour toute requête qui modifie des données.
- Sessions avec cookie HttpOnly, rôles client et administrateur vérifiés côté serveur.
- Photos envoyées : type réel vérifié, JPG et PNG uniquement, 2 Mo maximum, nom choisi par le serveur, aucun script exécutable dans le dossier des photos.
- Erreurs : le détail reste dans le journal du serveur, le visiteur ne voit qu'un message général.

## Limites connues

- Les photos envoyées ne sont pas redimensionnées.
- Une réservation est confirmée tout de suite, il n'y a pas de statut « en attente ».
- Pas de limitation du nombre de tentatives de connexion.
- Pas de bouton pour supprimer son compte.

