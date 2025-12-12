# Research Survey Application

A pure PHP-based web application for conducting research surveys with participant management and admin analytics. **Fully compatible with shared hosting like cPanel - just upload files and it runs!**

## 🚀 Key Features

- ✅ **Zero Dependencies**: Pure PHP, no Composer, no Node.js, no build tools
- ✅ **CDN Only**: Uses TailwindCSS and Chart.js via CDN
- ✅ **cPanel Ready**: Upload to public_html and it works instantly
- ✅ **Database**: MySQL with simple SQL import
- ✅ **Responsive**: Works on all devices
- ✅ **Secure**: Procedural PHP with proper validation

## 📋 Prerequisites

- **PHP 7.1+** with PDO MySQL extension
- **MySQL 5.7+** database
- **Web Server**: Apache/Nginx (cPanel provides this)

## 🛠️ Installation (cPanel Compatible)

### Step 1: Upload Files
1. Download/extract all project files
2. Upload **EVERYTHING** to your cPanel `public_html` directory
3. **Important**: Do NOT create subdirectories - upload directly to `public_html`

### Step 2: Create Database
1. In cPanel, go to **MySQL Databases**
2. Create a new database (e.g., `yourusername_research`)
3. Create a database user and assign it to the database
4. Note down: database name, username, password

### Step 3: Import Database Schema
1. In cPanel, go to **phpMyAdmin**
2. Select your database
3. Click **Import** tab
4. Upload `database.sql` file
5. Click **Go** to import

### Step 4: Configure Database
1. In cPanel **File Manager**, edit `config.php`
2. Update these lines with your database details:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'yourusername_research');  // Your database name
   define('DB_USER', 'yourusername_user');      // Your database user
   define('DB_PASS', 'yourpassword');           // Your database password
   ```

### Step 5: Access Your Application
- **Participant Survey**: `https://yourdomain.com/`
- **Admin Login**: `https://yourdomain.com/admin/login`

## 🔧 Local Development (Optional)

If you want to run locally for testing:

### Using XAMPP
1. Install XAMPP
2. Copy all files to `htdocs/` directory
3. Start Apache and MySQL in XAMPP
4. Create database and import `database.sql`
5. Update `config.php` with local credentials
6. Access at `http://localhost/`

### Manual Setup
1. Install PHP and MySQL locally
2. Copy files to web server document root
3. Follow database setup steps above
4. Access at your local server URL

## 📖 Usage Guide

### For Participants
1. Visit your domain root URL
2. Fill out the participant information form
3. Complete the 6 survey modules
4. View completion confirmation

### For Administrators
1. Go to `/admin/login`
2. Use default admin credentials (check `database.sql` for users table)
3. Access dashboard, manage questions, view analytics

## 🗂️ File Structure

```
public_html/ (your cPanel directory)
├── index.php              # Main application entry point
├── config.php             # Database configuration
├── database.sql           # Database schema and sample data
├── includes/              # PHP function files
│   ├── admin.php         # Admin panel functions
│   ├── auth.php          # Authentication functions
│   ├── db.php            # Database connection
│   └── survey.php        # Survey logic
└── templates/             # HTML templates
    ├── admin/            # Admin panel pages
    ├── auth/             # Login/register pages
    └── survey/           # Participant survey pages
```

## 🔒 Security Notes

- Change default admin password after first login
- Keep `config.php` secure (contains database credentials)
- The app uses prepared statements for SQL security
- Session-based authentication for admin access

## 🐛 Troubleshooting

### Common Issues
- **"Database connection failed"**: Check `config.php` credentials
- **"Table doesn't exist"**: Re-import `database.sql`
- **"Permission denied"**: Ensure files are uploaded with correct permissions (644 for files, 755 for directories)
- **"Page not found"**: Ensure `.htaccess` is uploaded (if using Apache)

### cPanel Specific
- Some hosts disable certain PHP functions - this app uses only standard functions
- If CDN doesn't load, check if your host blocks external requests
- Database host is usually `localhost` on shared hosting

## 📞 Support

If you encounter issues:
1. Check PHP error logs in cPanel
2. Verify database credentials in `config.php`
3. Ensure all files were uploaded correctly
4. Test with the sample data in `database.sql`

**Remember**: This app is designed for maximum compatibility. If it doesn't work after following these steps, it's likely a hosting configuration issue, not the app itself.</content>
<parameter name="filePath">README.md