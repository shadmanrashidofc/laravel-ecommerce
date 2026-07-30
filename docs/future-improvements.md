# Future Improvements

Planned enhancements for this project, organized by priority.

## High Priority

### Payment Gateway Integration
- Integrate Stripe, PayPal, or a local payment provider
- Replace the placeholder `card`, `paypal`, `bank_transfer` payment methods with real processing
- Implement payment webhooks for status updates

### Email Notifications
- Order confirmation emails after successful checkout
- Order status update notifications (shipped, delivered, cancelled)
- Password reset and email verification emails (Breeze defaults exist but need mail configuration)

### Product Reviews & Ratings
- Review model and migration (product_id, user_id, rating, comment)
- Star rating UI on product detail pages
- Admin moderation for reviews

## Medium Priority

### Wishlist
- Wishlist model with user and product relationship
- Add/remove toggle on product cards and detail pages
- Separate wishlist page

### Coupon & Discount System
- Coupon model with code, discount type (percentage/fixed), usage limits, expiry
- Apply coupon at checkout
- Admin coupon management

### Shipping Management
- Shipping address fields (state, zip code, country)
- Shipping cost calculation (flat rate, free shipping thresholds)
- Multiple shipping methods

### Database-Backed Cart
- Replace session-based cart with a `carts` and `cart_items` table
- Persist cart across devices and browser sessions
- Enable cart recovery and abandoned cart emails

## Low Priority

### API Layer
- RESTful API with Laravel Sanctum for headless frontends
- API resource collections and transformers
- Rate limiting and versioning

### Advanced Search
- Full-text search with Laravel Scout / Meilisearch
- Faceted filtering (price range, brand, attributes)

### Multi-Vendor Support
- Vendor profiles and dashboards
- Commission tracking
- Marketplace admin features

### Localization (i18n)
- Language files for Blade templates
- Multi-currency support
- RTL layout support

### Performance
- Redis for cache and session (currently using database driver)
- Queue for email dispatch and order processing
- Database query optimization and indexing review
- Image optimization and CDN integration

## Technical Debt

- Extract business logic from controllers into dedicated Service classes
- Add Form Request validation for all controller actions
- Replace hardcoded enums (order status, payment method) with PHP enums
- Add comprehensive test coverage for all e-commerce domain logic
- Standardize named routes across all admin resource controllers
