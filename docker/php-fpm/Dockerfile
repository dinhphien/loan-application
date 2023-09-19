FROM php:8.2-fpm AS build-base

ARG USER_ID
ARG GROUP_ID
##
# install needed php packages
RUN apt-get update  \
    && apt-get install -y tzdata curl zip unzip vim \
    && docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
##
# set timezone
ENV TZ=Asia/Ho_Chi_Minh
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

##
# define working directory
WORKDIR /mnt/code

##
# stage for development
FROM build-base AS build-dev

# add packages for the development state
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# update user www-data with host's user_id and group_id
RUN usermod -u $USER_ID www-data \
    && groupmod -g $GROUP_ID www-data
USER www-data

COPY docker/php-fpm/custom-xdebug.ini /usr/local/etc/php/conf.d/custom-xdebug.ini
COPY docker/php-fpm/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini



