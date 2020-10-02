<?php


namespace Anpi\Models;


use Illuminate\Database\Eloquent\Model;


//модель таблицы InfectedByCountry
class InfectedByCountry extends Model
{
    public $timestamps = false;
    protected $table = 'InfectedByCountry';
    protected $fillable = [
        'id',
        'country',
        'new_confirmed',
        'total_confirmed',
        'new_death',
        'total_death',
        'new_recovered',
        'total_recovered',
        'date',
    ];
}