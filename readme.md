# Bienvenue sur le repo backend du projet pokemon-team-builder

  ## Mise en place de la base de données

  - Clôner le repo du back, et ensuite se placer dans le dossier pokemon-team-builder
  - Pour installer doctrine, dans la console, faire un composer install
  - Pour configurer la connexion avec la base de données, créer un fichier .env.local, et y mettre : la variable DATABASE_URL= avec en valeur l'url requise pour accéder à la bdd (voir avec Geoffrey ou Emilie pour ça)
  - Pour créer la base de données et la charger dans adminer, dans la console, taper bin/console doctrine:database:create
  - Pour créer les tables dans la bdd, charger les migrations : dans la console, taper bin/console doctrine:migration:migrate
  - Pour charger les données dans les différentes tables, utiliser la commande, taper bin/console database-fill

  ## Chargement des fixtures (fausses données de user et team)

  - Pour récupérer le composant qui s'occupe de charger les fixtures, dans la console faire un composer update
  - Pour charger les fixtures dans la bdd, dans la console taper bin/console doctrine:fixtures:load


  ## Fonctionnement des routes de l'api 

| route                                            | method | description                                                                                     | controller    | nom                                 |
| ------------------------------------------------ | ------ | ----------------------------------------------------------------------------------------------- | ------------- | ----------------------------------- |
| api/v1                                           | GET    | page d'accueil de l'api                                                                         | Main          | api_v1_                             |
| api/v1/pokemon                                   | GET    | liste des pokemons                                                                              | Api           | api_v1_pokemon                      |
| api/v1/pokemon/{id}                              | GET    | détail d'un pokemon selon l'id recherché                                                        | Api           | api_v1_pokemon_by_id                |
| api/v1/pokemon/{name}                            | GET    | détail d'un pokemon selon le nom recherché                                                      | Api           | api_v1_pokemon_by_name              |
| api/v1/pokemon/type/{name}                       | GET    | liste des pokemons selon le nom du type recherché                                               | Api           | api_v1_pokemon_by_type_name         |
| api/v1/pokemon/generation/{id}                   | GET    | liste des pokemons selon l'id de la génération recherchée                                       | Api           | api_v1_pokemon_generation_by_id     |
| api/v1/pokemon/limit/{number}                    | GET    | liste des pokemons selon le {nombre} demandé                                                    | Api           | api_v1_pokemon_limit                |
| api/v1/pokemon/types/{typeName1}/{typeName2}     | GET    | liste des pokemons selon le nom des types recherchés                                            | Api           | api_v1_pokemon_by_double_type       |
| api/v1/pokemon/type/weakness/{typeName}          | GET    | liste des pokemons selon le nom du type auquel ils sont faibles                                 | Api           | api_v1_pokemon_by_weakness          |
| api/v1/pokemon/type/double-weakness/{typeName}   | GET    | liste des pokemons selon le nom du type auquel ils sont doublement faibles                      | Api           | api_v1_pokemon_by_double_weakness   |
| api/v1/pokemon/type/resistance/{typeName}        | GET    | liste des pokemons selon le nom du type auquel ils sont résistants                              | Api           | api_v1_pokemon_by_resistance        |
| api/v1/pokemon/type/double-resistance/{typeName} | GET    | liste des pokemons selon le nom du type auquel ils sont doublement résistants                   | Api           | api_v1_pokemon_by_double-resistance |
| api/v1/pokemon/type/immunity/{typeName}          | GET    | liste des pokemons selon le nom du type auquel ils sont immunisés                               | Api           | api_v1_pokemon_by_immunity          |
| api/v1/pokemon/types                             | GET    | liste des types (sans les pokemons associés)                                                    | Api           | api_v1_types                        |
| api/v1/random/team                               | GET    | équipe de 6 pokemons générée de manière aléatoire                                               | RandomTeam    | api_v1_random_team                  |
| api/v1/random/team/suggest                       | GET    | équipe de 3 pokemons générée de manière aléatoire et complétée par la suggestion                | RandomTeam    | api_v1_random_team_suggest          |
| api/v1/team/defensive-coverage                   | POST   | retourne une couverture défensive à partir d'un tableau d'ids                                   | DataTreatment | api_v1_defensive_coverage           |
| api/v1/team/suggestion                           | POST   | retourne une liste de pokemon suggérés en fonction du tableau d'ids envoyé                      | DataTreatment | api_v1_pokemon_suggestion           |
| api/v1/pokemon/{id}/ability/{abilityName}        | GET    | retourne un pokemon avec l'id renseigné et ses resistances modifiées par l'ability renseignée   | Api           | api_v1_pokemon_by_id_with_ability   |
| api/v1/pokemon/{name}/ability/{abilityName}      | GET    | retourne un pokemon avec le nom renseigné et ses resistances modifiées par l'ability renseignée | Api           | api_v1_pokemon_by_name_with_ability |
