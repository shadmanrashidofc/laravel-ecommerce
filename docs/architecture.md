# Architecture Overview

## MVC Structure

This project follows Laravel's Model-View-Controller architecture with a clean separation between frontend (customer-facing) and admin domains.

```
Request → Route → Middleware → Controller → Model → View → Response
```

## Request Flow

1. **HTTP Request** enters via `public/index.php`
2. **Routing** (`routes/web.php`, `routes/auth.php`) maps the URL to a controller
3. **Middleware** applies — `web` (session, CSRF), `auth` (authentication), `admin` (role check)
4. **Form Request** validation occurs before the controller method executes
5. **Controller** orchestrates the business logic, queries models, and returns a view
6. **Blade View** renders the HTML response

## Domain Separation

### Frontend (`app/Http/Controllers/Frontend/`)
- Public routes accessible without authentication
- Session-based shopping cart
- Guest checkout flow
- Product browsing and search

### Admin (`app/Http/Controllers/Admin/`)
- Protected by `auth` and `admin` middleware
- Full CRUD for products, categories, and variants
- Order lifecycle management
- Dashboard analytics

### Auth (`app/Http/Controllers/Auth/`)
- Standard Laravel Breeze authentication controllers
- Registration, login, password reset, email verification

## Key Design Decisions

### Session-Based Cart
The cart is stored in the PHP session rather than the database. This allows guest users to add items without an account. Trade-offs:
- **Pros:** No database writes for cart operations, works for guests, simple implementation
- **Cons:** Lost on session expiry, not shared across devices
- *Future:* A database-backed cart is recommended for production

### Product Snapshots in Orders
Order items store a snapshot of product name and variant details at the time of purchase. This ensures order history remains accurate even if products are later modified or deleted.

### Order Number Generation
Order numbers follow the format `ORD-YYYY-NNNNN` (e.g., `ORD-2026-00001`). Generation uses Laravel's cache lock to prevent duplicates under concurrent requests.

### Image Gallery
Products support a single thumbnail and multiple gallery images. Images are stored in `storage/app/public/products/` and served via the `public/storage` symlink.

## Database Relationships

```
Category ──1:N──> Product ──1:N──> ProductImage
                    │
                    ├──1:N──> ProductVariant
                    │
                    └──1:N──> OrderItem ──N:1──> Order ──N:1──> User
```
