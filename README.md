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

*SQLite (default)*

- If you are still using SQLite, ensure the `data/` directory exists and is writable by the web server user. If not, create it yourself:

  ```bash
  mkdir -p data
  touch data/shortit.db
  chown www-data:www-data data data/shortit.db  # or your PHP user
  chmod 664 data/shortit.db
  ```

  Without write permission, PHP will throw an exception which, with `SHOW_ERRORS=false`, results in a blank 500 response.

- The SQLite PDO driver must be enabled for **the PHP process serving the app**. Verify via `phpinfo()` or `PDO::getAvailableDrivers()`; the list must include `sqlite`.

- You can pre-create the SQLite file if desired; the app will also create it automatically when it can write. Once you have migrated to MySQL and updated `DB_DRIVER`, you can safely delete `data/shortit.db` (as you just did) and the `data/` folder is no longer used.

*MySQL (alternate)*

To run against MySQL instead of SQLite, set environment variables
(`DB_DRIVER=mysql` plus connection info) and the code will switch. A simple
MySQL schema, suitable for importing on production, is included in
`mysql-schema.sql` (see below). After importing, the application will
create any missing tables when first accessed.

If you already have data in `data/shortit.db` and want to migrate it, you
can dump and convert it with tools like `sqlite3` and `mysql`:

```bash
# export sqlite data as INSERT statements
sqlite3 data/shortit.db \
  ".output /tmp/shortit.sql" \
  ".dump links" \
  ".dump clicks"

# the dump may need minor editing (change AUTOINCREMENT syntax, etc.)
# or use the mysql-schema.sql as a base and load data with csv export:
sqlite3 -header -csv data/shortit.db "SELECT * FROM links;" > links.csv
mysql -u root shortit -e "LOAD DATA LOCAL INFILE 'links.csv' INTO TABLE links FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS;"
```

The above gives you an idea; for a clean production import just run:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS shortit;"
mysql -u root shortit < mysql-schema.sql
# then follow migration commands above if necessary
```

Once MySQL is in use, the PHP application will run completely via `mysqli`.

The PHP code uses `mysqli` for MySQL connections.

Features include login, create/edit shortlinks, view stats, and redirects via `/s/<slug>`.
