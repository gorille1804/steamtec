# Contributing to SteamTec

Thank you for your interest in contributing to SteamTec! This document provides guidelines and standards for contributing to the project.

## Code of Conduct

- Be respectful and professional in all interactions
- Follow the project's architectural decisions
- Help maintain code quality and consistency
- Document your changes appropriately

## Development Process

### 1. Branching Strategy

- `main`: Production-ready code
- `develop`: Main development branch
- `feature/*`: New features
- `bugfix/*`: Bug fixes
- `hotfix/*`: Urgent production fixes
- `release/*`: Release preparation

### 2. Commit Messages

Follow the Conventional Commits specification:

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Formatting
- `refactor`: Code restructuring
- `test`: Adding tests
- `chore`: Maintenance

Example:
```
feat(user): add email verification functionality

- Add email verification table
- Implement verification token generation
- Add email sending service

Closes #123
```

### 3. Pull Request Process

1. Create a feature branch from `develop`
2. Write/update tests
3. Implement changes
4. Update documentation
5. Submit PR to `develop`

PR requirements:
- All tests pass
- Code follows style guidelines
- Documentation is updated
- PR description is clear and complete

### 4. Code Style

#### PHP

- Follow PSR-12 coding standard
- Use type hints and return types
- Document classes and methods with PHPDoc
- Maximum line length: 120 characters

Example:
```php
/**
 * Handles user registration process.
 */
final class UserRegistrationService
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher
    ) {}

    /**
     * Registers a new user in the system.
     *
     * @throws UserAlreadyExistsException
     */
    public function register(string $email, string $password): User
    {
        // Implementation
    }
}
```

#### Testing

- One assertion per test method
- Descriptive test method names
- Use data providers for multiple test cases
- Mock external dependencies

Example:
```php
/**
 * @test
 * @dataProvider provideInvalidEmails
 */
public function register_should_throw_exception_for_invalid_email(string $invalidEmail): void
{
    $this->expectException(InvalidEmailException::class);
    $this->service->register($invalidEmail, 'password123');
}
```

### 5. Documentation

#### Code Documentation

- Document all public methods
- Explain complex algorithms
- Add context to business rules
- Keep comments up to date

#### Technical Documentation

- Update README.md when needed
- Document architectural decisions
- Maintain API documentation
- Update deployment guides

### 6. Quality Assurance

#### Required Checks

- PHPUnit tests
- PHP CS Fixer
- PHPStan (level 8)
- Psalm
- Security checkers

#### Performance

- Profile database queries
- Monitor memory usage
- Check response times
- Optimize assets

### 7. Security

- Never commit sensitive data
- Use prepared statements
- Validate all input
- Follow OWASP guidelines
- Report security issues privately

### 8. Database Changes

- Create migrations for schema changes
- Document complex migrations
- Test migration rollback
- Avoid data loss
- Consider backward compatibility

### 9. Dependencies

- Keep dependencies up to date
- Document major version upgrades
- Check security advisories
- Maintain compatibility

### 10. Review Process

#### Code Review Checklist

- [ ] Follows architecture patterns
- [ ] Meets requirements
- [ ] Tests are complete
- [ ] Documentation updated
- [ ] No security issues
- [ ] Performance considered
- [ ] Error handling complete

#### Reviewer Responsibilities

- Be constructive
- Review thoroughly
- Respond promptly
- Consider edge cases
- Check for security issues

### 11. Release Process

1. Create release branch
2. Update version numbers
3. Generate changelog
4. Run full test suite
5. Create release PR
6. Deploy to staging
7. Final review
8. Merge to main
9. Tag release
10. Deploy to production

### 12. Continuous Integration

Our CI pipeline checks:
- Code style
- Static analysis
- Unit tests
- Integration tests
- Security
- Build process
- Documentation

### Getting Help

- Read the documentation
- Check existing issues
- Ask in team chat
- Contact maintainers

### Additional Resources

- [Technical Architecture](technical-architecture.md)
- [Getting Started Guide](getting-started.md)
- [API Documentation](api-docs.md)
- [Security Policy](SECURITY.md) 