# Ralph Agent Configuration - Martian Republic

## Build Instructions

```bash
# Clear and rebuild Laravel caches
sudo php /home/martianrepublic/artisan config:cache
sudo php /home/martianrepublic/artisan route:cache
sudo php /home/martianrepublic/artisan view:cache
```

## Test Instructions

```bash
# Run Laravel tests
cd /home/martianrepublic && sudo php artisan test

# Verify site is responding
curl -sk -o /dev/null -w "%{http_code}" -H "Host: martianrepublic.org" https://localhost/

# Verify security: PHP blocked in assets
curl -sk -o /dev/null -w "%{http_code}" -H "Host: martianrepublic.org" https://localhost/assets/index.php
# Expected: 403
```

## Run Instructions

```bash
# The site runs on Apache (always running as a systemd service)
sudo systemctl status apache2

# Restart Apache if needed
sudo systemctl restart apache2

# Check Laravel logs
sudo tail -50 /home/martianrepublic/storage/logs/laravel.log

# Check Apache error logs
sudo tail -50 /var/log/apache2/error.log
```

## Environment
- PHP 8.2 (cli: `php` or `sudo php`)
- Laravel 11 with Livewire
- MySQL on localhost:3306 (user: serverusr)
- Apache 2.4 with mod_php
- IPFS node on localhost:5001
- Files owned by root, use `sudo` for modifications
- Web server runs as www-data

## Key Paths
- App root: `/home/martianrepublic/`
- Public: `/home/martianrepublic/public/`
- Routes: `/home/martianrepublic/routes/`
- Controllers: `/home/martianrepublic/app/Http/Controllers/`
- Views: `/home/martianrepublic/resources/views/`
- Config: `/home/martianrepublic/config/`
- Storage: `/home/martianrepublic/storage/`

## Notes
- Use `sudo` when editing files (owned by root)
- After route changes: `sudo php /home/martianrepublic/artisan route:cache`
- After config changes: `sudo php /home/martianrepublic/artisan config:cache`
- After view changes: `sudo php /home/martianrepublic/artisan view:cache`
