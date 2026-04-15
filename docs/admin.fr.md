# Administration

Les paramètres de configuration sont accessibles dans Mercator via :

> **Admin → Configuration → Paramètres** (`/admin/config/parameters`)

Contrairement aux variables d'environnement, ces paramètres sont stockés dans le fichier **config/mercator.php** et peuvent être modifiés à chaud par un administrateur, sans redémarrer l'application ni modifier le fichier `.env`.

La page des paramètres est organisée en quatre onglets.

---

## Général

Cet onglet regroupe les options de comportement générales de l'application.

### Infrastructure logique

| Paramètre | Description | Défaut |
|-----------|-------------|--------|
| **Authentification des besoins de sécurité** | Lorsque cette option est activée, les champs de besoin de sécurité sur les serveurs logiques nécessitent une authentification préalable avant d'être affichés. | `false` |

---

## Certificats

Gère la surveillance automatisée et les notifications d'expiration des certificats TLS/SSL.

| Paramètre | Description | Valeurs |
|-----------|-------------|---------|
| **Sujet du message** | Objet de l'e-mail d'alerte d'expiration. | Texte libre |
| **Expéditeur** | Adresse d'envoi des e-mails d'alerte de certificat. | Adresse e-mail |
| **Destinataires** | Adresse(s) de destination des e-mails d'alerte de certificat. | Adresse(s) e-mail |
| **Délai d'alerte** | Nombre de jours avant expiration à partir duquel la notification est envoyée. | 1, 7, 15, 30, 60 ou 90 jours |
| **Fréquence de vérification** | Intervalle auquel Mercator recherche les certificats arrivant à expiration. | Jamais / Quotidien / Hebdomadaire / Mensuel |
| **Regroupement** | Envoyer un e-mail consolidé pour tous les certificats expirants, ou un e-mail par certificat. | Un seul e-mail / Un par certificat |
| **Répétition des notifications** | Envoyer une seule notification par événement d'expiration, ou répéter jusqu'au renouvellement ou à la suppression du certificat. | Unique / Répétée |

Un bouton **Test** permet d'envoyer un e-mail de test avec la configuration en cours sans sauvegarder.

---

## CVE

Gère la recherche automatisée des vulnérabilités connues (CVE) affectant les éléments de l'inventaire.

| Paramètre | Description | Valeurs |
|-----------|-------------|---------|
| **Sujet du message** | Objet de l'e-mail d'alerte CVE. | Texte libre |
| **Expéditeur** | Adresse d'envoi des e-mails d'alerte CVE. | Adresse e-mail |
| **Destinataires** | Adresse(s) de destination des e-mails d'alerte CVE. | Adresse(s) e-mail |
| **Fréquence de vérification** | Intervalle auquel Mercator recherche les nouvelles CVE affectant l'inventaire. | Jamais / Quotidien / Hebdomadaire / Mensuel |
| **URL du CPE Guesser** | URL du service CPE Guesser utilisé pour inférer les identifiants CPE à partir des noms de logiciels. | URL |
| **URL du fournisseur CVE** | URL du fournisseur de données CVE. | URL |

### Services externes

**Fournisseur CVE** — Mercator interroge une instance [vulnerability-lookup](https://github.com/vulnerability-lookup/vulnerability-lookup) pour récupérer les CVE affectant les éléments de l'inventaire. Une instance publique est disponible à l'adresse `https://vulnerability.circl.lu`. Le service peut également être auto-hébergé.

**CPE Guesser** — Mercator utilise [cpe-guesser](https://github.com/vulnerability-lookup/cpe-guesser) pour inférer automatiquement les identifiants CPE à partir des noms de logiciels. Une instance publique est disponible à l'adresse `https://cpe-guesser.cve-search.org`. Le service peut également être auto-hébergé.

Trois boutons **Test** sont disponibles :

* **Test Mail** — envoie un e-mail de test avec la configuration en cours.
* **Test Provider** — vérifie la connectivité avec le fournisseur CVE configuré.
* **Test Guesser** — vérifie la connectivité avec le service CPE Guesser configuré.

---

## Documents

Cet onglet est **en lecture seule** et fournit une vue d'ensemble des documents stockés dans Mercator.

| Information | Description |
|-------------|-------------|
| **Nombre de documents** | Nombre total de documents enregistrés en base de données. |
| **Taille totale** | Taille cumulée de tous les documents stockés (affichée en unités lisibles). |

Un bouton **Vérifier** déclenche une vérification d'intégrité de tous les documents : Mercator compare le hachage SHA-256 enregistré pour chaque document avec le fichier présent sur le disque et affiche l'un des trois statuts suivants :

| Statut | Signification |
|--------|---------------|
| `OK` | Le fichier est présent et son hachage correspond à l'enregistrement en base. |
| `MISSING` | Le fichier est référencé en base mais introuvable sur le disque. |
| `HASH FAILS` | Le fichier est présent mais son hachage ne correspond pas — le fichier est peut-être corrompu ou altéré. |

---

## Tableau récapitulatif

| Onglet | Paramètre | Description | Défaut |
|--------|-----------|-------------|--------|
| Général | Authentification des besoins de sécurité | Requiert une authentification pour afficher les champs de besoin de sécurité | `false` |
| Certificats | Sujet du message | Objet des e-mails d'alerte d'expiration | — |
| Certificats | Expéditeur | Adresse d'envoi des alertes de certificat | — |
| Certificats | Destinataires | Adresse(s) de destination des alertes de certificat | — |
| Certificats | Délai d'alerte | Jours avant expiration pour déclencher la notification | 30 jours |
| Certificats | Fréquence de vérification | Intervalle de recherche des certificats expirants | Jamais |
| Certificats | Regroupement | Un e-mail consolidé ou un par certificat | Un seul e-mail |
| Certificats | Répétition des notifications | Notification unique ou répétée jusqu'à résolution | Unique |
| CVE | Sujet du message | Objet des e-mails d'alerte CVE | — |
| CVE | Expéditeur | Adresse d'envoi des alertes CVE | — |
| CVE | Destinataires | Adresse(s) de destination des alertes CVE | — |
| CVE | Fréquence de vérification | Intervalle de recherche des nouvelles CVE | Jamais |
| CPE | URL du CPE Guesser | Point d'accès du service [cpe-guesser](https://github.com/vulnerability-lookup/cpe-guesser) | — |
| CVE | URL du fournisseur CVE | Point d'accès du fournisseur [vulnerability-lookup](https://github.com/vulnerability-lookup/vulnerability-lookup) | — |
| Documents | — | Lecture seule : nombre de documents, taille totale, vérification d'intégrité | — |