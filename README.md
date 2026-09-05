# L'EMPREINTE 🌿

**Framework de services numériques pour praticiens de santé holistique.**

Ce projet est une application Symfony 8 conçue pour offrir une autonomie totale aux praticiens (gestion de catalogue, lexique thérapeutique, blog saisonnier) tout en garantissant la souveraineté des données de santé.

## 🛠 Environnement Technique

- **PHP :** 8.4 (Typage strict)
- **Framework :** Symfony 8.0.\*
- **Serveur d'application :** FrankenPHP (Docker)
- **Base de données :** PostgreSQL 14
- **Gestion des actifs :** Symfony AssetMapper (Zéro Node.js)
- **CSS :** Tailwind CSS v4

## 🚀 Installation (Développement)

1. **Lancer l'infrastructure :**

    ```bash
    docker compose up -d

     Installer les dépendances :
     code Bash

     docker compose exec php composer install

     Préparer la base de données :
     code Bash

     docker compose exec php php bin/console doctrine:database:create
     docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
     docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

     Compiler les styles :
     code Bash

     docker compose exec php php bin/console tailwind:build
    ```

🔐 Sécurité & Confidentialité

    Identifiants : Usage systématique d'UUID pour l'anonymisation des dossiers patients dans les URLs.

    Hachage : Mots de passe sécurisés via l'algorithme natif de Symfony.

    RGPD : Gestion native du droit à l'oubli via les suppressions en cascade.

📅 Algorithme de Réservation

Le système garantit une "respiration" thérapeutique :

    Amont : 30 minutes de préparation de l'espace.

    Aval : 15 minutes de transition entre deux patients.

code Code

---

### 3. Petit conseil pour la suite

Une fois que tu as déplacé le fichier et collé le texte, n'oublie pas de faire un petit commit Git pour acter cette création :

```bash
git add README.md
git commit -m "docs: ajout du README professionnel"
```
