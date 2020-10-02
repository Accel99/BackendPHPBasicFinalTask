<?php


namespace Anpi\Controllers;


use Anpi\Models\TotalInfected as ModelTotalInfected;


//контроллер таблицы TotalInfected
class TotalInfected
{

    //добавить строку в таблицу
    public static function addRecord(
        $newConfirmed,
        $totalConfirmed,
        $newDeath,
        $totalDeath,
        $newRecovered,
        $totalRecovered,
        $date
    ) {
        $result = ModelTotalInfected::query()->create([
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

    //получить все записи таблицы
    public static function getRecords() {
        $result = ModelTotalInfected::all();

        return $result;
    }
}