# Data Model: QA, Polish & Deployment

**Feature Branch:** `008-qa-polish-deployment`

No new database models, migrations, or Eloquent schemas are required for this phase.

## Environment configuration

As part of the deployment, the target infrastructure will require establishing production credentials. A sample schema of the production environment expectations is detailed here:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://target-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hr_platform_prod
DB_USERNAME=forge
DB_PASSWORD=secret

CACHE_STORE=file
SESSION_DRIVER=database
QUEUE_CONNECTION=sync
```

## Security Requirements
- Ensure `APP_KEY` is freshly generated and correctly copied onto the server.
- Ensure `APP_DEBUG` is definitively set to `false`.
