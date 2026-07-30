# Testing

This project uses [Pest v4](https://pestphp.com/) for testing.

## Running Tests

```bash
# Run all tests
php artisan test

# Run a specific file
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run a specific method
php artisan test --filter=test_users_can_authenticate

# With coverage (requires Xdebug or PCOV)
php artisan test --coverage

# Run with parallel processing
php artisan test --parallel
```

## Test Configuration

Tests are configured in `phpunit.xml`:

- **Database:** SQLite in-memory (`:memory:`) — no external database needed
- **Cache:** Array driver
- **Queue:** Sync driver (jobs execute immediately)
- **Session:** Array driver

## Test Structure

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── AuthenticationTest.php    # Login/logout
│   │   ├── EmailVerificationTest.php # Email verification flow
│   │   ├── PasswordConfirmationTest.php
│   │   ├── PasswordResetTest.php
│   │   ├── PasswordUpdateTest.php
│   │   └── RegistrationTest.php      # Registration flow
│   ├── ExampleTest.php               # Smoke test
│   └── ProfileTest.php               # Profile CRUD
├── Unit/
│   └── ExampleTest.php               # Basic unit test
├── Pest.php                          # Test case configuration
└── TestCase.php                      # Base test case
```

## Writing Tests

Reference the existing tests for patterns:

```php
<?php

use App\Models\Product;

test('product listing shows active products only', function () {
    // Arrange
    Product::factory()->create(['status' => 'active', 'name' => 'Visible Product']);
    Product::factory()->create(['status' => 'inactive', 'name' => 'Hidden Product']);

    // Act
    $response = $this->get(route('products'));

    // Assert
    $response->assertOk();
    $response->assertSee('Visible Product');
    $response->assertDontSee('Hidden Product');
});
```

## Test Database

- Each test run creates a fresh SQLite in-memory database
- Migrations run automatically via `RefreshDatabase` trait
- Seeders can be called within individual tests as needed
