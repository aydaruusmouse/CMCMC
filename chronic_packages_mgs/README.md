# Chronic & Maternal Care Management System (CMCMS)

A comprehensive Laravel-based digital platform designed to manage chronic disease and maternity patients through a coordinated process involving agents, doctors, and patients.

## Features

### User Roles

1. **Admin**
   - Register agents and doctors
   - Manage packages, prices, and discounts
   - Monitor payments and overall reports
   - Approve or deactivate users
   - View all patients, bookings, and activities

2. **Agent**
   - Perform patient registration
   - Create bookings with doctor assignment
   - Manage package selection
   - Apply discounts (when approved)
   - Record payment confirmations (Zaad/Edahab)
   - Schedule patient appointments

3. **Doctor**
   - View assigned patients
   - Access patient health reports and trends
   - Record consultation notes and prescriptions
   - Add medical recommendations
   - View critical health alerts

4. **Patient**
   - Submit daily health data based on package type
   - View personal health dashboard
   - Access doctor's feedback and prescriptions
   - View appointment schedules
   - Track payment and package details

## Packages

- **Maternal Package** - $2
- **Chronic Package** - $3
  - Option A: Diabetes
  - Option B: Hypertension
  - Option C: Both Diabetes & Hypertension
- **Pediatric Package** - $2

## Technology Stack

- **Framework:** Laravel 12
- **Frontend:** Bootstrap 5
- **Database:** MySQL/SQLite
- **Styling:** Custom CSS with soft colors and modern design

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Copy environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in `.env`

6. Run migrations:
   ```bash
   php artisan migrate
   ```

7. Seed initial data:
   ```bash
   php artisan db:seed
   ```

8. Build assets:
   ```bash
   npm run build
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## Default Login Credentials

After seeding, you can login with:

- **Admin:** admin@cmcms.com / password
- **Agent:** agent@cmcms.com / password
- **Doctor:** doctor@cmcms.com / password
- **Patient:** patient@cmcms.com / password

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Agent/
│   │   ├── Doctor/
│   │   ├── Patient/
│   │   └── Auth/
│   └── Middleware/
├── Models/
├── Services/
└── ...

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── admin/
│   ├── agent/
│   ├── doctor/
│   ├── patient/
│   └── auth/
├── css/
└── js/

database/
├── migrations/
└── seeders/
```

## Key Features

- Role-based access control
- Patient registration and booking management
- Health data tracking (Diabetes, Hypertension, Maternal, Pediatric)
- Consultation management
- Payment processing (Zaad/Edahab)
- Appointment scheduling (2 per month)
- Discount management
- Comprehensive reporting

## Development

Run the development server with hot reload:
```bash
npm run dev
php artisan serve
```

## License

This project is proprietary software.