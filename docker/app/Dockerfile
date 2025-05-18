FROM node:12-alpine
LABEL Maintainer="Reishou <thorhuynhduc@gmail.com>" \
      Description="Lightweight container with Nginx & PHP-FPM 8 based on Alpine Linux."

# Install packages
RUN apk --no-cache add bash zlib zlib-dev php8 php8-fpm php8-bcmath php8-ctype php8-json php8-fileinfo \
    php8-mbstring php8-openssl acl php8-pdo_pgsql php8-curl php8-pdo php8-tokenizer php8-xml \
    php8-opcache nginx curl openssh openssl runit openrc php8-simplexml php8-dom php8-xmlwriter \
    php8-sockets php8-phar git php8-gd php8-iconv php8-xmlreader php8-zip php8-session php8-sodium php8-imap php8-pdo_mysql php8-zlib php8-exif nano

RUN apk add --no-cache -X http://dl-cdn.alpinelinux.org/alpine/edge/testing
#  php8-pecl-grpc

# fix work iconv library with alphine
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN cp /usr/bin/php8 /usr/bin/php

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN apk add --update supervisor
COPY supervisord.conf /etc/supervisord.conf
COPY queue/*.conf /etc/supervisor.d/

# Configure nginx
COPY nginx.conf /etc/nginx/nginx.conf
# Remove default server definition
#RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY php.ini /etc/php8/conf.d/custom.ini

# Configure runit boot script
COPY boot.sh /sbin/boot.sh

RUN adduser -D -u 1001 -g 1001 -s /bin/sh www && \
    mkdir -p /var/www/html && \
    mkdir -p /var/cache/nginx && \
    chown -R www:www /var/www/html && \
    chown -R www:www /run && \
    chown -R www:www /var/lib/nginx && \
    chown -R www:www /var/log/nginx

COPY nginx.run /etc/service/nginx/run
COPY php.run /etc/service/php/run

RUN chmod +x /etc/service/nginx/run \
    && chmod +x /etc/service/php/run \
    && ls -al /var/www/html/

WORKDIR /var/www/html

# Expose the port nginx is reachable on
EXPOSE 80

# Let boot start nginx & php-fpm
CMD ["sh", "/sbin/boot.sh"]

