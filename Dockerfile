FROM php:8.3-apache
RUN apt-get update && apt-get install -y --no-install-recommends unzip && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install pdo_mysql && a2enmod rewrite \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
COPY trabzonparkur-v3.7-php-railway.zip /tmp/trabzonparkur.zip
RUN unzip -q /tmp/trabzonparkur.zip -d /var/www/html && rm /tmp/trabzonparkur.zip \
 && cp /var/www/html/railway-start.sh /usr/local/bin/railway-start \
 && chmod +x /usr/local/bin/railway-start \
 && chown -R www-data:www-data /var/www/html/uploads \
 && mv /var/www/html/railway-init.php /usr/local/bin/railway-init.php \
 && rm -f /var/www/html/railway-start.sh /var/www/html/install.php /var/www/html/upgrade.php /var/www/html/Dockerfile /var/www/html/railway.json /var/www/html/RAILWAY.txt
CMD ["/usr/local/bin/railway-start"]
