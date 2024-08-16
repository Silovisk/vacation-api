<?php

namespace App\Repositories\Contracts;

interface VacationPlanRepositoryInterface
{
    public function getAllVacationPlans(int $perPage);
    public function createVacationPlan(array $vacationPlan);
    public function getVacationPlanById($id);
    public function updateVacationPlan(object $existingVacationPlan, array $vacationPlan);
    public function destroyVacationPlan(object $existingVacationPlan);
}
