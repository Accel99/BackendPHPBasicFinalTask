<?php


namespace Anpi;


use Exception;
use GuzzleHttp\Client;


//класс для работы с api по коронавирусу
class CovidApi
{

    //guzzlehttp клиент
    private $client = null;

    //конструктор
    public function __construct()
    {
        $this->client = new Client();
    }

    //получить сводку по заболевшим
    public function getSummary() {
        try{
            $result = $this->client->request('GET', 'https://api.covid19api.com/summary');

            if ($result->getStatusCode() == 200) {
                return json_decode($result->getBody(), true);
            } else {
                return $result->getStatusCode();
            }
        } catch (Exception $e) {
            return $e->getCode();
        }
    }

}