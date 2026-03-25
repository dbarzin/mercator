# Politique de Contribution de Mercator

Nous vous remercions de l'intérêt que vous portez à Mercator et de votre volonté d'y contribuer ! Ce document décrit notre politique de contribution afin d'assurer que notre projet reste convivial, productif et agréable pour tous les participants.

## Code de Conduite

Notre projet adhère à un [Code de Conduite](https://github.com/dbarzin/mercator/CODE_OF_CONDUCT.fr.md) qui définit les comportements attendus et les comportements inacceptables. Nous attendons de tous les contributeurs qu'ils lisent et respectent ce code.

## Comment Contribuer

### 1. Questions et Discussions

Avant de commencer à travailler sur une contribution, veuillez consulter les [issues](https://github.com/dbarzin/mercator/issues) existantes et les discussions pour voir si votre sujet a déjà été abordé. Si vous avez des questions ou souhaitez discuter d'une idée, utilisez les [discussions](https://github.com/dbarzin/mercator/discussions) ou ouvrez une nouvelle issue.

Nous vous conseillons de commencer par présenter une description détaillée de votre contribution avant de la développer, afin de recueillir des avis et des retours des mainteneurs et des autres contributeurs.

Cette approche permet de s'assurer que la contribution est alignée avec les objectifs du projet et peut éviter des efforts inutiles si la contribution proposée ne correspond pas aux besoins actuels du projet.

### 2. Fork du Répertoire

1. **Fork** le répertoire principal depuis GitHub.

2. **Clone** votre fork localement :
    ```sh
    git clone -b dev https://github.com/dbarzin/mercator.git
    ```

3. Configurez le dépôt principal comme `remote upstream` :
    ```sh
    git remote add upstream https://github.com/dbarzin/mercator.git
    ```

### 3. Créer une Branche

Créez une nouvelle branche pour votre travail. Utilisez un nom de branche descriptif :

```sh
git checkout -b nom-de-la-branche
```

### 4. Faire des Modifications

Effectuez vos modifications dans la nouvelle branche. Assurez-vous de :

- Suivre les conventions de codage du projet.
- Ajouter ou mettre à jour les tests si nécessaire.
- Vérifier que tous les tests passent avant de soumettre votre contribution.

### 5. Mettre à Jour Votre Fork

Avant de soumettre une pull request, assurez-vous que votre fork est à jour avec le dépôt principal :

```sh
git fetch upstream
git checkout dev
git merge upstream/nom-de-la-branche
```

### 6. Faire une Pull Request

1. Poussez votre branche vers votre fork :
    ```sh
    git push origin nom-de-la-branche
    ```

2. Ouvrez une pull request sur GitHub depuis votre branche vers la branche dev du projet.

Dans la description de la pull request, veuillez inclure les informations suivantes :

- Une description claire de ce que fait votre contribution.
- Toute information contextuelle nécessaire (liens vers des issues, etc.).
- Une note sur les tests ajoutés ou modifiés.

### 7. Revue et Intégration

Votre pull request sera examinée par les mainteneurs du projet. Nous vous demandons d'être réceptif aux commentaires et de faire les modifications nécessaires. Une fois approuvée, votre contribution sera fusionnée dans la branche principale.

### 8. Crédit

Notre philosophie générale en matière d'octroi de crédits est la suivante :

- Nous citons le nom de tous les contributeurs. Lors de la soumission d'une contribution, nous attendons des contributeurs qu'ils fournissent des informations précises sur les personnes qui ont contribué au travail.

- Les responsables du projet sont les seuls à pouvoir déterminer qui est crédité.

- Si une contribution comprend un travail substantiel de plusieurs auteurs, nous attribuerons le crédit principal au contributeur le moins expérimenté.

- Si nous devons réécrire une contribution, nous collaborerons avec l'auteur original pour apporter les modifications nécessaires, pour autant qu'il réponde de bonne foi et dans les délais impartis. Dans tous les cas, c'est l'auteur original qui sera crédité en premier lieu.

- Toutes les personnes et organisations impliquées dans la contribution sont créditées de la même manière.

- Si plusieurs contributeurs soumettent des travaux similaires, l'auteur accepté reçoit le crédit principal et tous les autres contributeurs dont les travaux ont été rejetés reçoivent un crédit secondaire.

## Licence

En contribuant à Mercator, vous acceptez que votre contribution soit sous la même licence que le projet principal. Mercator est sous licence GPLv3.

## Remerciements

Nous apprécions grandement toutes les contributions et remercions chaque contributeur pour leur temps et leurs efforts. Ensemble, nous pouvons rendre Mercator meilleur pour tout le monde !
