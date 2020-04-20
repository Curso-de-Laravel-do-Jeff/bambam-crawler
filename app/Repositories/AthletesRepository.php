<?php

namespace App\Repositories;

use App\Repositories\Collections\Athlete;

class AthletesRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = new Athlete();
    }
}