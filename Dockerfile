FROM php:8.1-alpine
RUN apk update && apk upgrade && apk add git unzip
COPY --from=composer/composer:2-bin /composer /usr/bin/composer
