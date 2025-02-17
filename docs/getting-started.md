# Getting Started Guide

This guide will help you set up the SteamTec project for local development.

## Prerequisites

Before you begin, ensure you have the following installed:
- Docker Desktop
- Git
- A code editor (VS Code recommended)
- Composer (for PHP dependency management)

## Initial Setup

1. **Clone the Repository**
   ```bash
   git clone [repository-url]
   cd steamtec
   ```

2. **Environment Configuration**
   ```bash
   # Copy the environment files
   cp .env .env.local
   ```
   
   Edit `.env.local` and set the following variables:
   ```
   DB_PORT=3306
   DB_DATABASE=steamtec
   DB_USER=your_username
   DB_PASSWORD=your_password
   DB_HOST=mysql
   ```

3. **Start Docker Environment**
   ```bash
   docker-compose up -d
   ```

4. **Install Dependencies**
   ```bash
   docker-compose exec php composer install
   ```

5. **Create Database Schema**
   ```bash
   docker-compose exec php bin/console doctrine:migrations:migrate
   ```

## Development Workflow

### Code Organization

The project follows Hexagonal Architecture:
- Business logic goes in `src/Domain/`
- Technical implementations go in `src/Infrastructure/`

### Making Changes

1. Create a new branch
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Write tests in the appropriate directory:
   - Unit tests: `tests/Unit/`
   - Integration tests: `tests/Integration/`
   - Functional tests: `tests/Functional/`

3. Implement your changes following the architecture patterns

4. Run tests
   ```bash
   docker-compose exec php bin/phpunit
   ```

5. Check code style
   ```bash
   docker-compose exec php vendor/bin/php-cs-fixer fix
   ```

### Common Tasks

#### Creating a New Entity
1. Create the entity in `src/Domain/YourModule/Data/Model/`
2. Create the repository interface in `src/Domain/YourModule/Repository/`
3. Implement the repository in `src/Infrastructure/Database/Repository/`
4. Create the database migration:
   ```bash
   docker-compose exec php bin/console doctrine:migrations:diff
   ```

#### Adding a New API Endpoint
1. Create/modify the controller in `src/Infrastructure/Controller/`
2. Add the route using attributes
3. Implement the business logic in a domain service
4. Add tests in `tests/Functional/`

## Useful Commands

### Docker
```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Access PHP container
docker-compose exec php bash
```

### Symfony
```bash
# Clear cache
bin/console cache:clear

# Create migration
bin/console doctrine:migrations:diff

# Run migrations
bin/console doctrine:migrations:migrate

# Create new controller
bin/console make:controller

# List routes
bin/console debug:router
```

### Testing
```bash
# Run all tests
bin/phpunit

# Run specific test file
bin/phpunit tests/path/to/test

# Run tests with coverage
bin/phpunit --coverage-html var/coverage
```

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check if MySQL container is running
   - Verify database credentials in `.env.local`
   - Ensure correct port mapping in `docker-compose.yml`

2. **Permission Issues**
   - Run `chmod -R 777 var/` for cache directory
   - Check file ownership in Docker containers

3. **Composer Issues**
   - Clear composer cache: `composer clear-cache`
   - Remove vendor directory and reinstall: `rm -rf vendor && composer install`

### Getting Help

1. Check the technical documentation in `docs/technical-architecture.md`
2. Review existing issues in the project repository
3. Contact the development team on [communication channel]

## Next Steps

- Review the [Technical Architecture Documentation](technical-architecture.md)
- Familiarize yourself with the codebase
- Set up your IDE with the recommended extensions
- Join the development team's communication channels 