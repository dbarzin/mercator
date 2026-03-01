# Cas d'usage

🇬🇧 [Read in English](/mercator/usecases)

Mercator permet d'aider à la mise en place d'un grand nombre de mesures de sécurité recommandées par la norme ISO 27002.
En suivant les cas d'utilisation décrits ci-dessous, vous pouvez mettre en place un processus de cartographie des
systèmes d'information solide et efficace qui pourra vous assister dans la gestion des risques de sécurité de l'
information auxquels fait face votre organisation.

### Inventaire des informations et autres actifs associés

La norme ISO 27002-5.9 recommande d'élaborer et de tenir à jour un inventaire des informations et autres actifs
associés, y compris leurs propriétaires.

Avec Mercator, il est possible de documenter les informations utilisées ainsi que les liens entre ces informations, les
applications, les processus et les bases de données où elles sont conservées.
Grâce au rapport des [applications par groupe applicatif](reports.fr.md) il est possible d'identifier les applications,
les bases de données, processus et macro-processus qui utilisent ces informations.

Vous pouvez vérifier grâce à ce rapport et en interrogeant les responsables métier que les informations documentées dans
Mercator sont correctes et à jour.

### Classification de l'information

La norme ISO 27002-5.12 recommande de classifier les informations conformément aux besoins de sécurité de l'information
de l'organisation, sur base des exigences de confidentialité, d'intégrité, de disponibilité et des besoins des parties
intéressées.

Avec Mercator, il est possible d’attribuer un niveau de sécurité aux informations en termes de confidentialité,
d'intégrité, de disponibilité et de traçabilité ainsi qu'aux bases de données, applications, processus et
macro-processus qui utilisent ces informations.

Afin de s'assurer que ces besoins sont conformes aux besoins de sécurité de l'information de l'organisation, il est
possible de générer un rapport d'[analyse des besoins de sécurité](reports.fr.md) qui dénormalise les besoins de
sécurité entre les informations, les bases de données, les applications, les processus et macro-processus qui utilisent
ces informations.

Vous pouvez vérifier si les besoins de sécurité sont correctement documentés pour chaque ligne de ce rapport.

### Planification et préparation de la gestion des incidents de sécurité de l'information

La norme ISO 27002-5.24 recommande que l'organisation planifie et prépare la gestion des incidents de sécurité de l'
information en procédant à la définition, à l'établissement et à la communication des processus, fonctions et
responsabilités liés à la gestion des incidents de sécurité de l'information .

Afin de pouvoir assurer une réponse rapide, efficace, cohérente et ordonnée aux incidents de sécurité de l'information,
il est important de pouvoir identifier rapidement lors d'un incident les processus, les fournisseurs et les responsables
concernés.

Mercator permet de disposer d'un inventaire complet de ces informations, avec
l'[outil de recherche texte libre](application.fr.md) qui se trouve en haut à gauche de l'écran, il est possible de
rapidement retrouver un asset, le ou les processus concernés, les fournisseurs et les responsables.

### Emplacement et protection du matériel

La norme ISO 27002-7.8 recommande de choisir un emplacement sécurisé pour le matériel et de le protéger.

Mercator permet pour chaque équipement physique (serveur, routeur, commutateur ..) de spécifier sa localisation et
d'extraire un inventaire des équipements par localisation.

Vous pouvez vérifier avec le rapport d'[inventaire de l'infrastructure physique](reports.fr.md) que cet inventaire
est à jour et qu'il n'existe pas d'équipement qui, soit ne se trouve pas dans l'inventaire, soit qui est dans inventaire
mais à un mauvais emplacement.

Il est recommandé de vérifier au minimum annuellement que les équipements physiques présents dans l'inventaire sont
effectivement présents dans les locaux où ils sont référencés et qu'il n'existe pas d'équipement qui ne soit pas présent
dans l'inventaire.

### Dimensionnement

La norme ISO 27002-8.6 recommande que les projections des besoins de dimensionnement futurs tiennent compte des nouveaux
besoins métier et systèmes, et des tendances actuelles et prévues en termes de capacité de traitement de l'information
de l'organisation.

Avec Mercator, il est possible de prendre à des intervalles réguliers via le rapport
de [configuration des serveurs logiques](reports.fr.md) une image des ressources consommées. En faisant une table
pivot avec un tableur, il est possible de faire des projections sur l'évolution des besoins de capacité de traitement de
l'information de l'organisation.

Vous pouvez vérifier que les besoins futurs de capacités de traitement de l'information de l'organisation sont couverts.

### Gestion des vulnérabilités techniques

La norme ISO 27002-8.8 recommande d'obtenir des informations sur les vulnérabilités techniques des systèmes d'
information utilisés, d'évaluer l'exposition de l'organisation à ces vulnérabilités et de prendre les mesures
appropriées.

Mercator permet d'identifier les vulnérabilité présentes dans le système d'information sur base du nom des applications
et sur base des CPE (Common Plateforme Enumeration) associés aux applications et équipements. Un rapport peut être
envoyé lors de la détection d'une CVE qui permet d'identifier l'application, sa criticité et son exposition.

Vous pouvez vérifier que ces alertes de détection sont analysées, et que des mesures préventives ou correctives sont
prises.

### Redondance des moyens de traitements de l'information

Le norme ISO 27002-8.14 recommande que les moyens de traitement de l'information soient mis en œuvre avec suffisamment
de redondance pour répondre aux exigences de disponibilité.

Avec Mercator, il est possible d'identifier avec le rapport des [applications par groupe applicatif](reports.md)
d'identifier les applications critiques en fonction de leur besoin de disponibilité, ainsi que les serveurs logiques et
physiques sur lesquels ces applications sont installées.

Vous pouvez vérifier pour ces applications qu'elles disposent de redondance suffisante pour répondre aux exigences de
disponibilité.
Une application critique devrait se trouver sur plus d'un équipement physique et selon le modèle de déploiement sur plus
d'un serveur logique.

### Cloisonnement des réseaux

Le norme ISO 27001-8.22 recommande que les groupes de services d'information, d'utilisateurs et de systèmes d'
information soient cloisonnés dans les réseaux de l'organisation.

Avec Mercator, il est possible de générer un rapport des VLAN qui identifie pour chaque VLAN les types d'équipement, de
serveurs logiques et d'applications qui se trouve dans ce VLAN.

Vous pouvez vérifier que chaque VLAN correspond à un groupe distinct de services d'information, d'utilisateurs ou de
systèmes d'information.

### Gestion des changements

La norme ISO 27002-8.32 recommande que les changements apportés aux moyens de traitement de l'information et aux
systèmes d'information soient soumis à des procédures de gestion des changements.

Avec Mercator il est possible d'explorer la cartographie du système d'information et d'ainsi identifier les dépendances
entre les objets de la cartographie. Cette analyse peut être faite soit au travers de l'explorateur, soit au travers des
différentes vues du système d'information ou directement avec le [rapport de cartographie](reports.md).

Vous pouvez identifier que l'impact d'un changement a correctement été identifié au moyen de la cartographie.

De plus, vous pouvez vérifier lorsque des changements ont eu lieu, que les éléments de la cartographie impliqués dans ce
changement ont été documenté au moyen du rapport de [suivi des changements](reports.md).
