<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZoneRepository
{
    const DELIVERY_ZONES = ['near', 'middle', 'far'];

    public function fetchZone(string $lon, string $lat): string
    {
        try {
            $sql = "SELECT title
            FROM delivery_zones
            WHERE ST_Contains(zone, ST_GeomFromText('POINT($lon $lat)', 4326))";
            $zones = DB::select($sql);
            if (empty($zones)) {
                return '';
            }

            $titles = array_column($zones, 'title');

            foreach (self::DELIVERY_ZONES as $zone) {
                if (in_array($zone, $titles)) {
                    return $zone;
                }
            }

            return '';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return '';
        }
    }
}