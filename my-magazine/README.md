# My Magazine — PHP/MySQL CMS

A responsive magazine/news CMS starter for PHP 8+ and MySQL/MariaDB.

## Requirements
- PHP 8.0+ (8.1+ recommended)
- MySQL 5.7+ / MariaDB 10.4+
- XAMPP, Apache, PHP and MySQL
- PHP extensions: pdo_mysql, fileinfo, mbstring

## Installation (XAMPP)
1. Extract this folder into `C:\xampp\htdocs\my-magazine`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and create a database named `my_magazine`.
4. Import `database/schema.sql`.
5. Edit `config/database.php` if your MySQL username/password differs.
6. Visit `http://localhost/my-magazine/install.php` and create the first administrator.
7. Delete `install.php` after the administrator account is created.
8. Visit `http://localhost/my-magazine/` and `http://localhost/my-magazine/admin/login.php`.

## Included
- Public homepage, article details, category pages and search
- Admin login with password hashing and CSRF protection
- Dashboard, article CRUD, category CRUD
- Draft/published article status
- Comment moderation
- Basic site settings
- Safe image upload validation
- PDO prepared statements and role checks

## Important production checklist
- Change database credentials and use a strong database password.
- Delete `install.php` immediately after setup.
- Serve the site over HTTPS.
- Configure PHP upload limits and backup the database/uploads.
- Review and customize privacy, cookie, moderation and editorial policies.
- Add rate limiting, email verification, password reset and audit logging before public production use.
- The visual layout is an original magazine-inspired design, not a copy of Colorlib's proprietary assets. Only use third-party template assets where the applicable licence permits it.
