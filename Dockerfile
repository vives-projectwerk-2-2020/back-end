# intall php
FROM php:7.3.2

RUN apt update && apt install git -y

# make directory
WORKDIR /back-end

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY composer.* ./

# instal dpendecies
RUN composer install && docker-php-ext-install pdo pdo_mysql

# copy in directory
COPY . .

# open poort
EXPOSE 3000

# start server
CMD vendor/bin/phinx rollback && vendor/bin/phinx migrate && php -S 0.0.0.0:3000 -t public
