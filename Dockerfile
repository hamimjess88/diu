# Use the official PHP image from Docker Hub
FROM php:8.1-apache

# Copy all project files into the web server's root directory
COPY . /var/www/html/

# Set proper permissions for the files
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (default web server port)
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
