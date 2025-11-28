# Research Survey Application

A PHP-based web application for conducting research surveys with participant management and admin analytics.

## Prerequisites

- PHP 7.1 or higher
- MySQL 5.7 or higher
- Composer (for dependency management)
- Apache or Nginx web server (or use Docker)

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd research-app
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Set up the database:
   - Create a MySQL database named `research_db`
   - Import the schema:
     ```bash
     mysql -u root -p research_db < schema.sql
     ```
   - Update database credentials in `.env` file (see Configuration section)

4. Configure environment variables:
   - Copy `.env.example` to `.env` and update the values

## Configuration

Create a `.env` file in the root directory with the following variables:

```
DB_HOST=localhost
DB_NAME=research_db
DB_USER=root
DB_PASS=your_password
```

## Running Locally

### Using XAMPP

1. Place the project in `htdocs` directory
2. Start XAMPP (Apache and MySQL)
3. Access the application at `http://localhost/research-app/public/`

### Using Docker

1. Ensure Docker and Docker Compose are installed
2. Run:
   ```bash
   docker-compose up --build
   ```
3. Access at `http://localhost:8080`

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

### Docker Deployment
Use the provided `docker-compose.yml` for easy deployment.

### Manual Deployment
1. Upload files to web server
2. Ensure PHP and MySQL are configured
3. Set environment variables
4. Run database migrations if needed

## Project Structure

- `public/`: Web root with entry point
- `src/`: PHP controllers and database class
- `views/`: BladeOne templates
- `config/`: Configuration files
- `cache/`: BladeOne cache directory
- `vendor/`: Composer dependencies

## Technologies Used

- PHP
- MySQL
- BladeOne templating engine
- Composer for dependency management