# LinkAce Developer Instructions

LinkAce is a self-hosted bookmark manager built with Laravel PHP framework. It supports multiple databases, features advanced tagging and categorization, and provides both web interface and API access.

**ALWAYS follow these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Working Effectively

### Bootstrap, Build, and Test the Repository

1. **Environment Setup (Required for all development):**
   ```bash
   # Copy environment file and set up basic configuration
   cp .env.dev .env
   
   # Modify .env for local development:
   # - Set APP_ENV=local
   # - Set APP_DEBUG=true  
   # - For SQLite: Set DB_CONNECTION=sqlite and DB_DATABASE=/absolute/path/to/database.sqlite
   ```

2. **Install Dependencies:**
   ```bash
   # Install PHP dependencies - takes 2-3 minutes
   composer install --no-interaction
   
   # Install Node.js dependencies - takes 30 seconds
   npm install
   ```

3. **Application Configuration:**
   ```bash
   # Generate Laravel application key
   php artisan key:generate
   
   # Create database file for SQLite (if using SQLite)
   mkdir -p database && touch database/database.sqlite
   
   # Create testing database
   touch database/testing.sqlite
   
   # Run database migrations
   php artisan migrate --force
   ```

4. **Build Assets:**
   ```bash
   # Development build (for active development)
   npm run dev
   
   # Production build - takes 15 seconds. NEVER CANCEL.
   npm run production
   ```

5. **Run Tests and Linting:**
   ```bash
   # Run linting - takes 5 seconds. NEVER CANCEL.
   composer run lint
   
   # Run test suite - takes 50 seconds. NEVER CANCEL. Set timeout to 90+ seconds.
   composer run test
   ```

### Run the Application

**ALWAYS complete the bootstrapping steps above first.**

#### Local Development Server:
```bash
# Start PHP development server on port 8000
php artisan serve --host=0.0.0.0 --port=8000
# Application will be available at http://localhost:8000
```

#### Docker Development (Alternative):
```bash
# IMPORTANT: Docker setup may fail due to package availability issues
# Use native PHP setup (above) if Docker build fails

# Copy Docker development environment
cp .env.dev .env

# Start Docker services - takes 6+ minutes, may fail. NEVER CANCEL. Set timeout to 15+ minutes.
docker compose up -d --build

# If successful, run inside PHP container:
docker compose exec -it php composer install
docker compose exec -it php php artisan key:generate
docker compose exec -it php php artisan migrate
```

## Validation

### Manual Testing Requirements
- **ALWAYS test complete user workflows after making changes**
- **ALWAYS run both linting and tests before committing**
- Test the application setup process by accessing http://localhost:8000
- Verify the setup wizard redirects properly (should redirect to /setup/start)
- Create a test user: `php artisan registeruser --admin testuser test@example.com`
- Test login functionality through the web interface

### Required Validation Steps
```bash
# Before any commit, ALWAYS run:
composer run lint     # Must pass without errors
composer run test     # 438 tests, may have warnings about coverage (acceptable)
npm run production    # Must complete successfully
```

## Common Tasks

### User Management
```bash
# Create new admin user
php artisan registeruser --admin username email@example.com

# Create regular user  
php artisan registeruser username email@example.com

# Complete setup if wizard fails
php artisan setup:complete
```

### Database Operations
```bash
# Run migrations
php artisan migrate

# Reset database completely
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

### Asset Management
```bash
# Watch for changes during development
npm run watch

# One-time development build
npm run dev

# Production build (for deployment)
npm run production
```

## Build and Test Timing

- **Composer install**: 2-3 minutes (production), 3-4 minutes (with dev dependencies)
- **npm install**: 30 seconds
- **npm run production**: 15 seconds - NEVER CANCEL
- **composer run lint**: 5 seconds - NEVER CANCEL  
- **composer run test**: 50 seconds - NEVER CANCEL. Set timeout to 90+ seconds
- **Docker build**: 6+ minutes, may fail - NEVER CANCEL. Set timeout to 15+ minutes

## Technology Stack

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Laravel Mix, Bootstrap 5, TomSelect
- **Database**: MySQL/MariaDB, PostgreSQL, SQLite
- **Cache**: Redis (optional)
- **Node.js**: Version 22 LTS (minimum 20 LTS)
- **Development**: Docker Compose (optional), PHP built-in server

## Project Structure

### Key Directories
- `app/` - Laravel application code (Models, Controllers, Commands)
- `resources/assets/` - Frontend assets (JS, SCSS)  
- `public/assets/dist/` - Compiled assets
- `database/` - Migrations, factories, seeds
- `tests/` - PHPUnit test suite (438 tests)
- `config/` - Laravel configuration files
- `resources/docker/` - Docker configuration files

### Important Files
- `composer.json` - PHP dependencies and scripts
- `package.json` - Node.js dependencies and build scripts  
- `webpack.mix.js` - Asset compilation configuration
- `phpunit.xml` - Test configuration
- `phpcs.xml` - Code style configuration
- `.env.dev` - Environment configuration template
- `CONTRIBUTING.md` - Development setup guide

## Environment Configuration

### Required Environment Variables
```bash
APP_ENV=local                    # Set to local for development
APP_DEBUG=true                   # Enable debug mode for development  
APP_KEY=                         # Generated by artisan key:generate
DB_CONNECTION=sqlite             # or mysql/pgsql
DB_DATABASE=/path/to/database    # Absolute path for SQLite
```

### Database Configuration Examples
```bash
# SQLite (recommended for development)
DB_CONNECTION=sqlite
DB_DATABASE=/home/user/LinkAce/database/database.sqlite

# MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=linkace
DB_USERNAME=linkace
DB_PASSWORD=password

# PostgreSQL  
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=linkace
DB_USERNAME=linkace
DB_PASSWORD=password
```

## Common Issues and Solutions

### Docker Build Failures
- **Issue**: Package availability errors in Alpine Linux
- **Solution**: Use native PHP setup instead of Docker

### Test Failures
- **Issue**: Database file does not exist
- **Solution**: `touch database/testing.sqlite`

### Permission Errors
- **Issue**: SQLite database not writable
- **Solution**: `chmod 664 database/database.sqlite && chmod 775 database/`

### Asset Build Issues
- **Issue**: Mix compilation errors
- **Solution**: Delete `node_modules/` and run `npm install` again

## CI/CD Pipeline

The repository uses GitHub Actions for testing:
- **Node.js asset build**: Tests on Node 22, uploads build artifacts
- **PHP testing**: Tests on PHP 8.1, 8.2, 8.3, 8.4 across Ubuntu
- **Artifacts**: Built assets are shared between build and test jobs

### CI Commands (matches local development)
```bash
npm ci && npm run production     # Asset building
composer install --prefer-dist  # Dependency installation  
composer run lint               # Code style checking
composer run test              # Test suite execution
```

## Setup Process Flow

1. Fresh clone → Copy `.env.dev` to `.env`
2. Install Composer dependencies → `composer install`
3. Generate app key → `php artisan key:generate`  
4. Install Node dependencies → `npm install`
5. Build assets → `npm run production`
6. Set up database → Create database file/connection
7. Run migrations → `php artisan migrate`
8. Create user → `php artisan registeruser --admin`
9. Start server → `php artisan serve`
10. Test application → Access setup wizard at http://localhost:8000

## Important Notes

- **NEVER CANCEL long-running commands** - builds and tests can take up to several minutes
- **ALWAYS use absolute paths** for SQLite database configuration
- **Docker setup is optional** - native PHP setup is more reliable
- **Test database required** - `database/testing.sqlite` must exist for tests
- **Admin user creation** - Use `--admin` flag for admin privileges
- **Setup wizard** - Application redirects to `/setup/start` until configuration is complete
