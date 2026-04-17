# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Mercator** is an open-source web application for information system mapping and governance, aligned with ANSSI's cartography guide. It manages entities such as applications, servers, networks, flows, physical/logical infrastructure, GDPR data processing, and security controls.

## Development Commands

### Backend (PHP/Laravel)

```bash
# Install dependencies
composer install

# Run database migrations
php artisan migrate

# Seed initial data (admin user, roles, permissions)
php artisan db:seed

# Seed demo data
php artisan db:seed --class=DemoUsersTableSeeder  # and other Demo* seeders

# Run all tests
php artisan test
# or
./vendor/bin/pest

# Run a specific test file
./vendor/bin/pest tests/Feature/Controller/ActivityControllerTest.php

# Run tests by group
./vendor/bin/pest --group=api
./vendor/bin/pest --group=controller

# Static analysis (level 5)
./vendor/bin/phpstan analyse

# Code style fixing
./vendor/bin/pint
```

### Frontend (Node/Vite)

```bash
npm install
npm run dev      # Start Vite dev server on localhost:5173
npm run build    # Build assets for production (reads version.txt)
```

### Full build (version bump + frontend + Docker)

```bash
./build.sh
```

## Architecture

### Tech Stack

- **Backend:** PHP 8.4, Laravel 11, Laravel Passport (OAuth2 API), Laravel Sanctum
- **Frontend:** Bootstrap 5, jQuery, DataTables, Chart.js, MaxGraph (for cartography diagrams), D3/Graphviz, Vis-Network
- **TypeScript:** `resources/js/map.show.ts` and `resources/js/map.edit.ts` for interactive maps
- **Auth integrations:** LDAP/Active Directory (`ldaprecord-laravel`), Keycloak (Socialite)
- **Data export:** Excel/CSV (Maatwebsite Excel), Word reports (PHPWord)

### Core Package

All domain models live in the `sourcentis/mercator-core` Composer package (`Mercator\Core\Models\*`). The app models in `app/Models/` are thin extensions (e.g., `User` extends `Mercator\Core\Models\User` to add LDAP traits). Do not create new Eloquent models directly in `app/Models/` for domain entities — extend from the core package.

### Controller Structure

- `app/Http/Controllers/Admin/` — ~72 CRUD controllers for every entity (Activity, Actor, Application, Bay, Building, Certificate, Cluster, LogicalServer, Network, Router, etc.)
- `app/Http/Controllers/API/` — ~58 REST API controllers extending `APIController` (which uses Spatie QueryBuilder for auto-filtering/includes via reflection)
- `app/Http/Controllers/Report/` — Report generation controllers (Word documents, views, lists: `CartographyController`, `ReportController`, `EcosystemView`, `LogicalInfrastructureView`, etc.)
- `app/Http/Controllers/Auth/` — Authentication (local, LDAP, Keycloak/SSO)

### Authorization

Uses Laravel Gate. Every controller action starts with `abort_if(Gate::denies('permission_name'), 403)`. Roles: **Admin**, **User**, **Auditor**, **Cartographer**. Permissions are seeded via `PermissionsTableSeeder` and `PermissionRoleTableSeeder`.

### API

- REST JSON API protected by `api.protected` middleware (Passport OAuth2 or Sanctum)
- `APIController` base class auto-discovers Eloquent relations via reflection to build allowed includes/filters
- Supports mass-store, mass-update, mass-destroy operations
- API routes in `routes/api.php`

### Frontend Assets

Vite builds from `vite.config.mjs`. Key entry points:
- `resources/js/app.js` — Bootstrap + global JS
- `resources/js/map.show.ts` / `map.edit.ts` — MaxGraph-based cartography viewer/editor (TypeScript)
- `resources/js/graphviz.js` — D3/Graphviz network diagrams
- `resources/js/vis-network.js` — Vis.js dependency graphs
- Chart files for dashboard widgets

### Testing

Tests use **Pest** (PHPUnit wrapper). Test groups are configured in `tests/Pest.php`:
- `tests/Feature/Api/` — API endpoint tests (tagged `api`)
- `tests/Feature/Controller/` — Admin controller tests (tagged `controller`)
- `tests/Feature/View/` — View rendering tests
- `tests/Feature/Console/` — Console command tests
- `tests/Unit/` — Unit tests

The test environment uses `RefreshDatabase`. DB connection is not forced to SQLite in `phpunit.xml` by default — configure your `.env.testing` accordingly.

### Configuration

- `config/mercator-config.php` — App-specific config: CVE provider URL, security need authentication, mail settings
- `config/ldap.php` — LDAP/AD connection settings
- `config/panel.php` — UI panel configuration

### Key Services

- `app/Services/ExcelExportHelper.php` — Shared Excel export logic
- `app/Services/MonarcExportService.php` — Export to MONARC risk management format
- `app/Services/MospService.php` — MOSP (security policy) integration
- `app/Services/IconUploadService.php` — Icon management for entities
- `app/Services/KeycloakProviderService.php` — Keycloak SSO

### Data Model Layers (ANSSI mapping layers)

The IS cartography follows ANSSI layers:
1. **Ecosystem** — Entities, Actors, External connected entities
2. **Business** — Macro-processes, Processes, Activities, Operations, Data processing (GDPR)
3. **Application** — Application blocks, Applications (MApplication), Application services/modules
4. **Administration** — AD Forests, Domains, Annuaires (LDAP directories)
5. **Logical infrastructure** — Logical servers, Clusters, Containers, Databases, Certificates, Flows
6. **Physical infrastructure** — Sites, Buildings, Bays, Physical servers/routers/switches, VLANs, LANs, WANs, Security devices
