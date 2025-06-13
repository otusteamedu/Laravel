<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZoneRepository
{
    const DELIVERY_ZONES = ['near', 'middle', 'far'];

    public function fetchZone(string $lon, string $lat): array
    {
        try {
            $sql = "SELECT title, price
            FROM delivery_zones
            WHERE ST_Contains(zone, ST_GeomFromText('POINT($lon $lat)', 4326))";
            $zones = DB::select($sql);
            if (empty($zones)) {
                return [];
            }

            $titles = array_column($zones, 'title');
            $prices = array_column($zones, 'price', 'title');

            foreach (self::DELIVERY_ZONES as $zone) {
                if (in_array($zone, $titles)) {
                    return ['title' => $zone, 'price' => $prices[$zone]];
                }
            }

            return [];
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }
}