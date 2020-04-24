<?php

namespace App\Jobs;

use App\Repositories\AthletesRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AthleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dataArr;

    public function __construct(array $dataArr)
    {
        $this->dataArr = $dataArr;
    }

    public function handle()
    {
        $athleteRepository = new AthletesRepository();

        $data = [
            'nome' => $this->dataArr[0],
            'idade' => $this->dataArr[1],
            'peso' => $this->dataArr[2],
            'genero' => $this->dataArr[3]
        ];

        $athleteRepository->create($data);
    }
}
