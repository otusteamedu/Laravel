<?php

namespace App\Helpers;

class AreaCalculator
{
    /**
     * Рассчитать площадь по количеству проживающих и общей площади
     *
     * @param int $livedQt Количество проживающих
     * @param float $totalArea Общая площадь
     * @return array [площадь по норме, площадь сверх нормы]
     */
    public static function calculateArea(int $livedQt, float $totalArea): array
    {
        if ($livedQt >= 3) {
            $areaByNorm = ($livedQt * 18) + 10;
        } elseif ($livedQt == 2) {
            $areaByNorm = 52;
        } elseif ($livedQt == 1) {
            $areaByNorm = 43;
        } else {
            $areaByNorm = 0;
        }

        if ($areaByNorm < $totalArea) {
            $areaOverNorm = round($totalArea - $areaByNorm, 2);
        } else {
            $areaOverNorm = 0;
            $areaByNorm = $totalArea;
        }

        return [$areaByNorm, $areaOverNorm];
    }
}
