# Quickstart: Employee Management Phase 2

## Prerequisites

- Ensure the Laravel environment is set up.
- Run `composer require maatwebsite/excel` to install Laravel Excel (if not already done).

## Running the Feature Locally

1. **Database Setup**
   ```bash
   php artisan migrate
   ```

2. **Storage Link**
   Make sure your local environment is aware of the storage. Note: The employee files are stored in `storage/app/private`, which does *not* get publicly linked via `storage:link`. They are served through an authenticated route instead.

3. **Running Tests**
   ```bash
   php artisan test --filter Employee
   ```

4. **Testing Excel Imports**
   - Create a sample `.xlsx` file with the following headers: `name`, `position`, `national_id`, `salary`, `hire_date`.
   - Log in as a client and upload it via the dashboard.
