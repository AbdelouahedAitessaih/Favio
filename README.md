<p align="center">
  <img src="docs/favio-logo.png" alt="Favio Logo" width="200">
</p>

<h1 align="center">Favio</h1>

<p align="center">
  A modern web application for product catalog management and favorites system, built with Laravel 12.
</p>

## 📋 Description

Favio is a full-featured product catalog application that allows users to browse, search, and manage their favorite products. The application includes a comprehensive admin panel for product and user management, making it suitable for e-commerce or product showcase platforms.

## ✨ Features

### User Features
- **Product Browsing**: Browse through a catalog of products with images, descriptions, and prices
- **Search & Filter**: Search products by name or description, filter by category
- **Sorting Options**: Sort products by price (ascending/descending), name, or most recent
- **Favorites System**: Add and remove products from your personal favorites list
- **User Authentication**: Secure registration and login system
- **Profile Management**: Update your profile information

### Admin Features
- **Product Management**: Full CRUD operations for products (Create, Read, Update, Delete)
- **User Management**: Manage users, including creating, editing, and deleting accounts
- **Product Status Control**: Activate or deactivate products
- **Image Upload**: Upload and manage product images
- **Advanced Filtering**: Filter products by category, status, and search terms
- **Admin Dashboard**: Dedicated dashboard for administrators

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: 
  - Tailwind CSS 3.1+ for styling
  - Alpine.js for interactive components
  - Vite for asset bundling
- **Database**: SQLite (can be configured for MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze
- **Testing**: PHPUnit

## 🚀 Installation

1. Clone the repository:
```bash
git clone https://github.com/AbdelouahedAitessaih/Favio.git
cd Favio
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Run migrations:
```bash
php artisan migrate
```

6. Build assets:
```bash
npm run build
```

7. Start the development server:
```bash
php artisan serve
```

For development with hot reload:
```bash
composer run dev
```

## 📁 Project Structure

```
favio/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/               # Eloquent models
│   └── View/Components/      # Blade components
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── views/                # Blade templates
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript files
├── routes/                    # Application routes
└── tests/                     # Feature and unit tests
```

## 🧪 Testing

Run the test suite:
```bash
composer test
```

Or use PHPUnit directly:
```bash
php artisan test
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">Built with ❤️ using <a href="https://laravel.com">Laravel</a></p>
