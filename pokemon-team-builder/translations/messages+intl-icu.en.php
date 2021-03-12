<?php

return [
  'vulnerable' => 'Warning: your team is vulnerable to this type !',
  'slightly-vulnerable' => 'Your team is slightly vulnerable to this type.',
  'balanced' => 'Your team\'s resistaces are well balanced.',
  'slightly-resistant' => 'Your team is slightly resistant to this type.',
  'resistant' => 'Your team resists well to this type !',
  'full-team' => 'Your team already has 6 Pokemon, please remove at least one Pokemon to get a suggestion.',
  'empty-team' => 'No selected Pokemon detected, please select at least one pokemon.',
  'too-much-pokemon' => 'Your team contains too much Pokemon',
  'too-few-pokemon' => 'Your team should contain 6 Pokemon',
  'invalid-password' => 'Invalid password.',
  'wrong-username' => 'Please enter a valid username.',
  'user-creation' => 'User {user} created',
  'user-deletion' => 'User {user} deleted',
  'user-edition' => 'User {user} edited',
  'existing-user' => 'This username already exists',
  'wrong-email' => 'incorrect e-mail adress',
  'wrong-team-name' => 'Please enter a valid team name.',
  'team-creation' => 'Team {team} created',
  'team-deletion' => 'Team {team} deleted',
  'team-edition' => 'Team {team} edited',
  'non-matching-passwords' => 'Passwords do not match.',
  'login' => 'Connexion successful. Welcome {user}.',
  'wrong-pokemon' => 'Please enter a valid pokemon.',
  'user-edition-null' => 'Request succesful for the {user}, but nothing was updated',
  'greetings' => 'Hello',
  'more-vulnerabilities' => 'Your team as more vulnerabilities than weaknesses, be careful.',
  'equal-resistances' => 'Your team as as many vulnerabilities as weaknesses.',
  'low-vulnerabilities' => 'Your team has few or no vulnerabilities and some resistances, well done.',
  'more-resistances' => 'Your team has more resistances than weaknesses.',
  'coverage-notice-start' => 'To use this route, use the',
  'coverage-notice-url' => '/api/v1/team/defensive-coverage/v2',
  'coverage-notice-end' => 'url, in a POST request, sendinq a json object with the chosen pokemon ids as keys and the corresponding selected skills as values (french name without accents, with - instead of scpaces) (put as value en empty string, false or null for pokemon with no selected skills). Example : {
                                                                                                                                              3: null,
                                                                                                                                              110: "Levitation",
                                                                                                                                              6: "",
                                                                                                                                              292: "Garde-Mystik"
                                                                                                                                           }',
  'suggestion-notice-start' => 'To use this route, use the',
  'suggestion-notice-url' => '/api/v1/team/suggestion/v2',
  'suggestion-notice-end' => 'url, in a POST request, sendinq a json object with the chosen pokemon ids as keys and the corresponding selected skills as values (french name without accents, with - instead of scpaces) (put as value en empty string, false or null for pokemon with no selected skills). Example : {
                                                                                                                                              3: null,
                                                                                                                                              110: "Levitation",
                                                                                                                                              6: "",
                                                                                                                                              292: "Garde-Mystik"
                                                                                                                                           }'
];