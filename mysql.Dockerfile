FROM mariadb

ENV MYSQL_USER Chinook
ENV MYSQL_PASSWORD Chinook

RUN apt-get -y update
RUN apt-get -y install openssh-server
RUN useradd -m -s /usr/bin/bash $MYSQL_USER
RUN echo "${MYSQL_USER}:${MYSQL_PASSWORD}" | chpasswd