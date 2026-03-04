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

Features include login, create/edit shortlinks, view stats, and redirects via `/s/<slug>`.
