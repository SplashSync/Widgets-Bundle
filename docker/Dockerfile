################################################################################
#
#  This file is part of SplashSync Project.
# 
#  Copyright (C) Splash Sync <www.splashsync.com>
# 
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
# 
#  For the full copyright and license information, please view the LICENSE
#  file that was distributed with this source code.
# 
#  @author Bernard Paquier <contact@splashsync.com>
#
################################################################################

FROM php:7.3-alpine
EXPOSE 80
WORKDIR /app

################################################################################
# Install Required Libs
RUN apk add nano git 
#RUN apk add libzip-dev zlib1g-dev
RUN apk add libzip-dev php7-zip
################################################################################
# Install Required PHP Extensions
RUN docker-php-ext-install zip
################################################################################
# Install Composer
ENV COMPOSER_MEMORY_LIMIT: -1
ENV COMPOSER_HOME: '/home/docker/.composer'
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 
#################################################################################
## Clone Project
#RUN git clone https://github.com/SplashSync/Toolkit.git --depth=1 /app
#################################################################################
## Configure Project
#COPY .env /app/.env
#COPY config/splash.yml /app/config/packages/splash.yml
#################################################################################
## Run Composer
#RUN cd /app && php -d memory_limit=-1 /usr/bin/composer update
##RUN cd /app && php -d memory_limit=-1 /usr/bin/composer install
#################################################################################
## Install Project
#RUN php /app/bin/console assets:install --no-debug
#RUN php /app/bin/console doctrine:schema:update --force
#RUN php /app/bin/console cache:clear

CMD ["php", "/app/bin/console", "server:run", "80"]