<<<<<<< HEAD
# intall php
FROM php:7.3.2

RUN apt update && apt install git -y

# make directory
WORKDIR /back-end

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY composer.* ./

# instal dpendecies
RUN composer install

# copy in directory
COPY . .

# open poort
EXPOSE 3000

# start server
CMD php -S 0.0.0.0:3000
=======
# intall php
FROM php:7.3.2

RUN apt update && apt install git -y

# make directory
WORKDIR /back-end

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY composer.* ./

# instal dpendecies
RUN composer install

# copy in directory
COPY . .

# open poort
EXPOSE 3000

ENV APP_URL=

# start server
CMD php -S ${APP_URL}
>>>>>>> f3e5d213d59376daa35b773e228f1abc54a68ce2
