# Docker Deployment Guide

Quick guide for deploying the Dynamic Online Services WordPress plugin using Docker.

## Prerequisites

- Docker Desktop installed ([Download](https://www.docker.com/products/docker-desktop))
- Docker Compose (included with Docker Desktop)
- At least 4GB of free disk space

## Quick Start

### 1. Setup Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` if you want to customize:

- Database credentials
- WordPress port (default: 8000)
- phpMyAdmin port (default: 8080)

### 2. Start Services

```bash
docker-compose up -d
```

This will:

- Download WordPress 6.9 with PHP 8.3
- Setup MySQL 8.0 database
- Start phpMyAdmin for database management
- Mount your plugin directory

### 3. Access WordPress

Open your browser and navigate to:

```
http://localhost:8000
```

Complete the WordPress installation:

1. Select language
2. Enter site title, username, password, email
3. Click "Install WordPress"

### 4. Activate Plugin

1. Log in to WordPress admin: `http://localhost:8000/wp-admin`
2. Go to **Plugins → Installed Plugins**
3. Find "Dynamic Online Services"
4. Click **Activate**

### 5. Build Plugin Assets (First Time)

The plugin needs compiled assets. Run:

```bash
# Install dependencies
npm install

# Build for production
npm run build

# OR for development with hot reload
npm start
```

## Common Commands

### View Logs

```bash
# All services
docker-compose logs -f

# WordPress only
docker-compose logs -f wordpress

# Database only
docker-compose logs -f db
```

### Stop Services

```bash
docker-compose down
```

### Stop and Remove All Data

```bash
docker-compose down -v
```

⚠️ **Warning:** This will delete your database and WordPress installation!

### Restart Services

```bash
docker-compose restart
```

### Rebuild Containers

```bash
docker-compose up -d --build
```

## Access Points

| Service | URL | Credentials |
|---------|-----|-------------|
| WordPress | <http://localhost:8000> | Set during installation |
| WordPress Admin | <http://localhost:8000/wp-admin> | Set during installation |
| phpMyAdmin | <http://localhost:8080> | root / rootpassword |

## Database Access

### Via phpMyAdmin

1. Open <http://localhost:8080>
2. Login with:
   - **Username:** `root`
   - **Password:** `rootpassword` (or your custom value from `.env`)

### Via Command Line

```bash
docker-compose exec db mysql -u root -p
```

Enter password: `rootpassword`

## Plugin Development Workflow

### 1. Make Code Changes

Edit files in your local directory. Changes are immediately reflected in the container.

### 2. Rebuild Assets

If you modify JavaScript or CSS:

```bash
npm run build
```

Or use watch mode:

```bash
npm start
```

### 3. Clear WordPress Cache

If using LiteSpeed Cache or other caching plugins:

1. Go to WordPress admin
2. Clear cache from the plugin settings

### 4. Test Changes

Refresh your browser to see changes.

## Troubleshooting

### Port Already in Use

If port 8000 or 8080 is already in use:

1. Edit `.env` file
2. Change `WP_PORT` or `PMA_PORT`
3. Restart: `docker-compose down && docker-compose up -d`

### WordPress Installation Loop

If WordPress keeps asking to install:

1. Stop containers: `docker-compose down`
2. Remove volumes: `docker volume rm dynamic-online-services_wordpress_data`
3. Start again: `docker-compose up -d`

### Plugin Not Showing

1. Check if plugin directory is mounted:

   ```bash
   docker-compose exec wordpress ls -la /var/www/html/wp-content/plugins/
   ```

2. Ensure you're in the plugin directory when running `docker-compose up`

### Database Connection Error

1. Wait for database to be ready (check logs):

   ```bash
   docker-compose logs db
   ```

2. Restart WordPress container:

   ```bash
   docker-compose restart wordpress
   ```

### Permission Issues

If you get permission errors:

```bash
# Fix permissions
docker-compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content/plugins/dynamic-online-services
```

## Development Tips

### Enable Debug Mode

Debug mode is enabled by default. Check logs:

```bash
docker-compose exec wordpress tail -f /var/www/html/wp-content/debug.log
```

### Install Additional Plugins

```bash
docker-compose exec wordpress wp plugin install <plugin-name> --activate --allow-root
```

### Install Themes

```bash
docker-compose exec wordpress wp theme install <theme-name> --activate --allow-root
```

### Run WP-CLI Commands

```bash
docker-compose exec wordpress wp --allow-root <command>
```

Examples:

```bash
# List plugins
docker-compose exec wordpress wp plugin list --allow-root

# Update WordPress
docker-compose exec wordpress wp core update --allow-root

# Create a test post
docker-compose exec wordpress wp post create --post_type=service --post_title="Test Course" --post_status=publish --allow-root
```

## Testing the Plugin

### 1. Create Test Content

1. Go to **Courses → Add New**
2. Create a test course with:
   - Title
   - Content
   - Featured image
   - Category
   - FAQs

### 2. Test Shortcodes

Create a new page and add:

```
[service_cards category="web-development" columns="3" limit="6"]
```

### 3. Test Gutenberg Block

1. Create a new page
2. Add the "Service Cards" block
3. Configure settings in the sidebar

### 4. Test Settings

Go to **Settings → Dynamic Services** and customize:

- Hero section colors
- Card styles
- FAQ accordion appearance

## Production Deployment

⚠️ **This Docker setup is for development only!**

For production deployment:

1. Use managed WordPress hosting
2. Or use production-ready Docker images with:
   - SSL/TLS certificates
   - Proper security hardening
   - Backup solutions
   - CDN integration

## Backup & Restore

### Backup Database

```bash
docker-compose exec db mysqldump -u root -p wordpress > backup.sql
```

### Restore Database

```bash
docker-compose exec -T db mysql -u root -p wordpress < backup.sql
```

### Backup Uploads

```bash
docker cp dynos-wordpress:/var/www/html/wp-content/uploads ./uploads-backup
```

## Clean Up

### Remove Everything

```bash
# Stop and remove containers, networks, volumes
docker-compose down -v

# Remove images (optional)
docker rmi wordpress:6.9-php8.3-apache mysql:8.0 phpmyadmin:latest
```

## Support

For plugin-specific issues, see:

- [Plugin Documentation](README.md)
- [GitHub Issues](https://github.com/techmire-solutions/dynamic-online-services/issues)

For Docker issues:

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
