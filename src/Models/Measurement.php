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
        $result = $database->query('select * from sensors GROUP BY sensor_id');
        return $result->getPoints();
    }

    public static function find($id, $period, $properties)
    {
        $database = self::$database;

        //put the time parameter in easyer to process way
        $period_range =  substr($period, -1);
        $period_time = (int) substr($period, 0, -1);
        if ($period_range == "y") {
            $period_time *= 365;
            $period_range = "d";
        }

        //untested
        $new_date = $period_time . $period_range;
        //echo "select pm10,pm25,temperature,humidity FROM sensors WHERE sensor_id = $id AND time > now() - $new_date";

        $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ AND time > now() - $new_date";
        $result = $database->query($query);

        if ($period_range == "d") {
            //remove time from response and make response shorter
            $decoded = $result->getPoints();
        } else {
            //remove time from response
            $decoded = $result->getPoints();
            for ($i = 0; $i < count($decoded); $i++) {
                unset($decoded[$i]['time']);
            }
        }

        return $decoded;
    }
}
