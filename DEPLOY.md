# Deployment — my-workbase

## First Deploy
1. Upload project files to server (outside public_html)
2. Symlink public/ into domain document root
3. Copy .env.example → .env and fill all values
4. SSH into server, navigate to project root
5. composer install --no-dev --optimize-autoloader
6. php artisan key:generate
7. php artisan migrate --force
8. php artisan db:seed
9. php artisan storage:link
10. php artisan config:cache
11. php artisan route:cache
12. php artisan view:cache
13. chmod -R 775 storage bootstrap/cache
14. Add both cPanel cron jobs (see hosting-constraints.md)

## Subsequent Deploys
1. Run npm run build locally → upload public/build/ to server
2. Upload changed files
3. php artisan migrate --force
4. php artisan config:cache && php artisan route:cache && php artisan view:cache
5. chmod -R 775 storage bootstrap/cache

## Local Before Every Deploy
npm run build   ← run this locally, then upload public/build/
