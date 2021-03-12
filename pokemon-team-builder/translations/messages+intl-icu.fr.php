<?php

return [
  'vulnerable' => 'Attention : votre équipe est vulnérable à ce type !',
  'slightly-vulnerable' => 'Votre équipe est légèrement faible à ce type.',
  'balanced' => 'Les resistances de votre équipe à ce type sont bien équilibrées.',
  'slightly-resistant' => 'Votre équipe est légèrement résistante à ce type',
  'resistant' => 'Votre équipe résiste bien à ce type !',
  'full-team' => 'Votre équipe comporte déjà 6 Pokémon. Veuillez retirer au moins un Pokémon pour avoir une suggestion.',
  'empty-team' => 'Votre équipe ne contient pas de Pokémon, veuillez sélectionner au moins un Pokemon.',
  'too-much-pokemon' => 'Votre équipe contient trop de Pokémon',
  'too-few-pokemon' => 'Votre équipe doit contenir 6 Pokémon',
  'invalid-password' => 'Mot de passe invalide.',
  'wrong-username' => 'Utilisateur inconnu.',
  'user-creation' => 'Utilisateur {user} créé',
  'user-deletion' => 'Utilisateur {user} supprimé',
  'user-edition' => 'Utilisateur {user} modifié',
  'existing-user' => 'Ce nom d\'utilisateur existe déjà',
  'wrong-email' => 'Adresse e-mail incorrecte',
  'wrong-team-name' => 'Merci de donner un nom à votre équipe.',
  'team-creation' => 'Equipe {team} créée',
  'team-deletion' => 'Equipe {team} supprimée',
  'team-edition' => 'Equipe {team} modifiée',
  'non-matching-passwords' => 'Les deux mots de passe ne correspondent pas.',
  'login' => 'Connexion réussie. Bienvenue {user}.',
  'wrong-pokemon' => 'Pokemon inconnu.',
  'user-edition-null' => 'Requête réussie pour l\'utilisateur {user}, aucune donnée à modifier',
  'greetings' => 'Bonjour',
  'more-vulnerabilities' => 'Votre équipe a plus de vulnérabilités que de résistances',
  'equal-resistances' => 'Votre équipe a autant de résistances que de faiblesses.',
  'low-vulnerabilities' => 'Votre équipe a peu de vulnérabilités et plus de résistances, bien joué.',
  'more-resistances' => 'Votre équipe a plus de résistances que de faiblesses.',
  'coverage-notice-start' => 'Pour utiliser cette route, utilisez l\'url',
  'coverage-notice-url' => '/api/v1/team/defensive-coverage/v2',
  'coverage-notice-end' => 'en POST en envoyant, en json, un objet qui indique l\'id des Pokémons choisis en clé et leur capacité (sans accents et avec des - au lieu des espaces) (mettre une châine de caractères vide, null ou false si pas de capacité pour le Pokémon). Exemple : {
                                                                                                                  3: null,
                                                                                                                  110: "Levitation",
                                                                                                                  6: "",
                                                                                                                  292: "Garde-Mystik"
                                                                                                               }',
  'suggestion-notice-start' => 'Pour utiliser cette route, utilisez l\'url',
  'suggestion-notice-url' => '/api/v1/team/suggestion/v2',
  'suggestion-notice-end' => 'en POST en envoyant, en json, un objet qui indique l\'id des Pokémons choisis en clé et leur capacité (sans accents et avec des - au lieu des espaces) (mettre une châine de caractères vide, null ou false si pas de capacité pour le Pokémon). Exemple : {
                                                                                                                  3: null,
                                                                                                                  110: "Levitation",
                                                                                                                  6: "",
                                                                                                                  292: "Garde-Mystik"
                                                                                                               }'
];