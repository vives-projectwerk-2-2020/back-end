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

        $new_date = $period_time . $period_range;

        if ($period_range == "all") {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                GROUP BY time(24h)";
        } elseif ($period_range == "d" && $period_time == 1095) {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - 1095d GROUP BY time(24h)";
        } elseif ($period_range == "d" && $period_time == 365) {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - 365d GROUP BY time(24h)";
        } elseif ($period_range == "d" && $period_time == 30) {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - 30d GROUP BY time(1h)";
        } elseif ($period_range == "d" && $period_time == 7) {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - 7d GROUP BY time(30m)";
        } elseif ($period_range == "h" && $period_time == 24) {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - 24h GROUP BY time(5m)";
        } elseif ($period_range == "h" && $period_time == 1) {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - 1h";
        } else {
            $query = "select $properties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - $new_date";
        }

        //echo "select pm10,pm25,temperature,humidity FROM sensors WHERE sensor_id = $id AND time > now() - $new_date";

        $result = $database->query($query);

        $decoded = $result->getPoints();

        for ($i = 0; $i < count($decoded); $i++) {
            //remove time from response
            unset($decoded[$i]['time']);
        }

        return $decoded;
    }
}
