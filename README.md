# Reel and Roll Photography - Full-Stack Gallery Management System

A professional photography portfolio website with a dynamic gallery management system. Features a PHP backend API, MySQL database, Docker-based local development, and an admin interface for managing gallery images.

## 🚀 Features

- **Dynamic Gallery**: Images loaded from MySQL database via REST API
- **Admin Dashboard**: Upload, edit, and delete gallery images
- **Category Filtering**: Wedding, Portrait, Event, and Landscape categories
- **Docker Support**: Complete local development environment
- **Responsive Design**: Modern, mobile-first design
- **Drag-and-Drop Upload**: Easy image upload interface
- **Production Ready**: Separate configuration for production deployment

## 📋 Prerequisites

For local development with Docker:
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed
- Windows, macOS, or Linux

For production deployment:
- Web hosting with PHP 8.1+ and MySQL 8.0+
- Apache with mod_rewrite enabled

## 🏃 Quick Start (Local Development)

### 1. Clone the Repository

```bash
cd c:\Myprojects\ReelandRoll
```

### 2. Start Docker Environment

```bash
docker-compose up -d
```

This will start three containers:
- **web**: PHP 8.1 with Apache (port 8080)
- **mysql**: MySQL 8.0 (port 3306)
- **phpmyadmin**: Database management (port 8081)

### 3. Access the Application

- **Website**: http://localhost:8080
- **Gallery Page**: http://localhost:8080/gallery.html
- **Admin Login**: http://localhost:8080/admin
- **PHPMyAdmin**: http://localhost:8081

### 4. Admin Credentials

```
Username: admin
Password: admin123
```

> ⚠️ **IMPORTANT**: Change these credentials  immediately for production use!

## 📁 Project Structure

```
ReelandRoll/
├── index.html              # Homepage
├── gallery.html            # Gallery page (dynamic)
├── about.html              # About page
├── services.html           # Services page
├── portfolio.html          # Portfolio page
├── contact.html            # Contact page
├── styles.css              # Main stylesheet
├── script.js               # Frontend JavaScript
├── gallery-api.js          # Gallery API integration
├── assets/                 # Images and static files
├── uploads/gallery/        # Uploaded gallery images
├── api/                    # Backend API
│   ├── config/
│   │   ├── database.php    # Database connection
│   │   └── config.php      # App configuration
│   ├── models/
│   │   ├── User.php        # User model
│   │   └── Gallery.php     # Gallery model
│   ├── auth.php            # Authentication endpoint
│   ├── logout.php          # Logout endpoint
│   └── gallery.php         # Gallery CRUD endpoint
├── admin/                  # Admin interface
│   ├── index.html          # Login page
│   ├── dashboard.html      # Gallery management
│   ├── admin.js            # Admin JavaScript
│   └── admin.css           # Admin styles
├── database/               # Database files
│   ├── schema.sql          # Database schema
│   └── seed.sql            # Sample data
├── docker-compose.yml      # Docker configuration
├── Dockerfile              # PHP/Apache container
├── .htaccess               # Apache configuration
└── .env.example            # Environment variables template

```

## 🗄️ Database Setup

The database is automatically created when Docker starts. The schema includes:

### Tables

- **users**: Admin authentication
- **gallery**: Gallery images with categories

### Database Details

```
Database: reelandroll
User: reeluser
Password: reelpass123
Root Password: rootpassword
```

## 🔌 API Endpoints

### Public Endpoints

- `GET /api/gallery.php` - Get all gallery images
- `GET /api/gallery.php?category=wedding` - Filter by category

### Admin Endpoints (Authentication Required)

- `POST /api/auth.php` - Login
- `POST /api/logout.php` - Logout
- `GET /api/auth.php` - Verify session
- `POST /api/gallery.php` - Upload image
- `PUT /api/gallery.php?id={id}` - Update image
- `DELETE /api/gallery.php?id={id}` - Delete image

## 🎨 Admin Interface

### Accessing the Admin

1. Navigate to `http://localhost:8080/admin`
2. Login with default credentials
3. Upload and manage gallery images

### Features

- Drag-and-drop image upload
- Category assignment
- Alt text for accessibility
- Edit existing images
- Delete images
- Filter by category

## 🚀 Production Deployment

### 1. Prepare Environment

Create a `.env` file from `.env.example`:

```bash
cp .env.example .env
```

Update with production values:

```env
DB_HOST=your-production-host
DB_NAME=your-production-db
DB_USER=your-production-user
DB_PASSWORD=your-strong-password
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Upload Files

Upload all files to your web hosting via FTP/SFTP:
- Upload to public_html or www directory
- Ensure `uploads/gallery/` has write permissions (755)

### 3. Create Database

1. Access your hosting's PHPMyAdmin
2. Create a new database
3. Import `database/schema.sql`
4. Import `database/seed.sql`

### 4. Update Admin Password

Login to PHPMyAdmin and run:

```sql
UPDATE users 
SET password = '$2y$10$YOUR_HASHED_PASSWORD_HERE' 
WHERE username = 'admin';
```

Generate a password hash using this PHP code:

```php
<?php
echo password_hash('your-new-password', PASSWORD_DEFAULT);
?>
```

### 5. Configure Apache

Ensure `.htaccess` is uploaded and mod_rewrite is enabled.

### 6. Test

- Visit your website
- Test gallery page loads images
- Login to admin panel
- Upload a test image

## 🔧 Troubleshooting

### Docker Issues

**Containers won't start:**
```bash
docker-compose down
docker-compose up --build
```

**Database connection errors:**
```bash
docker-compose restart mysql
docker-compose logs mysql
```

**Permission errors:**
```bash
docker-compose exec web chown -R www-data:www-data /var/www/html/uploads
```

### Production Issues

**Images not uploading:**
- Check `uploads/gallery/` permissions (755 or 777)
- Verify PHP upload settings in `.htaccess`

**API not working:**
- Verify mod_rewrite is enabled
- Check `.htaccess` is uploaded
- Ensure PHP 8.1+ is installed

**Database connection failed:**
- Verify database credentials in environment
- Check database server is running
- Ensure database name is correct

## 📦 Stopping the Application

```bash
docker-compose down
```

To remove all data (including database):
```bash
docker-compose down -v
```

## 🔐 Security Notes

1. **Change default admin credentials** immediately
2. Use strong passwords for production database
3. Keep PHP and dependencies updated
4. Enable HTTPS in production
5. Set file upload limits appropriately
6. Never commit `.env` file to Git

## 🛠️ Development

### Adding New Categories

1. Update `database/schema.sql` ENUM values
2. Update filter buttons in `gallery.html`
3. Update admin dashboard categories

### Customizing Design

- Main styles: `styles.css`
- Admin styles: `admin/admin.css`
- Colors: Edit CSS variables in `:root`

## 📝 License

© 2026 Reel and Roll Photography. All rights reserved.

## 🆘 Support

For issues or questions:
1. Check Docker logs: `docker-compose logs`
2. Verify database schema is correct
3. Ensure all environment variables are set
4. Check file permissions on uploads directory

---

**Built with ❤️ using PHP, MySQL, Docker, and vanilla JavaScript**
