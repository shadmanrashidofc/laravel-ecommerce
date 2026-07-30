# Deployment Guide

## Prerequisites

- PHP 8.2+ with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD
- Composer 2.x
- Node.js 20+ and npm
- MySQL 8+ or compatible database
- Web server (Nginx or Apache)
- Queue worker (optional, for email/jobs)

## Production Checklist

Before deploying, ensure:

- [ ] `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] Strong, unique `APP_KEY` generated
- [ ] Database credentials configured
- [ ] Strong admin password set (change after seeding)
- [ ] HTTPS enabled with valid TLS certificate
- [ ] Storage directory is writable by the web server
- [ ] Queue worker is running (`php artisan queue:work`)
- [ ] Scheduler is configured (`* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1`)
- [ ] Mail driver configured for transactional emails

## Steps

### 1. Prepare the server

```bash
# Update package list and install PHP extensions
sudo apt update
sudo apt install php8.4 php8.4-cli php8.4-mbstring php8.4-xml php8.4-bcmath \
  php8.4-curl php8.4-gd php8.4-mysql php8.4-zip unzip nginx mysql-server

# Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# Install Node.js (if building assets on the server)
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install nodejs
```

### 2. Deploy the code

```bash
cd /var/www
git clone https://github.com/your-username/laravel-ecommerce-platform.git
cd laravel-ecommerce-platform

composer install --optimize-autoloader --no-dev
npm ci && npm run build

cp .env.example .env
php artisan key:generate
# Edit .env with production values
php artisan migrate --force
php artisan storage:link

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Configure web server

**Nginx example:**

```nginx
server {
    listen 443 ssl;
    server_name your-domain.com;

    root /var/www/laravel-ecommerce-platform/public;
    index index.php;

    ssl_certificate /etc/ssl/certs/your-cert.pem;
    ssl_certificate_key /etc/ssl/private/your-key.pem;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4. Set up queue worker

Using Supervisor:

```ini
[program:ecommerce-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel-ecommerce-platform/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/laravel-ecommerce-platform/storage/logs/queue-worker.log
```

### 5. Set up scheduler

```cron
* * * * * cd /var/www/laravel-ecommerce-platform && php artisan schedule:run >> /dev/null 2>&1
```

## Laravel Sail (Local Docker)

For local development with Docker:

```bash
# Install Sail
composer require laravel/sail --dev
php artisan sail:install

# Start services
./vendor/bin/sail up -d

# Run commands inside the container
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm run dev
```
