FROM php:8.3-apache
RUN docker-php-ext-install pdo_mysql && a2enmod rewrite \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
COPY . /var/www/html/
COPY railway-start.sh /usr/local/bin/railway-start
RUN chmod +x /usr/local/bin/railway-start && mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads \
 && mv /var/www/html/railway-init.php /usr/local/bin/railway-init.php \
 && rm -f /var/www/html/railway-start.sh /var/www/html/Dockerfile /var/www/html/railway.json \
 && rm -rf /var/www/html/.git
CMD ["/usr/local/bin/railway-start"]
