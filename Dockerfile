FROM php:7.4.23-cli-alpine as base

COPY --from=composer:1.10.24 /usr/bin/composer /usr/bin/composer

FROM base

ENV COMPOSER_HOME=/tmp/.composer

#WORKDIR /app
#COPY . /app

#RUN Resources/Private/Build/test.sh
