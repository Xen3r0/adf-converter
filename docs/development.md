# Development

## With Docker (recommended)

The repository ships a `compose.yml` and `Dockerfile` with PHP 8.2 and Composer,
so you do not need a local PHP install.

```bash
# Build the image and install dependencies
docker compose build
docker compose run --rm php composer install

# Run the test suite
docker compose run --rm php vendor/bin/phpunit

# Static analysis (PHPStan, level 8)
docker compose run --rm php vendor/bin/phpstan analyse

# Coding standards (dry run — shows what would change)
docker compose run --rm php vendor/bin/php-cs-fixer fix --dry-run --diff

# Coding standards (apply fixes)
docker compose run --rm php vendor/bin/php-cs-fixer fix
```

## With a local PHP

If you have PHP 8.2+ and Composer installed:

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse
vendor/bin/php-cs-fixer fix --dry-run --diff
```

## Quality gates

| Tool          | Config              | Purpose                             |
| ------------- | ------------------- | ----------------------------------- |
| PHPUnit       | `phpunit.xml`       | Unit tests (`tests/`)               |
| PHPStan       | `phpstan.neon`      | Static analysis at level 8          |
| php-cs-fixer  | `.php-cs-fixer.php` | Coding standards (`@Symfony` ruleset) |

## Continuous integration

Two GitHub Actions workflows live in `.github/workflows/`:

- **`ci.yml`** runs on every push and pull request to `main`. It lints
  (php-cs-fixer + PHPStan) and runs the test suite across PHP 8.2, 8.3 and 8.4.
- **`release.yml`** runs when a GitHub release is published and notifies
  Packagist to refresh the package. It requires two repository secrets,
  `PACKAGIST_USERNAME` and `PACKAGIST_TOKEN`.

## Releasing

1. Make sure `main` is green.
2. Create a version tag following [SemVer](https://semver.org/), e.g. `v1.2.0`.
3. Publish a GitHub release for that tag. The `release.yml` workflow updates
   Packagist automatically.
