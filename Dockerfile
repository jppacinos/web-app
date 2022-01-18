ARG phpVersion="7.4"

FROM php:${phpVersion}-apache

RUN apt-get update && apt-get install -y git zip && apt-get clean

# we install composer in this app
# to access terminal in this comtainer run: docker composer exec app bash
COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG serverName=localhost
RUN echo "ServerName $serverName" >> /etc/apache2/apache2.conf

# we set /public folder as an entry point of our application
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . /var/www/html

EXPOSE 80
