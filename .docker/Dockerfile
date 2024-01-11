FROM php:8.2-apache

COPY /usr/local/etc/php/php.ini-production /usr/local/etc/php/conf.d/php.ini
RUN sed -e 's/max_execution_time = 30/max_execution_time = 3000/' -i /usr/local/etc/php/conf.d/php.ini
RUN sed -n 'H;${x;s/^\n//;s/DirectoryIndex .*$/SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=\$1\n    &/;p;}' -i /etc/apache2/sites-enabled/000-default.conf

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        unzip \
        git \
        cron \
        nano \
        supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    pdo \
    pdo_pgsql \
    zip \
    pdo \
    pdo_mysql \
    opcache

# Install XDEBUG, APCU
RUN pecl install xdebug apcu
RUN docker-php-ext-enable xdebug apcu

# Install symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install Composer CLI
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setup crontab
COPY ./crontab /var/www/html/.docker/crontab
RUN crontab /var/www/html/.docker/crontab

# You can add your own aliases here
RUN echo 'alias console="php /var/www/html/bin/console"' >> ~/.bashrc
RUN echo 'alias phpstan="/var/www/html/vendor/phpstan/phpstan/phpstan"' >> ~/.bashrc
RUN echo 'alias dsu="/var/www/html/bin/console d:s:u --force --complete"' >> ~/.bashrc

RUN echo "umask 0000" >> /root/.bashrc

# Start Supervisor, which will run cron -f by default.
CMD ["/usr/bin/supervisord"]