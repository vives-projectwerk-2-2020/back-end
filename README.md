# Particula Backend

master:
![PHPCS PSR2 Linter](https://github.com/vives-projectwerk-2-2020/back-end/workflows/PHPCS%20PSR2%20Linter/badge.svg?branch=master)
![Docker Image CI](https://github.com/vives-projectwerk-2-2020/back-end/workflows/Docker%20Image%20CI/badge.svg?branch=master)

develop:
![PHPCS PSR2 Linter](https://github.com/vives-projectwerk-2-2020/back-end/workflows/PHPCS%20PSR2%20Linter/badge.svg?branch=develop)
![Docker Image CI](https://github.com/vives-projectwerk-2-2020/back-end/workflows/Docker%20Image%20CI/badge.svg?branch=develop)

## install

```bash
composer install
```

## make .env

coppy the .env.examble  
and change the name to .env

## make a mysql db

make sure your db_name in the env file is the same of the one you use for your mysql db

## to start-up

```bash
vendor/bin/phinx migrate  
php -S localhost:3000 -t public
```

## create new table

vendor/bin/phinx create MyNewMigration

## api-data-influxdb

De Hisorische data kan je opvragen door volgende stappen te volgen.
Eerst roepen we de server aan op de poort van de backend, hier vermelden we bij 
dat we de meetingen willen opvragen:

```
http://134.209.207.119:8080/measurements
```

door hierbij het id toe te voegen krijg je een specifieke sensor:

```
http://134.209.207.119:8080/measurements/nico-prototype-l432
```

Om data te kunnen weergeven moet je een periode en een propertie toevoegen:

```
http://134.209.207.119:8080/measurements/nico-prototype-l432?period=1d&properties=pm25
```

in dit geval voegen we de pm2.5 waarde van de sensor van nico dit van de afgelopen 24h.

Dit kan ook weergegeven worden dankzij insomnia:

![](images/insomnia.PNG)

Extra informatie in verband met de structuur van deze toepassing is te vinden op `https://app.swaggerhub.com/apis-docs/sillevl/Particula/0.1#/`

## Development

### Linter

Check if the code complies to the PSR2 recommendations with the following command:

```bash
composer lint
```

### Linter autofix

Some Linter errors and warnings can be fixed automatically. Use the following composer command:

```bash
composer lint-fix
```

### Migrations

To run a database migration use the command:

```bash
composer migrate
```
