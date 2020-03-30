# back-end 

![Docker Image CI](https://github.com/vives-projectwerk-2-2020/back-end/workflows/Docker%20Image%20CI/badge.svg?branch=develop)

## install

composer install

## to start-up

php -S localhost:3000  
vendor/bin/phinx migrate

## create new table

vendor/bin/phinx create MyNewMigration

## api-data-influxdb

De Hisorische meet data kan je opvragen kan door volgende stappen te volgen.
Eerst roepen we de server aan op de poort van de backend hier vermelden we bij 
dat we de metingen willen opvragen:
```
http://134.209.207.119:8080/measurements
```

door hierbij het id toevoegen krijg je een specifieke sensor:
```
http://134.209.207.119:8080/measurements/nico-prototype-l432
```

Om data te kunnen weergeven moet je een periode en een propertie toevoegen:
```
http://134.209.207.119:8080/measurements/nico-prototype-l432?period=1d&properties=pm25
```

in dit geval vroegen we de pm25 waarde van de sensor van nico dit van de afgelopen 24h.

Dit kan weergegeven worden dankzij insomnia:

![](images/insomnia.PNG)

Extra informatie in verband met de structuur van deze toepassing is te vinden op `https://app.swaggerhub.com/apis-docs/sillevl/Particula/0.1#/`