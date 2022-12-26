FROM php:8.2-cli
COPY . /app
WORKDIR /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip
RUN apt-get -y update
RUN apt-get -y install git
ENTRYPOINT ["tail", "-f", "/dev/null"]