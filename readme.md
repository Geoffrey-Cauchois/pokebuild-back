# Bienvenue sur le repo backend du projet pokemon-team-builder

  ## Mise en place de la base de données

  - Clôner le repo du back, et ensuite se placer dans le dossier pokemon-team-builder
  - Pour installer doctrine, dans la console, faire un composer install
  - Pour configurer la connexion avec la base de données, créer un fichier .env.local, et y mettre : DATABASE_URL="mysql://explorateur:Ereul9Aeng@127.0.0.1:3306/pokemon_team_builder"
  - Pour créer la base de données et la charger dans adminer, dans la console, taper bin/console doctrine:database:create
  - Pour créer les tables dans la bdd, charger les migrations : dans la console, taper bin/console doctrine:migration:migrate
  - Pour charger les données dans les différentes tables, utiliser la commande, taper bin/console database-fill

  ## Chargement des fixtures (fausses données de user et team)

  - Pour récupérer le composant qui s'occupe de charger les fixtures, dans la console faire un composer update
  - Pour charger les fixtures dans la bdd, dans la console taper bin/console doctrine:fixtures:load
