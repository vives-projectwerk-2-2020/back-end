<?php

namespace App\Models;

use InfluxDB\Client;

class Measurement
{
    private static $database;
    private static $logger;

    public static function setDatabase($database)
    {
        self::$database = $database;
    }

    public static function all()
    {
        $database = self::$database;
        $result = $database->query('select * from sensors WHERE time > now() - 1h GROUP BY sensor_id');
        return $result->getPoints();
    }

    public static function find($id, $period, $properties)
    {
        $database = self::$database;

        //convert guid to dev_id
        $sensor = Sensor::find($args['guid']);
        $id = $sensor->name;

        //put the time parameter in easier to process way
        $period_range =  substr($period, -1);
        $period_time = (int) substr($period, 0, -1);

        if ($period_range == "y") {
            $period_time *= 365;
            $period_range = "d";
        }

        if ($properties == "pm2.5") {
            $properties = "pm25";
        }

        $new_date = $period_time . $period_range;

        $validProperties = $properties != "all" && $properties != "" && $properties != "pm10"
            && $properties != "humidity" && $properties != "pm25" && $properties != "pressure"
            && $properties != "temperature";

        $meanProperties = "MEAN($properties) as $properties";
        $time = " AND time > now() - ";

        if ($period == "all") {
            $groupBy = " GROUP BY time(3d)";
            $time = "";
            $new_date = "";
        } elseif ($period == "last") {
            $groupBy = " LIMIT 1";
            $time = "";
            $new_date = "";
            $meanProperties = $properties;
        } elseif ($new_date == "1095d") {
            $groupBy = " GROUP BY time(3d)";
        } elseif ($new_date == "365d") {
            $groupBy = " GROUP BY time(24h)";
        } elseif ($new_date == "30d") {
            $groupBy = " GROUP BY time(2h)";
        } elseif ($new_date == "7d") {
            $groupBy = " GROUP BY time(30m)";
        } elseif ($new_date == "1h") {
            $groupBy = "";
            $meanProperties = $properties;
        } elseif (($new_date == "24h" || ($period == ""))) {
            //Default value : 24h
            $groupBy = " GROUP BY time(5m)";
            $new_date = "24h";
        } else {
            $errorMessage = "ERROR: 400 Invalid Period ";
        }

        if ($properties == "all" || $properties == "") {
            if ($new_date == "1h" || $period == "last") {
                $meanProperties = "pm10,humidity,pm25,
                pressure,temperature";
            } else {
                $meanProperties = "MEAN(pm10) as pm10, MEAN(humidity) as humidity, MEAN(pm25) as pm25,
                MEAN(pressure) as pressure, MEAN(temperature) as temperature";
            }
        }

        if ($validProperties) {
            $errorMessage = "ERROR: 400 Invalid properties ";
        }

        $query = "select $meanProperties FROM sensors WHERE sensor_id =~ /$id/ 
            $time $new_date $groupBy";

        $result = $database->query($query);

        $decoded = $result->getPoints();

        if ($errorMessage == "" &&  empty($decoded)) {
            $errorMessage = "ERROR: 400 Invalid id ";
        }


        // for ($i = 0; $i < count($decoded); $i++) {
        //     //remove time from response
        //     unset($decoded[$i]['time']);
        // }

        if ($errorMessage == "") {
            return $decoded;
        } else {
            return $errorMessage;
        }
    }
}
