## Vues

La cartographie est composée de trois vues allant progressivement du métier vers la technique, elles-mêmes déclinées en
vues :

### Vision RGPD

La vue du RGPD permet de maintenir le registre des traitements et de faire le lien avec les processus, informations,
applications et mesures de sécurité mises en place.

### Vision métier

La vue de l’écosystème présente les différentes entités ou systèmes avec lesquels le SI interagit pour remplir sa
fonction.

La vue métier du système d’information représente le SI à travers ses processus et informations principales, qui sont
les valeurs métier au sens de la méthode d’appréciation des risques EBIOS Risk Manager.

### Vision applicative

La vue des applications décrit les composants logiciels du système d’information, les services qu’ils offrent et les
flux de données entre eux.

La vue des flux applicatifs décrits les flux d’information entre les différentes applications, services, modules et
bases de données.

### Vision administrative

La vue de l’administration répertorie les périmètres et les niveaux de privilèges des utilisateurs et des
administrateurs.

### Vision logique

La vue des infrastructures logiques illustre le cloisonnement logique des réseaux, notamment par la définition des
plages d’adresses IP, des VLAN et des fonctions de filtrage et routage ;

### Vision infrastructure

La vue des infrastructures physiques décrit les équipements physiques qui composent le système d’information ou utilisés
par celui-ci.

## Niveaux de maturité

Les niveaux de maturité représentent le pourcentage d'exhaustivité de la cartographie. C'est un indicateur de l'effort
restant à fournir pour atteindre une cartographie complète, conformément aux recommandations
du [guide de cartographie du système d'information de l'ANSSI](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/).

Cette maturité est divisée en trois niveaux :

- La **granularité minimale de niveau 1** qui contient les informations indispensables à la cartographie ;
- La **granularité intermédiaire de niveau 2** qui contient les informations importantes à la cartographie ;
- La **granularité fine de niveau 3** qui contient les informations utiles à la gestion de la sécurité du système
  d'information.

[<img src="/mercator/images/maturity.png" width="600">](images/maturity.png)

### Signification des champs marqués d'un cardinal

Certains libellés de champs sont suivis d'un symbole **#** de couleur orange. Ces marqueurs indiquent que le champ
contribue au calcul du niveau de maturité de la cartographie, selon le niveau concerné :

| Marqueur | Signification                                                         |
|----------|-----------------------------------------------------------------------|
| `#`      | Champ contribuant au niveau de maturité 1 — granularité minimale      |
| `##`     | Champ contribuant au niveau de maturité 2 — granularité intermédiaire |
| `###`    | Champ contribuant au niveau de maturité 3 — granularité fine          |

### Calcul du niveau de maturité

Un élément est considéré comme **conforme** lorsque tous les champs marqués `#` correspondant au niveau visé sont
renseignés et que les liens attendus avec d'autres éléments de la cartographie sont établis.

Un élément est considéré comme **non conforme** lorsque :

- un champ marqué `#` est vide ou non renseigné (ex. : absence de description ou de responsable),
- un lien attendu vers un autre élément est manquant (ex. : une application qui ne soutient aucun processus métier, un
  serveur non rattaché à une application).

Le niveau de maturité est calculé selon la formule suivante :

```
Niveau de maturité (%) = (Nombre d'éléments conformes / Nombre total d'éléments) × 100
```

### Identification visuelle des éléments non conformes

Dans les listes, les entrées **non conformes** sont surlignées en **jaune**, signalant qu'au moins un champ marqué `#`
n'a pas été renseigné. Cela permet d'identifier rapidement les éléments qui nécessitent une attention particulière pour
progresser vers une cartographie complète.

## Besoins de sécurité

Les besoins de sécurité de l'information sont exprimés en termes de confidentialité, intégrité, disponibilité et
traçabilité avec l'échelle suivante :

| Niveau | Description  |                 Couleur                  |
|:------:|:------------:|:----------------------------------------:|
|   0    | Insignifiant |                  Blanc                   |
|   1    |    Faible    |  <span style="color:green">Vert</span>   |
|   2    |    Moyen     | <span style="color:yellow;">Jaune</span> |
|   3    |     Fort     | <span style="color:orange">Orange</span> |
|   4    |  Très fort   |   <span style="color:red">Rouge</span>   |
    



