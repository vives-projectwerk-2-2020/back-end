# back-end

## install

composer install

## to start-up

php -S localhost:3000 -t public
vendor/bin/phinx migrate

## create new table

vendor/bin/phinx create MyNewMigration