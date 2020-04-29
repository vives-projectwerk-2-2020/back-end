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

        //put the time parameter in easier to process way
        $period_range =  substr($period, -1);
        $period_time = (int) substr($period, 0, -1);

        if ($period_range == "y") {
            $period_time *= 365;
            $period_range = "d";
        }

        if ($period_range == "pm2.5") {
            $period_range = "pm25";
        }

        $new_date = $period_time . $period_range;

        $meanProperties = "MEAN($properties)";

        if ($period_range == "all") {
            $groupBy = " GROUP BY time(3d)";
        } elseif ($period_range == "d" && $period_time == 1095) {
            $groupBy = " GROUP BY time(3d)";
        } elseif ($period_range == "d" && $period_time == 365) {
            $groupBy = " GROUP BY time(24h)";
        } elseif ($period_range == "d" && $period_time == 30) {
            $groupBy = " GROUP BY time(2h)";
        } elseif ($period_range == "d" && $period_time == 7) {
            $groupBy = " GROUP BY time(30m)";
        } elseif ($period_range == "h" && $period_time == 1) {
            $groupBy = "";
            $meanProperties = $properties;
        } else {
            //Default value : 24h
            $groupBy = " GROUP BY time(5m)";
            $new_date = "24h";
        }

        $query = "select $meanProperties FROM sensors WHERE sensor_id =~ /$id/ 
                AND time > now() - $new_date $groupBy";

        $result = $database->query($query);

        $decoded = $result->getPoints();

        for ($i = 0; $i < count($decoded); $i++) {
            //remove time from response
            unset($decoded[$i]['time']);
        }

        return $decoded;
    }
}
