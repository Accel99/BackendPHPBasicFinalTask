<?php


namespace Anpi;


use Anpi\Controllers\InfectedByCountry;
use Anpi\Controllers\TotalInfected;
use Anpi\Controllers\Countries;


class DBHandler
{
    //заполнить таблицы InfectedByCountry и TotalInfected
    private static function fillSummaryInDB(array $summary) {
        self::fillInfectedByCountry($summary['Countries']);
        self::fillTotalInfected($summary['Global'], $summary['Date']);
    }

    //заполнить таблицу InfectedByCountry
    private static function fillInfectedByCountry(array $countries) {
        foreach ($countries as $key => $value) {
            InfectedByCountry::addRecord(
                $value['Country'],
                $value['NewConfirmed'],
                $value['TotalConfirmed'],
                $value['NewDeaths'],
                $value['TotalDeaths'],
                $value['NewRecovered'],
                $value['TotalRecovered'],
                $value['Date']
            );
        }
    }

    //заполнить таблицу TotalInfected
    private static function fillTotalInfected(array $global, string $date) {
        TotalInfected::addRecord(
            $global['NewConfirmed'],
            $global['TotalConfirmed'],
            $global['NewDeaths'],
            $global['TotalDeaths'],
            $global['NewRecovered'],
            $global['TotalRecovered'],
            $date
        );
    }

    //получить данные для диаграммы
    public static function getData($btn_type, $stat_type, $country = null) {
        $result = null;

        switch ($btn_type) {
            case 'by_countries':
                $result = InfectedByCountry::getLastDateRecords();
                $result = $result->map
                    ->only(['country_ru', $stat_type, 'date'])
                    ->sortBy($stat_type)
                    ->toArray();
                break;

            case 'by_country':
                $result = InfectedByCountry::getRecordsByCountry($country);
                $result = $result->map
                    ->only(['date', $stat_type])
                    ->sortBy('date')
                    ->toArray();
                break;

            case 'total':
                $result = TotalInfected::getRecords();
                $result = $result->map
                    ->only(['date', $stat_type])
                    ->sortBy('date')
                    ->toArray();
                break;
        }

        //обновить данные если дата записей старее 1 дня
        if (strtotime('now') - strtotime($result[array_key_last($result)]['date']) > 86400) {
            $covidApi = new CovidApi();
            $summary = null;
            while (!is_array($summary)) {
                sleep(1);
                $summary = $covidApi->getSummary();
            }
            self::fillSummaryInDB($summary);

            switch ($btn_type) {
                case 'by_countries':
                    $result = InfectedByCountry::getLastDateRecords();
                    $result = $result->map
                        ->only(['country', $stat_type])
                        ->sortBy($stat_type)
                        ->toArray();
                    break;

                case 'by_country':
                    $result = InfectedByCountry::getRecordsByCountry($country);
                    $result = $result->map
                        ->only(['country', 'date', $stat_type])
                        ->sortBy('date')
                        ->toArray();
                    break;

                case 'total':
                    $result = TotalInfected::getRecords();
                    $result = $result->map
                        ->only(['date', $stat_type])
                        ->sortBy('date')
                        ->toArray();
                    break;
            }
        }

        //обрезать до 30 элементов
        $result = array_slice($result, -30);

        return $result;
    }

    //получить таблицу Countries
    public static function getCountries() {
        $result = Countries::getAllTable();
        $result = $result->toArray();

        return $result;
    }

    //получить всю статистику за последнюю дату по странам с широтой и долготой
    public static function getDataWithCoord() {
        $result = InfectedByCountry::getLastDateRecords();
        $result = $result->toArray();

        //обновить данные если дата записей старее 1 дня
        if (strtotime('now') - strtotime($result[0]['date']) > 86400) {
            $covidApi = new CovidApi();
            $summary = null;
            while (!is_array($summary)) {
                sleep(1);
                $summary = $covidApi->getSummary();
            }
            self::fillSummaryInDB($summary);

            $result = InfectedByCountry::getLastDateRecords();
            $result = $result->toArray();
        }

        return $result;
    }

}