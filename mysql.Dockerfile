FROM mariadb

ARG MYSQL_USER
ARG MYSQL_PASSWORD

COPY ./db/mysql-entrypoint.sh /home/Chinook/mysql-entrypoint.sh

RUN apt-get -y update
RUN apt-get -y install openssh-server
RUN useradd -m -s /usr/bin/bash $MYSQL_USER
RUN echo "${MYSQL_USER}:${MYSQL_PASSWORD}" | chpasswd