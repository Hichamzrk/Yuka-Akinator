<!-- PROJECT LOGO -->
<br />
<div align="center">
    <img src="/public/images/favicon.png" alt="Logo" width="100" height="100">

<h3 align="center">Yuka Akinator</h3>
</div>



<!-- ABOUT THE PROJECT -->
## Présentation du projet

<div align="center">
    <img src="/public/images/page_screenshot.jpg" alt="screen-short-app" width="800" height="400">
</div>


Application qui trouve le plat auquel l'utilisateur penses via un arbre de decision à choix binaire. Si l'application ne trouve pas le plat. L'utilisateur rentre le nom du plat et la question qui la différencie du plat précédent. Alors le plat et la question sont ensuite insérés au bon endroit dans la base de donnée.


<!-- GETTING STARTED -->
## Getting Started


### Prerequis

Stack technique :
* php 8.1
* Symfony 6.2
* MySQL 8

### Installation

1. Cloner le repository
   ```sh
   git clone https://github.com/Hichamzrk/Yuka-Akinator.git
   ```
2. Configurer la base de donnée dans le .env à la racine du dossier
2. Créer la base de donné et insérer les fixtures
   ```sh
   make install
   ```
3. Ensuite lancer le serveur Symfony
   ```sh
   make start
   ```

4. Lancer les test
   ```sh
   make test
   ```
5. Enjoy 🎉
<!-- ROADMAP -->
## Structure

### Voici un exemple d'un arbre construit avec l'application :

<div align="center">
    <img src="/public/images/diagramme-Tree.drawio.png" alt="screen-short-diagramm" width="800" height="400">
</div>
