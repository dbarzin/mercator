# Mercator Helm Chart
## TL;DR

```bash
helm install mercator .
```

## Introduction 

This chart bootstraps **Mercator** deployment on a [Kubernetes](https://kubernetes.io/) cluster using the [Helm](https://helm.sh/) package manager.

## Prerequisites
- Kubernetes 1.23+
- Helm 3.8.0+

## Persistence
The Mercator chart relies on the PostgreSQL chart persistence. This means that Mercator does not persist anything.

## Parameters
This part provides an overview of all the configurable parameters used in the `values.yaml` file for deploying the Mercator application using a Helm chart.

### Global parameters

| Parameter                                      | Description                                                                  | Default Value              |
|------------------------------------------------|------------------------------------------------------------------------------|----------------------------|
| **replicaCount**                               | Number of application replicas to deploy.                                    | `1`                        |
| **image.repository**                           | Docker image repository for the Mercator application.                        | `ghcr.io/dbarzin/mercator` |
| **image.pullPolicy**                           | Specifies when to pull the Docker image (`Always`, `IfNotPresent`, `Never`). | `IfNotPresent`             |
| **image.tag**                                  | Tag of the Docker image.                                                     | `"latest"`                 |
| **imagePullSecrets**                           | Secrets to use for pulling private Docker images.                            | `[]`                       |
| **serviceAccount.create**                      | Specifies whether a service account should be created.                       | `true`                     |
| **serviceAccount.automount**                   | Automatically mount the ServiceAccount’s API credentials.                    | `true`                     |
| **serviceAccount.annotations**                 | Annotations to add to the service account.                                   | `{}`                       |
| **serviceAccount.name**                        | Name of the service account to use or create.                                | `""`                       |
| **podAnnotations**                             | Annotations to add to the pod.                                               | `{}`                       |
| **podLabels**                                  | Labels to add to the pod.                                                    | `{}`                       |
| **podSecurityContext**                         | Security context for the pod.                                                | `{}`                       |
| **securityContext**                            | Security context for the container, e.g., user permissions.                  | `{}`                       |
| **service.type**                               | Kubernetes service type (`ClusterIP`, `NodePort`, `LoadBalancer`).           | `ClusterIP`                |
| **service.port**                               | Port exposed by the service.                                                 | `80`                       |
| **ingress.enabled**                            | Enable or disable ingress resource creation.                                 | `false`                    |
| **resources**                                  | Resource limits and requests for the application.                            | `{}`                       |
| **livenessProbe.httpGet.path**                 | HTTP path for liveness probe.                                                | `/`                        |
| **livenessProbe.httpGet.port**                 | Port for liveness probe.                                                     | `http`                     |
| **readinessProbe.httpGet.path**                | HTTP path for readiness probe.                                               | `/`                        |
| **readinessProbe.httpGet.port**                | Port for readiness probe.                                                    | `http`                     |
| **autoscaling.enabled**                        | Enable or disable horizontal pod autoscaling.                                | `false`                    |
| **autoscaling.minReplicas**                    | Minimum number of replicas for autoscaling.                                  | `1`                        |
| **autoscaling.maxReplicas**                    | Maximum number of replicas for autoscaling.                                  | `100`                      |
| **autoscaling.targetCPUUtilizationPercentage** | Target CPU utilization for autoscaling.                                      | `80`                       |
| **volumes**                                    | Additional volumes for the deployment.                                       | `[]`                       |
| **volumeMounts**                               | Additional volume mounts for the deployment.                                 | `[]`                       |
| **nodeSelector**                               | Node selector for scheduling pods.                                           | `{}`                       |
| **tolerations**                                | Tolerations for scheduling pods.                                             | `[]`                       |
| **affinity**                                   | Affinity rules for scheduling pods.                                          | `{}`                       |

### Mercator parameters

| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **debug**                                  | Enable debug mode for the application.                                       | `false`                      |
| **environment**                            | Environment to deploy (`development`, `production`).                         | `"development"`              |
| **key**                                    | Secret key for the application.                                              | `""`                         |
| **reverse_proxy**                          | URL for the reverse proxy used by the application.                           | `"http://localhost"`         |
| **use_demo_data**                          | Specify if demo data should be used (1 for true, 0 for false).               | `1`                          |

### Mercator LDAP parameters

| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **ldap.enabled**                           | Enable or disable LDAP integration.                                          | `false`                      |
| **ldap.type**                              | Type of LDAP server (e.g., Active Directory).                                | `"AD"`                       |
| **ldap.host**                              | LDAP server host.                                                            | `"127.0.0.1"`                |
| **ldap.username**                          | LDAP bind user credentials.                                                  | `"cn=user,dc=local,dc=com"`  |
| **ldap.password**                          | Password for the LDAP bind user.                                             | `"secret"`                   |
| **ldap.base_dn**                           | Base DN for LDAP queries.                                                    | `"dc=local,dc=com"`          |
| **ldap.service.number**                    | LDAP service port number.                                                    | `"389"`                      |
| **ldap.scope**                             | Scope for LDAP queries.                                                      | `"ou=Accounting,dc=com"`     |

### Mercator SMTP parameters
| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **smtp.host**                              | SMTP server host for sending emails.                                         | `"smtp.mailtrap.io"`         |
| **smtp.port**                              | SMTP server port.                                                            | `"25"`                       |
| **smtp.username**                          | SMTP username for authentication.                                            | `""`                         |
| **smtp.password**                          | SMTP password for authentication.                                            | `""`                         |

### Mercator AWS parameters
| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **aws.access_key_id**                      | AWS access key ID.                                                           | `""`                         |
| **aws.secret_access_key**                  | AWS secret access key.                                                       | `""`                         |
| **aws.default_region**                     | Default AWS region.                                                          | `"us-east-1"`                |
| **aws.bucket**                             | AWS S3 bucket name.                                                          | `""`                         |

### Mercator PUSHER parameters
| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **pusher.app_id**                          | Pusher app ID.                                                               | `""`                         |
| **pusher.app_key**                         | Pusher app key.                                                              | `""`                         |
| **pusher.app_secret**                      | Pusher app secret.                                                           | `""`                         |

### Mercator KEYCLOAK parameters
| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **keycloak.enabled**                       | Enable or disable Keycloak integration.                                      | `false`                      |
| **keycloak.client_id**                     | Keycloak client ID.                                                          | `""`                         |
| **keycloak.client_secret**                 | Keycloak client secret.                                                      | `""`                         |
| **keycloak.redirect_uri**                  | Redirect URI for Keycloak integration.                                       | `""`                         |
| **keycloak.base_url**                      | Base URL of Keycloak server.                                                 | `""`                         |
| **keycloak.realm**                         | Keycloak realm name.                                                         | `""`                         |

### Mercator database parameters

| Parameter                                  | Description                                                                  | Default Value                |
|--------------------------------------------|------------------------------------------------------------------------------|------------------------------|
| **secret.postgres.postgresPassword**       | PostgreSQL password for the application.                                     | `"2ù_-qeeYT21!8zA2~"`        |
| **postgresql.enabled**                     | Enable or disable PostgreSQL deployment.                                     | `true`                       |
| **postgresql.auth.username**               | PostgreSQL username for authentication.                                      | `"mercator_user"`            |
| **postgresql.auth.database**               | PostgreSQL database name.                                                    | `"mercator"`                 |
| **redis.enabled**                          | Enable or disable Redis deployment.                                          | `true`                       |
| **redis.auth.enabled**                     | Enable or disable Redis authentication.                                      | `true`                       |
| **redis.auth.password**                    | Password for Redis authentication.                                           
