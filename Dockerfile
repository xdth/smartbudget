FROM php:7.3-cli

RUN apt-get update -y && apt-get install -y libmcrypt-dev wget git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring

WORKDIR /app
COPY . /app
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN composer install

EXPOSE 8000
CMD php bin/console cache:clear && symfony serve