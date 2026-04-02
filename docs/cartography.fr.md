# Cartographie / Vues

La cartographie du système d'information est organisée en plusieurs vues complémentaires, allant progressivement du
métier vers la technique. Plutôt que de gérer des inventaires cloisonnés, Mercator place l'ensemble des objets dans un
graphe de dépendances : on ne gère plus des listes isolées, mais des relations entre objets. On peut ainsi identifier
les chemins critiques, repérer les fournisseurs stratégiques, et comprendre ce qui dépend de quoi.

### Vision RGPD

La vue RGPD permet de maintenir le registre des traitements et de faire le lien avec les processus, informations,
applications et mesures de sécurité mises en place.

### Vision métier

La vue de l'écosystème présente les différentes entités — fournisseurs, partenaires, sous-parties de l'organisation —
avec lesquelles le SI interagit, ainsi que les relations qu'elles entretiennent : contrats de support, partenariats,
adhésions.

La vue métier du système d'information représente le SI à travers ses macroprocessus, processus, activités, opérations,
acteurs et informations manipulées. Ces éléments constituent les valeurs métier au sens de la méthode d'appréciation des
risques EBIOS Risk Manager.

### Vision applicative

La vue des applications décrit les composants logiciels du système d'information : applications regroupées en blocs
applicatifs, bases de données, services et modules, ainsi que les liens avec les processus métier qu'elles supportent.

La vue des flux applicatifs décrit les échanges d'information entre les différentes applications, services, modules et
bases de données.

### Vision administrative

La vue de l'administration répertorie les périmètres et les niveaux de privilèges des utilisateurs et des
administrateurs, ainsi que les annuaires qui les référencent.

### Vision logique

La vue des infrastructures logiques illustre le cloisonnement logique des réseaux : plages d'adresses IP, VLANs,
fonctions de filtrage et de routage. Elle permet notamment de comparer ce que le système est *capable* de faire avec ce
qu'il était *autorisé* à faire.

### Vision infrastructure

La vue des infrastructures physiques décrit les équipements physiques qui composent le système d'information : serveurs,
baies, salles, bâtiments et sites.


