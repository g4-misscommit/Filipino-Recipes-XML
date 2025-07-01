FROM php:8.2-apache

# Install MySQLi and PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all files to Apache's web root
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80
