# Laravel E-Commerce Platform

A full-featured e-commerce web application built with Laravel 12. Features a customer-facing storefront, session-based shopping cart, guest checkout, and a complete admin dashboard for managing products, categories, variants, orders, and inventory.

## Features

### Customer Frontend
- **Product catalog** — Browse products with search, category filtering, and sorting (price, name, newest)
- **Product detail pages** — View product descriptions, images, variants (size/color/SKU), pricing, and related products
- **Shopping cart** — Session-based cart with add, update, remove, and clear operations; real-time stock validation per variant
- **Checkout** — Streamlined checkout flow with guest (no login required) and authenticated checkout options
- **Order management** — Order confirmation page, order history in user profile, and one-click reorder
- **User dashboard** — Profile management (edit name, email, password), account deletion
- **Authentication** — Full Laravel Breeze authentication (register, login, password reset, email verification)

### Admin Panel
- **Dashboard** — Overview statistics (total products, categories, orders, revenue) with recent orders list
- **Product management** — Create, edit, and delete products; upload product thumbnail and gallery images; manage product status
- **Category management** — Full CRUD with active/inactive status; deletion blocked when products exist
- **Variant management** — Create and manage product variants (SKU, size, color, price, stock) per product
- **Order management** — View all orders with search and status filtering; update order status (pending → processing → shipped → delivered / cancelled)
- **Admin profile** — Update admin account details

## Screenshots

> Screenshots coming soon.

## Technologies

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 12 |
| **Language** | PHP 8.4+ |
| **Database** | MySQL 8+ (production), SQLite (testing) |
| **Frontend** | Blade templates, Tailwind CSS v3, Alpine.js |
| **Build tool** | Vite 7 with Laravel plugin |
| **Auth** | Laravel Breeze (Blade stack) |
| **Testing** | Pest v4 |
| **Linting** | Laravel Pint |
| **Dev environment** | Laravel Sail (Docker) |

## Installation

### Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 20+ and npm
- MySQL 8+ (or SQLite for local development)
- Git

### Setup

```bash
# Clone the repository
git clone https://github.com/your-username/laravel-ecommerce-platform.git
cd laravel-ecommerce-platform

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database and run migrations
php artisan migrate

# Seed demo data
php artisan db:seed

# Create storage link
php artisan storage:link

# Build frontend assets
npm run build
```

### Quick Setup

```bash
composer run setup
```

### Development Server

```bash
composer run dev
```

This runs four services concurrently:
- `php artisan serve` — HTTP server on port 8000
- `php artisan queue:listen` — Queue worker
- `php artisan pail` — Log watcher
- `npm run dev` — Vite HMR

## Configuration

Key environment variables (`.env`):

```
APP_NAME=E-Commerce
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
MAIL_MAILER=log
```

### Admin Access

After seeding (`php artisan db:seed`), an admin user is created:

- **Email:** `admin@example.com`
- **Password:** `password`

A test customer user is also created:

- **Email:** `test@example.com`
- **Password:** `password`

> **Important:** Change these credentials immediately after your first login.

## Usage

### URLs

| Path | Description |
|------|-------------|
| `/` | Homepage — featured products |
| `/products` | Product catalog with search & filters |
| `/cart` | Shopping cart |
| `/checkout` | Checkout page |
| `/login` | Customer login |
| `/register` | Customer registration |
| `/admin/dashboard` | Admin dashboard |
| `/admin/products` | Manage products |
| `/admin/categories` | Manage categories |
| `/admin/orders` | Manage orders |

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Auth/           # Auth controllers (Breeze)
│   │   │   └── Frontend/       # Customer-facing controllers
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/           # Form request validation
│   ├── Models/                 # Eloquent models
│   └── Providers/
├── bootstrap/                  # App bootstrap & cache
├── config/                     # Configuration files
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/                     # Web server root
├── resources/
│   ├── css/                    # Tailwind CSS entry point
│   ├── js/                     # Alpine.js & Axios setup
│   └── views/                  # Blade templates
│       ├── admin/              # Admin panel views
│       ├── auth/               # Auth views
│       ├── components/         # Reusable Blade components
│       ├── frontend/           # Customer-facing views
│       └── layouts/            # Layout templates
├── routes/
│   ├── web.php                 # Frontend & admin routes
│   ├── auth.php                # Auth routes
│   └── console.php             # Artisan commands
├── tests/                      # Pest tests
└── vite.config.js              # Vite configuration
```

## Database Schema

### Core Tables

- **`users`** — Customers and admins (`role`: customer / admin)
- **`categories`** — Product categories (`name`, `slug`, `status`)
- **`products`** — Products (`name`, `slug`, `description`, `price`, `discount_price`, `stock`, `status`)
- **`product_images`** — Product gallery images
- **`product_variants`** — Variants by size/color (`sku`, `price`, `stock`)
- **`orders`** — Customer orders with billing info and status
- **`order_items`** — Line items with product snapshots

### Order Status Flow

```
pending → processing → shipped → delivered
                     ↘ cancelled
```

### Payment Methods

Cash on Delivery (COD) is the default. The schema supports `card`, `paypal`, `bank_transfer` for future integration.

## Improving the Project

See [Future Improvements](docs/future-improvements.md) for planned enhancements including payment gateway integration, email notifications, product reviews, wishlist, coupons, and API support.

## Testing

```bash
# Run all tests
composer run test

# Or directly
php artisan test

# Run with coverage (requires Xdebug or PCOV)
php artisan test --coverage
```

Tests use SQLite in-memory database and refresh between runs.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines.

## Code of Conduct

Please review our [Code of Conduct](CODE_OF_CONDUCT.md) before participating.

## Security

If you discover a security vulnerability, please see [SECURITY.md](SECURITY.md) for reporting instructions.

## License

This project is open-source software licensed under the [MIT License](LICENSE).
