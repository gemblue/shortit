Simple PHP Link Shortener

Quick start:

1. Install dependencies (requires Composer):

```bash
composer install
```

2. Copy `.env.example` to `.env` and adjust credentials if needed:

```bash
cp .env.example .env
```

   - you can also set `SHOW_ERRORS=true` in `.env` to display all PHP errors during development.

3. Start built-in PHP server from the project root:

```bash
php -S localhost:8000
```

4. Open http://localhost:8000/admin/login to access admin.

Database file: `data/shortit.db` (created automatically on first run).

⚠️ **Production notes**

*MySQL*  

This application now uses MySQL exclusively. Make sure to set environment variables for connection:

```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=shortit
DB_USER=root
DB_PASS=secret
```

Then import the schema into your database:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS shortit;"
mysql -u root -p shortit < mysql-schema.sql
```

The code will automatically create missing tables when first accessed, so the import step is mainly for initial provisioning or when upgrading.

If you previously used SQLite, you can migrate the data by exporting rows and loading them into MySQL using CSV or SQL dump; review the `mysql-schema.sql` file for the table layout.

Once MySQL is in use, the PHP application runs entirely via `mysqli`.

The PHP code uses `mysqli` for MySQL connections.

Features include login, create/edit shortlinks, view stats, and redirects via `/s/<slug>`.
