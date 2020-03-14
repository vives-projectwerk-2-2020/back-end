# back-end

## install

composer install

## make .env

coppy the .env.examble  
and change the name to .env

## make a mysql db

make sure your db_name in the env file is the same of the one you use for your mysql db

## to start-up

vendor/bin/phinx migrate
php -S localhost:3000 -t public

## create new table

vendor/bin/phinx create MyNewMigration