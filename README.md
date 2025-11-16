# 🗞️ VietNews CMS PHP

A modern, lightweight MVC framework for News content management system (CMS) built with pure PHP.

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Status](https://img.shields.io/badge/status-active-success)

## 📋 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Project Structure](#-project-structure)
- [Security](#-security)
- [Development](#-development)
- [Author](#-author)

## ✨ Features

- ✅ **MVC Architecture** - Clean separation of concerns
- ✅ **Composer Integration** - Modern dependency management with PSR-4 autoloading
- ✅ **Environment Configuration** - Secure `.env` based configuration
- ✅ **Email Service** - PHPMailer integration with HTML templates
- ✅ **Database Abstraction** - PDO-based database layer with singleton pattern
- ✅ **Routing System** - Simple and flexible routing
- ✅ **Template Engine** - Clean view rendering system
- ✅ **Security** - CSRF protection, password hashing, prepared statements

## 📦 Requirements

- **PHP** >= 7.4
- **MySQL** >= 5.7 or **MariaDB** >= 10.2
- **Composer** (for dependency management)
- **Apache** or **Nginx** web server
- **mod_rewrite** enabled (for Apache)

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/trgiahuy14/vietnews-cms-php.git
cd vietnews-cms-php
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
# Copy .env.example to .env
cp .env.example .env

# Edit .env with your configuration
nano .env
```

**Required configurations:**

```env
# Database credentials
DB_HOST=localhost
DB_NAME=php_mvc_db
DB_USER=root
DB_PASS=your_password

# Application URL
BASE_URL=http://localhost/vietnews-cms-php

# Mail settings (for email features)
MAIL_HOST=smtp.gmail.com
MAIL_USER=your-email@gmail.com
MAIL_PASS=your-app-password
```

### 4. Import database

```bash
mysql -u root -p < database/migrations/php_mvc_db.sql
```

Or import via phpMyAdmin:

1. Open phpMyAdmin
2. Create database `php_mvc_db`
3. Import file `database/migrations/php_mvc_db.sql`

### 5. Configure web server

#### Apache (with `.htaccess`)

Point your document root to `public/` directory:

```apache
<VirtualHost *:80>
    ServerName vietnews.local
    DocumentRoot "/path/to/vietnews-cms-php/public"

    <Directory "/path/to/vietnews-cms-php/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### PHP Built-in Server (for development)

```bash
php -S localhost:8000 -t public/
```

Then open: http://localhost:8000

## ⚙️ Configuration

### Environment Variables

All configuration is done through the `.env` file:

| Variable    | Description       | Example                           |
| ----------- | ----------------- | --------------------------------- |
| `APP_NAME`  | Application name  | VietNews CMS                      |
| `APP_ENV`   | Environment       | local/production                  |
| `APP_DEBUG` | Debug mode        | true/false                        |
| `DB_HOST`   | Database host     | localhost                         |
| `DB_PORT`   | Database port     | 3306                              |
| `DB_NAME`   | Database name     | php_mvc_db                        |
| `DB_USER`   | Database username | root                              |
| `DB_PASS`   | Database password |                                   |
| `BASE_URL`  | Application URL   | http://localhost/vietnews-cms-php |
| `MAIL_HOST` | SMTP host         | smtp.gmail.com                    |
| `MAIL_PORT` | SMTP port         | 465                               |
| `MAIL_USER` | Email address     | your@gmail.com                    |
| `MAIL_PASS` | Email password    | your-app-password                 |

### Gmail Configuration

To use Gmail for sending emails:

1. Enable 2-factor authentication on your Gmail account
2. Generate an App Password: https://myaccount.google.com/apppasswords
3. Use the generated password in `MAIL_PASS`

## 📁 Project Structure

```
vietnews-cms-php/
├── 📁 database/              # Database files
│   └── migrations/           # SQL migration files
│
├── 📁 public/                # Web root (document root)
│   ├── index.php             # Application entry point
│   ├── .htaccess             # Apache rewrite rules
│   └── assets/               # Static files (CSS, JS, images)
│
├── 📁 routes/                # Route definitions
│   └── web.php               # Web routes
│
├── 📁 src/                   # Application source code
│   ├── 📁 app/               # Application layer
│   │   ├── Controllers/      # Controllers (handle HTTP requests)
│   │   ├── Models/           # Models (database entities)
│   │   ├── Views/            # View templates
│   │   │   ├── layouts/      # Layout templates
│   │   │   └── emails/       # Email templates
│   │   └── Services/         # Business logic services
│   │       └── MailService.php
│   │
│   ├── 📁 Core/              # Framework core
│   │   ├── Controller.php    # Base controller
│   │   ├── Model.php         # Base model
│   │   ├── Router.php        # Router
│   │   ├── View.php          # View renderer
│   │   ├── Database.php      # Database connection
│   │   └── session.php       # Session
│   │
│   ├── 📁 configs/           # Configuration files
│   │   └── app.php           # Application config
│   │
│   └── 📁 helpers/           # Helper functions
│       └── functions.php     # Global helpers
│
├── 📁 storage/               # Storage directory
│   ├── logs/                 # Application logs
│   ├── cache/                # Cache files
│   └── uploads/              # User uploads
│
├── 📁 vendor/                # Composer dependencies (auto-generated)
│
├── .env                      # Environment config
├── .env.example              # Environment template
├── .gitignore                # Git ignore rules
├── composer.json             # Composer dependencies
└── README.md                 # This file
```

## 🔐 Security

- ✅ Environment variables for sensitive data
- ✅ Password hashing with `password_hash()`
- ✅ SQL injection prevention with prepared statements
- ✅ CSRF token protection
- ✅ XSS prevention with `htmlspecialchars()`
- ✅ `.htaccess` protection for sensitive directories

## 🛠️ Development

### Debug Mode

Enable debug mode in `.env`:

```env
APP_DEBUG=true
```

### Logging

Application logs are stored in `storage/logs/`:

- `storage/logs/app.log` - Application logs
- `storage/logs/mail.log` - Email logs
- `storage/logs/error.log` - PHP errors

### Testing

```bash
# Test database connection
php test-database.php

# Test email functionality
php test-mail.php
```

## 👤 Author

**Tran Gia Huy**

- Email: giahuy-codes@gmail.com
- GitHub: [@huy-codes](https://github.com/huy-codes)

## 🙏 Acknowledgments

- [PHPMailer](https://github.com/PHPMailer/PHPMailer) - Email sending library
- [PHP-DotEnv](https://github.com/vlucas/phpdotenv) - Environment variable loader

- Inspired by Laravel and other modern PHP frameworks

---

Made with ❤️ by Tran Gia Huy
