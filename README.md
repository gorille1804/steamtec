# SteamTec Project

## Architecture Overview

This project follows a Hexagonal Architecture (also known as Ports and Adapters or Clean Architecture) implemented with Symfony 6+. This architecture ensures a clear separation of concerns and maintainable codebase.

## Project Structure

```
steamtec/
├── src/
│   ├── Domain/             # Business logic and domain models
│   │   └── User/          # User domain module
│   └── Infrastructure/     # Technical implementations
│       ├── Controller/    # HTTP Controllers
│       ├── Database/      # Database persistence
│       ├── Symfony/       # Symfony specific configurations
│       └── templates/     # Twig templates
├── config/                 # Symfony configuration files
├── migrations/             # Database migrations
├── public/                 # Web entry point
├── tests/                  # Automated tests
├── translations/           # Translation files
├── assets/                # Frontend assets
├── bin/                   # Executable files
├── var/                   # Cache and logs
└── vendor/                # Dependencies
```

## Technical Stack

- **Framework**: Symfony 6+
- **Database**: MySQL
- **ORM**: Doctrine
- **Testing**: PHPUnit
- **Containerization**: Docker
- **Asset Management**: Symfony Asset Mapper
- **Template Engine**: Twig

## Architecture Details

### Domain Layer (`src/Domain/`)
Contains the business logic and rules of the application. This layer is independent of any framework or technical implementation.

- Business entities
- Value objects
- Domain services
- Interfaces (ports)

### Infrastructure Layer (`src/Infrastructure/`)
Contains all technical implementations and adapters for external services.

- Controllers (HTTP endpoints)
- Database repositories
- Framework specific configurations
- Template views

## Development Setup

### Prerequisites
- Docker
- Docker Compose
- PHP 8.x
- Composer

### Environment Configuration
The project uses different environment configurations:
- `.env`: Default configuration
- `.env.dev`: Development environment
- `.env.test`: Test environment
- `.env.local`: Local overrides (not committed)

### Docker Setup
The project includes Docker configuration:
- `compose.yaml`: Main Docker configuration
- `compose.override.yaml`: Local Docker overrides

## Testing

Tests are configured with PHPUnit and can be found in the `/tests` directory.

## Multilingual Support

The application supports multiple languages through Symfony's translation system. Translation files are located in the `/translations` directory.

## Asset Management

Frontend assets are managed through Symfony Asset Mapper and located in the `/assets` directory.

## Best Practices

The project follows these architectural principles:
- SOLID principles
- Dependency Injection
- Interface Segregation
- Clean Architecture patterns
- Domain-Driven Design concepts

## Contributing

When contributing to this project, please:
1. Follow the existing architecture patterns
2. Maintain separation of concerns
3. Write tests for new features
4. Update documentation as needed

## License

[Your License Here] 