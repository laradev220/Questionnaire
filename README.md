# Research Survey Application

A procedural PHP-based web application for conducting research surveys with participant management and admin analytics.

## Prerequisites

- PHP 7.1 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server

## Installation

1. Clone the repository:
    ```bash
    git clone <repository-url>
    cd research-app
    ```

2. Set up the database:
    - Create a MySQL database named `research_db`
    - Import the schema:
      ```bash
      mysql -u root -p research_db < schema.sql
      ```
    - Update database credentials in `public/config.php`

3. Configure database settings:
    - Edit `public/config.php` and update DB_HOST, DB_NAME, DB_USER, DB_PASS

## Running Locally

### Using XAMPP

1. Place the `public/` directory contents in `htdocs` directory (or set document root to `public/`)
2. Start XAMPP (Apache and MySQL)
3. Access the application at `http://localhost/`

### Manual Setup

1. Copy `public/` to your web server's document root
2. Ensure PHP has PDO MySQL extension enabled
3. Access at your server's URL

## Usage

### Participant Flow
- Visit the root URL to start the survey
- Fill out the participant form
- Complete survey modules
- View thank-you page upon completion

### Admin Panel
- Access admin login at `/admin/login`
- Manage questions, view analytics, and dashboard

## Deployment

### Manual Deployment
1. Upload `public/` directory to web server
2. Ensure PHP and MySQL are configured
3. Set database credentials in `config.php`
4. Import `schema.sql` to database

## Project Structure

- `public/`: Web root with all application files
  - `index.php`: Main entry point
  - `config.php`: Database configuration
  - `includes/`: Procedural function files
  - `templates/`: HTML templates
- `database.sql`: Sample data
- `schema.sql`: Database schema

## Technologies Used

- PHP (procedural)
- MySQL
- Tailwind CSS (via CDN)
- Font Awesome (via CDN)