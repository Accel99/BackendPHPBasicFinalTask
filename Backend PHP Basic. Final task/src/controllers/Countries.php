<?php


namespace Anpi\Controllers;


use Anpi\Models\Countries as ModelCountries;


//контроллер таблицы Countries
class Countries
{
    //заполнить таблицу
    public static function fill($arr) {
        foreach ($arr as $key => $value) {
            ModelCountries::query()->create([
                'country_en' => $value['country'],
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude']
            ]);
        }
    }

    //получить все записи таблицы
    public static function getAllTable() {
        $result = ModelCountries::all();

        return $result;
    }
}