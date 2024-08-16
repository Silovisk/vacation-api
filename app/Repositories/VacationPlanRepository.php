<?php

namespace App\Repositories;

use App\Models\VacationPlan;
use App\Repositories\Contracts\VacationPlanRepositoryInterface;
use Illuminate\Support\Facades\Log;

class VacationPlanRepository implements VacationPlanRepositoryInterface
{
    public function __construct
    (
        protected VacationPlan $vacationPlan
    )
    {
    }

    /**
     * Get all vacation plans with pagination
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllVacationPlans($perPage)
    {
        return $this->vacationPlan->paginate($perPage);
    }

    /**
     * Create a new vacation plan
     * @param array $vacationPlan
     * @return VacationPlan
     */
    public function createVacationPlan(array $vacationPlan)
    {
        return $this->vacationPlan->create($vacationPlan);
    }

    /**
     * Get a vacation plan by its ID
     * @param int $id
     * @return VacationPlan
     */
    public function getVacationPlanById($id)
    {
        return $this->vacationPlan->find($id);
    }

    /**
     * Update a vacation plan by its ID
     * @param int $id
     * @param array $vacationPlan
     * @return bool
     */
    public function updateVacationPlan(object $existingVacationPlan, array $vacationPlan)
    {
        return $existingVacationPlan->update($vacationPlan);
    }

    /**
     * Delete a vacation plan by its ID
     * @param int $id
     * @return bool|null
     */
    public function destroyVacationPlan(object $existingVacationPlan)
    {
        return $existingVacationPlan->delete();
    }

}
