FROM php:8.1.28-cli-alpine as base

COPY --from=composer:2.7.4 /usr/bin/composer /usr/bin/composer

FROM base

ENV COMPOSER_HOME=/tmp/.composer

WORKDIR /app
COPY . /app

RUN Resources/Private/Build/test.sh
