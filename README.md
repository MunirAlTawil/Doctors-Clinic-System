# Doctors Clinic System

A clean and organized **clinic appointment booking system** built with **Laravel** for managing patients, doctors, and administrators in one platform.

## Overview

This project is designed to make clinic management easier by providing one system for:

- **Patients** to browse doctors and book appointments
- **Doctors** to manage availability and appointments
- **Admins** to manage users, specialties, pages, and reports

The system follows a role-based workflow and provides separate dashboards for each user type.

## Main Roles

### Patient
- Register and log in
- Browse doctors and specialties
- Book appointments
- View upcoming and past appointments

### Doctor
- Register and wait for approval
- Manage profile information
- Set availability and break times
- View appointments
- Update appointment status
- Track earnings and reports

### Admin
- Approve or reject doctor requests
- Manage users and specialties
- Manage clinic settings
- Manage public pages
- Monitor bookings and reports

## Main Features

- Authentication and role-based access
- Doctor approval workflow
- Appointment booking system
- Doctor availability management
- Notifications inside the system
- Admin dashboard and reporting
- Specialty management
- CMS-style page management
- Profile management

## Tech Stack

- **Backend:** PHP, Laravel
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Database:** MySQL
- **Build Tool:** Vite
- **Authentication:** Laravel Breeze

## Project Structure

```bash
app/                # Application logic
bootstrap/          # Framework bootstrap
config/             # Laravel configuration files
database/           # Migrations, seeders, factories
public/             # Public entry point
resources/views/    # Blade templates
routes/             # Web and auth routes
storage/            # Logs and uploads
tests/              # Automated tests
```

## How the System Works

1. The user visits the website.
2. The user registers as a **patient** or **doctor**.
3. Doctors stay in **pending approval** until approved by the admin.
4. Approved doctors manage their available working times.
5. Patients choose a doctor and book an appointment.
6. Admin manages users, specialties, appointments, and reports.

## Installation

### Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL

### Setup Steps

#### 1. Install dependencies
```bash
composer install
npm install
```

#### 2. Create environment file
```bash
copy .env.example .env
```

Then update your database settings inside `.env`.

#### 3. Generate application key
```bash
php artisan key:generate
```

#### 4. Run migrations
```bash
php artisan migrate
```

#### 5. Create storage link
```bash
php artisan storage:link
```

#### 6. Seed demo data (optional)
```bash
php artisan db:seed
```

#### 7. Run the frontend
```bash
npm run dev
```

#### 8. Start the application
```bash
php artisan serve
```

## Notes

- The system uses **MySQL** as the main database.
- Doctors must be approved by the admin before appearing in booking pages.
- Uploaded images and media are stored using Laravel public storage.
- The project is mainly **server-rendered** with Blade templates.

## Suggested Screenshots

You can add screenshots for:

- Home page
- Doctors page
- Booking page
- Patient dashboard
- Doctor dashboard
- Admin dashboard

## Future Improvements

- Online payment integration
- Email notifications
- Better doctor search and filtering
- Calendar-based schedule view
- Ratings and reviews

## Author

**Muhammed Munir Al Tawil**
