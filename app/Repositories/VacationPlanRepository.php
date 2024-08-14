<?php

namespace App\Repositories;

use App\Models\VacationPlan;

class VacationPlanRepository
{
    public function __construct
    (
        protected VacationPlan $vacationPlan
    )
    {
    }

    public function all($perPage)
    {
        return $this->vacationPlan->paginate($perPage);
    }
}
