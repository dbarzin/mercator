# Politique de sécurité

## Versions prises en charge

Les versions suivantes de Mercator bénéficient actuellement des correctifs de sécurité :

| Version               | Prise en charge        |
|-----------------------|------------------------|
| 2026.xx.xx (dernière) | ✅ Activement maintenue |

Nous recommandons fortement de toujours utiliser la dernière version stable pour bénéficier de l'ensemble des correctifs
de sécurité.

---

## Signalement d'une vulnérabilité

Nous prenons la sécurité de Mercator très au sérieux. Si vous pensez avoir découvert une vulnérabilité de sécurité, *
*veuillez ne pas ouvrir un ticket public sur GitHub**.

### Comment signaler

Merci de nous transmettre votre rapport par l'un des moyens suivants :

- **Signalement privé GitHub** (recommandé) : utilisez la
  fonctionnalité [Signaler une vulnérabilité](../../security/advisories/new) de ce dépôt.
- **E-mail** : envoyez un rapport détaillé à **info@sourcentis.com** avec pour objet
  `[SÉCURITÉ] Mercator – <brève description>`.

Merci d'inclure autant d'informations que possible pour nous aider à qualifier et reproduire le problème :

- Type de vulnérabilité (ex. XSS, injection SQL, contournement d'authentification, IDOR, etc.)
- Chemin complet du ou des fichiers sources ou URL concernés
- Instructions pas-à-pas pour reproduire le problème
- Code de preuve de concept ou d'exploitation (si disponible)
- Impact potentiel et scénario d'attaque
- Correction ou atténuation suggérée (optionnel mais bienvenu)

### Délais de réponse

| Étape                                              | Délai cible              |
|----------------------------------------------------|--------------------------|
| Accusé de réception de votre signalement           | Sous **48 heures**       |
| Triage initial et évaluation de la sévérité        | Sous **5 jours ouvrés**  |
| Mise à jour sur l'avancement de l'investigation    | Sous **10 jours ouvrés** |
| Publication du correctif (sévérité critique/haute) | Sous **30 jours**        |
| Publication du correctif (sévérité moyenne/faible) | Sous **90 jours**        |

Nous vous tiendrons informé tout au long du processus et vous créditerons dans les notes de version, sauf si vous
souhaitez rester anonyme.

---

## Politique de divulgation

Mercator suit un modèle de **divulgation coordonnée des vulnérabilités** :

1. Vous signalez la vulnérabilité de manière confidentielle.
2. Nous confirmons et évaluons le problème.
3. Nous développons et testons un correctif.
4. Nous publions le correctif et diffusons un [Avis de sécurité GitHub](../../security/advisories).
5. Une fois le correctif disponible publiquement, vous êtes libre de publier vos conclusions.

Nous vous demandons de ne pas divulguer publiquement la vulnérabilité avant qu'un correctif soit disponible, ou avant
que nous ayons conjointement convenu d'une date de divulgation.

---

## Périmètre de sécurité

Les éléments suivants sont considérés **dans le périmètre** des signalements de vulnérabilités :

- L'application cœur de Mercator (ce dépôt)
- Les modules entreprise distribués par Sourcentis
- Les mécanismes d'authentification et d'autorisation (y compris l'intégration Keycloak / LDAP)
- Les points de terminaison API et l'exposition des données
- XSS, CSRF, injection SQL et autres vulnérabilités d'injection
- Exposition de données sensibles
- Référence directe à des objets non sécurisée (IDOR)
- Configurations par défaut non sécurisées

Les éléments suivants sont considérés **hors périmètre** :

- Les vulnérabilités dans les dépendances tierces (merci de les signaler directement au projet concerné)
- Les problèmes exploitables uniquement par des administrateurs disposant d'un accès complet
- Les attaques par déni de service nécessitant un trafic important
- Les attaques par ingénierie sociale ou hameçonnage
- Les problèmes de sécurité sur des instances de démonstration ou de développement non contrôlées par Sourcentis

---

## Bonnes pratiques de déploiement

Lors du déploiement de Mercator, nous recommandons les mesures suivantes :

- **Maintenez Mercator et ses dépendances à jour** en exécutant régulièrement `composer update` et `npm update`.
- **Utilisez exclusivement HTTPS** en production. Mercator supporte Caddy ou nginx comme reverse proxy.
- **Restreignez l'accès réseau** à l'application aux seuls utilisateurs et réseaux autorisés.
- **Activez les en-têtes CSP** — Mercator intègre la prise en charge de la Content Security Policy ; assurez-vous
  qu'elle est correctement configurée.
- **Utilisez des identifiants forts et uniques** pour l'accès à la base de données, les tokens API et les secrets
  applicatifs.
- **Activez la journalisation d'audit** et surveillez les journaux pour détecter toute activité suspecte.
- **Exécutez Mercator sous un compte utilisateur dédié et non privilégié** sur le système hôte.
- **Vérifiez votre fichier `.env`** et assurez-vous qu'il n'est pas accessible depuis le web.
- **Exécutez `php artisan key:generate`** pour générer un `APP_KEY` unique pour chaque déploiement.

---

## Tableau d'honneur sécurité

Nous remercions les personnes suivantes qui ont divulgué de manière responsable des vulnérabilités de sécurité :

| Rapporteur                           | Vulnérabilité        | Année |
|--------------------------------------|----------------------|-------|
| [@hadbuh](https://github.com/hadhub) | Stored XSS displayed | 2026  |

---

## Contact

Pour toute question de sécurité ne concernant pas une vulnérabilité (conseils de durcissement, conformité, revue
d'architecture), vous pouvez ouvrir une [Discussion GitHub](../../discussions) ou nous contacter à *
*info@sourcentis.com**.
