# back-end 

![Docker Image CI](https://github.com/vives-projectwerk-2-2020/back-end/workflows/Docker%20Image%20CI/badge.svg?branch=develop)

## install

composer install

## to start-up

php -S localhost:3000  
vendor/bin/phinx migrate

## create new table

vendor/bin/phinx create MyNewMigration
