Pour le code quality j'ai utilisé phpcs pour avoir un style uniforme.
Et phpstan (level ~9) pour détecter certaines erreurs de type.

Pour le CI/CD:
- validation par phpcs et phpstan (ou autre analyseur statique)
- construction des image docker
- lancer les différents tests automatique
- Pour le CD je ne sais pas trop, peut être pousser les images docker sur le
  registry avec incrementation du tag (des image docker) et modifier la
  configuration de kubernetes avec les nouveaux tag et reload les pods
  gracefully.
