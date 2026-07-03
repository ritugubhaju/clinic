# Clinic Management System

A role-based clinic admin panel for managing Doctors and Appointments, built with **Laravel 10**, **Vue 3**, **Inertia.js**, and **Pinia**.


### Installation

```bash
git clone <repo-url>
cd clinic-management

composer install

npm install

cp .env.example .env
php artisan key:generate
```

Configure `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in `.env`, then:

```bash
php artisan migrate

php artisan db:seed

npm run dev
```

Open `http://localhost/clinic-management/public` in your browser.

Default super admin:
- **Email:** admin@clinic.com
- **Password:** password

---
