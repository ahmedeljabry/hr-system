# Quickstart: QA, Polish & Deployment

## Deployment Workflow

This guide details the procedure for transitioning the local HR Management Platform codebase to the production deployment server.

### 1. Prerequisites (On Server)
- Install **PHP 8.3** with standard Laravel extensions (BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, Curl).
- Install **MySQL 8.0+** and create an empty database `hr_prod`.
- Install **Composer** and **Node.js** (for frontend asset bundling).

### 2. Application Setup
```bash
# 1. Clone repository
git clone <repo_url> /var/www/hr-platform
cd /var/www/hr-platform

# 2. Install Dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Environment configuration
cp .env.example .env
nano .env # (Update DB credentials, set APP_ENV=production, APP_DEBUG=false)
php artisan key:generate

# 4. Initialize Database
php artisan migrate --force
php artisan db:seed --class=SuperAdminSeeder --force
```

### 3. Application Optimization
```bash
# Cache views, configuration, routes, and events
php artisan optimize

# If you need to clear the caches later:
# php artisan optimize:clear
```

### 4. Permissions
Ensure Laravel storage and cache directories are writable by the web server (e.g. Nginx/Apache):
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 5. Final Verification Testing
Once running, verify components:
1. Hit the `/login` route. Check if the login page loads properly without error.
2. Login as the Super Admin (using details specified in the `SuperAdminSeeder`).
3. Create a dummy tenant (client) explicitly verifying the localization.
