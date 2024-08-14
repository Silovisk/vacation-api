<?php

namespace App\Http\Controllers\API;

use App\Exceptions\VacationPlanException;
use App\Http\Requests\IndexVacationPlanRequest;
use App\Http\Resources\VacationPlanResource;
use App\Models\VacationPlan;
use App\Http\Requests\StoreVacationPlanRequest;
use App\Http\Requests\UpdateVacationPlanRequest;
use App\Services\VacationPlanService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VacationPlanController extends BaseController
{
    public function __construct
    (
        private VacationPlanService $vacationPlanService,
    )
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexVacationPlanRequest $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacationPlanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VacationPlan $vacationPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacationPlanRequest $request, VacationPlan $vacationPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VacationPlan $vacationPlan)
    {
        //
    }
}
