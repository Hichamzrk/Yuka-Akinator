<!-- PROJECT LOGO -->
<br />
<div align="center">
    <img src="/public/images/favicon.png" alt="Logo" width="100" height="100">

<h3 align="center">Yuka Akinator</h3>
</div>



<!-- ABOUT THE PROJECT -->
## Présentation du projet

<div align="center">
    <img src="/public/images/page_screenshot.jpg" alt="screen-short-app" width="800" height="450">
</div>

**Yuka Akinator** est une application innovante qui aide les utilisateurs à trouver le plat qu'ils ont en tête en utilisant un arbre de décision à choix binaires. Si l'application ne parvient pas à trouver le plat désiré, l'utilisateur peut alors entrer le nom du plat et une question qui permet de le différencier du plat précédemment proposé. Ces informations sont ensuite ajoutées à la base de données pour améliorer les résultats futurs. Avec Yuka Akinator, découvrez de nouveaux plats et élargissez votre horizon culinaire en quelques clics !


<!-- GETTING STARTED -->
## Getting Started ✅


### Prerequis 🛠

Stack technique:
* php 8.1 ☑️
* Symfony 6.2 ☑️
* MySQL 8 ☑️

### Installation 🟢

1. Cloner le repository :
   ```sh
   git clone https://github.com/Hichamzrk/Yuka-Akinator.git
   ```
2. Configurer les login de la base de donnée dans le .env
3. Configurer les login de la base de donnée de test dans le .env.test à la racine du dossier
4. Créer la base de donné et insérer les fixtures :
   ```sh
   make install
   ```
5. Ensuite lancer le serveur Symfony :
   ```sh
   make start
   ```

6. Lancer les tests :
   ```sh
   make test
   ```
7. **Enjoy 🎉**
<!-- ROADMAP -->
## Structure 📑

### Voici un exemple d'un arbre construit avec l'application :

<div align="center">
    <img src="/public/images/diagramme-Tree.drawio.png" alt="screen-short-diagramm" width="800" height="500">
</div>
