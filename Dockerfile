FROM php:alpine

LABEL maintainer="ahmadov90@gmail.com"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


WORKDIR /tmp
COPY composer.json composer.lock /tmp/
RUN composer install --prefer-dist -o && \
    rm -rf composer.json composer.lock database/ vendor/


WORKDIR /app
COPY . /app
COPY .env.example /app/.env
RUN composer install --prefer-dist -o


COPY onlineaz /usr/local/bin/onlineaz
RUN chmod +x /usr/local/bin/onlineaz


CMD ["sh", "-c", "while true; do onlineaz check & sleep 3600; done"]
