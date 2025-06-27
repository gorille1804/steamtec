# SteamTec Project

## Architecture Overview

This project follows a Hexagonal Architecture (Ports & Adapters) implemented with Symfony 6+, ensuring a clear separation of concerns and a maintainable codebase.

## Project Structure

```
steamtec/
├── bin/                    # Executables (e.g., bin/console)
├── config/                 # Symfony configuration (bundles, services, routes)
├── src/
│   ├── Domain/             # Core business logic (entities, value objects,
│   │                         domain services, repository interfaces)
│   └── Infrastructure/     # Adapters & framework code
│       ├── Controller/     # HTTP controllers (endpoints)
│       ├── Database/       # Doctrine repositories & migration support
│       ├── Form/           # Symfony Form types
│       ├── Event/          # Event subscribers/listeners
│       ├── Symfony/        # Console commands, security, framework glue
│       └── templates/      # Twig templates
├── migrations/             # Doctrine migrations
├── public/                 # Web entry point (index.php), asset entry points
├── assets/                 # Frontend assets (JS/CSS/images) – Webpack
├── translations/           # Symfony translation files (XLIFF/YAML)
├── tests/                  # PHPUnit tests (unit & functional)
├── var/                    # Runtime files (cache, logs, uploads)
├── vendor/                 # Composer dependencies
├── .env*                   # Environment configs (.env, .env.dev, .env.test)
├── composer.json/.lock     # PHP dependencies & scripts
├── package.json/.lock      # JS dependencies & build scripts
├── webpack.config.js       # Frontend build config
├── Dockerfile(.dev)        # Docker images
├── docker-compose.yml      # Docker Compose (with override)
├── apache.conf             # Apache vhost config
└── importmap.php           # Symfony Asset Mapper bootstrap
```

## Technical Stack

- PHP 8.x & Symfony 6+  
- MySQL via Doctrine ORM  
- Twig templates  
- Symfony Asset Mapper + Webpack (Node.js, npm)  
- PHPUnit for testing  
- Docker & Docker Compose for containerization  
- Apache (production container)

## Request Flow

1. `public/index.php` → Symfony Kernel  
2. Routing → Infrastructure Controller  
3. Controller invokes Domain services or repository interfaces  
4. Infrastructure\Database (Doctrine) loads/persists entities  
5. Controller returns a Response (HTML via Twig, JSON, redirect, etc.)

## Architecture Details

### Domain Layer (`src/Domain/`)
Pure business logic and rules (entities, value objects, domain services, repository interfaces).

### Infrastructure Layer (`src/Infrastructure/`)
Framework-specific implementations and adapters:
- Controllers (HTTP endpoints)  
- Database repositories & migrations support  
- Form types & validation  
- Event subscribers/listeners  
- Console commands, security, other Symfony glue  
- Twig templates

## Getting Started

1. Copy `.env.dev` → `.env.local`, configure database credentials.  
2. `docker-compose up --build` (or `docker-compose -f docker-compose.override.yml up`).  
3. `bin/console doctrine:migrations:migrate`  
4. `npm install && npm run dev`  
5. Access the application at `http://localhost` (or your Docker‑exposed port).

## Testing

Automated tests are written with PHPUnit. See the `tests/` directory for unit and functional tests:

```bash
bin/phpunit
```

## Internationalization (i18n)

The application supports multiple languages via Symfony’s translation component.  
Translation files live in `translations/` (XLIFF/YAML).

## Asset Management

Frontend assets (JS/CSS/images) are managed with Symfony Asset Mapper and Webpack.  
Sources in `assets/`, built via `npm run dev` (or `npm run build`).

## Best Practices

- SOLID principles  
- Dependency Injection  
- Interface Segregation  
- Clean Architecture patterns  
- Domain‑Driven Design (DDD)

## Contributing

When contributing, please:

1. Follow existing architectural patterns (Hexagonal / DDD).  
2. Keep a clear separation of concerns.  
3. Cover new features with tests.  
4. Update documentation & run pre-commit checks.

## License

[Add your license here]