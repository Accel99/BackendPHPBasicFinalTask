<?php


namespace Anpi\Models;


use Illuminate\Database\Eloquent\Model;


//модель таблицы Countries
class Countries extends Model
{
    public $timestamps = false;
    protected $table = 'Countries';
    protected $fillable = [
        'id',
        'country_en',
        'country_ru',
        'latitude',
        'longitude'
    ];
}