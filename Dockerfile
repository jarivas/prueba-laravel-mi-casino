FROM php:8.2-apache-bookworm

RUN apt-get -y update
RUN apt-get -y install git zip curl

RUN curl -fsSL https://deb.nodesource.com/setup_23.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN apt-get install -y nodejs

WORKDIR /app

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions zip # pdo_mysql

RUN adduser dev

USER dev

ENTRYPOINT ["tail", "-f", "/dev/null"]