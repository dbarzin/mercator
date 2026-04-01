# Application

🇬🇧 [Read in English](/mercator/application)

*📑 Note: Vous pouvez cliquer sur les images pour les agrandir*

[<img src="/mercator/fr/images/homepage.png" width="700">](images/homepage.fr.png)

---

### Page principale

La page principale est divisée en trois parties :

* Les niveaux de conformité

  [<img src="/mercator/fr/images/maturity.png" width="700">](images/maturity.fr.png)

* La répartition des objets de la cartographie par domaine

  [<img src="/mercator/fr/images/repartition.png" width="700">](images/repartition.fr.png)

* La carte proportionnelle globale des objets de la cartographie

  [<img src="/mercator/fr/images/treemap.png" width="700">](images/treemap.fr.png)

Chaque élément est sélectionnable et donne accès à la liste des objets de la cartographie sélectionnés.

---

### Les menus
  [<img src="/mercator/fr/images/menu.png" width="500">](images/menu.fr.png)

* 📕 Le menu latéral gauche permet d’accéder:
    * au tableau de bord
    * au travers des vues, aux différents écrans de gestion des objets de la cartographie
    * aux écrans de gestion des rôles et des utilisateurs
    * au bouton de déconnexion
* 📘 Le menu supérieur donne accès :
    * ☰ pour réduire le menu latéral gauche
    * Aux [vues](./cartography.fr.md)
    * Aux préférences
    * Aux outils (exploration, dépendances, rapports...)
    * A l'aide en ligne (documentation, guide...)
* 📒 Outil de recherche

---

### Exploration de la cartographie

Il est possible d'explorer la cartographie. 
Cette fonction est disponible via le menu supérieur **"outils"**.

#### Filtres
  [<img src="/mercator/fr/images/expfilters.png" width="700">](images/expfilters.fr.png)

- Le menu déroulant filtre permet de limiter l'exploration à un ou plusieurs domaines.
- Le champ "Objet" permet de sélectionner un élément de la cartographie et de l'ajouter à l'exploration en cliquant sur `ajouter`.

---

#### Affinage
  [<img src="/mercator/fr/images/expdepth.png" width="700">](images/expdepth.fr.png)  

- Le bouton "Recommencer" permet de réinitialiser la recherche.
- La profondeur et la direction permettent de peaufiner l'affichage des niveaux d'explorations souhaités.
- Le bouton "Supprimer" permet de retirer l'élément sélectionné de l'exploration de la cartographie.
- le bouton "déployer" lance l'exploration uniquement si un objet a été ajouté.

---

#### Sauvegarde et rendu
  [<img src="/mercator/fr/images/expsave.png" width="500">](images/expsave.fr.png)

- Vous pouvez sauvegarder l'image générée en cliquant sur le bouton qui se situe sous la visualisation
- Vous pouvez changer le rendu dans le menu qui se situe sous la visualisation.

---

#### Résultat
  [<img src="/mercator/fr/images/explore.png" width="700">](images/explore.fr.png)

Un double click sur un objet permet d'ajouter toutes ses connexions dans la visualisation.

💡 *Pour obtenir plus d'information, veuillez lire la page [Fonctionnalité Exploration](./exploration.fr.md)*

---

### Analyse des Dépendances

Mercator permet d'analyser le graphe de dépendances de n'importe quel objet du système d'information, en ammont ou en aval et ce sur plusieurs niveaux. Cet écran d'analyse est accessible depuis le menu supérieur **"outils"**.

#### Filtres
  [<img src="/mercator/fr/images/depfilters.png" width="700">](images/depfilters.fr.png)

- Les menu déroulants de filtres (Type ou attributs) permettent de restreindre la recherche et l'affichage à un ou plusieurs domaines ou attributs.
- Le champ "Objet" permet de sélectionner un élément de la cartographie et de le définir comme point de recherche de dépendances.
- 📢 *Note: l'affichage des dépendances, est restreinte par les filtres.*

---

#### Affinage
  [<img src="/mercator/fr/images/depdepth.png" width="700">](images/depdepth.fr.png)

- Le bouton "Recommencer" permet de réinitialiser la recherche.
- La profondeur et la direction permettent de peaufiner l'affichage des dépendances souhaitées.

---

#### Rendu
  [<img src="/mercator/fr/images/depsave.png" width="500">](images/depsave.fr.png)

- Vous pouvez télécharger l'image générée en cliquant sur le bouton qui se situe sous la visualisation
- Vous pouvez changer le rendu dans le menu qui se situe sous la visualisation.

---

#### Résultat
  [<img src="/mercator/fr/images/dependency.png" width="700">](images/dependency.fr.png)

Un double click sur un objet quitte les dépendances et affiche toutes ses informations.

💡 *Pour obtenir plus d'information, veuillez lire la page [Fonctionnalité Dependances](./dependency.fr.md)*