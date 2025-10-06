# 🏠 Filament Rental - Laravel Application

A modern rental management system built with Laravel 12, Filament 4.0, and Livewire. This project provides a comprehensive solution for managing rental properties, bookings, and customer relationships.

## 🚀 Quick Start with Warp

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (default) or MySQL/PostgreSQL

### Warp Workflows

This project includes custom Warp workflows to streamline development. Access them via `Cmd/Ctrl + Shift + R` in Warp:

#### 🔧 Setup & Installation
```bash
# Clone and setup the project
git clone <repository-url> filament-rental
cd filament-rental
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

#### 🏃‍♂️ Development Server
```bash
# Start all development services (Laravel server, queue worker, Vite)
composer run dev
```

#### 🧪 Testing & Quality
```bash
# Run tests
composer run test

# Run PHP CS Fixer (Laravel Pint)
./vendor/bin/pint

# Run tests with coverage
php artisan test --coverage
```

#### 📦 Production Deployment
```bash
# Build for production
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🛠 Technology Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Admin Panel**: Filament 4.0
- **Frontend**: Livewire 3, Livewire Flux 2.1
- **Real-time**: Livewire Volt 1.7
- **Authentication**: Laravel Fortify
- **Testing**: Pest 4.1
- **Database**: SQLite (default), MySQL, PostgreSQL
- **Queue**: Database driver (configurable)

## 🏗 Project Structure

```
filament-rental/
├── app/
│   ├── Filament/         # Filament admin resources
│   ├── Livewire/         # Livewire components
│   ├── Models/           # Eloquent models
│   └── Http/             # Controllers & middleware
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   ├── views/            # Blade templates
│   ├── js/               # JavaScript assets
│   └── css/              # Stylesheets
└── tests/                # Pest tests
```

## 🎯 Key Features

- **Property Management**: Complete rental property CRUD
- **Booking System**: Reservation management with calendar
- **Customer Portal**: Self-service booking and account management
- **Admin Dashboard**: Comprehensive Filament-powered admin interface
- **Real-time Updates**: Livewire-powered reactive components
- **Multi-tenant**: Support for multiple property managers
- **Payment Integration**: Ready for payment gateway integration
- **Reporting**: Analytics and financial reporting

## 🔑 Environment Configuration

Key environment variables to configure:

```env
# Application
APP_NAME="Filament Rental"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite

# Queue (use 'database' for simple setup)
QUEUE_CONNECTION=database

# Mail (configure for notifications)
MAIL_MAILER=log

# Filament
FILAMENT_DOMAIN=
```

## 🎨 Warp Terminal Customization

### Custom Aliases
Add these aliases to your Warp configuration:

```bash
# Laravel shortcuts
alias pa="php artisan"
alias tinker="php artisan tinker"
alias migrate="php artisan migrate"
alias rollback="php artisan migrate:rollback"
alias seed="php artisan db:seed"
alias fresh="php artisan migrate:fresh --seed"

# Filament shortcuts
alias filament="php artisan filament:"
alias make-resource="php artisan make:filament-resource"
alias make-page="php artisan make:filament-page"

# Testing shortcuts
alias pest="./vendor/bin/pest"
alias pint="./vendor/bin/pint"
alias test="composer run test"
```

### Warp Blocks
Create custom Warp blocks for common tasks:

#### Database Reset Block
```bash
# Reset database with fresh data
php artisan migrate:fresh --seed
echo "✅ Database reset complete"
```

#### Cache Clear Block
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "🧹 All caches cleared"
```

## 🔐 Default Admin Access

After running migrations and seeders:
- **URL**: `http://localhost:8000/admin`
- **Email**: `admin@example.com`
- **Password**: `password`

## 📝 Development Workflow

1. **Start Development**: `composer run dev`
2. **Make Changes**: Edit your code
3. **Run Tests**: `composer run test`
4. **Format Code**: `./vendor/bin/pint`
5. **Commit Changes**: Use conventional commits

## 🐛 Troubleshooting

### Common Issues

#### Permission Errors
```bash
# Fix storage permissions
chmod -R 755 storage bootstrap/cache
```

#### SQLite Database Issues
```bash
# Create SQLite database if missing
touch database/database.sqlite
php artisan migrate
```

#### Node Modules Issues
```bash
# Clean install Node dependencies
rm -rf node_modules package-lock.json
npm install
```

## 📊 Monitoring & Logs

```bash
# Watch Laravel logs in real-time
php artisan pail

# Monitor queue jobs
php artisan queue:monitor

# Check application status
php artisan about
```

## 🚀 Performance Optimization

```bash
# Production optimizations
php artisan optimize
php artisan filament:optimize

# Clear optimizations (development)
php artisan optimize:clear
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `composer run test`
5. Format code: `./vendor/bin/pint`
6. Submit a pull request

## 📄 License

This project is licensed under the MIT License.

---

**Built with ❤️ using Laravel, Filament, and Warp Terminal**