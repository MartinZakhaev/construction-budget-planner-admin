# Dokploy Deployment Guide

This guide will help you deploy the Construction Budget Planner to your VPS using Dokploy with PostgreSQL database.

## Prerequisites

1. **VPS with Dokploy installed**
2. **PostgreSQL database** running on the VPS (43.157.213.47)
3. **Domain**: `planin.43-157-213-47.sslip.io`
4. **Git repository** with your application code

## Database Setup

### 1. Create PostgreSQL Database

```sql
-- Connect to PostgreSQL as superuser
sudo -u postgres psql

-- Create database and user
CREATE DATABASE filament;
CREATE USER filament WITH PASSWORD 'your_secure_password_here';
GRANT ALL PRIVILEGES ON DATABASE filament TO filament;
ALTER USER filament CREATEDB;

-- Exit PostgreSQL
\q
```

### 2. Test Database Connection

```bash
# Test connection from VPS
psql -h 43.157.213.47 -U filament -d filament -W

# Test connection from Docker (after deployment)
docker exec -it construction-budget-planner psql -h 43.157.213.47 -U filament -d filament -W
```

## Dokploy Configuration

### 1. Create New Application

1. Log into your Dokploy dashboard
2. Click "New Application"
3. Select "Docker Compose" as application type
4. Connect your Git repository

### 2. Environment Variables

Set the following environment variables in Dokploy:

```bash
# Required
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
DB_PASSWORD=your_secure_password_here

# Optional (override defaults)
HTTP_PORT=8080
LOG_LEVEL=error
TZ=UTC

# Database (if different from defaults)
DB_HOST=43.157.213.47
DB_PORT=5432
DB_DATABASE=filament
DB_USERNAME=filament
```

### 3. Generate APP_KEY

```bash
# Generate Laravel app key
docker run --rm -it php:8.4-cli php artisan key:generate --show

# Or generate locally and copy
php artisan key:generate --show
```

### 4. Domain Configuration

1. In Dokploy, go to "Domains" tab
2. Add domain: `planin.43-157-213-47.sslip.io`
3. Enable SSL (Let's Encrypt)
4. Set port to `8080`

### 5. Deploy Settings

- **Build Context**: Root directory
- **Docker Compose File**: `docker-compose.yml`
- **Auto-deploy**: Enable (optional)
- **Health Check**: `/health` path on port `8080`

## Initial Database Setup

### 1. Run Migrations

After first deployment, access the container and run migrations:

```bash
# Access container
docker exec -it construction-budget-planner bash

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Create admin user
php artisan tinker
# Then run: User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password')])
```

### 2. Optimize Application

```bash
# Clear and cache all
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## Monitoring and Maintenance

### Health Checks

The application includes health checks at:
- **Endpoint**: `/health`
- **Port**: `8080`
- **Interval**: 30 seconds
- **Timeout**: 10 seconds

### Logs

View application logs:

```bash
# View container logs
docker logs construction-budget-planner

# View Laravel logs
docker exec -it construction-budget-planner tail -f storage/logs/laravel.log
```

### Backup Strategy

1. **Database Backup** (run on VPS host):

```bash
# Create backup script
cat > /home/user/backup-db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/user/backups"
mkdir -p $BACKUP_DIR

pg_dump -h 43.157.213.47 -U filament -d filament > $BACKUP_DIR/filament_backup_$DATE.sql
gzip $BACKUP_DIR/filament_backup_$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "filament_backup_*.sql.gz" -mtime +7 -delete
EOF

chmod +x /home/user/backup-db.sh

# Add to crontab (daily at 2 AM)
crontab -e
# Add: 0 2 * * * /home/user/backup-db.sh
```

2. **File Storage Backup**:

```bash
# Backup storage directory
tar -czf /home/user/backups/storage_backup_$(date +%Y%m%d).tar.gz -C /path/to/app/storage .
```

## Troubleshooting

### Common Issues

1. **Database Connection Failed**:
   - Check PostgreSQL is running: `sudo systemctl status postgresql`
   - Verify credentials in environment variables
   - Test connection: `psql -h 43.157.213.47 -U filament -d filament -W`

2. **502 Bad Gateway**:
   - Check container logs: `docker logs construction-budget-planner`
   - Verify health check endpoint is accessible
   - Check port mapping in docker-compose.yml

3. **SSL Certificate Issues**:
   - Ensure domain points to correct IP
   - Check Let's Encrypt rate limits
   - Verify DNS propagation

### Performance Optimization

1. **PHP OPcache**: Already configured in Dockerfile
2. **Nginx Caching**: Static assets cached for 1 year
3. **Database**: Consider adding connection pooling
4. **Redis**: Optional for session/cache storage

### Security Considerations

1. **Regular Updates**: Keep dependencies updated
2. **Firewall**: Configure UFW on VPS
3. **Database**: Use strong passwords and limited privileges
4. **SSL**: Always use HTTPS in production

## Scaling Considerations

For higher traffic, consider:

1. **Multiple Containers**: Use Docker Swarm or Kubernetes
2. **Load Balancer**: Configure multiple app instances
3. **Database Optimization**: Add read replicas
4. **CDN**: Use CloudFlare or similar for static assets
5. **Redis**: Add for session storage and caching

## Support

For issues related to:
- **Dokploy**: Check Dokploy documentation
- **Laravel**: Refer to Laravel documentation
- **PostgreSQL**: Check PostgreSQL logs
- **Docker**: Review Docker logs and configuration