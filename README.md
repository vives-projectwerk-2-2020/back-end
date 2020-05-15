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

The historical data is accessible by following these steps.
The measurements of a sensor can be requested via the following url using its GUID.

```
https://develop.particula.devbitapp.be/measurements/{GUID}
```

To be able to display data you have to enter a period: (Available values: 1h, 24h, 7d, 30d, 1y, 3y, all)

Default value : 24h

```
https://develop.particula.devbitapp.be/measurements/{GUID}?period=1h&properties=pm2.5
```

in this case we add the pm2.5 value of the sensor for the past 1h.

This can also be displayed thanks to insomnia:

![](images/insomnia.PNG)

When values greater than 1h (24h, 7d, 30d, 1y, 3y, all) are requested, averages are used so that the number of responses is limited to 300-400.
When you request data that is not yet in the database, you will receive NULL for the nonexistent data as shown below.

![](images/insomniaOld.PNG)

Additional information related to the structure of this application can be found at: [SwaggerHub Particula](https://app.swaggerhub.com/apis-docs/sillevl/Particula)

## Routes for MariaDB

`Sensors` and `Users` are stored in a MariaDB database and can be managed with the information provided in this chapter. 

### Sensors

The sensors table can be managed using GET, POST, PUT and DELETE request. More information about each request is listed bellow.

All sensors with their information can be obtained with following GET request:

```
GET https://<ip>/sensors
```

The format of the result is specified at [SwaggerHub Particula](https://app.swaggerhub.com/apis-docs/sillevl/Particula), for example:

![GET request sensors](images/get_sensors.jpg)

A sensor can be added to the database sending following POST request:

```
POST https://<ip>/sensors
```

A sensor can only be created if all information is entered:

![POST request sensors](images/get_sensors.jpg)

A sensor can be edited by using the sensor GUID:

```
PUT https://<ip>/sensors/{GUID}
```

![PUT request sensors](images/put_sensors.jpg)

Finally a sensor can be removed by its GUID:

```
DELETE https://<ip>/sensors/{GUID}
```

### Users

Users can be managed using a GET, POST, PUT or DELETE request. More information about each request and its output can be found underneath.

A list of users can be found using:

```
GET https://<ip>/users
```

![GET request users](images/get_users.jpg)

The information of one user can be found by adding the username to the previous request:

```
GET https://<ip>/users/{username}
```

A new user can be added as follows:

```
POST https://<ip>/users
```

A user can only be added if all of the following information is provided:

![POST request users](images/post_users.jpg)

A user can be updated by its username:

```
PUT https://<ip>/users/{username}
```

![PUT request users](images/put_users.jpg)

At last a user can be deleted using a username:

```
DELETE https://<ip>/users/{username}
```

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

### Unfinished

- Back-end API & Authentication API have the same functionality implemented
  - Authentication API uses query's from back-end
- Particle from SwaggerHub is not implemented