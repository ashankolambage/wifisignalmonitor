FROM php:8.2-fpm-alpine
ARG UID
RUN apk --update add shadow
RUN usermod -u $UID www-data && groupmod -g $UID www-data
RUN apk --update add sudo
RUN echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
RUN apk --update add composer


RUN apk add --no-cache php-openssl  
RUN apk add --no-cache php-pdo_mysql  
RUN apk add --no-cache php-mbstring  
RUN apk add --no-cache php-dom  
RUN apk add --no-cache php-fileinfo  
RUN apk add --no-cache php-xmlwriter  
RUN apk add --no-cache php-xmlreader 
RUN apk add --no-cache php-xml 
RUN apk add --no-cache php-tokenizer 
RUN apk add --no-cache php-exif 
RUN apk add --no-cache php-gd
RUN apk add --no-cache php-session

RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
  docker-php-ext-configure gd --with-freetype --with-jpeg NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j$(nproc) gd && \
  apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN apk add --update npm
RUN apk add --update make
