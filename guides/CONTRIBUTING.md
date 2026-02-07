# Mercator Contribution Policy

Thank you for your interest in Mercator and your willingness to contribute! This document describes our contribution policy to ensure that our project remains friendly, productive and enjoyable for all participants.

## Code of Conduct

Our project adheres to a [Code of Conduct](https://github.com/dbarzin/mercator/CODE_OF_CONDUCT.md) which defines expected and unacceptable behaviour. We expect all contributors to read and abide by this code.

## How to contribute

### 1. Questions and Discussions

Before you start working on a contribution, please check the existing [issues](https://github.com/dbarzin/mercator/issues) and discussions to see if your topic has already been covered. If you have any questions or would like to discuss an idea, use the [discussions](https://github.com/dbarzin/mercator/discussions) or open a new issue.

We recommend that you start by presenting a detailed description of your contribution before developing it, in order to gather opinions and feedback from maintainers and other contributors.

This approach ensures that the contribution is aligned with the project's objectives and can avoid unnecessary effort if the proposed contribution does not meet the project's current requirements.

### 2. Directory Fork

1. **Fork** the main directory from GitHub.

2. **Clone** your fork locally:
    ```sh
    git clone -b dev https://github.com/dbarzin/mercator.git
    ```

3. Configure the main repository as `remote upstream` :
    ```sh
    git remote add upstream https://github.com/dbarzin/mercator.git
    ```

### 3. Create a branch

Create a new branch for your work. Use a descriptive branch name:

```sh
git checkout -b branch-name
```

### 4. Making changes

Make your changes in the new branch. Make sure you :

- Follow the coding conventions of the project.
- Add or update tests if necessary.
- Check that all tests pass before submitting your contribution.

### 5. Updating your fork

Before submitting a pull request, make sure that your fork is up to date with the main repository:

```sh
git fetch upstream
git checkout dev
git merge upstream/branch-name
```

### 6. Making a Pull Request

1. Push your branch to your fork:
    ```sh
    git push origin branch-name
    ```
2. Open a pull request on GitHub from your branch to the dev branch of the project.

In the description of the pull request, include the following information:

- A clear description of what your contribution does.
- Any necessary contextual information (links to issues, etc.).
- A note on the tests added or modified.


### 7. Review and integration

Your pull request will be reviewed by the project maintainers. We ask you to be receptive to comments and to make any necessary changes. Once approved, your contribution will be merged into the main branch.

### 8. Credit

Our general philosophy on granting credit is as follows:

- We credit all contributors. When submitting a contribution, we expect contributors to provide accurate information about who contributed to the work.

- Only the project managers can determine who is credited.

- If a contribution includes substantial work by more than one author, we will give primary credit to the least experienced contributor.

- If we need to rewrite a contribution, we will work with the original author to make the necessary changes, as long as they respond in good faith and on time. In all cases, the original author will be credited first.

- All persons and organisations involved in the contribution will be credited in the same way.

- If more than one contributor submits similar work, the accepted author receives primary credit and all other contributors whose work has been rejected receive secondary credit.

## Licence

By contributing to Mercator, you agree that your contribution is under the same license as the main project. Mercator is licensed under GPLv3.

## Acknowledgements

We greatly appreciate all contributions and thank each contributor for their time and effort. Together, we can make Mercator better for everyone!
