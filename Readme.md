About the app:

- `public/index.html` is the main entry point set the root to `public`
- run `composer install` (requires Composer: https://getcomposer.org)
- run `php migrate.php` inside `services/products` folder

Using docker:

- run `docker compose up -d`
- run `docker compose exec app bash`
- cd to `services/products` folder and run `composer install` and `php migrate.php`
- the app runs in `http://localhost:80/`