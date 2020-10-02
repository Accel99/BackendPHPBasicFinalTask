<?php


namespace Anpi\Controllers;


use Anpi\Models\InfectedByCountry as ModelInfectedByCountry;


//контроллер таблицы InfectedByCountry
class InfectedByCountry
{

    //добавить строку в таблицу
    public static function addRecord(
        $country,
        $newConfirmed,
        $totalConfirmed,
        $newDeath,
        $totalDeath,
        $newRecovered,
        $totalRecovered,
        $date
    ) {
        $result = ModelInfectedByCountry::query()->create([
            'country' => $country,
            'new_confirmed' => $newConfirmed,
            'total_confirmed' => $totalConfirmed,
            'new_death' => $newDeath,
            'total_death' => $totalDeath,
            'new_recovered' => $newRecovered,
            'total_recovered' => $totalRecovered,
            'date' => $date
        ]);

        return $result;
    }

    //получить последние записи по странам
    public static function getLastDateRecords() {
        $result = ModelInfectedByCountry::query()
            ->leftJoin('Countries', 'Countries.country_en', '=', 'InfectedByCountry.country')
            ->orderBy('date', 'desc')
            ->take(186)
            ->get();

        return $result;
    }

    //получить все записи по стране
    public static function getRecordsByCountry($country) {
        $result = ModelInfectedByCountry::query()
            ->where('country', $country)
            ->get();

        return $result;
    }

    public static function getListCountry() {
        $result = ModelInfectedByCountry::query()
            ->groupBy('country')
            ->get('country');

        return $result;
    }

}