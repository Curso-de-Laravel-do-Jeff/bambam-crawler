<?php

namespace App\Repositories\Collections;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Athlete extends Model
{
    use SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'atletas';
    protected $fillable = [
        'nome',
        'idade',
        'peso',
        'genero'
    ];
}