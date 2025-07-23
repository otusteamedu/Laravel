<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AreaCalculator;
use App\Models\Apartment;
use App\Models\ApartmentCharge;
use App\Models\ApartmentCounter;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;
use App\Models\Tariff;

class ApartmentAreaController extends Controller
{
    public function calculate(Request $request)
    {
        $data = $request->validate([
            'livedQt' => 'required|integer|min:0',
            'totalArea' => 'required|numeric|min:0',
        ]);

        list($areaByNorm, $areaOverNorm) = AreaCalculator::calculateArea($data['livedQt'], $data['totalArea']);

        return response()->json([
            'areaByNorm' => $areaByNorm,
            'areaOverNorm' => $areaOverNorm,
        ]);
    }

    public function calculateFees()
    {
        ApartmentFee::truncate();
        $apartments = Apartment::all();

        foreach ($apartments as $apartment) {
            $apartmentDetail = ApartmentDetail::where('apartment_id', $apartment->id)->first();
            if (!$apartmentDetail) continue;

            $tariff = $apartmentDetail->tariff_id ? Tariff::find($apartmentDetail->tariff_id) : null;
            if (!$tariff) continue;

            $counters = ApartmentCounter::where('apartment_id', $apartment->id)->first();
            if (!$counters) continue;

            $charges = ApartmentCharge::where('apartment_id', $apartment->id)->first();
            if (!$charges) continue;

            $totalArea = $apartmentDetail->total_area;

            $roundFloat = fn($value, $precision = 2) => round($value, $precision);

            // Электроэнегия ОДН
            $electricity_odn = $roundFloat($totalArea * $tariff->electricity_odn);
            // Холодная вода ОДН
            $cold_water_odn = $roundFloat($totalArea * $tariff->cold_water_odn);
            // Сточные вода ОДН
            $sewage_odn = $roundFloat($totalArea * $tariff->sewage_odn);
            // Горячая вода ОДН
            $hot_water_odn = $roundFloat($totalArea * $tariff->hot_water_odn);
            // Лифт
            $lift = $roundFloat($totalArea * $tariff->lift);
            // Обращение с ТКО
            $solid_waste = $roundFloat($apartmentDetail->lived_qt * $tariff->solid_waste);
            // Электричество
            $electricity = $roundFloat($counters->electricity_value * $tariff->electricity);
            // Отопление Гкал
            $heating = $roundFloat($tariff->heating * $totalArea, 3);
            // Отопление руб
            $heating_rub = $roundFloat($heating * $tariff->heating_rub);

            // Горячая вода — added condition when counters didn't establish
            if ($counters->hot_water_previous == 0 && $counters->hot_water_current == 0) {
                $hot_water = $roundFloat($apartmentDetail->lived_qt * $tariff->hot_water * $tariff->multiplying_factor);
            } else {
                $hot_water = $roundFloat($counters->hot_water_value * $tariff->hot_water * $tariff->multiplying_factor);
            }

            // Холодная вода — added condition when counters didn't establish
            if ($counters->cold_water_previous == 0 && $counters->cold_water_current == 0) {
                $cold_water = $roundFloat($apartmentDetail->lived_qt * $tariff->cold_water * $tariff->multiplying_factor);
            } else {
                $cold_water = $roundFloat($counters->cold_water_value * $tariff->cold_water * $tariff->multiplying_factor);
            }

            // Водоотведение — added condition when counters didn't establish
            if ($counters->cold_water_previous == 0 && $counters->cold_water_current == 0) {
                $sewage = $roundFloat($apartmentDetail->lived_qt * $tariff->sewage);
            } else {
                $sewage = $roundFloat($counters->wastewater_value * $tariff->sewage);
            }

            // Перерасчет
            $re_maintenance = $charges->recalculation_maintenance ?? 0;

            $recalculation = $charges->recalculation_sewage
                + $charges->recalculation_electricity
                + $charges->recalculation_cold_water
                + $charges->recalculation_heating_rub
                + $charges->recalculation_hot_water
                + $charges->recalculation_solid_waste
                + $re_maintenance;

            $recalculation = $roundFloat($recalculation);

            // Итого коммунальные услуги
            $maintenance_total = $roundFloat(
                $electricity + $heating_rub + $hot_water + $cold_water + $sewage + $solid_waste + $recalculation
            );

            // Содержание помещения
            $maintenance = $roundFloat($totalArea * $tariff->maintenance);

            // Содержание помещения итого
            $maintenance_full = $roundFloat(
                $maintenance + $lift + $electricity_odn + $cold_water_odn + $hot_water_odn + $sewage_odn
            );

            // Начислено
            $accrued_expenses = $roundFloat(
                $maintenance_full + $solid_waste + $electricity + $heating_rub + $hot_water + $cold_water + $sewage
            );

            // Сальдо начало
            $balance_start = $roundFloat($charges->balance_start);

            // Оплачено
            $paid = $charges->money_deposited;

            // Сальдо конец
            $balance_end = $roundFloat($balance_start - $paid);

            // Пеня
            $fine = $roundFloat($charges->fine);

            // Итого к оплате
            $total = $roundFloat($accrued_expenses + $recalculation + $fine + $balance_end);

            // Создание записи
            $fee = new ApartmentFee([
                'solid_waste' => $solid_waste,
                'maintenance' => $maintenance,
                'lift' => $lift,
                'electricity_odn' => $electricity_odn,
                'cold_water_odn' => $cold_water_odn,
                'hot_water_odn' => $hot_water_odn,
                'heating' => $heating,
                'heating_rub' => $heating_rub,
                'hot_water' => $hot_water,
                'cold_water' => $cold_water,
                'sewage' => $sewage,
                'electricity' => $electricity,
                'paid' => $paid,
                'balance_start' => $balance_start,
                'fine' => $fine,
                'balance_end' => $balance_end,
                'accrued_expenses' => $accrued_expenses,
                'maintenance_full' => $maintenance_full,
                'maintenance_total' => $maintenance_total,
                'total' => $total,
                'recalculation' => $recalculation,
                'sewage_odn' => $sewage_odn,
                'apartment_id' => $apartment->id,
            ]);
            $fee->save();
        }

        return response()->json(['message' => 'Fees calculated successfully']);
    }
}
