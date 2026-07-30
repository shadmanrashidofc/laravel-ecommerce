# Contributing

Thank you for considering contributing to this project.

## Getting Started

1. Fork the repository.
2. Clone your fork:

   ```bash
   git clone https://github.com/your-username/laravel-ecommerce-platform.git
   cd laravel-ecommerce-platform
   ```

3. Create a feature branch:

   ```bash
   git checkout -b feat/your-feature-name
   ```

4. Install dependencies and set up the project as described in the [README](README.md#installation).

## Development Workflow

- Write tests for any new functionality.
- Ensure all existing tests pass before submitting:

  ```bash
  composer run test
  ```

- Run Laravel Pint for code style:

  ```bash
  ./vendor/bin/pint
  ```

- Keep commits atomic and write clear commit messages.

## Coding Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding style.
- Use Laravel conventions for controllers, models, routes, and views.
- Extract business logic into service classes when a controller method exceeds ~50 lines.
- Use type hints and return types on all methods.
- Prefix database migrations with descriptive names.

## Pull Request Guidelines

- Open your PR against the `main` branch.
- Provide a clear description of the change and its motivation.
- Reference any related issues.
- Keep PRs focused — one feature or fix per pull request.

## Reporting Issues

Report bugs and suggest features via [GitHub Issues](https://github.com/your-username/laravel-ecommerce-platform/issues). Include:

- A clear description of the problem or suggestion
- Steps to reproduce (for bugs)
- Environment details (PHP version, database, browser)

## Code of Conduct

Please note that this project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By participating you agree to uphold its terms.
