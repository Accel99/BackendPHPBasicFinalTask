<?php


namespace Anpi\Models;


use Illuminate\Database\Eloquent\Model;


//модель таблицы TotalInfected
class TotalInfected extends Model
{
    public $timestamps = false;
    protected $table = 'TotalInfected';
    protected $fillable = [
        'id',
        'new_confirmed',
        'total_confirmed',
        'new_death',
        'total_death',
        'new_recovered',
        'total_recovered',
        'date',
    ];
}