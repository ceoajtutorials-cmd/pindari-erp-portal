FROM php:8.1-apache
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY . /var/www/html/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html/application/logs
RUN chmod -R 777 /var/www/html/public
EXPOSE 80
