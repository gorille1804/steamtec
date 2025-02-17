# Technical Architecture Documentation

## Overview

This document provides detailed technical information about the SteamTec project architecture, implementation details, and development guidelines.

## Architectural Patterns

### Hexagonal Architecture

The project implements Hexagonal Architecture (also known as Ports and Adapters) with the following key components:

1. **Domain Layer** (`src/Domain/`)
   - Contains pure business logic
   - Framework-agnostic
   - No dependencies on external services
   - Defines interfaces (ports) for external services

2. **Infrastructure Layer** (`src/Infrastructure/`)
   - Implements adapters for external services
   - Contains framework-specific code
   - Implements interfaces defined in the domain layer

### Directory Structure Details

```
src/
├── Domain/
│   └── User/
│       ├── Data/
│       │   └── Model/          # Domain entities
│       ├── Repository/         # Repository interfaces
│       ├── Service/           # Domain services
│       └── Exception/         # Domain-specific exceptions
│
└── Infrastructure/
    ├── Controller/
    │   └── UserController.php  # HTTP endpoints
    ├── Database/
    │   └── Repository/        # Repository implementations
    ├── Symfony/
    │   └── Kernel.php         # Symfony configuration
    └── templates/
        └── user/              # User-related templates
```

## Implementation Guidelines

### Domain Layer

#### Entities
- Must be framework-agnostic
- Should contain business logic
- No ORM annotations in domain entities
- Value objects for complex attributes

Example:
```php
namespace App\Domain\User\Data\Model;

class User
{
    private UserId $id;
    private Email $email;
    private HashedPassword $password;
    
    // Business methods...
}
```

#### Repositories
- Define interfaces in the domain layer
- No implementation details
- Pure business methods

Example:
```php
namespace App\Domain\User\Repository;

interface UserRepository
{
    public function findById(UserId $id): ?User;
    public function save(User $user): void;
}
```

### Infrastructure Layer

#### Controllers
- Thin controllers
- Use application services
- Handle HTTP concerns only
- Input validation
- Response formatting

Example:
```php
namespace App\Infrastructure\Controller;

class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {}
    
    #[Route('/api/users', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Implementation...
    }
}
```

#### Repository Implementations
- Implement domain interfaces
- Handle database concerns
- Use Doctrine ORM
- Map between domain and persistence models

Example:
```php
namespace App\Infrastructure\Database\Repository;

class DoctrineUserRepository implements UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}
    
    public function findById(UserId $id): ?User
    {
        // Implementation...
    }
}
```

## Database

### Entity Mapping
- Mapping files in `config/doctrine/`
- Use XML or YAML mapping (not annotations)
- Separate domain models from persistence models

### Migrations
- Located in `migrations/`
- Generated using Doctrine migrations
- Version controlled
- Tested before deployment

## Testing Strategy

### Unit Tests
- Test domain logic
- No infrastructure dependencies
- Use mocks for external services
- Located in `tests/Unit/`

### Integration Tests
- Test infrastructure implementations
- Use test database
- Located in `tests/Integration/`

### Functional Tests
- Test complete features
- HTTP requests/responses
- Located in `tests/Functional/`

## Development Environment

### Docker Setup
```yaml
services:
  php:
    build: .docker/php
    volumes:
      - .:/var/www/html
    
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
```

### Local Development
1. Copy `.env` to `.env.local`
2. Configure database connection
3. Run `docker-compose up -d`
4. Install dependencies: `composer install`
5. Run migrations: `php bin/console doctrine:migrations:migrate`

## Security Considerations

### Authentication
- JWT-based authentication
- Token storage in HTTP-only cookies
- CSRF protection enabled

### Authorization
- Role-based access control
- Custom voters for complex permissions
- Attribute-based access control where needed

### Data Protection
- Input validation on all endpoints
- Output escaping in templates
- Prepared statements for database queries
- HTTPS enforced in production

## Performance Optimization

### Caching
- Doctrine second-level cache
- Redis for session storage
- HTTP cache headers
- Asset versioning

### Database
- Indexed columns
- Optimized queries
- Lazy loading where appropriate
- Pagination for large datasets

## Deployment

### Requirements
- PHP 8.x
- MySQL 8.0
- Redis (optional)
- Nginx/Apache

### Process
1. Build assets
2. Clear cache
3. Run migrations
4. Update dependencies
5. Restart services

## Monitoring

### Logging
- Application logs in `var/log/`
- Error tracking with Sentry
- Access logs in web server

### Metrics
- PHP-FPM status page
- MySQL slow query log
- Application-specific metrics

## Contributing Guidelines

### Code Style
- PSR-12 coding standard
- PHP CS Fixer configuration
- PHPStan level 8

### Git Workflow
1. Create feature branch
2. Write tests
3. Implement feature
4. Create pull request
5. Code review
6. Merge to develop

### Documentation
- Update technical documentation
- Add PHPDoc blocks
- Document architectural decisions
- Keep README.md updated 