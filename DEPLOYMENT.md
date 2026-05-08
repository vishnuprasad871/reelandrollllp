# Production Deployment Guide

## Prerequisites

- Web hosting with PHP 8.1+ and MySQL 8.0+
- Apache web server with mod_rewrite enabled
- FTP/SFTP access to your server
- Access to PHPMyAdmin or MySQL command line

## Step-by-Step Deployment

### 1. Prepare Your Files

Before uploading, ensure you have:
- All project files from your local development
- Database dump from `database/schema.sql` and `database/seed.sql`

### 2. Create `.env` File

Create a `.env` file in the root directory with your production settings:

```env
# Production Database Configuration
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASSWORD=your_secure_password

# Application Settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Upload Settings
UPLOAD_MAX_SIZE=5242880
ALLOWED_EXTENSIONS=jpg,jpeg,png,gif,webp

# Session Settings
SESSION_LIFETIME=7200
```

**IMPORTANT:** Never commit this file to Git!

### 3. Upload Files via FTP/SFTP

1. Connect to your server via FTP/SFTP client (FileZilla, WinSCP, etc.)
2. Navigate to your public_html or www directory
3. Upload all files maintaining the folder structure:

```
public_html/
├── index.html
├── gallery.html
├── (all other HTML files)
├── styles.css
├── script.js
├── gallery-api.js
├── .htaccess
├── .env
├── assets/
├── api/
├── admin/
└── uploads/
    └── gallery/
```

### 4. Set File Permissions

Using your FTP client or SSH, set the  following permissions:

```bash
# Directories
chmod 755 api
chmod 755 api/config
chmod 755 api/models
chmod 755 admin
chmod 755 uploads
chmod 777 uploads/gallery  # Needs write permission

# Files
chmod 644 .htaccess
chmod 644 .env
chmod 644 api/*.php
chmod 644 api/config/*.php
chmod 644 api/models/*.php
```

Via SSH:
```bash
cd /path/to/public_html
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod 777 uploads/gallery
```

### 5. Create MySQL Database

#### Via cPanel/Hosting Control Panel:

1. Login to your hosting control panel
2. Go to MySQL Databases
3. Create a new database (e.g., `username_reelandroll`)
4. Create a new MySQL user
5. Set a strong password
6. Add user to database with ALL PRIVILEGES

#### Via PHPMyAdmin:

1. Login to PHPMyAdmin
2. Click "New" to create database
3. Name it (e.g., `reelandroll`)
4. Click "Create"

### 6. Import Database Schema

#### Via PHPMyAdmin:

1. Select your new database
2. Click "Import" tab
3. Choose `database/schema.sql`
4. Click "Go"
5. Repeat for `database/seed.sql`

#### Via SSH/Command Line:

```bash
mysql -u your_user -p your_database < database/schema.sql
mysql -u your_user -p your_database < database/seed.sql
```

### 7. Update Database Credentials

Make sure your `.env` file has the correct database details that match what you created in Step 5.

### 8. Update Admin Password

For security, change the default admin password:

1. Login to PHPMyAdmin
2. Select your database
3. Click on `users` table
4. Click "Edit" on the admin user

Generate a new password hash using PHP:

```php
<?php
// Save as generate_password.php and run once
$password = 'your-new-secure-password';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Your hashed password: " . $hash;
?>
```

Run this file once via browser, copy the hash, then:

1. Update the `password` field with the hash
2. Delete `generate_password.php` from server
3. Note your new password securely

### 9. Configure Apache (if needed)

Ensure `.htaccess` is uploaded and working:

1. Test if mod_rewrite is enabled:
   - Visit: `https://yourdomain.com/api/gallery.php`
   - Should return JSON data

2. If you get 404 errors, check with your host to enable mod_rewrite

3. If you're in a subdirectory, update `.htaccess`:
   ```apache
   RewriteBase /subdirectory/
   ```

### 10. Enable HTTPS

For production, always use HTTPS:

1. Get an SSL certificate (Let's Encrypt is free)
2. Most hosts offer free SSL in cPanel
3. Force HTTPS by uncommenting in `.htaccess`:

```apache
# Uncomment these lines in .htaccess
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 11. Test Your Deployment

1. **Test Homepage**: Visit `https://yourdomain.com`
2. **Test Gallery**: Visit `https://yourdomain.com/gallery.html`
   - Should load images from database
3. **Test Admin Login**: Visit `https://yourdomain.com/admin`
   - Login with your credentials
4. **Test Image Upload**:
   - Upload a test image
   - Verify it appears on gallery page
5. **Test Filters**: Click category filters on gallery page

### 12. Security Checklist

- [ ] Changed default admin password
- [ ] Using strong database password
- [ ] `.env` file is NOT in Git repository
- [ ] HTTPS is enabled and forced
- [ ] `uploads/gallery/` has appropriate permissions (777 or 755)
- [ ] Database backups are configured
- [ ] PHP error display is OFF (`display_errors = 0`)
- [ ] File upload limits are set appropriately

## Troubleshooting

### Issue: Images Not Uploading

**Solution:**
```bash
chmod 777 uploads/gallery
# Or via FTP, set permissions to 777
```

### Issue: API Returns 404

**Cause:** mod_rewrite not enabled or `.htaccess` not working

**Solution:**
1. Verify `.htaccess` is uploaded
2. Contact host to enable mod_rewrite
3. Check if using subdirectory, update RewriteBase

### Issue: Database Connection Failed

**Solution:**
1. Verify database exists
2. Check credentials in `.env`
3. Ensure database user has privileges
4. Check if DB_HOST should be 'localhost' or IP address

### Issue: White Screen (WSOD)

**Solution:**
1. Enable error reporting temporarily:
   ```php
   // Add to top of api/config/config.php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
2. Check PHP error logs
3. Verify PHP version is 8.1+

### Issue: Gallery Shows No Images

**Solution:**
1. Check database has images: SELECT * FROM gallery;
2. Verify API endpoint works: /api/gallery.php
3. Check browser console for JavaScript errors
4. Ensure `gallery-api.js` is loaded

## Ongoing Maintenance

### Backups

Setup regular backups:
1. **Database**: Export weekly via PHPMyAdmin
2. **Files**: Backup `uploads/gallery/` folder
3. **Code**: Keep in Git repository (excluding .env)

### Updates

1. Keep PHP updated
2. Monitor error logs
3. Test image uploads periodically
4. Review admin access logs

### Adding Content

To add more gallery images:
1. Login to admin panel
2. Upload images with appropriate categories
3. Images immediately appear on gallery page

## Support

If you encounter issues:
1. Check error logs in cPanel
2. Verify all permissions are correct
3. Test each component individually
4. Check browser console for JavaScript errors

---

**Deployment Checklist**

Print this checklist and mark off each item:

- [ ] Created database and user
- [ ] Uploaded all files via FTP
- [ ] Set correct file permissions
- [ ] Imported schema.sql and seed.sql
- [ ] Created and configured .env file
- [ ] Changed admin password
- [ ] Enabled HTTPS
- [ ] Tested homepage
- [ ] Tested gallery page
- [ ] Tested admin login
- [ ] Tested image upload
- [ ] Tested category filters
- [ ] Setup backups
- [ ] Removed test files

## Emergency Rollback

If something goes wrong:
1. Revert to previous backup
2. Check error logs for specific errors
3. Verify database connection first
4. Test API endpoints individually
