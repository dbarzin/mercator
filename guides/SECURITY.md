# Security Policy

## Supported Versions

The following versions of Mercator currently receive security updates:

| Version               | Supported              |
|-----------------------|------------------------|
| 2026.xx.xx (dernière) | ✅ Activement maintenue |

We strongly recommend always running the latest stable release to benefit from all security fixes.

---

## Reporting a Vulnerability

We take the security of Mercator seriously. If you believe you have found a security vulnerability, **please do not open
a public GitHub issue**.

### How to Report

Please report security vulnerabilities by one of the following methods:

- **GitHub Private Vulnerability Reporting** (preferred): Use
  the [Report a vulnerability](../../security/advisories/new) feature on this repository.
- **Email**: Send a detailed report to **info@sourcentis.com** with the subject line
  `[SECURITY] Mercator – <brief description>`.

Please include as much of the following information as possible to help us triage and reproduce the issue:

- Type of vulnerability (e.g. XSS, SQL injection, authentication bypass, IDOR, etc.)
- Full path of the affected source file(s) or URL(s)
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if available)
- Potential impact and attack scenario
- Suggested fix or mitigation (optional but appreciated)

### Response Timeline

| Step                                   | Target timeframe            |
|----------------------------------------|-----------------------------|
| Acknowledgement of your report         | Within **48 hours**         |
| Initial triage and severity assessment | Within **5 business days**  |
| Status update on the investigation     | Within **10 business days** |
| Patch release (critical/high severity) | Within **30 days**          |
| Patch release (medium/low severity)    | Within **90 days**          |

We will keep you informed throughout the process and credit you in the release notes unless you prefer to remain
anonymous.

---

## Disclosure Policy

Mercator follows a **coordinated vulnerability disclosure** model:

1. You report the vulnerability privately.
2. We confirm and assess the issue.
3. We develop and test a fix.
4. We release the patch and publish a [GitHub Security Advisory](../../security/advisories).
5. After the patch is publicly available, you are free to publish your findings.

We kindly ask that you refrain from public disclosure until a fix is available, or until we have mutually agreed on a
disclosure date.

---

## Security Scope

The following are considered **in scope** for vulnerability reports:

- Mercator core application (this repository)
- Enterprise modules distributed by Sourcentis
- Authentication and authorization mechanisms (including Keycloak / LDAP integration)
- API endpoints and data exposure
- XSS, CSRF, SQL injection, and injection vulnerabilities
- Sensitive data exposure
- Insecure direct object reference (IDOR)
- Insecure default configurations

The following are considered **out of scope**:

- Vulnerabilities in third-party dependencies (please report directly to the relevant project)
- Issues only exploitable by administrators with full access
- Denial-of-service attacks requiring large amounts of traffic
- Social engineering or phishing attacks
- Security issues in demo or development instances not controlled by Sourcentis

---

## Security Best Practices for Deployers

When deploying Mercator, we recommend the following:

- **Keep Mercator and its dependencies up to date** by regularly running `composer update` and `npm update`.
- **Use HTTPS** exclusively in production. Mercator supports Caddy or nginx as reverse proxies.
- **Restrict network access** to the application to authorized users and networks only.
- **Enable CSP headers** — Mercator ships with Content Security Policy support; ensure it is correctly configured.
- **Use strong, unique credentials** for database access, API tokens, and application secrets.
- **Enable audit logging** and monitor logs for suspicious activity.
- **Run Mercator under a dedicated, unprivileged user** account on the host system.
- **Review your `.env` file** and ensure it is not accessible from the web.
- **Run `php artisan key:generate`** to generate a unique `APP_KEY` for each deployment.

---

## Security Hall of Fame

We are grateful to the following individuals who have responsibly disclosed security vulnerabilities:

| Reporter                             | Vulnerability        | Year |
|--------------------------------------|----------------------|------|
| [@hadbuh](https://github.com/hadhub) | Stored XSS displayed | 2026 |

---

## Contact

For non-vulnerability security questions (hardening advice, compliance, architecture review), you may open
a [GitHub Discussion](../../discussions) or contact us at **info@sourcentis.com**.

