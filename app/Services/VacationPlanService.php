<?php

namespace App\Services;

use App\Repositories\VacationPlanRepository;
use Illuminate\Support\Facades\Storage;

class VacationPlanService
{
    public function __construct
    (
        protected VacationPlanRepository $vacationPlanRepository
    )
    {
    }

    public function all($perPage)
    {
        return $this->vacationPlanRepository->all($perPage);
    }
}
