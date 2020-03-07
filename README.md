# back-end

## install

composer install

## to start-up

php -S localhost:3000
vendor/bin/phinx migrate -e production

## create new table

vendor/bin/phinx create MyNewMigration
