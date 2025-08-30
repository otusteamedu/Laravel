# Laravel

### Project Installation Instructions 

1. Run `git clone -b OLukianova/hw20 https://github.com/otusteamedu/Laravel.git Laravel` to clone project
2. From the project directory run `cp .env.example .env`
3. Configure `.env` (set database access)

```
cd storage/
mkdir -p framework/{sessions,views,cache}
mkdir -p framework/cache/data
chmod -R 775 framework
cd ../
```

4. Run `/opt/php83/bin/php /usr/local/bin/composer update` from project folder
if composer return "killed" state - check your RAM (required about 2 gb)
if your server hasn't enough RAM you can execute `composer update` on local computer and copy generated `composer.lock` to server project directory
in that case run `/usr/local/bin/composer install` after copying `composer.lock` instead of `composer update`

6. `chmod -R 755 ../Laravel`
7. `/opt/php83/bin/php artisan key:generate`
8. `/opt/php83/bin/php artisan migrate`
9. `/opt/php83/bin/php /usr/local/bin/composer dump-autoload`
10. `/opt/php83/bin/php artisan migrate:refresh --seed`
11. `/opt/php83/bin/php artisan config:cache`
12. To set up jwt auth
...
16. To actualize settings
```
/opt/php83/bin/php artisan cache:clear
/opt/php83/bin/php artisan config:clear
/opt/php83/bin/php artisan config:cache
```
