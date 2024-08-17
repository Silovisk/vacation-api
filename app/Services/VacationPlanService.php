<?php

namespace App\Services;

use App\Exceptions\VacationPlanException;
use App\Repositories\VacationPlanRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationPlanService
{
    public function __construct
    (
        protected VacationPlanRepository $vacationPlanRepository
    ) {
    }

    public function getAllVacationPlans($perPage): LengthAwarePaginator
    {
        $vacationPlans = $this->vacationPlanRepository->getAllVacationPlans($perPage);

        if ($vacationPlans->isEmpty()) {
            throw VacationPlanException::noDataAvailable();
        }
        return $vacationPlans;
    }

    public function createVacationPlan(array $vacationPlan)
    {
        $createdVacationPlan = $this->vacationPlanRepository->createVacationPlan($vacationPlan);

        if (!$createdVacationPlan) {
            throw VacationPlanException::notCreated();
        }

        return $createdVacationPlan;
    }

    public function getVacationPlanById($id)
    {
        $vacationPlan = $this->vacationPlanRepository->getVacationPlanById($id);

        if (!$vacationPlan) {
            throw VacationPlanException::notFound();
        }

        return $vacationPlan;
    }

    public function updateVacationPlan(int $id, array $vacationPlan)
    {
        $existingVacationPlan = $this->vacationPlanRepository->getVacationPlanById($id);

        if (!$existingVacationPlan) {
            throw VacationPlanException::notFound();
        }

        $updated = $this->vacationPlanRepository->updateVacationPlan($existingVacationPlan, $vacationPlan);

        if (!$updated) {
            throw VacationPlanException::notUpdated();
        }

        return $existingVacationPlan;
    }


    public function destroyVacationPlan(int $id)
    {
        $existingVacationPlan = $this->vacationPlanRepository->getVacationPlanById($id);

        if (!$existingVacationPlan) {
            throw VacationPlanException::notFound();
        }

        $deleted = $this->vacationPlanRepository->destroyVacationPlan($existingVacationPlan);

        if (!$deleted) {
            throw VacationPlanException::notDeleted();
        }

        return $deleted;
    }

}
