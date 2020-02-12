# intall php
FROM php:7.3.2

# make directory
WORKDIR /back-end

# instal dpendecies
RUN composer install

# copy in directory
COPY . .

# open poort
EXPOSE 3000

# start server
CMD php -S localhost:3000